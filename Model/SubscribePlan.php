<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
App::uses('AppModel', 'Model');
class SubscribePlan extends AppModel {

    var $name = 'SubscribePlan';
    
    public $validate = array(
        'plan_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Plan id is required.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'required' => 'create',
                'message' => 'Plan id already exists.'
            )
        ),
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Plan name is required.'
            )           
        ),
        'plan_interval' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'An interval of plan is required.'
            ),
            'inList' => array(
                'rule' => array('inList',array('day','month','week','year')),
                'required' => 'create',
                'message'   => 'Please choose from options.'
            ),
        ),
        'interval_count' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'An interval of plan is required.'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'required' => 'create',
                'message' => 'Please enter numeric values.'
            )
        ),
        'amount' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'An amount of plan is required.'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'required' => 'create',
                'message' => 'Please enter numeric values.'
            )
        ),
        'status' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Status of plan is required.'
            ),
            'inList' => array(
                'rule' => array('inList',array('active','deactive')),
                'required' => 'create',
                'message'   => 'Please choose from options.'
            ),
        ),
    );
    
    
}
