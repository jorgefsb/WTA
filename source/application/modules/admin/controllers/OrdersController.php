<?php

class Admin_OrdersController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->view->orders = Application_Entity_Transaction::listOrders();
    }
    
}