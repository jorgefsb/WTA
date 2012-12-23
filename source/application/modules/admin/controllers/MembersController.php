<?php

class Admin_MembersController extends Core_Controller_ActionAdmin {

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
                url: "/admin/members/get-member-information?member="+request+"&member="+requestTitle,
                data: { id: $(this).id }
            }).done(function(data) {
                $.each(data, function(index, value) { 
                    $("#"+index).text(value);
                });
            });
        });
    });
');

        
        if($this->_getParam('search','')==''){
            $members = Application_Entity_Member::listing();
        }else{
            $members = Application_Entity_Member::searchMember($this->_getParam('search',''));
        }
        
        $this->view->search = $this->_getParam('search','');
        $paginator = Zend_Paginator::factory($members);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingMember = $paginator;
    }

    public function activeAction() {
        $Member = new Application_Entity_Member();
        $Member->identify($this->getRequest()->getParam('id'));
        $Member->active();
        $this->_redirect('/admin/members/');
    }

    public function inactiveAction() {
        $Member = new Application_Entity_Member();
        $Member->identify($this->getRequest()->getParam('id'));
        $Member->inactive();
        $this->_redirect('/admin/members/');
    }

    /*
     * Nuevo metodo para obetner la informacion
     */
    public function getMemberInformationAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $member = new Application_Entity_Member();
        $data = $member->identify($this->getParam('member'));
        $nameMember = $this->getParam('member');
        $modelRegions = new Application_Model_Regions();
        $array['contact'] = 'Member: ' . $nameMember;
        $array['mail'] = 'Mail: ' . $data['member_mail'];
        
        $this->_helper->json($array);
    }
    
    /*
     *  Se sustituye por la informacion traida del servicio de pago
     * 
    public function getMemberInformationAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $member = new Application_Entity_Member();
        $data = $member->identify($this->getParam('member'));
        $nameMember = $this->getParam('member');
        $modelRegions = new Application_Model_Regions();
        $country = Core_Utils::fetchPairs($modelRegions->listing(), 4);
        $subregions = Core_Utils::fetchPairs($modelRegions->listingSubregions());
        $array['contact'] = 'Member: ' . $nameMember;
        $array['mail'] = 'Mail: ' . $data['member_mail'];

        $array['shiFirstName'] = 'First Name: ' . $data['member_shi_add_first_name'];
        $array['shiLastName'] = 'Last Name: ' . $data['member_shi_add_last_name'];
        $array['shiAddres'] = 'Address: ' . $data['member_shi_add_addres'];
        $array['shiAddresContinued'] = 'Address Continued: ' . $data['member_shi_add_addres_continued'];
        $array['shiPostalCode'] = 'Postal Code: ' . $data['member_shi_add_postal_code'];
        $array['shiRegion'] = 'Country: ' . (isset($country[$data['member_shi_add_region_id']])?$country[$data['member_shi_add_region_id']]:'');
        $array['shiSubRegion'] = 'State: ' . (isset($subregions[$data['member_shi_add_subregion_id']])?$subregions[$data['member_shi_add_subregion_id']]:'');
        $array['shiCity'] = 'City: ' . $data['member_shi_add_city'];
        $array['shiPhone'] = 'Phone: ' . $data['member_shi_add_phone_number'];
        $array['billFirstName'] = 'First Name: ' . $data['member_bill_add_first_name'];
        $array['billLastName'] = 'Last Name: ' . $data['member_bill_add_last_name'];
        $array['billAddres'] = 'Address: ' . $data['member_bill_add_addres'];
        $array['billAddresContinued'] = 'Address Continued: ' . $data['member_bill_add_addres_continued'];
        $array['billCity'] = 'City: ' . $data['member_bill_add_city'];
        $array['billRegion'] = 'Country: ' . (isset($country[$data['member_bill_add_region_id']])?$country[$data['member_bill_add_region_id']]:'');
        $array['billSubRegion'] = 'State: ' . (isset($subregions[$data['member_bill_add_subregion_id']])?$subregions[$data['member_bill_add_subregion_id']]:'');
        $array['billPostalCode'] = 'Postal Code: ' . $data['member_bill_add_postal_code'];
        $array['billPhone'] = 'Phone: ' . $data['member_bill_add_phone_number'];

//        $array['cardNumber'] = 'Card Number: ' . $data['member_card_number'];
//        $array['cardType'] = 'Card Type: ' . $data['card_type_id'];
//        $array['segurityCode'] = 'Segurity Code: ' . $data['member_card_segurity_code'];
//        $array['expire'] = 'Expire: ' . $data['member_card_expiration_month'] . '/' . $data['member_card_expiration_year'];
        $this->_helper->json($array);
    }
     * 
     */
}

