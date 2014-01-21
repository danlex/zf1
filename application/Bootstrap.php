<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    
	protected function _initPlaceholders() 
	{
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view   = $layout->getView();
        $view->strictVars();

        #jquery ui css / jqgrid css
        $view->headLink()->appendStylesheet('http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
        $view->headLink()->appendStylesheet('/jqgrid/css/ui.jqgrid.css');

        //javascript:
        $view->headScript()->prependFile('http://code.jquery.com/jquery-1.9.1.js');
        $view->headScript()->appendFile('http://code.jquery.com/ui/1.10.3/jquery-ui.js');

        #jqgrid
        $view->headScript()->appendFile('/jqgrid/js/i18n/grid.locale-ro.js');
        $view->headScript()->appendFile('/jqgrid/js/minified/jquery.jqGrid.min.js');
        
        $view->headLink()->appendStylesheet('/css/bootstrap.min.css');
        $view->headLink()->appendStylesheet('/css/bootstrap-theme.min.css');
        $view->headScript()->appendFile('/js/bootstrap.min.js');
        
        $view->headTitle()->setSeparator(' - ');
    }
    
    protected function _initConfig() 
    {
        $config = new Zend_Config_Ini("../application/configs/application.ini", APPLICATION_ENV);
        Zend_Registry::set('config', $config);
    }
    
    protected function _initCache()
    {
    	$manager = new Zend_Cache_Manager;
	    $frontendOpts = array(
		    'caching' => true,
		    'lifetime' => 1800,
		    'automatic_serialization' => true
		);
		  
		$backendOpts = array(
		    'servers' =>array(
		        array(
		        'host' => 'localhost',
		        'port' => 11211
		        )
		    ),
		    'compression' => false
		);
     
    	$cache = Zend_Cache::factory('Core', 'Memcached', $frontendOpts, $backendOpts);
    	Zend_Registry::set('Cache', $cache);
    }
}

