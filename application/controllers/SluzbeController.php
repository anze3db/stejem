<?php

class SluzbeController extends Zend_Controller_Action
{

    private $msg;
    public function init()
    {
        $this->msg = $this->_helper->getHelper('FlashMessenger');
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->msg->addMessage(array('Za dostop moraš biti prijavljen.', 'info'));
            $this->_redirect('/');
        }
    }

    public function indexAction()
    {
        $this->view->headTitle("Službe");
        $jobs = new Model_Jobs();
                
        $this->view->jobs = $jobs->get();

        $this->view->jobForm = new Form_JobForm();

        $this->view->jobForm->removeElement('Nazaj');
        $this->view->jobForm->populate(array('job_start_time' => '09:00', 'job_end_time' => '15:00'));
    }

    public function urediAction()
    {
        $jobForm = new Form_JobForm();
        $job = new Model_Jobs();

        $req = $this->getRequest();
        if($req->isPost() && $jobForm->isValid($req->getPost())){
            if($this->_getParam('Nazaj') == null){
                 $this->msg->addMessage(array('Spremembe so se shranile.', 'success'));
                $job->edit($this->_getAllParams());
            }
            $this->_redirect('/sluzbe/');

        }
        //GET JOB FIELD VALUES
        $jobsDef = $job->getById($this->_getParam('id'));
        $jobsDef['job_start_time'] = $this->view->timeFormat($jobsDef['start_time']);
        $jobsDef['job_end_time'] = $this->view->timeFormat($jobsDef['end_time']);
        $jobsDef['job_wage'] = $this->view->currencyFormat($jobsDef['wage']);
        $jobsDef['job_date'] = $this->view->dateFormat(time());
        $jobsDef['job_primary'] = $jobsDef['primary'];
        $jobForm->populate($jobsDef);
        //MODIFY FORM:
        $jobForm->Dodaj->setLabel("Shrani")
                       ->setValue("edit");

        $jobForm->setAction($this->view->url(array('controller'=>'sluzbe', 'action'=>'uredi')));
        $jobForm->dodajsluzbo->setLegend("Uredi službo");
        //SEND FORM TO VIEW:
        $this->view->jobForm = $jobForm;
    }

    public function addjobAction()
    {
        $job = new Model_Jobs();
        $form = new Form_JobForm();
        $req = $this->getRequest();
        if($req->isPost() && $form->isValid($req->getPost())){
            try{

                $job->add($this->_getAllParams());
            }
            catch(Exception $e){
                #TODO LOGIN ERROR HANDLING:
                throw $e;

            }
            $this->msg->addMessage(array('Služba je bila dodana.', 'success'));
            if(count($job->getNames())>1)
                    $this->_redirect('/sluzbe/');
            $this->msg->addMessage(array('Sedaj lahko začneš z delom, srečno!', 'info'));
            $this->_redirect('/delo/');
        }
        throw new Exception("FIELDS EMPTY", 30);
    }

    public function deletejobAction()
    {
        $id = $this->_getParam('id');
        $jobs = new Model_Jobs();
        $jobs->delete($id);
        $this->msg->addMessage(array('Služba je bila izbrisana.', 'info'));
        $this->_redirect('/sluzbe/');
    }

    public function primaryAction()
    {

        $id = $this->_getParam('id');
        $jobs = new Model_Jobs();
        $jobs->primary($id);
        $this->_redirect('/sluzbe/');
    
    }


}





