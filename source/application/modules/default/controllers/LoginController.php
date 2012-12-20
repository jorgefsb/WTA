<?php

class Default_LoginController extends Core_Controller_ActionDefault       
{
    
    public function init() {
        parent::init();
        
        $action = $this->_getParam('action','');
        if( Zend_Auth::getInstance()->hasIdentity() && $action !='signin' && 'forgotpass'){
            //$this->redirect('/beta');
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

