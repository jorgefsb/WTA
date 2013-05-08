<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    public function __construct($app) {
        parent::__construct($app);
                $app = $this->getOption('app');
        $this->getResourceLoader()->addResourceType('entity', 'entitys/', 'Entity');
        defined('STATIC_URL')
                || define('STATIC_URL', $app['staticUrl']);
        defined('DINAMIC_URL')
                || define('DINAMIC_URL', $app['dinamicUrl']);
        defined('BASE_URL')
                || define('BASE_URL', $app['siteUrl']);
        defined('NAME_SITE')
                || define('NAME_SITE', $app['siteName']);
        $doctypeHelper = new Zend_View_Helper_Doctype();
        $doctypeHelper->doctype(Zend_View_Helper_Doctype::XHTML1_TRANSITIONAL);
        
        require_once APPLICATION_PATH . '/../library/dompdf/dompdf_config.inc.php';
        $autoloader = Zend_Loader_Autoloader::getInstance(); // assuming we're in a controller  
        $autoloader->pushAutoloader('DOMPDF_autoload');
    }
    
/*    protected function _initForceSSL() {
        if ($_SERVER['SERVER_PORT'] != '443') {
            header('Location: https://' . $_SERVER['HTTP_HOST'] .
                    $_SERVER['REQUEST_URI']);
            exit();
        }
    }
*/

    public function _initRegistries() {
        //$config = Zend_Registry::get('config');
        /// Zend_Debug::dump($config); exit;
//        $this->_executeResource('cachemanager');        
//        $cacheManager = $this->getResource('cachemanager');
//        Zend_Debug::dump($cacheManager); exit;
//        $a = $cacheManager->getCache($config['app']['cache']);
//        Zend_Debug::dump($a); exit;
//        Zend_Registry::set('cache',
//            $cacheManager->getCache(
//                $config['app']['cache']
//            )
//        );
        $this->bootstrap('multidb');
        $db = $this->getPluginResource('multidb')->getDb('db');
        Zend_Db_Table::setDefaultAdapter($db);
        //$multidb = $this->getPluginResource('multidb');
        Zend_Registry::set('multidb', $db);
        //Zend_Debug::dump($db); exit;
//        $this->_executeResource('log');
//        $log = $this->getResource('log');
//        Zend_Registry::set('log', $log);
    }

    public function _initActionHelpers() {
//        Zend_Controller_Action_HelperBroker::addHelper(
//            new Core_Controller_Action_Helper_Auth()
//        );
//        Zend_Controller_Action_HelperBroker::addHelper(
//            new App_Controller_Action_Helper_Security()
//        );
        Zend_Controller_Action_HelperBroker::addHelper(
                new Core_Controller_Action_Helper_Mail()
        );
    }

//    public function _initTranslate() {
//        $translator = new Zend_Translate(
//                        Zend_Translate::AN_ARRAY,
//                        APPLICATION_PATH . '/configs/languages/',
//                        'es',
//                        array('scan' => Zend_Translate::LOCALE_DIRECTORY)
//        );
//
//        Zend_Validate_Abstract::setDefaultTranslator($translator);
//    }

//    protected function _initZFDebug()
//    {
//        if ('local' == APPLICATION_ENV) {
//            $options = array('plugins' => array(
//                    'Variables',
//                    'File' => array('base_path' => APPLICATION_PATH),
//                    'Memory',
//                    'Time',
//                    'Registry',
//                    'Exception'
//                ));
//
//            if ($this->hasPluginResource('multidb')) {
//                $this->bootstrap('multidb');
//                $db = $this->bootstrap('multidb')->
//                        getResource('multidb')->getDb('globalhumanitariaperu');
//                $options['plugins']['Database']['adapter'] = $db;
//            }
//
//            if ($this->hasPluginResource('cache')) {
//                $this->bootstrap('cache');
//                $cache = $this - getPluginResource('cache')->getDbAdapter();
//                $options['plugins']['Cache']['backend'] = $cache->getBackend();
//            }
//
//            $debug = new ZFDebug_Controller_Plugin_Debug($options);
//
//            $this->bootstrap('frontController');
//            $frontController = $this->getResource('frontController');
//            $frontController->registerPlugin($debug);
//        }
//    }
}

