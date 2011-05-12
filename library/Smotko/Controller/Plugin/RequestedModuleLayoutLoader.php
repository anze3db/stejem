<?php
/*
 * Loads different layout based on current module
 * 
 */
class Smotko_Controller_Plugin_RequestedModuleLayoutLoader
    extends Zend_Controller_plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $config     = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini');
        $config = $config->toArray();
        
        $moduleName = $request->getModuleName();
        
        if (isset($config[APPLICATION_ENV][$moduleName]['resources']['layout']['layout'])) {
            $layoutScript = $config[$moduleName]['resources']['layout']['layout'];
            Zend_Layout::getMvcInstance()->setLayout($layoutScript);
        }

        if (isset($config[APPLICATION_ENV][$moduleName]['resources']['layout']['layoutPath'])) {
            $layoutPath = $config[APPLICATION_ENV][$moduleName]['resources']['layout']['layoutPath'];
            $moduleDir = Zend_Controller_Front::getInstance()->getModuleDirectory();
            Zend_Layout::getMvcInstance()->setLayoutPath(
                $moduleDir. DIRECTORY_SEPARATOR .$layoutPath
            );
        }
    }
}