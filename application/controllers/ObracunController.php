<?php

class ObracunController extends Zend_Controller_Action
{

    public function init()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/');
        }
    }

    public function indexAction()
    {
        $this->view->headTitle("Obračun");
        $year = $this->_getParam('pokazi');

        #clearance table:
        $clear = new Model_Clearance();
        $this->view->years = $clear->getYears();
        if(empty($year))
            $year = date('Y');
        
        if($year == 'vse')
            $clearTable = $clear->get();
        else{
            $clearTable = $clear->getByYear($year);
        }
        
        $this->view->clearance=$clearTable;
        $this->view->year = $year;
        
    }
    public function deleteclearanceAction(){
        $id = $this->_getParam('id');
        $clearance = new Model_Clearance();
        $clearance->delete($id);
        $this->_helper->getHelper('FlashMessenger')->addMessage(array('Obračun je bil izbrisan, obračunana dela so se ti vrnila med trenutna dela.', 'info'));
        $this->_redirect('/delo');
    }


}

