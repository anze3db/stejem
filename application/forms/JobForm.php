<?php
class Form_JobForm extends Form_Form
{
        public function init()
        {
                        
            $this->setName('sluzba');
            
            $view = new Zend_View();
            $this->setAction($view->url(array('controller'=>'sluzbe', 'action'=>'addjob')));
            $podjetje = new Zend_Form_Element_Text('name');
            $podjetje->setDecorators($this->_standardElementDecorator)
                     ->setLabel('Podjetje: ')
                     ->setRequired()
                     ->setAttrib('size', '10');

            $postavka = new Zend_Form_Element_Text('job_wage');
            $postavka->setLabel('Urna postavka:')

                     ->setDecorators($this->_standardElementDecorator)
                     ->setRequired()
                     ->setAttrib('size', '4');

            $start_time = new Zend_Form_Element_Text('job_start_time');
            $start_time->setLabel('Ura prihoda:')
                     ->setAttrib("class", "time")
                     ->setDecorators($this->_standardElementDecorator)
                     ->setAttrib('size', '4');

            $end_time = new Zend_Form_Element_Text('job_end_time');
            $end_time->setLabel('Ura odhoda:')
                     ->setAttrib("class", "time")
                     ->setDecorators($this->_standardElementDecorator)
                     ->setAttrib('size', '4');
            
            $primary = new Zend_Form_Element_Checkbox('job_primary');
            $primary->setLabel('Primarna:')
                           ->setDecorators($this->_standardElementDecorator)
                           ->setValue(1)
                           ->setAttrib('checked', 'true');
            
            $submit = new Zend_Form_Element_Submit('Dodaj');
            $submit->setAttrib('id', 'submitSluzba')
            ->setDecorators($this->_buttonElementDecorator)
            ->setAttrib("value", "sluzba");

	    $back = new Zend_Form_Element_Submit('Nazaj');
            $back->setAttrib('id', 'cancelSluzba')
		   
		   ->setDecorators($this->_buttonElementDecorator)
		   ->setAttrib("value", "cancel");

            $this->addElements(array($podjetje, $start_time, $end_time, $postavka, $primary, $submit, $back));

            $disp = $this->addDisplayGroup(array('name', 'job_start_time', 'job_end_time', 'job_wage', 'job_primary', 'Dodaj', 'Nazaj'), 'dodajsluzbo', array(
                'disableLoadDefaultDecorators' => 'true',
                'decorators' => $this->_standardGroupDecorator,
                'class' => 'form',
                'legend' => 'Dodaj službo',
            ));

        }
}