<?php

class Zend_View_Helper_DateFormat {
    function dateFormat($value){
        
        //$date = new Zend_Date($value, 'yyyy-MM-dd');
        //return $date->toString('dd.MM.yyyy');
	$value = substr($value, 0, 10);
	return substr($value, 8) . '.' . substr($value, 5, 2) . '.' . substr($value, 0, 4);
        
    }
    
}

