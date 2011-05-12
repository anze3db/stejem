<?php

//require_once 'PHPUnit/Framework/TestCase.php';

class Mobile_DeloControllerTest extends Smotko_Controller_TestCase
{

    public function testRedirectIfNotLoggedIn(){

        $this->dispatch('/mobile/delo');
        $this->assertRedirectTo('/mobile');
    }
    public function testNotRedirectIfLoggedIn(){
        $identity = new stdClass();
        $identity->id = 1;
        Zend_Auth::getInstance()->getStorage()->write($identity);
        $this->dispatch('/mobile/delo');
        $this->assertNotRedirect();
    }
    public function testAddActiveWorkFormExists(){
        $identity = new stdClass();
        $identity->id = 1;
        Zend_Auth::getInstance()->getStorage()->write($identity);
        $this->dispatch('/mobile/delo');
        $this->assertQuery('form');
    }
}

