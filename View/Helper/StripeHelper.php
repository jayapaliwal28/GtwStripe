<?php

App::uses('HtmlHelper', 'View/Helper');

class StripeHelper extends HtmlHelper {

    public function showPrice($price=0){
		$currency = strtoupper(Configure::read('GtwStripe.currency'));
		$symbol = '';
		if($currency=='USD'){
			$symbol = '$';
		}
		return $symbol. number_format($price);
    }
}