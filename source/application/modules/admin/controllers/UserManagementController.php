<?php

class Admin_UserManagementController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $paginator = Zend_Paginator::factory(Application_Entity_User::listing());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingUser  = $paginator;
    }

    public function newAction() {
        $form = new Application_Form_CreateUserFrom();
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $user = new Application_Entity_User();
                $user->setPropertie('_name', $form->getValue('name'));
                $user->setPropertie('_user', $form->getValue('user'));
                $user->setPropertie('_mail', $form->getValue('user'));
                $user->setPropertie('_active', $form->getValue('active'));
                $user->createUser($form->getValue('password'));
                $this->_flashMessenger->addMessage($user->getMessage());
                $this->_redirect('/admin/actress/');
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {
        
    }

    public function publishAction() {
        
    }

    public function unpublishAction() {
        
    }

    public function upAction() {
        
    }

    public function downAction() {
        
    }

}

