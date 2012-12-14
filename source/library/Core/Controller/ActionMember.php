<?php

class Core_Controller_ActionMember extends Core_Controller_Action {

    public function init() {
        $this->_identity = Zend_Auth::getInstance()->getIdentity();
        $this->_helper->layout->setLayout('layout-member');
        parent::init();
        if(isset($this->_session->navigatorMember)){
            $container = new Zend_Navigation($this->_session->navigatorMember);
            $this->view->navigation($container)
                    ->findOneByController($this->getRequest()->getControllerName());
        }
    }
    
     protected function getNavigationMember() {
        $container = array(
            array(
                'label' => 'Dashboard',
                'id' => 'dashboard',
                'module' => 'member',
                'controller' => 'dashboard',
                'action' => 'index',
                'order' => 1
            ),
            array(
                'label' => 'Edit my Profile',
                'id' => 'edit-profile',
                'module' => 'member',
                'controller' => 'edit-profiler',
                'action' => 'index',
                'order' => 2
            ),
            array(
                'label' => 'My Address',
                'id' => 'my-address',
                'module' => 'member',
                'controller' => 'my-address',
                'action' => 'index',
                'order' => 3
                
            ),
            array(
                'label' => 'Payment Method',
                'id' => 'payment-method',
                'module' => 'member',
                'controller' => 'payment-method',
                'action' => 'index',
                'order' => 4
            )
        );
        $this->setRegisterNavigation($container);
    }
    protected function setRegisterNavigation(array $navigator) {
        $this->_session->navigatorMember = $navigator;
    }

}