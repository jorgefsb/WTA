<?php

class Default_BetaController extends Core_Controller_ActionDefault       
{
    private $_tackName = '';
    private $_tackUrl = '';
    private $_tackData = '';
    private $_tackUrlRef = '';
    private $_tackDate = '';
    
    
    public function init() {
        parent::init();
                
        $this->_helper->contextSwitch()
                ->addActionContext('addtocart', 'json')
                ->addActionContext('removeitem', 'json')
                ->addActionContext('changeitem', 'json')                
                ->addActionContext('countcart', 'json')
                ->initContext();
//        print_r($_SERVER);die($_SERVER['REQUEST_URI']);
        
        
        if( !isset($this->_session->_tracking) ){
            $this->_session->_tracking = new Core_Tracking();
        }
        
        $this->_helper->layout->setLayout('layout_beta');
        
    }
    
    public function indexAction(){
        
    }
        
    
    public function postDispatch() {
        if($this->_tackName && $this->_tackUrl){
            $this->_session->_tracking->setAction($this->_tackName,
                                                                            $this->_tackUrl,
                                                                            $this->_tackData,
                                                                            $this->_tackUrlRef,
                                                                            $this->_tackDate
                                                                        );
        }
        //Zend_Debug::dump($this->_session->_tracking);
    }
    
}

