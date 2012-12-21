<?php

class Core_Controller_ActionAdmin extends Core_Controller_Action {

    protected $_sessionAdmin;
    protected $_identityUserAdmin;
    
    public function init() {
        parent::init();
        
        $this->_identityUserAdmin = Zend_Auth::getInstance()->getIdentity();
        
        $this->_sessionAdmin = new Zend_Session_Namespace('sessionAdmin');
        $this->getNavigationSuperAdmin();
        $this->_helper->layout->setLayout('layout-admin');
        
        if(isset($this->_sessionAdmin->navigator) && isset($this->_identityUserAdmin) ){//findOneByController
            if(!isset($this->_identityUserAdmin->user_id)){
                
            }
            $container = new Zend_Navigation($this->_sessionAdmin->navigator);
            $acl = new Application_Entity_AclAdmin();
            $arrayRoles = Application_Entity_AclAdmin::$arrayRole;
            $role = $arrayRoles[isset($this->_identityUserAdmin->user_type_id)?$this->_identityUserAdmin->user_type_id:0];
            $this->view->navigation($container)
                    ->setAcl($acl)
                    ->setRole($role)
                    ->findOneByController($this->getRequest()->getControllerName());
            $resource = $this->getRequest()->getControllerName();
            if(!$acl->isAllowed($role, $resource)){
                $this->_helper->layout->setLayout('layout-admin-noautorize');
                $this->getRequest()
                        ->setModuleName('admin')
                        ->setControllerName('noautorize');
            }
            
            //print_r($this->_identityUserAdmin);
            //echo $this->getRequest()->getControllerName();
        }
        $this->view->headTitle()->setSeparator(' - ');
        $this->view->headTitle('WTA Admin!');
        if($this->getRequest()->getControllerName()!='login' && $this->getRequest()->getControllerName()!='noautorize'
                && empty($this->_identityUserAdmin)){
            $this->redirect('/admin/login');
        }
    }
    
    protected function getNavigationSuperAdmin() {
        $container = array(
            array(
                'label' => 'Users Management',
                'id' => 'user-management',
                'module' => 'admin',
                'controller' => 'user-management',
                'action' => 'index',
                'resource' => 'user-management',
                'order' => 1
            ),
            array(
                'label' => 'Products Catalog',
                'id' => 'products-catalog',
                'module' => 'admin',
                'controller' => 'product',
                'action' => 'index',
                'resource' => 'product',
                'order' => 2
                
            ),
            array(
                'label' => 'Celebrity',
                'id' => 'actress',
                'module' => 'admin',
                'controller' => 'actress',
                'action' => 'index',
                'resource' => 'actress',
                'order' => 3
                
            ),
            array(
                'label' => 'Orders',
                'id' => 'order-list',
                'module' => 'admin',
                'controller' => 'orders',
                'action' => 'index',
                'resource' => 'orders',
                'order' => 4
            ),
            array(
                'label' => 'Members',
                'id' => 'members',
                'module' => 'admin',
                'controller' => 'members',
                'resource' => 'members',
                'order' => 5
            ),
            array(
                'label' => 'Shopping reports',
                'id' => 'shopping-reports',
                'module' => 'admin',
                'resource' => 'shopping-reports',
                'controller' => 'shopping-reports',
                'order' => 6
            ),
        );
        $this->setRegisterNavigation($container);
    }
     protected function setRegisterNavigation(array $navigator) {
        $this->_sessionAdmin->navigator = $navigator;
    }

}