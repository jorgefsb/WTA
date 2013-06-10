<?php

class Admin_OrdersController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->view->headScript()->appendScript('
            $(document).ready(function(){
                $(".btn-setting").click(function(evento){
                var request = $(this).attr("id");
                var requestTitle = $(this).attr("title");
                    $.ajax({
                        type: "POST",
                        url: "/admin/orders/get-order-information?transaction="+request,
                        data: { id: $(this).id }
                    }).done(function(response) {
                        $("#myModal").find(".modal-body").html(response);
                    });
                });
                
                $(".btn-tracking").click(function(e){
                    e.preventDefault();
                    $("#myModalTracking").modal("show");
                    var request = $(this).attr("id");
                    var requestTitle = $(this).attr("title");
                    $.ajax({
                        type: "POST",
                        url: "/admin/orders/get-tracking?transaction="+request,
                        data: { id: $(this).id }
                    }).done(function(response) {
                        $("#myModalTracking").find(".modal-body").html(response);
                    });
                }); 
        
                $(".btn-returning").click(function(e){
                    e.preventDefault();
                    $("#myModalReturn").modal("show");
                });
            });
        ');
        $this->view->headScript()->appendScript('
            $(document).ready(function(){
            
                $("#countries").change(function(evento){
                
                $.ajax({
                        type: "POST",
                        url: "/admin/orders/get-state?country="+$(this).attr("value"),
                        data: { id: $(this).id }
                    }).done(function(data) {
                        $("#state").empty();
                        $.each(data, function(index, value) { 
                            $("#state").append("<option value=\'"+value["id"]+"\'>"+value["name"]+"</option>");
                        });
                        $("#state").trigger("liszt:updated");
                    });
                    
                });
            });
        ');
        
        $this->view->headScript()->appendScript('
            $(document).ready(function(){
            $("#filter").css("display", "none");
            $("#plusFilter").click(function () {        
                $("#filter").toggle("slow",function(){
                 if($("#filter").css("display") == "none"){
                $("#plusFilter").attr("class", "icon-plus");
                }else{
                $("#plusFilter").attr("class", "icon-minus");
                }
              });
            }); 
            });
        ');
        
        $this->view->headScript()->appendScript('
            $(document).ready(function(){
                $(".btn-returning").click(function(evento){
                    $("#yesReturn").attr("data",$(this).attr("href"));    
                });
                
                $("#yesReturn").click(function(evento){
                    window.location.href = $(this).attr("data");
                });
            });
        ');
        
        $modelRegions = new Application_Model_Regions();
        $arrayData = $this->getRequest()->getQuery();
        
        if( empty($arrayData) ){
            $arrayData['status'][] = Application_Entity_Transaction::TRANSACTION_PAID;
        }
        
        $this->view->fromDate = (isset($arrayData['fromDate']) && $arrayData['fromDate'] != '') ? $arrayData['fromDate'] : '';
        $this->view->toDate = (isset($arrayData['toDate']) && $arrayData['toDate'] != '') ? $arrayData['toDate'] : '';
        $this->view->menbers = isset($arrayData['menbers']) ? $arrayData['menbers'] : array();
        $this->view->status = isset($arrayData['status']) ? $arrayData['status'] : array();
        $this->view->stateDelivered = isset($arrayData['stateDelivered']) ? $arrayData['stateDelivered'] : array();
        $this->view->countrySelect = isset($arrayData['countries']) ? $arrayData['countries'] : array();
        if (!empty($this->view->countrySelect)) {
            $this->view->subRegions = $modelRegions->listingSubregions($arrayData['countries']);
            $this->view->subRegionsSelect = isset($arrayData['state']) ? $arrayData['state'] : array();
        } else {
            $this->view->subRegions = array();
            $this->view->subRegionsSelect = '';
        }
        if (isset($arrayData['fromDate']) && $arrayData['fromDate'] != '') {
            $data = explode('/', $arrayData['fromDate']);
            $arrayData['fromDate'] = $data[2] . '-' . $data[0] . '-' . $data[1];
        } else {
            unset($arrayData['fromDate']);
        }
        if (isset($arrayData['toDate']) && $arrayData['toDate'] != '') {
            $data = explode('/', $arrayData['toDate']);
            $arrayData['toDate'] = $data[2] . '-' . $data[0] . '-' . $data[1];
        } else {
            unset($arrayData['toDate']);
        }
        if (!(isset($arrayData['countries']) && $arrayData['countries'] != '')) {
            unset($arrayData['countries']);
        }
        
        $this->view->orders = Application_Entity_Transaction::listOrders($arrayData);
        $this->view->userOrders = Application_Entity_Transaction::listOrdensUsers();
        $this->view->country = $modelRegions->listing(array(840));
    }

    public function getOrderInformationAction() {
        $this->_helper->layout()->disableLayout();
        
        $idTransaction = $this->getParam('transaction');
        
        $transsaction = new Application_Entity_Transaction();
        $data = $transsaction->identify($idTransaction);
        $nameMember = $this->getParam('member');
        $modelRegions = new Application_Model_Regions();
        $modelsubRegion = new Application_Model_SubRegions();
        $this->view->country = Core_Utils::fetchPairs($modelRegions->listing(), 4);
        //$subregions = Core_Utils::fetchPairs($modelRegions->listingSubregions());
        
        $data['contact'] = 'Member: ' . $nameMember;
        $data['mail'] = 'Mail: ' . $data['transaction_mail'];

        if($data['transaction_shi_add_subregion_id']){
            $subregion = $modelsubRegion->getSubRegion($data['transaction_shi_add_subregion_id']);
            $this->view->subregion_shp = $subregion['name'];
        }
        
        if($data['transaction_bill_add_region_id']){
            $subregion = $modelsubRegion->getSubRegion($data['transaction_bill_add_subregion_id']);
            $this->view->subregion_bill = $subregion['name'];
        }
        
        $this->view->data = $data;
    }
    
    public function getTrackingAction(){
        $this->_helper->layout()->disableLayout();
        
        $idTransaction = $this->getParam('transaction');
        
        $modelTraking = new Application_Model_Tracking();
        $data = $modelTraking->getTrakingByTransaction($idTransaction);
        
        $data = unserialize($data['tracking_code']);
        
        $this->view->data = $data;
        
        $_product = new Application_Entity_Product();
        
        $productos_vistos = array();
        if(is_array($data)){
            foreach($data as $action){
                if( isset($action['data']['code']) && !empty($action['data']['code']) ){
                    if( !isset($productos_vistos[$action['data']['code']]) && (int)$action['data']['code']>0){
                        $_product->identify($action['data']['code']);
                        $tmpProperties = $_product->getProperties();
                        $tmpProperties['images'] = $_product->listingImg();
                        $productos_vistos[$action['data']['code']] = $tmpProperties;
                    }
                }
            }
        }
        
        $this->view->productos_vistos = $productos_vistos;
        
        
    }
    

    public function getStateAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $modelRegions = new Application_Model_Regions();
        $idRegion = $this->getParam('country');
        $array = $modelRegions->listingSubregions($idRegion);
        $this->_helper->json($array);
    }
    
    public function returningAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->identify($this->getParam('id'));
        $transaction->returned();
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }

    public function deliveredAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->identify($this->getParam('id'));
        $transaction->delivered();
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }

    public function undeliveredAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->identify($this->getParam('id'));
        $transaction->undelivered();
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }

    public function shippingAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->shipping($this->getParam('id'));
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }

    public function unshippingAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->unshipping($this->getParam('id'));
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }
    
}