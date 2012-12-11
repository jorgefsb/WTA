<?php

class Core_Controller_ActionMenber extends Core_Controller_Action {

    public function init() {
        $this->_identity = Zend_Auth::getInstance()->getIdentity();
        $this->_helper->layout->setLayout('layout-menber');
        parent::init();
        if(isset($this->_session->navigatorMenber)){
            $container = new Zend_Navigation($this->_session->navigatorMenber);
            $this->view->navigation($container)
                    ->findOneByController($this->getRequest()->getControllerName());
        }
    }
    
     protected function getNavigationMenber() {
        $container = array(
            array(
                'label' => 'Dashboard',
                'id' => 'dashboard',
                'module' => 'menber',
                'controller' => 'dashboard',
                'action' => 'index',
                'order' => 1
            ),
            array(
                'label' => 'Edit my Profile',
                'id' => 'edit-profile',
                'module' => 'menber',
                'controller' => 'edit-profiler',
                'action' => 'index',
                'order' => 2
            ),
            array(
                'label' => 'My Address',
                'id' => 'my-address',
                'module' => 'menber',
                'controller' => 'my-address',
                'action' => 'index',
                'order' => 3
                
            ),
            array(
                'label' => 'Payment Method',
                'id' => 'payment-method',
                'module' => 'menber',
                'controller' => 'payment-method',
                'action' => 'index',
                'order' => 4
            )
        );
        $this->setRegisterNavigation($container);
    }
    protected function setRegisterNavigation(array $navigator) {
        $this->_session->navigatorMenber = $navigator;
    }

}