<?php

class Core_Controller_ActionDefault extends Core_Controller_Action {

    
    protected $_tackName = '';
    protected $_tackUrl = '';
    protected $_tackData = '';
    protected $_tackUrlRef = '';
    protected $_tackDate = '';
    
    public function init() {
        parent::init();
        
        if( !isset($this->_session->_tracking) ){
            $this->_session->_tracking = new Core_Tracking();
        }
    }
    
    public function preDispatch() {
        
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