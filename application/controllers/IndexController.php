<?php

class IndexController extends Zend_Controller_Action
{

    public function init(){
   
    }

    public function indexAction(){

        $auth = Zend_Auth::getInstance();
        $this->view->headTitle("Index");
        $this->view->logged = $auth->hasIdentity();
        #REGISTER FORM:
        $this->view->registerForm = new Form_RegisterForm();
        #BLOGS:
        $blog = new Model_Blog();
        $this->view->blogs = $blog->getAll();
    }

    public function rssAction(){

        //Disable view/layout:
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();

        //Create array to store the RSS feed entrie:
        $entries = array();        
        $blog = new Model_Blog();

        $feed = Zend_Feed::importArray($blog->getFeed(), 'rss');

        //Show feed:
        $feed->send();
    }
}