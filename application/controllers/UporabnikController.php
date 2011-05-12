<?php

class UporabnikController extends Zend_Controller_Action
{

    private $user;
    private $msg;

    public function init(){
        $this->user = new Model_Users();
        $this->msg = $this->_helper->getHelper('FlashMessenger');
    }
    public function indexAction(){
        $this->view->headTitle("prijava");
    }
    public function registracijaAction(){
        $this->view->headTitle("registracija");
        $regForm = new Form_RegisterForm();

        if($this->getRequest()->isPost()){
            if(!$regForm->isValid($this->getRequest()->getPost()))
                    throw new Exception("Form not valid", 22);
            try{
                //SEND REGISTRATION EMAIL:
                $this->user->reg($this->_getAllParams());
                $this->sendRegistrationMail($this->_getParam('email'));
                $this->msg->addMessage(array('Registracija je bila uspešna', 'success'));
                $this->msg->addMessage(array('Sedaj lahko dodaš svojo prvo službo', 'info'));
                $this->_redirect('/sluzbe');
            }
            catch(Exception $e){
                #TODO REGISTER ERROR HANDLING:
                throw $e;
                #$login = new Form_LoginForm();
                #$login->populate($this->_getAllParams());
                #$this->view->form = "HELLO";
                #$this->view->notify = "Izpolnite vsa polja!";
            }

        }
        else{
            $this->_redirect('/');
        }
    }
    public function prijavaAction(){
        
        if($this->getRequest()->isPost()){
            try{
                 
                $this->user->auth($this->_getAllParams());
                $jobs = new Model_Jobs();
                $this->msg->addMessage(array('Uspešno si se prijavil', 'success'));
                if($jobs->hasJob()){
                    $this->_redirect('/delo');
                }
                else{
                    $this->_redirect('/sluzbe');
                }
                
            }
            catch(Exception $e){
                #TODO LOGIN ERROR HANDLING:
                throw $e;
            }

        }
        else{
            $this->_redirect('/');
        }
    }
    public function odjavaAction(){
        Zend_Auth::getInstance()->clearIdentity();
        $this->msg->addMessage(array('Uspešno si se odjavil, želim ti lep dan.', 'success'));
        $this->_redirect('/');
    }
    public function gesloAction(){
        $form = new Form_ForgottenPasswordForm();
        $form->removeElement('pass1');
        $form->removeElement('pass2');
        $this->view->form = $form;
        $users = new Model_Users();
        $usersPass = new Model_UsersPass();
        if($this->getRequest()->isPost()){
            if(!$form->isValid($this->getRequest()->getPost()))
                    throw new Exception("Form not valid", 12);
            try{
                //SEND REGISTRATION EMAIL:
                $id = $users->getOneByMail($this->_getParam('mail'));
                $token = $usersPass->insertToken($id);
                $this->sendRetrivalMail($this->_getParam('mail'), $id, $token);
                $this->msg->addMessage(array('Na tvoj E-Mail smo ti poslali nadalnja navodila za pridobitev novega gesla.', 'success'));
                $this->_redirect('/');
            }
            catch(Exception $e){
                #TODO REGISTER ERROR HANDLING:
                throw $e;
                #$login = new Form_LoginForm();
                #$login->populate($this->_getAllParams());
                #$this->view->form = "HELLO";
                #$this->view->notify = "Izpolnite vsa polja!";
            }
        }


    }
    public function novogesloAction(){
        $id = $this->_getParam('id');
        $token = $this->_getParam('token');
        
        $form = new Form_ForgottenPasswordForm();
        $form->removeElement('mail');
        $form->setAction('/uporabnik/novogeslo/id/'.$id.'/token/'.$token);
        $this->view->form = $form;        

        if($this->getRequest()->isPost()){
            $usersPass = new Model_UsersPass();
            $users = new Model_Users();
            
            $id_token = $usersPass->checkToken($id, $token);
            $users->changePassword($this->_getAllParams(), $id);
            $usersPass->removeToken($id_token);
            $this->msg->addMessage(array('Operacije je uspela, sedaj se lahko prijaviš z novim geslom!.', 'success'));
            $this->_redirect('/');
        }

    }
    private function sendRegistrationMail($email){
        $mail = new Zend_Mail();
        $mail->setFrom('admin@stejem.si', 'stejem.si')
             ->addTo($email, 'nov uporabnik')
             ->setSubject('stejem.si - Registracija')
             ->setBodyHtml($this->view->render('registrationMail.phtml'), 'utf8')
             ->send();
    }
    private function sendRetrivalMail($email, $id, $token){
        $mail = new Zend_Mail();
        $this->view->id= $id;
        $this->view->token = $token;
        $mail->setFrom('admin@stejem.si', 'stejem.si')
             ->addTo($email, 'nov uporabnik')
             ->setSubject('stejem.si - Novo geslo')
             ->setBodyHtml($this->view->render('retrivalMail.phtml'), 'utf8')
             ->send();
    }


}

