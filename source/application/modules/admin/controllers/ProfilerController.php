<?php

class Admin_ProfilerController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $user = new Application_Entity_User();
        $user->identify($this->_identityUserAdmin->user_id);
        $form = new Application_Form_CreateUserFrom();
        $properties = $user->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $arrayPopulate['user'] = $properties['_mail'];
        //$arrayPopulate['active'] = $properties['_active'];
        //$arrayPopulate['userType'] = $properties['_userType'];
        $form->removeElement('active');
        $form->removeElement('userType');
        $form->populate($arrayPopulate);
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $user->setPropertie('_name', $form->getValue('name'));
                $user->setPropertie('_mail', $form->getValue('user'));
                $user->setPropertie('_active', $form->getValue('active'));
                $user->setPropertie('_userType', $form->getValue('userType'));
                if ($user->update() !== FALSE) {
                    if ($form->getValue('password') != '') {
                        $user->reserPassword($form->getValue('password'));
                    }
                    $this->_flashMessenger->addMessage($user->getMessage());
                } else {
                    $this->_flashMessenger->error($user->getMessage());
                }
                $this->_redirect('/admin/profiler');
            }
        }
        $this->view->form = $form;
    }

}