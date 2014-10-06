<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */

class PaymentsController extends AppController {
	public $name = 'Payments';
	public $uses = array('GtwStripe.Transaction');
	
    public function beforeFilter(){
        if (CakePlugin::loaded('GtwUsers')){
            $this->layout = 'GtwUsers.users';
        }
    }	
    public function one_time_payment() {
		$this->__setStripe();
		if(!empty($this->request->data['stripeToken'])){
			$amountKey = 'GtwStripe.'.$this->request->data['Stripe']['key'];
			if($this->Session->check($amountKey)){
				$amount = $this->Session->read($amountKey);
				try{
					// Create a Customer
					$customer = Stripe_Customer::create(array(
									'email' => $this->request->data['stripeEmail'],
									'card'  => $this->request->data['stripeToken']
					));
					// Charge the Customer instead of the card
					$charge = Stripe_Charge::create(array(
						'customer' => $customer->id,
						'amount'   => $amount,
						'currency' => Configure::read('GtwStripe.currency')
					));
					$arrDetail = array(
									'transaction_type_id' => 1,
									'fixed_price' => 1,
									'stripe' => $charge
								);
					$transaction =  $this->Transaction->addTransaction($arrDetail);
					$redirectUrl = $this->referer();
					if($charge->paid){
						$this->Session->setFlash(__('Payment process has been successfully completed'), 'alert', array(
								'plugin' => 'BoostCake',
								'class' => 'alert-success'
							));
						if(!empty($this->request->data['Stripe']['success_url'])){
							$redirectUrl = $this->request->data['Stripe']['success_url'].'/transaction:'.$transaction['Transaction']['id'] ;
						}
					}else{
						$this->Session->setFlash(__('Unable to process your payment request, Please try again.'), 'alert', array(
								'plugin' => 'BoostCake',
								'class' => 'alert-danger'
							));
						if(!empty($this->request->data['Stripe']['fail_url'])){
							$redirectUrl = $this->request->data['Stripe']['fail_url'];
						}
					}
					$this->redirect($redirectUrl);
				}catch(Exception $e){
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
	public function one_time_payment_set_amount(){
		if($this->request->is('requested')){
			$amountKey = md5($this->request->named['amount']);
			$this->Session->write('GtwStripe.'.$amountKey,$this->request->named['amount']);
			return $amountKey;
		}else{
			$this->Session->setFlash(__('Invalid Opration'), 'alert', array(
								'plugin' => 'BoostCake',
								'class' => 'alert-danger'
							));
			$this->redirect($this->referer());	
		}
	}
	public function success(){
		if(isset($this->request->named['transaction'])){
			$transactionDetail = $this->Transaction->getTransactionDetail($this->request->named['transaction']);			
			$this->set('transactionId',$this->request->named['transaction']);
			$this->set('transactionDetail',$transactionDetail);
		}else{
			$this->Session->setFlash(__('Invalid Opration'), 'alert', array(
									'plugin' => 'BoostCake',
									'class' => 'alert-danger'
								));
			$this->redirect($this->referer());	
		}
	}	
	public function fail(){
		
	}
	public function index(){
		$this->paginate = array(
			'Transaction' => array(
                'fields'=>array(
                    'Transaction.*',
                    'TransactionType.name',
                    'User.id',
                    'User.first',
                    'User.last',
                ),
				'conditions' => array('Transaction.paid'=>1),
				'contain' => array(
					'UserModel'
				),
				'order' => 'Transaction.created DESC'
			)
		);
		$this->set('transactions', $this->paginate('Transaction'));
	}
	private function __setStripe(){
		App::import('Vendor', 'GtwStripe.Stripe', array('file' => 'stripe'.DS.'lib'.DS.'Stripe.php'));
		Stripe::setApiKey(Configure::read('GtwStripe.secret_key'));
	}
}