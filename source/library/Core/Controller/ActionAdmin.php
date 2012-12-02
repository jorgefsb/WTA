<?php

class Core_Controller_ActionAdmin extends Core_Controller_Action {

    protected $_sessionAdmin;
    
    public function init() {
        parent::init();
        $this->_sessionAdmin = new Zend_Session_Namespace('sessionAdmin');
        $this->getNavigationSuperAdmin();
        $this->_helper->layout->setLayout('layout-admin');
        if(isset($this->_sessionAdmin->navigator)){//findOneByController
            $container = new Zend_Navigation($this->_sessionAdmin->navigator);
            $this->view->navigation($container)
                    ->findOneByController($this->getRequest()->getControllerName());
        }
        $this->view->headTitle()->setSeparator(' - ');
        $this->view->headTitle('WTA Admin!');
    }
    
    protected function getNavigationSuperAdmin() {
        $container = array(
            array(
                'label' => 'Users Management',
                'id' => 'user-management',
                'module' => 'admin',
                'controller' => 'user-management',
                'action' => 'index',
                'order' => 1
            ),
            array(
                'label' => 'Products Catalog',
                'id' => 'products-catalog',
                'module' => 'admin',
                'controller' => 'product',
                'action' => 'index',
                'order' => 2
                
            ),
            array(
                'label' => 'Orders',
                'id' => 'order-list',
                'module' => 'admin',
                'controller' => 'order-list',
                'action' => 'email-preferences',
                'order' => 3
            ),
            array(
                'label' => 'Menbers',
                'id' => 'menbers',
                'module' => 'admin',
                'controller' => 'menbers',
                'order' => 4
            ),
            array(
                'label' => 'Shopping reports',
                'id' => 'shopping-reports',
                'module' => 'admin',
                'controller' => 'shopping-reports',
                'order' => 5
            ),
        );
        $this->setRegisterNavigation($container);
    }
     protected function setRegisterNavigation(array $navigator) {
        $this->_sessionAdmin->navigator = $navigator;
    }

}