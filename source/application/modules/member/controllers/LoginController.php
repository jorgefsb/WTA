<?php

class Member_LoginController extends Core_Controller_ActionMember {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $arrayResponse = array();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $loginForm = new Application_Form_LoginForm();
            $entityMember = new Application_Entity_Member();
            if ($loginForm->isValid($this->_getAllParams())) {
                if ($entityMember->autentificate(
                        $loginForm->getElement('email')->getValue(), 
                        $loginForm->getElement('password')->getValue())) {
                    $this->getNavigationMember();
                    $arrayResponse['response'] = 1;
                    $arrayResponse['redirect'] = '/member/dashboard';
                }else{
                    $arrayResponse['response'] = 0;
                    $arrayResponse['redirect'] = '';
                }
                $arrayResponse = array('message'=>$entityMember->getMessage());
            } else {
                $arrayResponse['formMessages'] = $loginForm->getMessages();
                $arrayResponse['formValues'] = $loginForm->getValues();
            }
            
            $this->_helper->json($arrayResponse);
        }
        //$this->view->form = $loginForm;
    }

}

