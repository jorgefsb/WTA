<?php

class Admin_LoginController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout-admin-login');
        $form = new Application_Form_LoginForm();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $user = new Application_Entity_User();
                if ($user->autentificate(
                        $form->getValue('login'), 
                        $form->getValue('password'))) {
                    $this->_redirect('/admin/');
                }else{
                    $this->_flashMessenger->error($user->getMessage());
                    $this->_redirect('/admin/login');
                }
            } else {
                
                $this->view->login = $form->getValue('login');
                $this->view->password = $form->getValue('password');
            }
        }
    }

}

