<?php

class Core_Controller_ActionCustomer extends Core_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('layout-customer');
        parent::init();
    }

}