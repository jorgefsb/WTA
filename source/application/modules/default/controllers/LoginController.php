<?php

class Default_LoginController extends Core_Controller_ActionDefault       
{
    
    public function init() {
        parent::init();
        
        if( !Zend_Auth::getInstance()->hasIdentity() && !$this->_session->authBeta){
            $this->redirect('/beta');
        }
        
                
    }
    
    public function signoutAction(){
        Zend_Auth::getInstance()->clearIdentity();
        $this->redirect('/');
    }      
    
    public function signinAction(){
        $this->_helper->layout->disableLayout();  
                
    }
    
    public function forgotpassAction(){
        $this->_helper->layout->disableLayout();  
    }
    
    
}

