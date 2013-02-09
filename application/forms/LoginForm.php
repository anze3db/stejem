<?php

class Form_LoginForm extends Form_Form{
    
    public function init(){

        $view = new Zend_View();
        $this->setAttrib("class", "expose");
        $this->setAction($view->url(array('controller'=>'uporabnik', 'action'=>'prijava')));

        $user = new Zend_Form_Element_Text('user_login');
        $user->setDecorators($this->_standardElementDecorator)
                
              ->setLabel("E-mail:")
              ->setRequired();


        $pass = new Zend_Form_Element_Password('pass_login');
        $pass->setDecorators($this->_standardElementDecorator)
             ->setLabel("Geslo:")
                
             ->setRequired();

        $remember = new Zend_Form_Element_Checkbox('remember_login');
        $remember->setDecorators($this->_standardElementDecorator)
             ->setLabel("Zapomni:");


        $this->addElements(array($user, $pass, $remember));

        $this->addElement('submit', 'submit_login', array(
            'decorators' => $this->_buttonElementDecorator,
            'label' => 'Prijavi',

        ));

        $this->addDisplayGroup(array('user_login', 'pass_login', 'remember_login', 'submit_login'), 'prijava', array(
                'disableLoadDefaultDecorators' => 'true',
                'decorators' => $this->_basicGroupDecorator,
        ));

    }
}
?>
