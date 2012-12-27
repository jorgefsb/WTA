<?php

class Member_LoginController extends Core_Controller_ActionMember {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $arrayResponse = array();
                
        if(Zend_Auth::getInstance()->hasIdentity()){
            $this->redirect('/member/dashboard');
        }        
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            
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
        }else{
            
            $loginForm = new Application_Form_LoginForm();
            $entityMember = new Application_Entity_Member();
            
            if ($loginForm->isValid($this->_getAllParams())) {
                if ($entityMember->autentificate(
                        $loginForm->getValue('email'), 
                        $loginForm->getValue('password'))) {
                    $this->getNavigationMember(); 
                    
                    if( !isset($this->_session->_tracking)) { //Captura el intento de logueo
                        $this->_session->_tracking = new Core_Tracking();
                    }
                    $this->_session->_tracking->setAction('LOGIN', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', $arrayResponse
                    );
                    
                    $this->_redirect('/member/dashboard');
                }
                    
                
                $this->getMessenger()->info($entityMember->getMessage());                
                
            } else {
                $arrayResponse['formMessages'] = $loginForm->getMessages();
                $arrayResponse['formValues'] = $loginForm->getValues();
            }
                        
            
            $this->view->form = $loginForm;
        }
        
    }

}

