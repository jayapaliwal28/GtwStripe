<?php

/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
class PaymentsController extends AppController {
    public $name = 'Payments';
    public $uses = array('GtwStripe.Transaction','GtwStripe.SubscribePlan','GtwStripe.UserCustomer');

    public function beforeFilter() {
        if (CakePlugin::loaded('GtwUsers')) {
            $this->layout = 'GtwUsers.users';
        }
    }

    public function one_time_payment() {
        $this->__setStripe();
        if (!empty($this->request->data['stripeToken'])) {
            $amountKey = 'GtwStripe.' . $this->request->data['Stripe']['key'];
            if ($this->Session->check($amountKey)) {
                $amount = $this->Session->read($amountKey);
                try {
                    // Create a Customer
                    $customer = Stripe_Customer::create(array(
                                'email' => $this->request->data['stripeEmail'],
                                'card' => $this->request->data['stripeToken']
                    ));

                    // Charge the Customer instead of the card
                    $charge = Stripe_Charge::create(array(
                                'customer' => $customer->id,
                                'amount' => $amount,
                                'currency' => Configure::read('GtwStripe.currency')
                    ));
                    $arrDetail = array(
                        'transaction_type_id' => 1,
                        'fixed_price' => 1,
                        'stripe' => $charge
                    );
                    $redirectUrl = $this->referer();
                    if ($charge->paid) {
                        $transaction = $this->Transaction->addTransaction($arrDetail);
                        $this->Session->setFlash(__('Payment process has been successfully completed'), 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-success'
                        ));
                        if (!empty($this->request->data['Stripe']['success_url'])) {
                            $this->Transac = $this->Components->load('GtwStripe.Transac');
                            $redirectUrl = $this->request->data['Stripe']['success_url'] . '/transaction:' . $this->Transac->setLastTransaction($transaction);
                        }
                    } else {
                        $this->Session->setFlash(__('Unable to process your payment request, Please try again.'), 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-danger'
                        ));
                        if (!empty($this->request->data['Stripe']['fail_url'])) {
                            $redirectUrl = $this->request->data['Stripe']['fail_url'];
                        }
                    }
                    $this->redirect($redirectUrl);
                } catch (Exception $e) {
                    //debug($e);
                }
            }
        }
        $this->Session->setFlash(__('Unable to process your payment request, Please try again.'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        $this->redirect($this->referer());
    }

    public function subscribe() {
        $this->__setStripe();
        if (!empty($this->request->data['stripeToken'])) {
            $amountKey = 'GtwStripe.' . $this->request->data['GtwStripe']['key'];
            if ($this->Session->check($amountKey)) {
                $amount = $this->Session->read($amountKey);
                try {
                    // Create a Customer / get customer
                    $customerId = $this->getCustomerId($this->Session->read('Auth.User.id'),$this->request->data['stripeEmail'],$this->request->data['stripeToken']);
                    $customer=Stripe_Customer::retrieve($customerId);
                    $subscribe = $customer->subscriptions->create(array("plan" => $this->request->data['GtwStripe']['plan_id']));
                    $subscribe->paid=($subscribe->status=='active')?1:0;
                    $subscribe->currency=$subscribe->plan->currency;
                    $subscribe->card = (object) array('name'=>$customer->cards->data[0]->name,'brand'=>$customer->cards->data[0]->brand,'last4'=>$customer->cards->data[0]->last4);
                    $subscribe->amount=$subscribe->plan->amount;
                    $charge = Stripe_Charge::create(array(
                                'customer' => $customer->id,
                                'amount' => $subscribe->plan->amount,
                                'currency' => Configure::read('GtwStripe.currency')
                    ));
                    $arrDetail = array(
                        'transaction_type_id' => 2,
                        'fixed_price' => 1,
                        'plan_id' => $this->request->data['GtwStripe']['plan_id'],
                        'plan_name' => $subscribe->plan->name,
                        'stripe' => $charge
                    );
                    $redirectUrl = $this->referer();
                    if ($subscribe->paid) {
                        $transaction = $this->Transaction->addTransaction($arrDetail);
                        $this->Session->setFlash(__('Subscribe has been successfully completed'), 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-success'
                        ));
                        if (!empty($this->request->data['GtwStripe']['success_url'])) {
                            $this->Transac = $this->Components->load('GtwStripe.Transac');
                            $redirectUrl = $this->request->data['GtwStripe']['success_url'] . '/transaction:' . $this->Transac->setLastTransaction($transaction);
                        }
                    } else {
                        $this->Session->setFlash(__('Unable to process your subscribe request, Please try again.'), 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-danger'
                        ));
                        if (!empty($this->request->data['GtwStripe']['fail_url'])) {
                            $redirectUrl = $this->request->data['GtwStripe']['fail_url'];
                        }
                    }
                    $this->redirect($redirectUrl);
                } catch (Exception $e) {
                    //debug($e);
                }
            }
        }
        $this->Session->setFlash(__('Unable to process your subscribe request, Please try again.'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        $this->redirect($this->referer());
    }

    function getCustomerId($userId=null,$stripeEmail=null,$stripeToken=null){
        if(!empty($userId)){
            $userCustomer=  $this->UserCustomer->find('first',array('conditions'=>array('UserCustomer.user_id'=>$userId)));
            if(!empty($userCustomer)){
                return $userCustomer['UserCustomer']['customer_id'];
            }else{
                if(!empty($stripeEmail) && !empty($stripeToken)){
                    $customer = Stripe_Customer::create(array(
                        'email' => $stripeEmail,
                        'card' => $stripeToken
                    ));
                    $arrCustomer['UserCustomer'] = array(
                        'user_id' => $userId,
                        'customer_id' => $customer->id,
                    );
                    $this->UserCustomer->save($arrCustomer);
                    return $customer->id;
                }
            }
        }
        return false;
    }
    
    public function one_time_payment_set_amount() {
        if ($this->request->is('requested')) {
            $amountKey = md5($this->request->named['amount']);
            $this->Session->write('GtwStripe.' . $amountKey, $this->request->named['amount']);
            return $amountKey;
        } else {
            $this->Session->setFlash(__('Invalid Opration'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            $this->redirect($this->referer());
        }
    }

    public function success() {
        if (isset($this->request->named['transaction'])) {
            $transactionDetail = $this->Transaction->getTransactionDetail($this->request->named['transaction']);
            $this->set('transactionId', $this->request->named['transaction']);
            $this->set('transactionDetail', $transactionDetail);
        } else {
            $this->Session->setFlash(__('Invalid Opration'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            $this->redirect($this->referer());
        }
    }

    public function fail() {
        
    }

    public function index() {
        $this->paginate = array(
            'Transaction' => array(
                'fields' => array(
                    'Transaction.*',
                    'TransactionType.name',
                    'User.id',
                    'User.first',
                    'User.last',
                ),
                'conditions' => array('Transaction.paid' => 1),
                'contain' => array(
                    'UserModel'
                ),
                'order' => 'Transaction.created DESC'
            )
        );
        $this->set('transactions', $this->paginate('Transaction'));
    }

    private function __setStripe() {
        App::import('Vendor', 'GtwStripe.Stripe', array('file' => 'stripe' . DS . 'lib' . DS . 'Stripe.php'));
        Stripe::setApiKey(Configure::read('GtwStripe.secret_key'));
    }

}
