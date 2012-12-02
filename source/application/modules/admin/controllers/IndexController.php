<?php

class Admin_IndexController extends Core_Controller_ActionAdmin       
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    { 
        $this->_helper->layout->setLayout('layout-admin-login');
    } 
}

