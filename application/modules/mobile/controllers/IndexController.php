<?php

class Mobile_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/mobile/delo');
        }
    }

    public function indexAction()
    {
        
        $this->view->form = new Form_LoginForm();
        $this->view->form->setAction('/mobile');
        $this->view->form->setAttrib('id', 'login');

        if($this->getRequest()->isPost()){
            if($this->view->form->isValid($this->getRequest()->getPost())){
                try{
                    $user = new Model_Users();

                    $user->auth($this->_getAllParams());

                    $jobs = new Model_Jobs();

                    if($jobs->hasJob()){
                        $this->_redirect('/mobile/delo');
                    }
                    else{
                        $this->_redirect('/mobile/sluzbe');
                    }
                }
                catch (Exception $e){
                    $this->getResponse()->setHttpResponseCode(401);
                        $this->view->msg = "Napaka pri avtentikaciji";
                        
                    
                }

            }
            else{
                $this->view->form->populate($this->getRequest()->getPost());
                
            }
        }
    }
    public function loginAction(){

        
    }


}

