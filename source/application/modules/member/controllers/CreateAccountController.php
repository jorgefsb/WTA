<?php

class Member_CreateAccountController extends Core_Controller_ActionMember {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $arrayResponse = array();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $form = new Application_Form_CreateAccountForm();
            $entityMember = new Application_Entity_Member();
            if ($form->isValid($this->_getAllParams())) {
                $entityMember->setPropertie('_name', $form->getElement('firstname')->getValue());
                $entityMember->setPropertie('_lastName', $form->getElement('lastname')->getValue());
                $entityMember->setPropertie('_mail', $form->getElement('email')->getValue());
                if ($entityMember->createMember($form->getElement('password1')->getValue())) {
                    $arrayResponse['response'] = 1;
                } else {
                    $arrayResponse['response'] = 0;
                }
                $arrayResponse = array('message' => $entityMember->getMessage());
            } else {
                $arrayResponse['formMessages'] = $form->getMessages();
                $arrayResponse['formValues'] = $form->getValues();
            }
            $this->_helper->json($arrayResponse);
        }
    }

    public function confirmAction() {
        $this->_helper->layout->setLayout('layout');
        $entityMember = new Application_Entity_Member();
        $entityMember->confirmAccount($this->_getParam('id'));
        $this->view->message = $entityMember->getMessage();
    }

}

