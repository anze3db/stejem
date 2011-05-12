<?php

class Form_Form extends Zend_Form {

    protected $_standardElementDecorator = array(
        'ViewHelper',
        array('Label'),
        array('Errors'),
        array('HtmlTag', array('tag'=>'li', )),
    );

    protected $_blockElementDecorator = array(
        'ViewHelper',
        array('Label'),
        //array('Errors'),
        array('HtmlTag', array('tag'=>'li', 'class'=>'block')),
    );

    protected $_buttonElementDecorator = array(
        'ViewHelper',
        array('HtmlTag', array('tag'=>'li')),
    );

    protected $_standardGroupDecorator = array(
        'FormElements',
        array('HtmlTag', array('tag'=>'ul')),
        'Fieldset',
    );
    protected $_basicGroupDecorator = array(
        'FormElements',
        array('HtmlTag', array('tag'=>'ul')),
    );

    protected $_buttonGroupDecorator = array(
        'FormElements',
        'Fieldset'
    );
    protected $_htmlBlockDescriptor = array(
        array('Description',array('escape'=>false,'tag'=>'li', 'class'=>'block'))
    );
    protected $_htmlDescriptor = array(
        array('Description',array('escape'=>false,'tag'=>'li'))
    );

    public function __construct($options = null){
        parent::__construct($options);
        $this->setAttrib("accept-charset", "UTF-8");
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));

    }
}

