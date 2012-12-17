<?php

class Admin_OrdersController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        //print_r($this->getRequest()->getRequestUri());
        $arrayData = $this->getRequest()->getQuery();
        $this->view->fromDate = (isset($arrayData['fromDate']) && $arrayData['fromDate']!='')?$arrayData['fromDate']:'';
        $this->view->toDate = (isset($arrayData['toDate'])  && $arrayData['toDate']!='')?$arrayData['toDate']:'';
        $this->view->menbers = isset($arrayData['menbers'])?$arrayData['menbers']:array();
        $this->view->state = isset($arrayData['state'])?$arrayData['state']:array();
        $this->view->stateDelivered = isset($arrayData['stateDelivered'])?$arrayData['stateDelivered']:array();
        if(isset($arrayData['fromDate']) && $arrayData['fromDate']!=''){
            $data = explode('/',$arrayData['fromDate']);
            $arrayData['fromDate'] = $data[2].'-'.$data[0].'-'.$data[1];
        }else{
            unset($arrayData['fromDate']);
        }
        if(isset($arrayData['toDate']) && $arrayData['toDate']!=''){
            $data = explode('/',$arrayData['toDate']);
            $arrayData['toDate'] = $data[2].'-'.$data[0].'-'.$data[1];
        }else{
            unset($arrayData['toDate']);
        }
        $this->view->orders = Application_Entity_Transaction::listOrders($arrayData);
        $this->view->userOrders = Application_Entity_Transaction::listOrdensUsers();
    }
    
}