<?php

/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class Transaction extends AppModel {

    var $name = 'Transaction';
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'TransactionType' => array(
            'className' => 'TransactionType',
            'foreignKey' => 'transaction_type_id'
        )
    );

    function addTransaction($arrDetail = array()) {
        $userId = CakeSession::check('Auth.User.id');
        if($userId){
           $userId = CakeSession::read('Auth.User.id');
        } else {
            $userId = 0;
        }
        $arrTransaction = array(
            'user_id' => $userId,
            'transaction_type_id' => $arrDetail['transaction_type_id'],
            'fixed_price' => $arrDetail['fixed_price'],
            'amount' => ($arrDetail['stripe']->amount / 100), // Convert to amount
            'currency' => $arrDetail['stripe']->currency,
            'transaction_id' => $arrDetail['stripe']->id,
            'customer_id' => $arrDetail['stripe']->customer,
            'email' => $arrDetail['stripe']->card->name,
            'brand' => $arrDetail['stripe']->card->brand,
            'last4' => $arrDetail['stripe']->card->last4,
            'paid' => $arrDetail['stripe']->paid,
            'captured' => (isset($arrDetail['stripe']->captured)?$arrDetail['stripe']->captured:''),            
        );
        if(isset($arrDetail['plan_id'])){
            $arrTransaction['plan_id']=$arrDetail['plan_id'];
        }
        if(isset($arrDetail['plan_name'])){
            $arrTransaction['plan_name']=$arrDetail['plan_name'];
        }
        $transaction = $this->save($arrTransaction);
        $this->afterTransaction($transaction);
        return $transaction;
    }

    function afterTransaction($transaction = array()) {
        //We can maintain our after Payment process here        
    }

    function getTransactionDetail($transactionId = 0) {
        return $this->find('first', array(
                    'fields' => array('Transaction.*', 'User.first', 'User.last', 'User.email', 'TransactionType.name'),
                    'conditions' => array('Transaction.id' => $transactionId, 'Transaction.captured' => 1)
        ));
    }
    
    function addTransactionSubscribe($arrDetail=array()){
        if(!empty($arrDetail)){
            $customer_id =  $arrDetail->data->object->customer;
            $paid =  $arrDetail->data->object->paid;
            $charge_date =  date('Y-m-d H:i:s',$arrDetail->data->object->date);
            foreach ($arrDetail->data->object->lines->data as $key => $value) {
                $conditions = array(
                    'transaction_type_id' => 2,
                    'plan_id' => $value->plan->id,
                    'customer_id' => $customer_id
                );
                $oldTransaction =$this->find('first',array('conditions'=>$conditions));
                if(!empty($oldTransaction)){
                    unset($oldTransaction['Transaction']['id']);
                    $oldTransaction['Transaction']['transaction_id'] =$arrDetail->data->object->charge;
                    $oldTransaction['Transaction']['paid'] = $paid;
                    $this->save($oldTransaction);
                    $planData = array(
                        'plan_id' => $value->plan->id,
                        'last_charged' => $charge_date,
                        'customer_id' => $customer_id,
                        'status' => ($paid)?'active':'fail'
                    );
                    ClassRegistry::init('GtwStripe.SubscribePlanUser')->updateSubscribePlan($planData);
                }
            }
            return true;
        }
        return false;
    }

}
