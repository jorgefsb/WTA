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
                        $loginForm->getValue('email'), 
                        $loginForm->getValue('password'))) {
                    $this->getNavigationMember();
                    $arrayResponse['response'] = 1;
                    $arrayResponse['redirect'] = '/member/dashboard';
                }else{
                    $arrayResponse['response'] = 0;
                    $arrayResponse['redirect'] = '';
                }
                $arrayResponse = array('message'=>$entityMember->getMessage());
                
                if( !isset($this->_session->_tracking) ){ //Captura el intento de logueo
                    $this->_session->_tracking = new Core_Tracking();
                }
                $this->_session->_tracking->setAction('LOGIN',
                                                                                isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                                                                                $arrayResponse
                                                                            );
                
            } else {
                $arrayResponse['formMessages'] = $loginForm->getMessages();
                $arrayResponse['formValues'] = $loginForm->getValues();
            }
            
            $this->_helper->json($arrayResponse);
        }
        //$this->view->form = $loginForm;
    }

}

