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
                'label' => 'Home Backgrounds',
                'id' => 'backgrounds',
                'module' => 'admin',
                'controller' => 'backgrounds',
                'action' => 'index',
                'resource' => 'backgrounds',
                'order' => 6
                
            ),
            
            array(
                'label' => 'Designers',
                'id' => 'designer',
                'module' => 'admin',
                'controller' => 'designer',
                'action' => 'index',
                'resource' => 'designer',
                'order' => 7
                
            ),
            
            array(
                'label' => 'Design Types',
                'id' => 'designtypes',
                'module' => 'admin',
                'controller' => 'designtypes',
                'action' => 'index',
                'resource' => 'designtypes',
                'order' => 8
                
            ),
            
            array(
                'label' => 'Dynamic Contents',
                'id' => 'contents',
                'module' => 'admin',
                'controller' => 'contents',
                'action' => 'index',
                'resource' => 'contents',
                'order' => 9
                
            ),
            
            array(
                'label' => 'Subscriptions',
                'id' => 'subscriptions',
                'module' => 'admin',
                'controller' => 'subscriptions',
                'action' => 'download',
                'resource' => 'subscriptions',
                'order' => 10
                
            )
             
            /*,
            array(
                'label' => 'Shopping reports',
                'id' => 'shopping-reports',
                'module' => 'admin',
                'resource' => 'shopping-reports',
                'controller' => 'shopping-reports',
                'order' => 7
            ),*/
        );
        
        $this->setRegisterNavigation($container);
    }
     protected function setRegisterNavigation(array $navigator) {
        $this->_sessionAdmin->navigator = $navigator;
    }

}