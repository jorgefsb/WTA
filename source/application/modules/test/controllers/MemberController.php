<?php

class Test_MemberController extends Zend_Controller_Action {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $form = new Application_Form_CreateAccountForm();
        $entityMember = new Application_Entity_Member();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $entityMember->setPropertie('_name', $form->getElement('firstname')->getValue());
                $entityMember->setPropertie('_lastName', $form->getElement('lastname')->getValue());
                $entityMember->setPropertie('_mail', $form->getElement('email')->getValue());
                if ($entityMember->createMember($form->getElement('password1')->getValue())) {
                    $arrayResponse['response'] = 1;
                    $entityMember->addMembership();
                } else {
                    $arrayResponse['response'] = 0;
                }
                //$arrayResponse = array('message' => $entityMember->getMessage());
            } 
        }
       $this->view->form = $form;
    }

    
}

