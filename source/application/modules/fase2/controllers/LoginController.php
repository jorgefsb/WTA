<?php

class Fase2_LoginController extends Core_Controller_ActionDefault       
{
    
    public function init() {
        parent::init();
        
        //if( !Zend_Auth::getInstance()->hasIdentity() && !$this->_session->authBeta){
          //  $this->redirect('/beta');
        //}
        
                
    }
    
    public function signoutAction(){
        Zend_Auth::getInstance()->clearIdentity();
        $this->redirect('/');
    }      
    
    public function signinAction(){
        $this->view->headTitle('Sign-in');
        $this->view->headScript()->appendScript('document.location.href = "'.BASE_URL.'#sign-in";');
        if( $this->getRequest()->isXmlHttpRequest()  ){
            $this->_helper->layout->disableLayout();
        }
    }
    
    public function forgotpassAction(){
        $this->_helper->layout->disableLayout();  
    }
    
    
    public function reservedAction(){
        $this->_helper->layout->disableLayout();  
    }
    
    public function exclusiveAction(){
        $this->_helper->layout->disableLayout();  
        $this->view->invitation = $this->_session->invitation = array(
            'email'=>$this->getRequest()->getParam('email'),
            'password'=>$this->getRequest()->getParam('password'));
    }
    
    
}

