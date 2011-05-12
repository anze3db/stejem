<?php
class Form_BlogForm extends Form_Form
{
        public function init()
        {
                        
            $this->setName('blog_form');
            
            $view = new Zend_View();
            $this->setAction($view->url(array('controller'=>'admin', 'action'=>'addblog')));
            $content = new Zend_Form_Element_Textarea('blog_content');
            $content->setDecorators($this->_blockElementDecorator)
                     ->setLabel('Vsebina: ')
                     ->setAttrib('rows', '10')
                     ->setAttrib('cols', '115')
                     ->setRequired();
	    $title = new Zend_Form_Element_Text('blog_title');
	    $title->setDecorators($this->_standardElementDecorator)
                     ->setLabel('Naslov: ')
		     ->setRequired();

            $submit = new Zend_Form_Element_Submit('Dodaj');
            $submit->setAttrib('id', 'submitBlog')
            ->setDecorators($this->_buttonElementDecorator)
            ->setAttrib("value", "blog");

	    $submit_preview = new Zend_Form_Element_Submit('Prekliči');
            $submit_preview->setAttrib('id', 'previewBlog')
            ->setDecorators($this->_buttonElementDecorator);

	    $objavi = new Zend_Form_Element_Checkbox('active');
            $objavi->setLabel('Objavi:')
                           ->setDecorators($this->_blockElementDecorator)
                           ->setRequired()
                           ->setAttrib('checked', 'true');

            $this->addElements(array($title, $content, $submit, $objavi, $submit_preview));

            $disp = $this->addDisplayGroup(array('blog_title', 'blog_content', 'active', 'Dodaj', 'Prekliči'), 'dodajblog', array(
                'disableLoadDefaultDecorators' => 'true',
                'decorators' => $this->_standardGroupDecorator,
                'class' => 'form',
                'legend' => 'Dodaj blog',
            ));

        }
}