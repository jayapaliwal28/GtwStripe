<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
App::uses('AppModel', 'Model');
class UserCustomer extends AppModel {
    
    public function getCustomerStripeId($userId=null){
        if(!empty($userId)){
            $userCustomer=  $this->find('first',array('conditions'=>array('UserCustomer.user_id'=>$userId)));
            if(!empty($userCustomer)){
                return $userCustomer['UserCustomer']['customer_id'];
            }
        }
        return false;
    }
}
