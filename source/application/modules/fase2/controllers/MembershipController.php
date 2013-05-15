<?php

class Fase2_MembershipController extends Core_Controller_ActionDefault
{

    protected $isMember = 0;

    public function init() {
        parent::init();

        $this->_helper->layout->setLayout('layout-fase2');

        //$this->isMember = Zend_Auth::getInstance()->hasIdentity();
        //$this->view->isMember = $this->isMember;

        //if( !Zend_Auth::getInstance()->hasIdentity() && !$this->_session->authBeta){
          //  $this->redirect('/beta');
        //}        

        $this->_helper->contextSwitch()
                ->addActionContext('create', 'json')
                ->initContext();

    }

    public function createAction(){

        if ($this->getRequest()->isXmlHttpRequest()) {
            unset($this->view->identity);

            $formValues = $this->_getAllParams();
            $errors = $this->validate($formValues);
            if( !empty($errors) ){
                $this->view->messages = $errors;
            }else{

                $transacction = new Application_Entity_Transaction();

                $transacction->setPropertie('_member', '');
                $transacction->setPropertie('_userMenbership', 0);
                if($this->isMember){                                                           
                            
                    if( isset($this->_identity->member_id)){

                        $email = $this->_identity->member_mail;
                        
                        $transacction->setPropertie('_member', $this->_identity->member_id);
                    }
                }else{
                    
                    $email = trim(isset($formValues['inf_emailaddress']) ? $formValues['inf_emailaddress'] : $formValues['folio_email']);
                    
                    $entity_member = new Application_Entity_Member();
                    $entity_member->identifyByEmail($email);
                    if($entity_member->getPropertie('_id')>0){
                        //$transacction->setPropertie('_member', $entity_member->getPropertie('_id')); Esto solo aplicaba en casos especiales
                        //$transacction->setPropertie('_userMenbership', 1);

                        $this->view->messages = array('error'=>'You need to sign in before continuing');
                        return;
                    }

                }
                

                # Si para este paso no tenemos un correo entonces manda error
                if(!$email){
                    $this->view->messages = array('error'=>'Oops, there\'s something wrong with your email. Please try again.');
                    return;
                }                
                
                $transacction->setPropertie('_mail', $email);                                               
                
                /*
                 * En caso de tener una membresia entonces se  hace el pago por la membresia
                 * y despues se hace la suscripcion
                 */
                
                if( !empty($this->_session->cartMembership)){
                    #Enviamos la transaccion para completar la membresia con los pagos
                    if( $this->createMembership(clone $transacction, $formValues)){
                        if( !empty($this->_session->cart) ){
                            $this->createOrderProducts(clone $transacction, $formValues); // Se hace el pago de la membresia y la suscripcion
                        }
                    }
                }elseif( !empty($this->_session->cart) ){
                    $this->createOrderProducts(clone $transacction, $formValues); // Se hace el pago de los productos
                }
            }
        }

    }
    
    private function createMembership(Application_Entity_Transaction $transacction, $formValues){
        
        $transacction->initTransactionDb(); // iniciamos la transaccion 
        //print_r($this->_identity );die();
        /*
         * Registramos al usuario 
         */
        if( !$this->isMember ){
            $entityMember = new Application_Entity_Member();

            $entityMember->setPropertie('_name', $formValues['folio_email']);
            $entityMember->setPropertie('_lastName', '');
            $entityMember->setPropertie('_mail', $formValues['folio_email']);

            if ( !$entityMember->createMember($formValues['folio_password']) ) {
                $this->view->messages = array('error' => $entityMember->getMessage());
                return false;
            }
            
                    
            if ($entityMember->autentificate( $formValues['folio_email'], $formValues['folio_password']) ) {
                $this->getNavigationMember();
                $this->_identity = Zend_Auth::getInstance()->getIdentity();
            }else{
                $this->view->messages = array('error' =>'Error Logon');
            }
            
            $transacction->setPropertie('_member', $entityMember->getPropertie('_id'));
            
        }elseif( $this->hasMembership ){
            $this->view->messages = array('error' => 'You already have a membership.');
            return false;
        }
        
        /*
         * Aqui se llenan los datos generales de las 2 transacciones que se haran
         * La primera corresponde al pago de los primeros 3 meses de la membresia
         * La segunda es la suscripcion
         */
        
        $transacction->setPropertie('_state', Application_Entity_Transaction::TRANSACTION_OUTSTANDING);
        $transacction->setPropertie('_codePayment', '');
        $transacction->setPropertie('_delivered', Application_Entity_Transaction::UNDELIVERID);
        $transacction->setPropertie('_userMenbership', $this->hasMembership);
        $transacction->setPropertie('_deliveredDate', '');

        $transacction->setPropertie('_contactName', $formValues['shp_firstname'].' '.$formValues['shp_lastname']);

        $transacction->setPropertie('_shiAddFirstName', $formValues['shp_firstname']);
        $transacction->setPropertie('_shiAddLastName', $formValues['shp_lastname']);
        $transacction->setPropertie('_shiAddAddAddres', $formValues['shp_address']);
        //$transacction->setPropertie('_shiAddAddresContinued', $formValues['shp_address2']);
        $transacction->setPropertie('_shiAddPostalCode', $formValues['shp_cp']);
        $transacction->setPropertie('_shiAddRegionId', $formValues['shp_country']);
        $transacction->setPropertie('_shiAddSubregionId', $formValues['shp_region']);
        $transacction->setPropertie('_shiAddCity', $formValues['shp_city']);
        $transacction->setPropertie('_shiAddPhoneNumber', $formValues['shp_phonenumber']);

        if (isset($formValues['bill_same'])) {
            $transacction->setPropertie('_billAddFirstName', $formValues['shp_firstname']);
            $transacction->setPropertie('_billAddLastName', $formValues['shp_lastname']);
            $transacction->setPropertie('_billAddAddAddres', $formValues['shp_address']);
            //$transacction->setPropertie('_billAddAddresContinued', $formValues['shp_address2']);
            $transacction->setPropertie('_billAddCity', $formValues['shp_city']);
            $transacction->setPropertie('_billAddRegionId', $formValues['shp_country']);
            $transacction->setPropertie('_billAddSubregionId', $formValues['shp_region']);
            $transacction->setPropertie('_billAddPostalCode', $formValues['shp_cp']);
            $transacction->setPropertie('_billAddPhoneNumber', $formValues['shp_phonenumber']);
        }else{
            $transacction->setPropertie('_billAddFirstName', $formValues['bill_firstname']);
            $transacction->setPropertie('_billAddLastName', $formValues['bill_lastname']);
            $transacction->setPropertie('_billAddAddAddres', $formValues['bill_address']);
            //$transacction->setPropertie('_billAddAddresContinued', $formValues['bill_address2']);
            $transacction->setPropertie('_billAddCity', $formValues['bill_city']);
            $transacction->setPropertie('_billAddRegionId', $formValues['bill_country']);
            $transacction->setPropertie('_billAddSubregionId', $formValues['bill_region']);
            $transacction->setPropertie('_billAddPostalCode', $formValues['bill_cp']);
            $transacction->setPropertie('_billAddPhoneNumber', $formValues['bill_phonenumber']);
        }

        $total = 60; //3 meses de membresia

        $transacction->setPropertie('_amount', $total);
        
        if ($transacction->createTransaction()) {

            $transacction->addMembership(array(
                'id'=>2,
                'price'=>60,
                'quantity'=>1
            ));

            $logTraking = $this->_session->_tracking->getLog();
            $transacction->saveTracking($logTraking);
            $transacction->commit();

            $paymentId = $transacction->sendSubscription2PG(array(
                'cardNumber' => $formValues['card_number'],
                'expirationDate' => $formValues['card_expirationyear'] . '-' . sprintf('%02d', $formValues['card_expirationmonth']),
                'cardCode' => $formValues['card_seccode'],
            ));

            if (!$paymentId) {
                $this->view->messages = array('error' => $transacction->getMessage());
            } else {
                $this->hasMembership = true;
                
                $this->view->membership_ok = 1;
                $this->view->membership_data = array(
                    'transactionID' => $paymentId,
                    'total' => $total
                );
                return true;
            }
        } else {
            $this->view->messages = array('error' => $transacction->getMessage());
        }
        return false;        
    }
    
    private function createOrderProducts(Application_Entity_Transaction $transacction, $formValues){
        
        $shipping_charge = 5;
        
        if($this->isMember){                                                                                       
            if( isset($this->_identity->member_id)){

                $transacction->setPropertie('_member', $this->_identity->member_id);
            }
        }
        
        $transacction->initTransactionDb(); // iniciamos la transaccion 
        
        $transacction->setPropertie('_state', Application_Entity_Transaction::TRANSACTION_OUTSTANDING);
        $transacction->setPropertie('_codePayment', '');
        $transacction->setPropertie('_delivered', Application_Entity_Transaction::UNDELIVERID);
        $transacction->setPropertie('_userMenbership', $this->hasMembership);
        $transacction->setPropertie('_deliveredDate', '');

        $transacction->setPropertie('_contactName', $formValues['shp_firstname'].' '.$formValues['shp_lastname']);

        $transacction->setPropertie('_shiAddFirstName', $formValues['shp_firstname']);
        $transacction->setPropertie('_shiAddLastName', $formValues['shp_lastname']);
        $transacction->setPropertie('_shiAddAddAddres', $formValues['shp_address']);
        //$transacction->setPropertie('_shiAddAddresContinued', $formValues['shp_address2']);
        $transacction->setPropertie('_shiAddPostalCode', $formValues['shp_cp']);
        $transacction->setPropertie('_shiAddRegionId', $formValues['shp_country']);
        $transacction->setPropertie('_shiAddSubregionId', $formValues['shp_region']);
        $transacction->setPropertie('_shiAddCity', $formValues['shp_city']);
        $transacction->setPropertie('_shiAddPhoneNumber', $formValues['shp_phonenumber']);

        if (isset($formValues['bill_same'])) {
            $transacction->setPropertie('_billAddFirstName', $formValues['shp_firstname']);
            $transacction->setPropertie('_billAddLastName', $formValues['shp_lastname']);
            $transacction->setPropertie('_billAddAddAddres', $formValues['shp_address']);
            //$transacction->setPropertie('_billAddAddresContinued', $formValues['shp_address2']);
            $transacction->setPropertie('_billAddCity', $formValues['shp_city']);
            $transacction->setPropertie('_billAddRegionId', $formValues['shp_country']);
            $transacction->setPropertie('_billAddSubregionId', $formValues['shp_region']);
            $transacction->setPropertie('_billAddPostalCode', $formValues['shp_cp']);
            $transacction->setPropertie('_billAddPhoneNumber', $formValues['shp_phonenumber']);
        }else{
            $transacction->setPropertie('_billAddFirstName', $formValues['bill_firstname']);
            $transacction->setPropertie('_billAddLastName', $formValues['bill_lastname']);
            $transacction->setPropertie('_billAddAddAddres', $formValues['bill_address']);
            //$transacction->setPropertie('_billAddAddresContinued', $formValues['bill_address2']);
            $transacction->setPropertie('_billAddCity', $formValues['bill_city']);
            $transacction->setPropertie('_billAddRegionId', $formValues['bill_country']);
            $transacction->setPropertie('_billAddSubregionId', $formValues['bill_region']);
            $transacction->setPropertie('_billAddPostalCode', $formValues['bill_cp']);
            $transacction->setPropertie('_billAddPhoneNumber', $formValues['bill_phonenumber']);
        }
        
        $products = array();
        /*agrega el producto a la transaccion*/
        $total = 0;
        foreach ($this->_session->cart as $prod) {
            $name = $prod['_name'];
            $strsize = '';
            $sizeId = null;
            foreach ($prod['sizes'] as $size) {
                if ($prod['size_prod'] == $size['product_size_size_id']) {
                    $sizeId = $size['product_size_size_id'];
                    $strsize .= $size['size_name'];
                }
            }
            $name .= $strsize;
            $prod['quantity'] = (isset($prod['quantity']) ? $prod['quantity'] : 1);

            if ($this->hasMembership) {
                $uniPrice = $prod['_priceMember'];
                $total += $uniPrice*$prod['quantity'];
            } else {
                $uniPrice = $prod['_price'];
                $total += $uniPrice*$prod['quantity'];
            }

            $products[] = array(
                                            'id'=>$prod['_id'],
                                            'code'=>$prod['_code'],
                                            'name'=>$prod['_name'],
                                            'quantity'=>$prod['quantity'],
                                            'price'=>$prod['_price'],
                                            'priceMember'=>$prod['_priceMember'],
                                            'finalPrice'=>$uniPrice,
                                            'sizeName'=>$strsize,
                                            'sizeId'=>$sizeId,
                                        );
        }


        $transacction->setPropertie('_shiAmount', $shipping_charge);
        $transacction->setPropertie('_amount', $total+$shipping_charge);

        $transacction->initTransactionDb();
        if( $transacction->createTransaction() ){

            foreach ($products as $prod){
                $transacction->addProduct($prod);
            }
            $logTraking = $this->_session->_tracking->getLog();
            $transacction->saveTracking($logTraking);
            $transacction->commit();

            $paymentId = $transacction->sendOrder2PG(array(
                                                                                                                'cardNumber'=>$formValues['card_number'],
                                                                                                                'expirationDate'=>$formValues['card_expirationyear'] . '-' . sprintf('%02d', $formValues['card_expirationmonth']),
                                                                                                                'cardCode'=>$formValues['card_seccode'],
                                                                                                            ));

            if(!$paymentId){
                $this->view->messages = array('error'=>$transacction->getMessage());
            }else{
                $this->view->ok =1;
                $this->view->data = array(
                    'products'    =>  $products,
                    'transactionID'=>$paymentId,
                    'total'=>$total
                );
                return true;
            }
        }else{
             $this->view->messages = array('error'=>$transacction->getMessage());
        }
        return false;
    }
    


    public function processedAction(){
        $this->_helper->layout->disableLayout();
    }


    public function congratulationsAction(){
        $this->_helper->layout->disableLayout();
    }

    public function errorAction(){
        $this->_helper->layout->disableLayout();
    }

    public function validate(&$formValues){

        $notempty = new Zend_Validate_NotEmpty();
        $correo = new Zend_Validate_EmailAddress();
        $creditcard = new Zend_Validate_CreditCard();

        $errores = array();

        $not_empty = array(
            //'inf_firstname',            'inf_lastname',
            //'inf_emailaddress',
            'shp_firstname',            'shp_lastname',            'shp_address',            'shp_country',            'shp_region',            'shp_city',            'shp_cp',            'shp_phonenumber',
            /*'card_name',*/            'card_number',            'card_expirationmonth',            'card_expirationyear',            'card_seccode'
        );

        $not_empty_bill = array('bill_firstname',            'bill_lastname',            'bill_address',            'bill_country',            'bill_region',            'bill_cp',            'bill_city',            'bill_phonenumber');

        foreach($not_empty as $field){
            if( false === ($notempty->isValid($formValues[$field])) ){
                $errores['empty'] = 'You have empty fields';
                break;
            }
        }

        if( !isset($formValues['bill_same'])){

            foreach($not_empty_bill as $field) {
                if (false === ($notempty->isValid($formValues[$field]))) {
                    $errores['empty'] = 'You have empty fields';
                    break;
                }
            }
        }
                
        if( isset($formValues['inf_emailaddress']) ){
            if( false === ($correo->isValid($formValues['inf_emailaddress'])) ){
                $errores['email'] = 'Oops, there\'s something wrong with your email. Please try again.';                
            }
        }elseif( isset($formValues['folio_email']) ){            
            if( false === ($correo->isValid($formValues['folio_email'])) ){
                $errores['email'] = 'Oops, there\'s something wrong with your email. Please try again.';                
            }
        }


        //if( false === ($creditcard->isValid($formValues['card_number'])) ){
          //  $errores['card'] = 'There was a problem with your payment method information.';
        //}

        $month = (int)$formValues['card_expirationmonth'];
        if( $month <= 0 && $month>12 ){
            $errores['card'] = 'There was a problem with your payment method information.';
        }

        $year = (int)$formValues['card_expirationyear'];
        if( !is_numeric($year) ){
            $errores['card'] = 'There was a problem with your payment method information.';
        }

        return $errores;
    }


}

