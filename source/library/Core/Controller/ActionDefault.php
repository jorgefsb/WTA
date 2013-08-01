<?php

class Core_Controller_ActionDefault extends Core_Controller_Action {

    protected $_tackName = '';
    protected $_tackUrl = '';
    protected $_tackData = '';
    protected $_tackUrlRef = '';
    protected $_tackDate = '';

    public function init() {
        parent::init();

        if (!isset($this->_session->_tracking)) {
            $this->_session->_tracking = new Core_Tracking();
        }

        $this->_identity = $this->view->identity = Zend_Auth::getInstance()->getIdentity();        
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            unset($this->view->identity);
        }
        
        $this->hasMembership = $this->view->hasMembership = false;

        if(!isset($this->_identity->member_id)){
            unset($this->_identity);
            unset($this->view->identity);
        }else{
            $this->view->isMember = $this->isMember = true;
            if(isset($this->_identity->membership) && !empty($this->_identity->membership)){
                $this->hasMembership = $this->view->hasMembership = true;
                $this->_session->cartMembership = null;
            }
        }

    }

    public function preDispatch() {

    }

    public function postDispatch() {
        if ($this->_tackName && $this->_tackUrl) {
            $this->_session->_tracking->setAction($this->_tackName, $this->_tackUrl, $this->_tackData, $this->_tackUrlRef, $this->_tackDate
            );
        }
        //Zend_Debug::dump($this->_session->_tracking);
    }

}