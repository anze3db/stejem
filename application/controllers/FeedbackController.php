<?php

class FeedbackController extends Zend_Controller_Action
{
    private $feedback;

    public function init()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
        
                    $this->_redirect('/');
        }
        $this->feedback = new Model_Feedback();
    }

    public function indexAction()
    {
        $this->view->headTitle("feedback");


	$blog = new Model_Blog();
	$paginator = Zend_Paginator::factory($blog->getAll());
	$paginator->setCurrentPageNumber($this->_getParam('stran'));
	$paginator->setDefaultItemCountPerPage(1);
	$this->view->blogs = $paginator;
	$curr = $paginator->getCurrentItems();
	$this->view->feedback = $this->feedback->get($curr[0]['id']);
        $this->view->feedbackForm = new Form_FeedbackForm();

	$users = new Model_Users();
	$user = $users->getOne(Zend_Auth::getInstance()->getIdentity()->id);
	if($user['name']){

	    if($user['website'])
		$this->view->feedbackForm->feedback_page->setAttrib('readonly', 'true');
	    $data = array('feedback_name' => $user['name'], 'feedback_page'=> $user['website']);
	    $this->view->feedbackForm->feedback_name->setAttrib('readonly', 'true');
	    $this->view->feedbackForm->feedback_change->setAttrib('style', 'visibility: visible;');
	}
	$data['id_blog'] = $curr[0]['id'];
	$this->view->feedbackForm->populate($data);
    }

    public function addfeedbackAction()
    {
        $form = new Form_FeedbackForm();
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())){

            $this->feedback->add($form->getValues());
	    if($this->_hasParam('stran'))
		    $this->_redirect('feedback/index/stran/' . $this->_getParam('stran'));
            $this->_redirect('feedback');
        }
        #$errors = $form->getErrors('feedback');
        #throw new Exception($errors[0]);
        
    }



}



