<?php

class BlogController extends Zend_Controller_Action
{
    private $blog;
    private $msg;

    public function init()
    {
        
        $this->blog = new Model_Feedback();
        $this->msg = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction()
    {
        
        $this->view->headTitle("Blog");

        $this->view->user = Zend_Auth::getInstance()->getIdentity();

        //Get blogs:
        $blog = new Model_Blog();
        $paginator = Zend_Paginator::factory($blog->getAll());
        $paginator->setCurrentPageNumber($this->_getParam('stran'));
        $paginator->setDefaultItemCountPerPage(1);
        $this->view->blogs = $paginator;

        //Get comments & form:
        $curr = $paginator->getCurrentItems();
        $this->view->feedback = $this->blog->get($curr[0]['id']);
        $this->view->feedbackForm = new Form_FeedbackForm();

        //Populate form with user info:
        if(Zend_Auth::getInstance()->hasIdentity()){
            $users = new Model_Users();
            $user = $users->getOne(Zend_Auth::getInstance()->getIdentity()->id);
        }
        else{
            $user['name'] = $this->getRequest()->getCookie('feedback_name');
            $user['website'] = $this->getRequest()->getCookie('feedback_page');
        }
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

            //Set cookies if not set:
            if(!Zend_Auth::getInstance()->hasIdentity()){

                setcookie("feedback_name", $this->_getParam('feedback_name'), time()+30*60*60*60);
                setcookie("feedback_page", $this->_getParam('feedback_page'), time()+30*60*60*60);
            }
            $this->msg->addMessage(array('Tvoj komentar je bil dodan, hvala.', 'success'));
            $this->blog->add($form->getValues());
            $this->redirectToPage();
        }
        else{
            $this->msg->addMessage(array('Vnesti moraš vsa zahtevana polja.', 'error'));
            $this->redirectToPage();

        }


        
        #$errors = $form->getErrors('feedback');
        #throw new Exception($errors[0]);
        
    }

    public function deletefeedbackAction(){
        $id = $this->_getParam('id');
        $feedback = new Model_Feedback();
        $feedback->delete($id);
        $this->msg->addMessage(array('Komentar je bil izbrisan.', 'info'));
        $this->redirectToPage();
    }

    private function redirectToPage(){
        if($this->_hasParam('stran'))
                $this->_redirect('blog/index/stran/' . $this->_getParam('stran'));
            else
                $this->_redirect('blog');
    }
    
}



