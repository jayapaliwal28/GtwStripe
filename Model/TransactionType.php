<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */

class TransactionType extends AppModel {
	var $name = 'TransactionType';
	
	public function setDefaultData(){
		$check = $this->find('count');
		if(empty($check)){
			$arrDefaultData = array(
				array('id'=>1,'name'=>'One Time Payment'),
				array('id'=>2,'name'=>'Subscription'),
			);
			foreach($arrDefaultData as $k=>$data){
				$this->create();
				$this->save($data);
			}
		}
	}
}