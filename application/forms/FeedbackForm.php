<?php
class Form_FeedbackForm extends Form_Form
{
        public function init()
        {
                        
            $this->setName('feedback_form');
            
            $view = new Zend_View();
            $this->setAction($view->url(array('controller'=>'blog', 'action'=>'addfeedback')));

	    $id_blog = new Zend_Form_Element_Hidden('id_blog');
	    $id_blog->setDecorators($this->_buttonElementDecorator)
		    ->addFilter('Int');

            $feedback = new Zend_Form_Element_Textarea('feedback');
            $feedback->setDecorators($this->_blockElementDecorator)
                     ->setLabel('Tvoje mnenje: ')
                     ->setAttrib('rows', '10')
                     ->setAttrib('cols', '115')
		    ->addFilter('HtmlEntities')
		    ->addFilter('StringTrim')
                     ->setRequired();
	    $feedbackName = new Zend_Form_Element_Text('feedback_name');
	    $feedbackName->setDecorators($this->_standardElementDecorator)
                     ->setLabel('Ime: ')
		    ->addFilter('StripTags')
		    ->addFilter('StringTrim')
		     ->setRequired();
	    $feedbackPage = new Zend_Form_Element_Text('feedback_page');
	    $feedbackPage->setDecorators($this->_standardElementDecorator)
                     ->setLabel('Stran: ')
		     ->setAttrib('size', '40')
		    ->addFilter('StripTags')
		    ->addFilter('StringTrim');

	    $change = new Zend_Form_Element_Button('feedback_change');
	    $change->setDecorators($this->_buttonElementDecorator)
		   ->setLabel('Spremeni vrednosti')
		   ->setAttrib('id', 'feedback_change');

	    $submit = new Zend_Form_Element_Submit('Dodaj');
            $submit->setAttrib('id', 'submitFeedback')
            ->setDecorators($this->_buttonElementDecorator)
            ->setAttrib("value", "feedback");
            $captcha = new Zend_Form_Element_Captcha('captcha', array(
                    'label' => "Prepričaj me, da si človek:",
                    'captcha' => array(
                        'captcha' => 'Figlet',
                        'wordLen' => 5,
                        'timeout' => 3000,
                    ),
                    ));
            $this->addElements(array($id_blog, $feedbackName, $feedbackPage, $change, $feedback, $captcha, $submit));

            $disp = $this->addDisplayGroup(array('feedback_name', 'feedback_page','id_blog', 'feedback_change', 'feedback','captcha', 'Dodaj'), 'dodajfeedback', array(
                'disableLoadDefaultDecorators' => 'true',
                'decorators' => $this->_standardGroupDecorator,
                'class' => 'form',
                'legend' => 'Dodaj mnenje',
            ));

        }
}