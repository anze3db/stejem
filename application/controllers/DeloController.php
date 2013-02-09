<?php

class DeloController extends Zend_Controller_Action
{
    private $msg;
    
    public function init(){

        $this->msg = $this->_helper->getHelper('FlashMessenger');

        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->msg->addMessage(array('Za dostop moraš biti prijavljen.', 'info'));
            $this->_redirect('/');
        }
        


    }
    public function indexAction(){

        $this->view->headTitle('Delo');
      
        $this->view->workForm = new Form_WorkForm();
        $this->view->workForm->removeElement('Nazaj');
        #active work:
        $activeWork = new Model_WorkActive();
        $this->view->activeWork = $activeWork->get();
        if(is_array($this->view->activeWork)){
            $this->view->workForm->submit_start->setAttrib('class', 'hide');
        }
        else{
            $this->view->workForm->submit_end->setAttrib('class', 'hide');
        }
        #jobs table:
        $jobs = new Model_Jobs();
        $jobsTable = $jobs->getNames();
        $this->view->jobs = $jobsTable;
        #work table:
	
        $work = new Model_Work();
        $workTable = $work->get();
        $this->view->work=$workTable;

        #default values for work form:
        if(!empty($jobsTable)){
            $jobsDef = $jobs->getDefault();
            $jobsDef['start_time'] = $this->view->timeFormat($jobsDef['start_time']);
            $jobsDef['end_time'] = $this->view->timeFormat($jobsDef['end_time']);
            $jobsDef['wage'] = $this->view->currencyFormat($jobsDef['wage']);
            $jobsDef['date'] = date("d.m.Y");
            $this->view->workForm->populate($jobsDef);
        }



    }

    public function addworkAction(){
        $work = new Model_Work();
        
        
        if($this->getRequest()->isPost()){
            try{
                $params = $this->_getAllParams();
                if($params["submit_start"]){
                    $activeWork = new Model_WorkActive();
                    $activeWork->start($params);
                    $this->msg->addMessage(array('Začel si z delom, naj bo čim bolj produktivno :)', 'info'));
                    $this->_redirect('/delo');

                }
                else if($params["submit_end"]){
                    $activeWork = new Model_WorkActive();
                    $activeWork->end();
                    $this->msg->addMessage(array('Delo je bilo uspešno dodano.', 'success'));
                    $this->_redirect('/delo');
                }
                else{
                    $work->add($params);
                    $this->msg->addMessage(array('Delo je bilo uspešno dodano.', 'success'));
                    $this->_redirect('/delo');
                }
            }
            catch(Exception $e){
                #TODO LOGIN ERROR HANDLING:
                throw $e;

            }

        }

        
    }
    public function addclearanceAction(){
        $work = new Model_Work();
        if($this->getRequest()->isPost()){
            $params = $this->_getAllParams();	    
            $work->updateClearance($params['stat']);
        }
	$this->msg->addMessage(array('Obdobje je bilo obračunano.', 'success'));
        $this->_redirect('/obracun');
    }
    public function deleteworkAction(){
        $id = $this->_getParam('id');
        $work = new Model_Work();
        $work->delete($id);
        $this->msg->addMessage(array('Delo je bilo izbrisano.', 'info'));
        $this->_redirect('/delo/');
    }

    public function urediAction(){
        
        $form = new Form_WorkForm();
        $form->submit->setLabel('Shrani');
        $form->dodajdelo->setLegend('Uredi delo');
        //Removes active work:
        $form->activework->setDecorators(array());

        $id = $this->_getParam('id');
        $form->setAction('/delo/uredi/id/'. (int)$id);

        $work = new Model_Work();
        $req = $this->getRequest();
        
        if($req->isPost() && $form->isValid($req->getPost())){
            if($this->_getParam('Nazaj') == null){
                $work->edit($this->_getAllParams());
                $this->msg->addMessage(array('Spremembe so se shranile.', 'success'));
            }
            $this->_redirect('/delo');

        }       
        $arr = $work->getById($id);

        $arr['start_time'] = $this->view->timeFormat($arr['start_time']);
        $arr['end_time'] = $this->view->timeFormat($arr['end_time']);
        $arr['wage'] = $this->view->currencyFormat($arr['wage']);
        $arr['date'] = $this->view->dateFormat($arr['date']);
        $form->populate($arr);
        $this->view->workForm = $form;
    }
    public function jsonAction(){
        $job = new Model_Jobs();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
        $res = $job->getById($this->_getParam('id'));
        $ele = array();
        $ele['start_time'] = $this->view->timeFormat($res['start_time']);
        $ele['end_time'] = $this->view->timeFormat($res['end_time']);
        $ele['wage'] = $this->view->currencyFormat($res['wage']);

        print Zend_Json::encode($ele);
       
    }

}

