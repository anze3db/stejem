<?php

class Zend_View_Helper_TimeFormat {
    function timeFormat($value){
	//ZEND DATE not optimized:
        //$date = new Zend_Date($value, Zend_Date::TIMES);
	//return $date->toString('HH:mm');
        return substr($value, 0, -3);
        
    }
}

