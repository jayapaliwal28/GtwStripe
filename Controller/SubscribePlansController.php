<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
App::uses('AppController', 'Controller');
class SubscribePlansController extends AppController {
    var $name= "SubscribePlans";
    public $uses = array('GtwStripe.Transaction','GtwStripe.SubscribePlan','GtwStripe.UserCustomer','GtwStripe.SubscribePlanUser');

    public function beforeFilter() {
        if (CakePlugin::loaded('GtwUsers')) {
            $this->layout = 'GtwUsers.users';            
        }
        $this->__setStripe();
    }
    
    public function index(){
        $this->set('plans',$this->SubscribePlan->find('all'));
    }
    
    public function delete($planId=null){
        if(!empty($planId)){
            $planDetails=$this->SubscribePlan->findById($planId);
            if($this->SubscribePlan->delete($planId)){
                $this->plan_delete($planDetails['SubscribePlan']['plan_id']);
                $this->Session->setFlash(__d('gtw_stripe','Plan has been deleted successfully.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-success'));
            }else{
                $this->Session->setFlash(__d('gtw_stripe','Unable to delete plan.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
            }
            $this->redirect($this->referer());
        }
    }
    
    public function plan_delete($plan_id){
        $plan = Stripe_Plan::retrieve($plan_id);
        $plan->delete();
        return;
    }
    
    public function create_plans($planId=null) {
        $this->__setStripe();
        if($this->request->is(array('post','put'))){
            $flag=false;
            $name=$this->request->data['SubscribePlan']['name'];
            if(empty($planId)){
                $plan_id=$this->request->data['SubscribePlan']['plan_id'];
                $amount=$this->request->data['SubscribePlan']['amount'];
                $interval=$this->request->data['SubscribePlan']['plan_interval'];
                $interval_count=$this->request->data['SubscribePlan']['interval_count'];
                $status=$this->request->data['SubscribePlan']['status'];
                $plans=Stripe_Plan::all();            
                $plans=$plans->__toArray();
                foreach ($plans['data'] as $key => $plan) {
                    $plan=$plan->__toArray();
                    if(empty($planId) && $plan['id']==$plan_id){
                        $flag=true;
                        break;
                    }
                    if($plan['id']==$plan_id && $plan['interval']==$interval && $plan['interval_count']==$interval_count && $plan['name']==$name && $plan['amount']==$amount){
                        $flag=true;
                        break;
                    }
                }
            }
            if($flag){
                $this->Session->setFlash(__d('gtw_stripe','Plan already exists, Please enter another plan'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
            }else{
                               
                //save plan to database
                if(!empty($planId)){
                    $this->request->data['SubscribePlan']['id']=$planId;
                }else{
                    $this->request->data['SubscribePlan']['amount'] = $amount;
                }
                $oldPlanId = $this->SubscribePlan->find('first', array('conditions' => array('id' => $planId)));
                if($this->SubscribePlan->save($this->request->data)){
                    if (empty($planId)) {
                        //create new plan
                        Stripe_Plan::create(array(
                            'amount' => $amount * 100,
                            'interval' => $interval,
                            'interval_count' => $interval_count,
                            'name' => $name,
                            'currency' => 'usd',
                            'id' => $plan_id
                        ));
                    } else {                        
                        //update plan
                        $p = Stripe_Plan::retrieve($oldPlanId['SubscribePlan']['plan_id']);
                        $p->name = $name;
                        $p->save();
                    }
                    $this->Session->setFlash(__d('gtw_stripe','Plan has been created successfully.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-success'));
                    $this->redirect(Router::url(array('controller'=>'subscribe_plans','action'=>'index'),true));
                }else{
                    $this->Session->setFlash(__d('gtw_stripe','Unable to create plan.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
                }
            }            
        }
        if(!empty($planId) && empty($this->request->data)){
            $this->request->data=$this->SubscribePlan->find('first',array('conditions'=>array('id'=>$planId)));
            $this->request->data['SubscribePlan']['amount']=$this->request->data['SubscribePlan']['amount'] / 100;
            $this->set('title','edit');
        }
    }
    
    private function __setStripe() {
        App::import('Vendor', 'GtwStripe.Stripe', array('file' => 'stripe' . DS . 'lib' . DS . 'Stripe.php'));
        Stripe::setApiKey(Configure::read('GtwStripe.secret_key'));
    }
    
    public function user_subscribe(){
        $this->__setStripe();
        $customerId=ClassRegistry::init('GtwStripe.UserCustomer')->getCustomerStripeId($this->Session->read('Auth.User.id'));
	$subscribePlans=array();
	if($customerId){
		$subscribes=Stripe_Customer::retrieve($customerId)->subscriptions->all();
		foreach ($subscribes->data as $key => $subscribe) {
		    $subscribePlans[]=array(
		        'plan_id' => $subscribe->plan->id,
		        'plan_name' => $subscribe->plan->name,
		        'created' => date("Y-m-d H:i:s", $subscribe->plan->created),
		        'subscribe_id' => $subscribe->id
		    );
		}	
	}

        $this->set(compact('subscribePlans'));
        $this->set('subscribes',ClassRegistry::init('Transaction')->find('all',array('conditions'=>array('Transaction.user_id'=>$this->Session->read('Auth.User.id'),'Transaction.transaction_type_id'=>2))));
    }
    
    public function unsubscribe_user($subscribeId=null){
        $this->__setStripe();
        if(!empty($subscribeId)){
            $customerId=ClassRegistry::init('GtwStripe.UserCustomer')->getCustomerStripeId($this->Session->read('Auth.User.id'));
            $subscribeStatus = Stripe_Customer::retrieve($customerId)->subscriptions->retrieve($subscribeId)->cancel();
            if($subscribeStatus->status == "canceled"){
                $planDetail = $this->SubscribePlan->getPlanDetail($subscribeStatus->plan->id);
                $response= $this->SubscribePlanUser->addToSubscribeList($planDetail['SubscribePlan']['id'],  $this->Session->read('Auth.User.id'),'fail');
                $this->Session->setFlash(__d('gtw_stripe','This Plan has been unsubscribe successfully.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-success'));
            }else{
                $this->Session->setFlash(__d('gtw_stripe','Unable to unsubscribe this plan.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
            }
        }else{
            $this->Session->setFlash(__d('gtw_stripe','Unable to unsubscribe this plan.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
        }
        $this->redirect($this->referer());
    }
    
    public function usertransaction($planId=null){
        if(empty($planId)){
            $this->Session->setFlash(__d('gtw_stripe','Invalid plan.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
            $this->redirect($this->referer());
        }
        $planUsers=$this->SubscribePlan->findByPlanId($planId);
        $this->loadModel('GtwUsers.User');
	$this->User->virtualFields = array('name' => 'CONCAT(User.first, " ",User.last)');
        $userList = $this->User->find('list',array('fields'=>array('User.id','User.name')));
        $title= $planUsers['SubscribePlan']['plan_id'] . ' plan ('. $planUsers['SubscribePlan']['name'] .') users';
        $backUrl = Router::url(array('controller'=>'subscribe_plans','action'=>'index'),true);
        $this->set(compact('title','backUrl','planUsers','userList'));
    }
    
    public function myplantransaction($planId=null,$userId=null,$allTransaction=false) {
        $conditions=array(
            'Transaction.paid' => 1,
            'Transaction.transaction_type_id'=>2,
        );
        if($allTransaction){
            unset($conditions['Transaction.user_id']);
        }
        if(!empty($planId)){
            $conditions['Transaction.plan_id']=$planId;
        }
        if(!empty($userId)){
            $conditions['Transaction.user_id'] = $this->Session->read('Auth.User.id');
        }
        if(empty($planId) && empty($userId) && empty($allTransaction)){
            $conditions['Transaction.user_id'] = $this->Session->read('Auth.User.id');
        }
        $this->paginate = array(
            'Transaction' => array(
                'fields' => array(
                    'Transaction.*',
                    'TransactionType.name',
                    'User.id',
                    'User.first',
                    'User.last',
                ),
                'conditions' => $conditions,
                'contain' => array(
                    'UserModel'
                ),
                'order' => 'Transaction.created DESC'
            )
        );
        $this->set('transactions', $this->paginate('Transaction'));
        $all=false;
        if(empty($planId)){
            $all=true;
        }
        $this->set(compact('all'));
        $planDetail=$this->SubscribePlan->findByPlanId($planId);
        $title= ' Transactions ' . (empty($planDetail)?'':('for '.$planDetail['SubscribePlan']['plan_id'] .' plan'));
        if(empty($planId) && empty($userId) && empty($allTransaction)){
            $title=__d('gtw_stripe', 'My Transactions');
        }
        $backUrl = Router::url($this->referer(),true);
        $this->set(compact('planDetail','title','allTransaction','backUrl'));
    }
    
    function subscribeslist(){
        $customerId=ClassRegistry::init('GtwStripe.UserCustomer')->getCustomerStripeId($this->Session->read('Auth.User.id'));
        $arrSubscribePlans=array();
        if($customerId){
            try{
                $customer=Stripe_Customer::retrieve($customerId);
                if(!empty($customer->subscriptions)){
                    $subscribes=Stripe_Customer::retrieve($customerId)->subscriptions->all();
                    $plans=ClassRegistry::init('GtwStripe.SubscribePlan')->find('list',array('fields'=>array('plan_id','plan_id')));
                    foreach ($subscribes->data as $key => $subscribe) {
                        if(in_array($subscribe->plan->id, $plans) ){
                            $arrSubscribePlans[$subscribe->plan->id]= $subscribe->id;
                        }
                    }
                }
            }  catch (Exception $ex){
                
            }
        }
        $this->set(compact('arrSubscribePlans'));
        $this->set('plans',ClassRegistry::init('GtwStripe.SubscribePlan')->find('all'));
    }
    
}
