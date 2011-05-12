<?php

class Form_RegisterForm extends Form_Form{
    
    public function init(){

        $view = new Zend_View();
        $this->setAction($view->url(array('controller'=>'uporabnik', 'action'=>'registracija')));

        $email = new Zend_Form_Element_Text('email');
        $email->setDecorators($this->_standardElementDecorator)
              ->setLabel('E-mail: ')
              ->setValidators(array(new Zend_Validate_EmailAddress()))
              ->setRequired();


        $pass1 = new Zend_Form_Element_Password('pass1');
        $pass1->setDecorators($this->_standardElementDecorator)
            ->setLabel('Geslo:')
            ->setRequired();

        $pass2 = new Zend_Form_Element_Password('pass2');
        $pass2->setDecorators($this->_standardElementDecorator)
            ->setLabel('Geslo (ponovno):')
            ->setRequired();


        $this->addElements(array($email, $pass1, $pass2));

        $this->addElement('submit', 'submit', array(
            'decorators' => $this->_buttonElementDecorator,
            'label' => 'Registriraj',

        ));

        $this->addDisplayGroup(array('email', 'pass1', 'pass2', 'submit'), 'registracija', array(
                'disableLoadDefaultDecorators' => 'true',
                'decorators' => $this->_basicGroupDecorator,
        ));

    }
}
?>
