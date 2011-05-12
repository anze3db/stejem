<?php

class Zend_View_Helper_LoggedIn {

    function loggedIn($admin = 0){

    $auth = Zend_Auth::getInstance();
	if($admin == 0){
	    return $auth->hasIdentity();
	}
	$user = $auth->getStorage()->read();
    if(!$user) return false;
	if($user->admin == "1")
            return true;
	return false;


    }
}