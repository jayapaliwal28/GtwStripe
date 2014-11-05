<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubscribePlanUser
 *
 * @author securemetasys
 */

App::uses('AppModel', 'Model');
class SubscribePlanUser extends AppModel {
    var $name = 'SubscribePlanUser';
    var $belongsTo = array(
        'SubscribePlan' => array(
            'className' => 'SubscribePlan',
            'foreignKey' => 'plan_id',
            'counterCache' => true
        )
    );
    
    public function addToSubscribeList($planId=null,$userId=null,$status='active',$lastCharged=''){
        //planId is integer
        $arrDetail['SubscribePlanUser'] = array(
            'user_id' => $userId,
            'plan_id' => $planId,
        );
        $oldPlan=$this->find('first',array('conditions'=>array('SubscribePlanUser.user_id'=>$userId,'SubscribePlanUser.plan_id'=>$planId)));
        if(!empty($oldPlan)){
            $arrDetail['SubscribePlanUser']['id']=$oldPlan['SubscribePlanUser']['id'];
        }
        if(!empty($lastCharged)){
            $arrDetail['SubscribePlanUser']['last_charged'] = $lastCharged;
        }
        $arrDetail['SubscribePlanUser']['status'] = $status;
        if($this->save($arrDetail)){
            return true;
        }
        return false;
    }
    
    
    public function updateSubscribePlan($subscribeDetail=array()){
        if(!empty($subscribeDetail) && isset($subscribeDetail['plan_id']) && isset($subscribeDetail['last_charged']) && isset($subscribeDetail['customer_id'])){
            //$subscribeDetail['plan_id'] is the varachar here
            $planDetail=  ClassRegistry::init('GtwStripe.SubscribePlan')->getPlanDetail($subscribeDetail['plan_id']);
            $customerDetail = ClassRegistry::init('GtwStripe.UserCustomer')->findByCustomerId($subscribeDetail['customer_id']);
            $subscribePlanUser = $this->find('first',array('conditions'=>array('SubscribePlanUser.user_id'=>$customerDetail['UserCustomer']['user_id'],'SubscribePlanUser.plan_id'=>$planDetail['SubscribePlan']['id'])));
            $subscribePlanUser['SubscribePlanUser']['last_charged']=  $subscribeDetail['last_charged'];
            $subscribePlanUser['SubscribePlanUser']['status']=$subscribeDetail['status'];
            if($this->save($subscribePlanUser)){
                return true;
            }
            return false;
        }
        return false;
    }
    
}
