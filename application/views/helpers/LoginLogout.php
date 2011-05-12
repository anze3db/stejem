<?php

class Zend_View_Helper_LoginLogout {

    function loginLogout(){
        
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            // Identity exists; get it
            $view = new Zend_View;
            return "Zdravo! Se želiš <a href='".$view->url(array('controller'=>'uporabnik', 'action'=>'odjava'))."' title='odjava'>odjaviti</a>?";
        }
        else{
            return new Form_LoginForm;
        }
    }
}