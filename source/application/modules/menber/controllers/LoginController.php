<?php

class Menber_LoginController extends Core_Controller_ActionMenber {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $arrayResponse = array();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $loginForm = new Application_Form_LoginForm();
            $entityMenber = new Application_Entity_Menber();
            if ($loginForm->isValid($this->_getAllParams())) {
                if ($entityMenber->autentificate(
                        $loginForm->getElement('login')->getValue(), 
                        $loginForm->getElement('password')->getValue())) {
                    $this->getNavigationMenber();
                    $arrayResponse['response'] = 1;
                    $arrayResponse['redirect'] = '/menber/dashboard';
                }else{
                    $arrayResponse['response'] = 0;
                    $arrayResponse['redirect'] = '';
                }
                $arrayResponse = array('message'=>$entityMenber->getMessage());
            } else {
                $arrayResponse['formMessages'] = $loginForm->getMessages();
                $arrayResponse['formValues'] = $loginForm->getValues();
            }
            
            $this->_helper->json($arrayResponse);
        }
        //$this->view->form = $loginForm;
    }

}

