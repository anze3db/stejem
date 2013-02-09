<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{

    protected function _initAutoload(){
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH));
        return $moduleLoader;
    }
    protected function _initViewHelpers(){
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $view->setEscape(array('Bootstrap', 'escape'));
        $view->addHelperPath(APPLICATION_PATH . '/views/helpers');
        
        $view->setEncoding('UTF-8');
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->headTitle()->setSeparator(' - ');
        $view->headTitle('Štejem.si');

    }
    public function escape($str){
        return stripslashes(htmlspecialchars($str));
    }


}

