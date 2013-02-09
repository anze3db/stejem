<?php

class Mobile_DeloController extends Zend_Controller_Action
{

    private $user = null;

    public function init()
    {
        
        if(Zend_Auth::getInstance()->hasIdentity())
            $this->user = Zend_Auth::getInstance()->getIdentity();
        else
            $this->_redirect('/mobile');

    }

    public function indexAction()
    {
        //'cos otherwise it fails when testing :F
        if(!Zend_Auth::getInstance()->hasIdentity()) return;
        
        $this->view->headTitle('Delo');
        
        $this->view->workActiveForm = new Form_WorkActiveMobileForm();


        $activeWork = new Model_WorkActive();
        
        $activeWork = $activeWork->get();
        if(is_array($activeWork)){
            $this->view->workActiveForm->removeElement('submit_start');
            $this->view->workActiveForm->setAction('/mobile/delo/end');
            $this->view->workActiveForm->id_job->setAttrib('readonly', 'readonly');
            $this->view->workActiveForm->removeElement('wage');
            $this->view->workActiveForm->removeElement('id_job');
        }
        else{
            $this->view->workActiveForm->removeElement('submit_end');
            $this->view->workActiveForm->setAction('/mobile/delo/start');
        }
        $this->view->workActive = $activeWork;
        #jobs table:
        $jobs = new Model_Jobs();
        $jobsTable = $jobs->getNames();
        $this->view->jobs = $jobsTable;

        $jobsDef = $jobs->getDefault();
        $jobsDef['wage'] = $this->view->currencyFormat($jobsDef['wage']);
        $this->view->workActiveForm->populate($jobsDef);

        //last 5 work:
        $work = new Model_Work();
        $workTable = $work->get();
        $this->view->work=$workTable;
        
    }

    public function startAction()
    {
        $activeWork = new Model_WorkActive();
        $activeWork->start($this->_getAllParams());
        $this->_redirect('/mobile/delo');
    }

    public function endAction()
    {
        $activeWork = new Model_WorkActive();
        $activeWork->end();
        $this->_redirect('/mobile/delo');
    }


}





