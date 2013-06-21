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
    
    public function editAction(){
        $member = new Application_Entity_Member();
        $member->identify($this->getRequest()->getParam('id'));
        
        $form = new Application_Form_CreateMemberForm();
        $form->setAction('/admin/members/edit/id/'.$this->getRequest()->getParam('id'));
        
        $properties = $member->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $arrayPopulate['last_name'] = $properties['_lastName'];
        $arrayPopulate['mail'] = $properties['_mail'];
        $arrayPopulate['active'] = $properties['_active'];
        $form->populate($arrayPopulate);
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $member->setPropertie('_name', $form->getValue('name'));
                $member->setPropertie('_lastName', $form->getValue('last_name'));
                $member->setPropertie('_mail', $form->getValue('mail'));
                $member->setPropertie('_active', $form->getValue('active'));
                $member->update();
                
                $this->_flashMessenger->addMessage($member->getMessage());
                $this->_redirect('/admin/members');
            }
        }

        $this->view->form = $form;
    }

    public function shippingAction(){
        
        $member = new Application_Entity_Member();
        $member->identify($this->getRequest()->getParam('id'));
        
        $form = new Application_Form_ShippingAddressForm();
        $form->setAction('/admin/members/shipping/id/'.$this->getRequest()->getParam('id'));
        
        if($this->getRequest()->isPost()){
            $formValues = $this->getRequest()->getPost();
            
            if( $form->isValid($formValues) ){        
                $saved = $member->saveShippingAddress(array(
                                                            '_customerAddressId'=>$form->getValue('id'),
                                                            '_firstName'=>$form->getValue('firstName'),
                                                            '_lastName'=>$form->getValue('lastName'),
                                                            '_address'=>$form->getValue('address'),
                                                            '_city'=>$form->getValue('city'),
                                                            '_state'=>$form->getValue('state'),
                                                            '_zip'=>$form->getValue('zip'),
                                                            '_country'=>$form->getValue('country'),
                                                            '_phoneNumber'=>$form->getValue('phoneNumber')
                                                        ));
                
                if($saved){                    
                    $this->getMessenger()->info($member->getMessage());
                    $this->_redirect('/admin/members');
                }else{
                    $this->getMessenger()->info($member->getMessage());                    
                }
                
            }else{
                $form->populate($formValues);            
            }
        }
        else{
            $member->loadProfile();
            
            $shippingAddress = $member->getPropertie('_shippingAddress');

            $populate = array();
            if(!empty($shippingAddress)){
                foreach($shippingAddress[0] as $key=>$value){
                    $populate[preg_replace('/^_/', '', $key)] = $value;
                }
            
                $populate['id']=$shippingAddress[0]['_customerAddressId'];
            }
            
            $form->populate($populate);   
        }
        
        $this->view->form = $form;
    }

    public function billingAction(){
        
        $member = new Application_Entity_Member();
        $member->identify($this->getRequest()->getParam('id'));
        
        $form = new Application_Form_BillingInformationForm();
        $form->setAction('/admin/members/billing/id/'.$this->getRequest()->getParam('id'));
        
        if( $this->getRequest()->isPost()){
            $formValues = $this->getRequest()->getPost();
            
            if(!$form->getValue('id')>0){
                $form->getElement('cardNumber')->setRequired(false);
                $form->getElement('expirationDate')->setRequired(false);
                $form->getElement('cardCode')->setRequired(false);
            }
            
            if( $form->isValid($formValues) ){
                $saved = $member->saveBillingInformation(array(
                                                                '_customerPaymentProfileId'=>$form->getValue('id'),
                                                                '_firstName'=>$form->getValue('firstName'),
                                                                '_lastName'=>$form->getValue('lastName'),
                                                                '_address'=>$form->getValue('address'),
                                                                '_city'=>$form->getValue('city'),
                                                                '_state'=>$form->getValue('state'),
                                                                '_zip'=>$form->getValue('zip'),
                                                                '_country'=>$form->getValue('country'),
                                                                '_phoneNumber'=>$form->getValue('phoneNumber'),
                                                                '_cardNumber'=>$form->getValue('cardNumber'),
                                                                '_expirationDate'=>$form->getValue('expirationDate')
                                                                //'_cardCode'=>$form->getValue('cardCode')
                                                            ));
                
                if($saved){                    
                    $this->getMessenger()->info($member->getMessage());
                    $this->_redirect('/admin/members');
                }else{
                    echo $member->getMessage();
                    $this->getMessenger()->info($member->getMessage());                    
                }
                
            }else{
                $form->populate($formValues);            
            }
        }else{
            $member->loadProfile();

            $billingInformation = $member->getPropertie('_billingInformation');

            $populate = array();
            if(!empty($billingInformation)){
                foreach($billingInformation[0] as $key=>$value){
                    $populate[preg_replace('/^_/', '', $key)] = $value;
                }

                $populate['id']=$billingInformation[0]['_customerPaymentProfileId'];
                
                $form->getElement('cardNumber')->setAttrib('style', 'display:none')->setLabel('');
                $form->getElement('expirationDate')->setAttrib('style', 'display:none')->setLabel('');
                $form->getElement('cardCode')->setAttrib('style', 'display:none')->setLabel('');
            }else{
                
            }
            
            $form->populate($populate);
            
        }
        
        $this->view->form = $form;
    }

    public function downloadAction(){
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $members = Application_Entity_Member::listing();
        
        require_once APPLICATION_PATH.'/../library/phpexcel/PHPExcel.php';
        require_once APPLICATION_PATH.'/../library/phpexcel/PHPExcel/Writer/Excel2007.php';
        
        $objPHPExcel = new PHPExcel();
        
        $negrita = array('font' => array('bold' => true), 'alignment' => array('wrap' => true,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));

        $normal = array('alignment' => array('wrap' => false));
        
        $style_num = array(
                            'font' => array(
                                                'color' => array('rgb' => '000000'),
                                                'bold' => false,
                                           ),
                            'alignment' => array(
                                                    'wrap'       => true,
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                ),
                          );
        
        //Definir propiedades del objeto
        $properties = $objPHPExcel->getProperties();
        
        $properties->setCreator("WTA System");
        $properties->setLastModifiedBy("WTA System");
        $properties->setTitle("Members Report");
        $properties->setSubject("Members Report");
        $properties->setDescription("This document is a list of exported members of WTA system.");
        
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
            
        $letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");
        $titles = array(
                            "member_id" => "Id",
                            "member_name" => "Fisrt Name",
                            "member_last_name" => "Last Name",
                            "member_mail" => "E-mail",
                            //"member_password" => "Password",
                            "member_link_confirm" => "Link Confirm",
                            "member_id_confirm" => "Id Confirm",
                            "member_create_date" => "Created Date",
                            "member_confirm_date" => "Confirm Date",
                            "member_active" => "Is Active",
                            "member_confirm" => "Confirm",
                            "member_avatar" => "Avatar",
                            //"member_password_reset" => "Password Reset",
                            "member_membership_free" => "Membership Free",
                            "member_last_date_login" => "Last Date Login",
                            "member_customerProfileId" => "Customer Profile Id"
                        );
                        
        $shipping_titles = array(
                            //"_customerAddressId" => "Customer Address Id",
                            "_firstName" => "Shipping First Name",
                            "_lastName" => "Shipping Confirm Date",
                            "_address" => "Shipping Address",
                            "_city" => "Shipping City",
                            "_state" => "Shipping State",
                            "_zip" => "Shipping Zip",
                            "_country" => "Shipping Country",
                            "_phoneNumber" => "Shipping Phone Number"
                        );
                        
        $billing_titles = array(
                            //"_customerPaymentProfileId" => "Customer Payment Profile Id",
                            "_firstName" => "Billing First Name",
                            "_lastName" => "Billing Confirm Date",
                            "_address" => "Billing Address",
                            "_city" => "Billing City",
                            "_state" => "Billing State",
                            "_zip" => "Billing Zip",
                            "_country" => "Billing Country",
                            "_phoneNumber" => "Billing Phone Number",
                            "_cardNumber" => "Billing Phone Number",
                            "_expirationDate" => "Billing Phone Number"
                        );
                        
        $n = 0;
        if($titles) foreach($titles as $key => $value){
            $sheet->SetCellValue($letters[$n]."1", $value);
            $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
            $sheet->getStyle($letters[$n]."1")->applyFromArray($negrita);
            $n++;
        }
        
        if($shipping_titles) foreach($shipping_titles as $key => $value){
            $sheet->SetCellValue($letters[$n]."1", $value);
            $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
            $sheet->getStyle($letters[$n]."1")->applyFromArray($negrita);
            $n++;
        }
        
        if($billing_titles) foreach($billing_titles as $key => $value){
            $sheet->SetCellValue($letters[$n]."1", $value);
            $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
            $sheet->getStyle($letters[$n]."1")->applyFromArray($negrita);
            $n++;
        }
        
        $row = 2;
        if(!empty($members)) foreach($members as $member){
            $n = 0;
            
            $memberEntity = new Application_Entity_Member();
            $memberEntity->identify($member["member_id"]);
            $profile = $memberEntity->loadProfile();
            
            if($titles) foreach($titles as $key => $value){
                $sheet->SetCellValue($letters[$n].$row, $member[$key]);
                $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                $n++;
            }
            
            if(!empty($profile["shipping"])){
                
                if($shipping_titles) foreach($shipping_titles as $key => $value){
                    $sheet->SetCellValue($letters[$n].$row, $profile["shipping"][0][$key]);
                    $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                    $n++;
                }
            }
            
            if(!empty($profile["billing"])){
                
                if($billing_titles) foreach($billing_titles as $key => $value){
                    $sheet->SetCellValue($letters[$n].$row, $profile["billing"][0][$key]);
                    $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                    $n++;
                }
            }
            
            $row++;
        }
        
        $sheet->setTitle('Members');
        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        
        header('content-type: application/zip');
        header('content-disposition: inline; filename="members.xlsx"');
        
        $objWriter->save('php://output');
        
        //if(!empty($members)) foreach($members as $member){
        //    echo $member["member_name"]." ".$member["member_last_name"]."<br>";
        //}
        
        die();
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

