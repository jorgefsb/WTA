<?php

class Core_Controller_ActionMenber extends Core_Controller_Action {

    public function init() {
        $this->_identity = Zend_Auth::getInstance()->getIdentity();
        parent::init();
    }

}