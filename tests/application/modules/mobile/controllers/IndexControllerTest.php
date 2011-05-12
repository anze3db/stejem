<?php

//require_once 'PHPUnit/Framework/TestCase.php';

class Mobile_IndexControllerTest extends Smotko_Controller_TestCase
{

    public function testModuleMobile(){

        $this->dispatch('/mobile');
        $this->assertModule('mobile');
    }
    public function testShowLoginFormIfNotLogged(){

        $this->dispatch('/mobile');
        $this->assertLoginForm();
    }
    public function testLoginFormSetIdentity(){
        
        $this->request->setMethod('POST')
             ->setPost(array('user_login' => '1',
                             'pass_login' => '1',
                             'remember_login'=>'0'));
        $this->dispatch('/mobile');
        
        $this->assertTrue(Zend_Auth::getInstance()->hasIdentity());        
    }
    public function testLoginRedirectDelo(){

        $this->request->setMethod('POST')
             ->setPost(array('user_login' => '1',
                             'pass_login' => '1',
                             'remember_login'=>'0'));
        $this->dispatch('/mobile');
        $this->assertRedirectTo('/mobile/delo');
    }
    public function testLoginFailedResponse(){
        $this->request->setMethod('POST')
                ->setPost(array('user_login' => '1',
                                'pass_login' => '2'));
        $this->dispatch('/mobile');
        $this->assertResponseCode(401);
    }
    private function assertLoginForm(){
        $this->assertQuery('form#login');
        $this->assertQuery('form#login input[type="text"]');
        $this->assertQuery('form#login input[type="password"]');
        $this->assertQuery('form#login input[type="submit"]');

    }

}

