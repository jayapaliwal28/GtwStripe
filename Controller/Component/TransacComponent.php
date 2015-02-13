<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */

App::uses('Component', 'Controller');
class TransacComponent extends Component {
	public $components = array('Session');
    public function initialize(Controller $controller){
        $this->Controller = $controller;
    }
	function setLastTransaction($transaction = array()){
		$key = md5($transaction['Transaction']['id']);
		$this->Session->write($key,$transaction);
		return $key;
	}
	function getLastTransaction($key){
        $transaction = $this->Session->read($key);
        $this->Session->delete($key);
        if(!empty($transaction['Transaction']['user_id'])){
            ClassRegistry::init('User')->recursive = -1;
            $transaction['User'] = ClassRegistry::init('User')->findById($transaction['Transaction']['user_id']);
            $transaction['User'] = $transaction['User']['User'];
        }
        return $transaction;
	}
}