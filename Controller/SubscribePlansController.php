<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
App::uses('AppController', 'Controller');
class SubscribePlansController extends AppController {
    var $name= "SubscribePlans";

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
                $this->Session->setFlash(__('Plan has been deleted successfully.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-success'));
            }else{
                $this->Session->setFlash(__('Unable to delete plan.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
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
                $amount=$this->request->data['SubscribePlan']['amount'] * 100;
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
                $this->Session->setFlash(__('Plan already exists, Please enter another plan'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
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
                            'amount' => $amount,
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
                    $this->Session->setFlash(__('Plan has been created successfully.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-success'));
                    $this->redirect(Router::url(array('controller'=>'subscribe_plans','action'=>'index'),true));
                }else{
                    $this->Session->setFlash(__('Unable to create plan.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
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
        $subscribes=Stripe_Customer::retrieve($customerId)->subscriptions->all();
        $subscribePlans=array();
        foreach ($subscribes->data as $key => $subscribe) {
            $subscribePlans[]=array(
                'plan_id' => $subscribe->plan->id,
                'plan_name' => $subscribe->plan->name,
                'created' => date("Y-m-d H:i:s", $subscribe->plan->created),
                'subscribe_id' => $subscribe->id
            );
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
                $this->Session->setFlash(__('This Plan has been unsubscribe successfully.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-success'));
            }else{
                $this->Session->setFlash(__('Unable to unsubscribe this plan.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
            }
        }else{
            $this->Session->setFlash(__('Unable to unsubscribe this plan.'), 'alert', array('plugin' => 'BoostCake','class' => 'alert-danger'));
        }
        $this->redirect($this->referer());
    }
    
}
