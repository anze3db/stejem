<?php
include APPLICATION_PATH . '/models/Jobs.php';

class Form_WorkActiveMobileForm extends Form_Form {

     public function init()
    {
        
        $jobDb = new Model_Jobs();
        $view = new Zend_View();
        $this->setAction('/mobile/delo');

        $job = new Zend_Form_Element_Select('id_job');
        $job->setLabel("Služba: ")
            ->setDecorators($this->_blockElementDecorator)
            ->setMultiOptions($jobDb->getNames())
                    ;

        

        $startWork = new Zend_Form_Element_Submit("submit_start");
        $startWork->setDecorators($this->_buttonElementDecorator)
                  ->setLabel('Začni z delom');

        $endWork = new Zend_Form_Element_Submit("submit_end");
        $endWork->setDecorators($this->_buttonElementDecorator)
                  ->setLabel('Končaj z delom');

        $hourlyWage = new Zend_Form_Element_Text('wage');
        $hourlyWage->setDecorators($this->_standardElementDecorator)
                    ->setLabel('Urna postavka: ')
                    ->setRequired()
                    ->setAttrib('size', '4');

        $this->addElements(array($job, $hourlyWage, $startWork, $endWork));

        
       $this->addDisplayGroup(array('submit_start', 'submit_end', 'id_job', 'wage'), 'group_work', array(
            'disableLoadDefaultDecorators' => 'true',
            'decorators' => $this->_standardGroupDecorator,
            'class' => 'form',

        ));

    }
}

