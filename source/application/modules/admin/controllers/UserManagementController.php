<?php

class Admin_UserManagementController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $paginator = Zend_Paginator::factory(Application_Entity_User::listing());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingUser = $paginator;
    }

    public function newAction() {
        $form = new Application_Form_CreateUserFrom();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $user = new Application_Entity_User();
                $user->setPropertie('_name', $form->getValue('name'));
                $user->setPropertie('_mail', $form->getValue('user'));
                $user->setPropertie('_active', $form->getValue('active'));
                if ($user->createUser($form->getValue('password'))) {
                    $this->_flashMessenger->addMessage($user->getMessage());
                } else {
                    $this->_flashMessenger->error($user->getMessage());
                }
                $this->_redirect('/admin/user-management/');
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {
        $user = new Application_Entity_User();
        $user->identify($this->getRequest()->getParam('id'));
        $form = new Application_Form_CreateUserFrom();
        $form->setAction('/admin/user-management/edit/id/' . $this->getRequest()->getParam('id'));
        $properties = $user->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $arrayPopulate['user'] = $properties['_mail'];
        $arrayPopulate['active'] = $properties['_active'];
        $form->populate($arrayPopulate);
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $user->setPropertie('_name', $form->getValue('name'));
                $user->setPropertie('_mail', $form->getValue('user'));
                $user->setPropertie('_active', $form->getValue('active'));
                if ($user->update()!==FALSE) {
                    if($form->getValue('password')!=''){
                        $user->reserPassword($form->getValue('password'));
                    }
                    $this->_flashMessenger->addMessage($user->getMessage());
                } else {
                    $this->_flashMessenger->error($user->getMessage());
                }
                $this->_redirect('/admin/user-management/');
            }
        }
        $this->view->form = $form;
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

