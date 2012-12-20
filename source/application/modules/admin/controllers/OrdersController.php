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
                url: "/admin/orders/get-member-information?transaction="+request+"&member="+requestTitle,
                data: { id: $(this).id }
            }).done(function(data) {
                $.each(data, function(index, value) { 
                    $("#"+index).text(value);
                });
            });
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
        $modelRegions = new Application_Model_Regions();
        $arrayData = $this->getRequest()->getQuery();
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
        $this->view->country = $modelRegions->listing();
    }

    public function getMemberInformationAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $transsaction = new Application_Entity_Transaction();
        $data = $transsaction->identify($this->getParam('transaction'));
        $nameMember = $this->getParam('member');
        $modelRegions = new Application_Model_Regions();
        $country = Core_Utils::fetchPairs($modelRegions->listing(), 4);
        $subregions = Core_Utils::fetchPairs($modelRegions->listingSubregions());
        $array['contact'] = 'Member: ' . $nameMember;
        $array['mail'] = 'Mail: ' . $data['transaction_mail'];

        $array['shiFirstName'] = 'First Name: ' . $data['transaction_shi_add_first_name'];
        $array['shiLastName'] = 'Last Name: ' . $data['transaction_shi_add_last_name'];
        $array['shiAddres'] = 'Address: ' . $data['transaction_shi_add_addres'];
        $array['shiAddresContinued'] = 'Address Continued: ' . $data['transaction_shi_add_addres_continued'];
        $array['shiPostalCode'] = 'Postal Code: ' . $data['transaction_shi_add_postal_code'];
        $array['shiRegion'] = 'Country: ' . (isset($country[$data['transaction_shi_add_region_id']])?$country[$data['transaction_shi_add_region_id']]:'');
        $array['shiSubRegion'] = 'State: ' . (isset($subregions[$data['transaction_shi_add_subregion_id']])?$subregions[$data['transaction_shi_add_subregion_id']]:'');
        $array['shiCity'] = 'City: ' . $data['transaction_shi_add_city'];
        $array['shiPhone'] = 'Phone: ' . $data['transaction_shi_add_phone_number'];
        $array['billFirstName'] = 'First Name: ' . $data['transaction_bill_add_first_name'];
        $array['billLastName'] = 'Last Name: ' . $data['transaction_bill_add_last_name'];
        $array['billAddres'] = 'Address: ' . $data['transaction_bill_add_addres'];
        $array['billAddresContinued'] = 'Address Continued: ' . $data['transaction_bill_add_addres_continued'];
        $array['billCity'] = 'City: ' . $data['transaction_bill_add_city'];
        $array['billRegion'] = 'Country: ' . (isset($country[$data['transaction_bill_add_region_id']])?$country[$data['transaction_bill_add_region_id']]:'');
        $array['billSubRegion'] = 'State: ' . (isset($subregions[$data['transaction_bill_add_subregion_id']])?$subregions[$data['transaction_bill_add_subregion_id']]:'');
        $array['billPostalCode'] = 'Postal Code: ' . $data['transaction_bill_add_postal_code'];
        $array['billPhone'] = 'Phone: ' . $data['transaction_bill_add_phone_number'];

        $array['cardNumber'] = 'Card Number: ' . $data['transaction_card_number'];
        $array['cardType'] = 'Card Type: ' . $data['card_type_id'];
        $array['segurityCode'] = 'Segurity Code: ' . $data['transaction_card_segurity_code'];
        $array['expire'] = 'Expire: ' . $data['transaction_card_expiration_month'] . '/' . $data['transaction_card_expiration_year'];
        $this->_helper->json($array);
    }

    public function getStateAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $modelRegions = new Application_Model_Regions();
        $idRegion = $this->getParam('country');
        $array = $modelRegions->listingSubregions($idRegion);
        $this->_helper->json($array);
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

}