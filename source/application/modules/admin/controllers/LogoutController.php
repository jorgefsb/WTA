<?php

class Admin_LogoutController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/admin');
    }

}

