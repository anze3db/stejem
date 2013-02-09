<?php

class Zend_View_Helper_UserName {

    
    function userName($value){
	if($value['name'])
	    #registriran uporabnik:
	    return $value['name'];
	else
	    #uporabnik v cookiju:
	    return $value['user_name'];
	
    }
}