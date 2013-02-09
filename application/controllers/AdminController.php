<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        $auth = Zend_Auth::getInstance();        
	$user = $auth->getStorage()->read();
	if (!Zend_Auth::getInstance()->hasIdentity() || $user->admin == '0') {
	    $this->_redirect('/');
	}
    }

    public function indexAction()
    {
        $this->view->headTitle("Admin");
	$user = new Model_Users();
	$users = $user->getAll();
	$this->view->users = $users;

	$blog = new Model_Blog();
	$blogs = $blog->getAll();
	$this->view->blogs = $blogs;
    }

    public function userAction()
    {
        $this->view->headTitle("admin / podrobnosti");        
	$request = $this->getRequest();
	$user = $request->getParam('id');
	$work = new Model_Work();
	$this->view->work = $work->get($user);
	$clear = new Model_Clearance();
	$clearTable = $clear->get($user);
	$this->view->clearance=$clearTable;
    }

    public function blogAction()
    {
	
        $form = new Form_BlogForm();
	$this->view->form = $form;
	if($this->_getParam('id')){
	    $blog = new Model_Blog();
	    $con = $blog->fetchRow('id = '.(int)$this->_getParam('id'));
	    $con = $con->toArray();
	    $con['blog_content'] = $con['content'];
	    $con['blog_title'] = $con['title'];
	    $this->view->form->populate($con);
	    $this->view->form->Dodaj->setName('Shrani');
	    
	}
    }
    public function addblogAction(){
	$blog = new Model_Blog();
	$params = $this->getRequest()->getParams();

	if($params['Dodaj']){
	    $blog->add($params);
	    $this->_redirect('/admin/');
	}
	else if($params['Shrani']){
	    $blog->change($params);
	    $this->_redirect('/admin/');
	}
	else{
	    $this->_redirect('/admin/');
	}

    }
    public function deleteblogAction(){
	$blog = new Model_Blog();
	$request = $this->getRequest();
	$blog->delete($request->getParam('id'));
	$this->_redirect('/admin/'  );
	
    }


}



