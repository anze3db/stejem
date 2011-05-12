<?php

class MobileController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()){
            $this->view->loginForm = new Form_LoginForm();
            $this->view->loginForm->setAction('/mobile');
        }
        if($this->getRequest()->isPost()){
            try{
                $user = new Model_Users();
                $user->auth($this->_getAllParams());
                $this->_redirect('/mobile/delo');


            }
            catch(Exception $e){
                #TODO LOGIN ERROR HANDLING:
                throw $e;
            }

        }
    }

    public function deloAction()
    {
        $this->view->workForm = new Form_WorkForm();
    }


}



