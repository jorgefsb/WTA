<?php

class Menber_CreateAccountController extends Core_Controller_ActionMenber {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $arrayResponse = array();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $form = new Application_Form_CreateAccountForm();
            $entityMenber = new Application_Entity_Menber();
            if ($form->isValid($this->_getAllParams())) {
                $entityMenber->setPropertie('_name', $form->getElement('firstName')->getValue());
                $entityMenber->setPropertie('_lastName', $form->getElement('lastName')->getValue());
                $entityMenber->setPropertie('_mail', $form->getElement('mail')->getValue());
                if ($entityMenber->createMenber($form->getElement('password')->getValue())) {
                    $arrayResponse['response'] = 1;
                } else {
                    $arrayResponse['response'] = 0;
                }
                $arrayResponse = array('message' => $entityMenber->getMessage());
            } else {
                $arrayResponse['formMessages'] = $form->getMessages();
                $arrayResponse['formValues'] = $form->getValues();
            }
            $this->_helper->json($arrayResponse);
        }
    }

    public function confirmAction() {
        $entityMenber = new Application_Entity_Menber();
        $entityMenber->confirmAccount($this->_getParam('id'));
        $this->view->message = $entityMenber->getMessage();
    }

}

