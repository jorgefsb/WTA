<?php

class Menber_RecoveryAccountController extends Core_Controller_ActionMenber {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $form = new Application_Form_RecoveryAccountSendForm();
        $menber = new Application_Entity_Menber();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                if ($menber->sendPasswordRecovery($form->getValue('mail'))) {
                    $this->getMessenger()->info($menber->getMessage());
                } else {
                    $this->getMessenger()->error($menber->getMessage());
                }
                $this->_redirect('/menber/recovery-account');
            }
        }
        $this->view->form = $form;
    }

    public function confirmAction() {
        $menber = new Application_Entity_Menber();
        $form = new Application_Form_RecoveryAccountFrom();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $token = $this->getRequest()->getParam('id');
                $password = $form->getValue('password');
                if ($menber->passwordRecovery($token, $password)) {
                    $this->getMessenger()->info($menber->getMessage());
                } else {
                    $this->getMessenger()->error($menber->getMessage());
                }
                $this->_redirect('/menber/login');
            }
        }
        $this->view->form = $form;
    }

}

