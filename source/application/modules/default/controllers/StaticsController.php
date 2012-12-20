<?php

class Default_StaticsController extends Core_Controller_ActionDefault       
{
    
    
    public function init() {
        parent::init();
        
        if( !Zend_Auth::getInstance()->hasIdentity() && !$this->_session->authBeta){
            $this->redirect('/beta');
        }
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }          
    
    public function affiliatesAction(){

        
    } 
    
    public function aboutusindividualAction(){
        
    }
    
    
    public function smallaboutAction(){
        $this->_helper->layout->disableLayout();        
        
    }
    
    
    public function contactusAction(){
        $this->_helper->layout->disableLayout();        
    }
    
    public function comingsoonAction(){
        $this->_helper->layout->disableLayout();        
        
    }
    
    public function termsAction(){

        
    }    
    
    public function rewardingmembersAction(){

        
    }
    
    public function helpAction(){

        
    }   
    
    
    public function privacyAction(){

        
    }   
    
    public function howitworksAction(){

        
    }   
    
    
    
}

