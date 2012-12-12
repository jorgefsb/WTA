<?php

class Default_IndexController extends Core_Controller_ActionDefault       
{
    
    public function init() {
        parent::init();
        $this->_helper->contextSwitch()->addActionContext('addtocart', 'json')->initContext();
        
        if( !isset($this->_session->count) ){
            $this->_session->count = 0;
        }
    }
    
    
    public function indexAction(){
        
    } 
    
    
    public function designersAction(){
        
    } 
    
    
    
    public function exclusiveAction(){
        
    }
    
    
    public function limitedAction(){
        
    }
    
    public function cartAction(){
        $this->_helper->layout->disableLayout();
        
        $this->view->nprod = $this->_session->count;    
        
    }
    
    
    public function addtocartAction(){
        $this->_helper->layout->disableLayout();
        $this->_session->count++;
        $return = array('ok'=>true);
        //die(json_encode($return));
        $this->view->ok = 1;
        
    }
    
    
    public function aboutusindividualAction(){
        
    }
    
    
    public function smallaboutAction(){
        $this->_helper->layout->disableLayout();        
    }
    
    
    public function contactusAction(){
        $this->_helper->layout->disableLayout();        
    }
    public function comingSoonAction(){
        $this->_helper->layout->disableLayout();        
    }
    
    public function signinAction(){
        $this->_helper->layout->disableLayout();  
    }
    
    public function forgotpassAction(){
        $this->_helper->layout->disableLayout();  
    }
    
    public function termsAction(){

    }
    
    
    public function rewardingMembersAction(){

    }
    
    
}

