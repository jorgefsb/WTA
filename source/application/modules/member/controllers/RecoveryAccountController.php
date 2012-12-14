<?php

class Member_RecoveryAccountController extends Core_Controller_ActionMember {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $form = new Application_Form_RecoveryAccountSendForm();
        $member = new Application_Entity_Member();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                if ($member->sendPasswordRecovery($form->getValue('mail'))) {
                    $this->getMessenger()->info($member->getMessage());
                } else {
                    $this->getMessenger()->error($member->getMessage());
                }
                $this->_redirect('/member/recovery-account');
            }
        }
        $this->view->form = $form;
    }

    public function confirmAction() {
        $member = new Application_Entity_Member();
        $form = new Application_Form_RecoveryAccountFrom();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $token = $this->getRequest()->getParam('id');
                $password = $form->getValue('password');
                if ($member->passwordRecovery($token, $password)) {
                    $this->getMessenger()->info($member->getMessage());
                } else {
                    $this->getMessenger()->error($member->getMessage());
                }
                $this->_redirect('/member/login');
            }
        }
        $this->view->form = $form;
    }

}

