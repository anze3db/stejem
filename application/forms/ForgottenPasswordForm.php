<?php

class Form_ForgottenPasswordForm extends Form_Form{
    
    public function init(){

        $view = new Zend_View();
        $this->setAttrib("class", "expose");
        $this->setAction('/uporabnik/geslo');

        $mail = new Zend_Form_Element_Text('mail');
        $mail->setDecorators($this->_standardElementDecorator)
              ->setLabel("Zaupati mi boš moral svoj E-Mail:")
              ->setRequired();
        $pass1 = new Zend_Form_Element_Password('pass1');
        $pass1->setDecorators($this->_standardElementDecorator)
            ->setLabel('Geslo:')
            ->setRequired();

        $pass2 = new Zend_Form_Element_Password('pass2');
        $pass2->setDecorators($this->_standardElementDecorator)
            ->setLabel('Geslo (ponovno):')
            ->setRequired();

        $this->addElements(array($mail, $pass1, $pass2));

        $this->addElement('submit', 'submit_email', array(
            'decorators' => $this->_buttonElementDecorator,
            'label' => 'Potrdi',

        ));

        $this->addDisplayGroup(array('mail', 'pass1', 'pass2', 'submit_email'), 'geslo', array(
                'disableLoadDefaultDecorators' => 'true',
                'decorators' => $this->_standardGroupDecorator,
                'class' => 'form',
                'legend' => 'Izgubljeno geslo',
        ));

    }
}
?>
