<?php

class Core_Controller_ActionAdmin extends Core_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('layout-admin');
        parent::init();
    }

}