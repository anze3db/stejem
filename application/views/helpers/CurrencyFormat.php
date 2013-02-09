<?php

class Zend_View_Helper_CurrencyFormat {

    
    function currencyFormat($value){
	//ZEND currency not fast enough for me :)
	//$currency = new Zend_Currency('sl_SI');
	//return $currency->toCurrency($value);
	return number_format($value, 2, ',', '.') . ' €';
    }
}