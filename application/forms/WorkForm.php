<?php

class Form_WorkForm extends Form_Form {

     public function init()
    {
        $jobDb = new Model_Jobs();
        $view = new Zend_View();
        $this->setAction($view->url(array('controller'=>'delo', 'action'=>'addwork')));

        $job = new Zend_Form_Element_Select('id_job');
        $job->setLabel("Služba: ")
            ->setDecorators($this->_blockElementDecorator)
            ->setMultiOptions($jobDb->getNames());

        $submitJob = new Zend_Form_Element_Submit("submit_job");
        $submitJob->setDecorators($this->_buttonElementDecorator)
                  ->setLabel('Uredi službo');

        $addJob = new Zend_Form_Element_Button('addjobBtn');
        $addJob->setLabel("Dodaj službo")
               ->setDecorators($this->_buttonElementDecorator)
               ->setAttrib('id', 'addjobBtn');

       
        $date = new Zend_Form_Element_Text('date');
        $date->setDecorators($this->_standardElementDecorator)
             ->setLabel('Datum: ')
             ->setRequired()
             ->setAttrib('size', '8');

        
        $arrivalTime = new Zend_Form_Element_Text('start_time');
        $arrivalTime->setDecorators($this->_standardElementDecorator)
                    ->setLabel('Ura prihoda: ')
                    ->setRequired()
                    ->setAttrib('class', 'time')
                    ->setAttrib('size', '4');


        $departureTime = new Zend_Form_Element_Text('end_time');
        $departureTime->setDecorators($this->_standardElementDecorator)
                    ->setLabel('Ura odhoda: ')
                    ->setRequired()
                    ->setAttrib('class', 'time')
                    ->setAttrib('size', '4');

        $hourlyWage = new Zend_Form_Element_Text('wage');
        $hourlyWage->setDecorators($this->_standardElementDecorator)
                    ->setLabel('Urna postavka: ')
                    ->setRequired()
                    ->setAttrib('size', '4');


         $submit = new Zend_Form_Element_Submit('submit');

        $startWork = new Zend_Form_Element_Submit("submit_start");
        $startWork->setDecorators($this->_buttonElementDecorator)
                  ->setLabel('Začni z delom');

        $endWork = new Zend_Form_Element_Submit("submit_end");
        $endWork->setDecorators($this->_buttonElementDecorator)
                  ->setLabel('Končaj z delom');

        $back = new Zend_Form_Element_Submit('Nazaj');
        $back->setAttrib('id', 'cancelSluzba')
               ->setDecorators($this->_buttonElementDecorator)
               ->setAttrib("value", "cancel");




        $this->addElements(array($job, $date, $arrivalTime, $departureTime, $hourlyWage, $startWork, $endWork, $back));

        $this->addElement('submit', 'submit', array(
            'decorators' => $this->_buttonElementDecorator,
            'label' => 'Dodaj',

        ));

        /*
       $this->addDisplayGroup(array('id_job', 'submit_job', 'addjobBtn'), 'group_job', array(
            'disableLoadDefaultDecorators' => 'true',
            'decorators' => $this->_standardGroupDecorator,
            'legend' => 'Izberi službo',
            'class' => 'form',

        ));
        */
        $this->addDisplayGroup(array('id_job', 'date', 'start_time', 'end_time','wage', 'edit_job', 'submit', 'Nazaj', 'activework'), 'dodajdelo', array(
                'disableLoadDefaultDecorators' => 'true',
                'decorators' => $this->_standardGroupDecorator,
                'legend' => 'Dodaj delo',
                'class' => 'form',
        ));
       $this->addDisplayGroup(array('submit_start', 'submit_end'), 'activework', array(
                'disableLoadDefaultDecorators' => 'true',
	        'legend' => 'Aktivno delo',
                'decorators' => $this->_standardGroupDecorator,
       ));
    }
}

