<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Transaction extends Core_Entity {
    const TRANSACTION_OUTSTANDING = 1;
    const TRANSACTION_PAID = 2;
    const TRANSACTION_REMOVED = 3;

    const TYPE_MENBERSHIP = 1;
    const TYPE_PRODUCT = 2;
    const DELIVERID = 1;
    const UNDELIVERID = 0;

    protected $_id;
    protected $_member;
    protected $_amount;
    protected $_state;
    protected $_codePayment;
    protected $_delivered;
    protected $_userMenbership;
    protected $_deliveredDate;
    protected $_shiAddFirstName;
    protected $_shiAddLastName;
    protected $_shiAddAddAddres;
    protected $_shiAddAddresContinued;
    protected $_shiAddPostalCode;
    protected $_shiAddRegionId;
    protected $_shiAddSubregionId;
    protected $_shiAddCity;
    protected $_shiAddPhoneNumber;
    protected $_billAddFirstName;
    protected $_billAddLastName;
    protected $_billAddAddAddres;
    protected $_billAddAddresContinued;
    protected $_billAddCity;
    protected $_billAddRegionId;
    protected $_billAddSubregionId;
    protected $_billAddPostalCode;
    protected $_billAddPhoneNumber;
    protected $_cardNumber;
    protected $_mail;
    protected $_contactName;

    protected $_shiAmount;

    /**
     * __Construct
     *
     */
    function __construct() {

    }

    /*
     * Inicia una transaccion a nivel Base de datos
     */
    public function initTransactionDb(){
        $modelTransaction = new Application_Model_Transaction();
        $modelTransaction->initTransactionDb();
    }


    /*
     * Guarda los cambios en la base de datos de forma permanente
     */
    public function commit(){
        $modelTransaction = new Application_Model_Transaction();
        $modelTransaction->commit();
    }


    static function listing() {
        $modelTransaction = new Application_Model_Transaction();
        return $modelTransaction->listing();
    }

    private function asocParams($data) {
        $this->_id = $data['transaction_id'];
        $this->_amount = $data['transaction_amount'];
        $this->_member = $data['member_id'];
        $this->_state = $data['tansaction_state_id'];
        $this->_codePayment = $data['transaction_code_payment'];
        $this->_delivered = $data['transaction_delivered'];
        $this->_userMenbership = $data['transaction_user_menbership'];
        $this->_deliveredDate = $data['transaction_delivered_date'];

        $this->_shiAddFirstName = $data['transaction_shi_add_first_name'];
        $this->_shiAddLastName = $data['transaction_shi_add_last_name'];
        $this->_shiAddAddAddres = $data['transaction_shi_add_addres'];
        $this->_shiAddAddresContinued = $data['transaction_shi_add_addres_continued'];
        $this->_shiAddPostalCode = $data['transaction_shi_add_postal_code'];
        $this->_shiAddRegionId = $data['transaction_shi_add_region_id'];
        $this->_shiAddSubregionId = $data['transaction_shi_add_subregion_id'];
        $this->_shiAddCity = $data['transaction_shi_add_city'];
        $this->_shiAddPhoneNumber = $data['transaction_shi_add_phone_number'];
        $this->_billAddFirstName = $data['transaction_bill_add_first_name'];
        $this->_billAddLastName = $data['transaction_bill_add_last_name'];
        $this->_billAddAddAddres = $data['transaction_bill_add_addres'];
        $this->_billAddAddresContinued = $data['transaction_bill_add_addres_continued'];
        $this->_billAddCity = $data['transaction_bill_add_city'];
        $this->_billAddRegionId = $data['transaction_bill_add_region_id'];
        $this->_billAddSubregionId = $data['transaction_bill_add_subregion_id'];
        $this->_billAddPostalCode = $data['transaction_bill_add_postal_code'];
        $this->_billAddPhoneNumber = $data['transaction_bill_add_phone_number'];

        $this->_shiAmount = $data['transaction_shi_amount'];

        $this->_cardNumber = $data['transaction_card_number'];
        $this->_mail = $data['transaction_mail'];
        $this->_contactName = $data['transaction_contact_name'];

    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idTransaction
     * @return void
     */

    function identify($idTransaction) {
        $modelTransaction = new Application_Model_Transaction();
        $data = $modelTransaction->getTransaction($idTransaction);
        if ($data != '') {
            $this->asocParams($data);
            return $data;
        }
    }

    /*
     * metodo setParamsDataBase()
     *
     * @param
     * @return array
     */

    function setParamsDataBase() {
        $data['transaction_id'] = $this->_id;
        $data['transaction_amount'] = $this->_amount;
        $data['tansaction_state_id'] = $this->_state;
        $data['member_id'] = $this->_member;
        $data['transaction_code_payment'] = $this->_codePayment;
        $data['transaction_delivered'] = $this->_delivered;
        $data['transaction_user_menbership'] = $this->_userMenbership;
        $data['transaction_delivered_date'] = $this->_deliveredDate;

        $data['transaction_shi_add_first_name'] = $this->_shiAddFirstName;
        $data['transaction_shi_add_last_name'] = $this->_shiAddLastName;
        $data['transaction_shi_add_addres'] = $this->_shiAddAddAddres;
        $data['transaction_shi_add_addres_continued'] = $this->_shiAddAddresContinued;
        $data['transaction_shi_add_postal_code'] = $this->_shiAddPostalCode;
        $data['transaction_shi_add_region_id'] = $this->_shiAddRegionId;
        $data['transaction_shi_add_subregion_id'] = $this->_shiAddSubregionId;
        $data['transaction_shi_add_city'] = $this->_shiAddCity;
        $data['transaction_shi_add_phone_number'] = $this->_shiAddPhoneNumber;
        $data['transaction_bill_add_first_name'] = $this->_billAddFirstName;
        $data['transaction_bill_add_last_name'] = $this->_billAddLastName;
        $data['transaction_bill_add_addres'] = $this->_billAddAddAddres;
        $data['transaction_bill_add_addres_continued'] = $this->_billAddAddresContinued;
        $data['transaction_bill_add_city'] = $this->_billAddCity;
        $data['transaction_bill_add_region_id'] = $this->_billAddRegionId;
        $data['transaction_bill_add_subregion_id'] = $this->_billAddSubregionId;
        $data['transaction_bill_add_postal_code'] = $this->_billAddPostalCode;
        $data['transaction_bill_add_phone_number'] = $this->_billAddPhoneNumber;

        $data['transaction_shi_amount'] = $this->_shiAmount;

        $data['transaction_card_number'] = $this->_cardNumber;

        $data['transaction_mail'] = $this->_mail;
        $data['transaction_contact_name'] = $this->_contactName;

        return $this->cleanArray($data);
    }

    function update() {
        $data = $this->setParamsDataBase();
        $modelTransaction = new Application_Model_Transaction();
        $this->_message = 'Registration ok';
        return $modelTransaction->update($data, $this->_id);
    }

    /*
     * metodo createTransaction()
     *
     * @param
     * @return
     */

    function createTransaction() {
        $modelTransaction = new Application_Model_Transaction();
        $data = $this->setParamsDataBase();
        $data['transaction_register_date'] = date('Y-m-d H:i:s');
        $id = $modelTransaction->insert($data);
        if ($id != false) {
            $this->_id = $id;
            $this->_message = 'Registration ok';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }

    function addMembership($membership) {
        $transactionDetails = new Application_Model_TransactionDetails();
        $data['membership_member_id'] = $membership['id'];
        $data['product_type_id'] = self::TYPE_MENBERSHIP;
        $data['transaction_detail_final_price'] = $membership['price'];
        $data['transaction_details_amount'] = $membership['price'];
        $data['transaction_details_product_cant'] = $membership['quantity']; 
        $data['transaction_id'] = $this->_id;
        return $transactionDetails->insert($data);
    }

    function addProduct($product) {
        $transactionDetails = new Application_Model_TransactionDetails();
        $data['product_id'] = $product['id'];
        $data['product_code'] = $product['code'];
        $data['product_name'] = $product['name'];
        $data['product_type_id'] = self::TYPE_PRODUCT;
        $data['transaction_details_amount'] = $product['quantity']*$product['finalPrice'];   // cantital * Precio final
        $data['transaction_details_product_cant'] = $product['quantity']; // Cantidad
        $data['transaction_details_product_price'] = $product['price']; // Precio Normal
        $data['transaction_details_product_price_member'] = $product['priceMember'];        // Precio miembros
        $data['transaction_detail_final_price'] = $product['finalPrice'];// Precio final
        $data['product_size'] = $product['sizeName']; // Nombre del tamano
        $data['product_size_id'] = $product['sizeId']; // Id del tamano
        $data['transaction_id'] = $this->_id;

        return $transactionDetails->insert($data);
    }

    function confirmPayment() {
        $data['tansaction_state_id'] = self::TRANSACTION_PAID;
        $modelTransaction->update($data, $this->_id);
    }
    function delivered() {
        $modelTransaction = new Application_Model_Transaction();
        $data['transaction_delivered'] = self::DELIVERID;
        $data['transaction_delivered_date'] = date('Y-m-d H:i:s');
        $modelTransaction->update($data, $this->_id);
    }
    function undelivered() {
        $modelTransaction = new Application_Model_Transaction();
        $data['transaction_delivered'] = self::UNDELIVERID;
        $data['transaction_delivered_date'] = NULL;
        $modelTransaction->update($data, $this->_id);
    }

    function saveTracking($aTraking){
        $modelTraking = new Application_Model_Tracking();
        return $modelTraking->insert(array(
                                                            'tracking_transaction_id' => $this->_id,
                                                            'tracking_code'=>  serialize($aTraking)
                                                        ));
    }


    static function listOrders($filtro=array()) {
        $modelTransaction = new Application_Model_Transaction();
        return $modelTransaction->listOrdens($filtro);
    }

    static function listOrdensUsers() {
        $modelTransaction = new Application_Model_Transaction();
        return $modelTransaction->listOrdensUsers();
    }

    public function listProducts(){
        $modelTransaction = new Application_Model_Transaction();
        return $modelTransaction->listProducts($this->_id);
    }
    
    
    public function listMemberships(){
        $modelTransaction = new Application_Model_Transaction();
        return $modelTransaction->listMemberships($this->_id);
    }

    /*
     * Send Subscription to payment gateway
     */
    public function sendSubscription2PG($dataCard){

        // Creamos el pago por 3 meses de la membresia
        // Si pasa lo suscribimos
        // 
        $customerProfileId = 0;
        $_customerAddressId = 0;
        $_customerPaymentProfileId = 0;
        
        
        # Usuario Miembro 
        $_member = new Application_Entity_Member();
        $_member->identify($this->_member);
        
        $_member->loadProfile();

        $customerProfileId = $_member->getPropertie('_customerProfileId');
        
        if($customerProfileId){
        
            $shippingAddress = $_member->getPropertie('_shippingAddress');
            $billingInformation = $_member->getPropertie('_billingInformation');

            if( !empty($shippingAddress)){
                $_customerAddressId =$shippingAddress[0]['_customerAddressId'];
            }
            if( !empty($billingInformation)){
                $_customerPaymentProfileId = $billingInformation[0]['_customerPaymentProfileId'];
            }
        }
        
        
        
        
        
        $_transaction = new Payment_Transaction(Payment_Transaction::PAYMENT_SERVICE_AUTHORIZE);
        
        $_customer = $_transaction->customer();        
        
         #Llenamos los datos del comprador
         $_customer->_email = $this->_mail;

         
        $_country = new Application_Model_Regions();
        $_state = new Application_Model_SubRegions();
         
        #Llenamos los datos de shipping
        $sha_state = $_state->getSubRegion($this->_shiAddSubregionId);
        $sha_country = $_country->getRegion($this->_shiAddRegionId);

        $_shpAdd = $_customer->shippingAddress();
        $_shpAdd->_customerProfileId = $customerProfileId;
        $_shpAdd->_customerAddressId = $_customerAddressId;
        $_shpAdd->_firstName = $this->_shiAddFirstName;
        $_shpAdd->_lastName = $this->_shiAddLastName;
        $_shpAdd->_address = $this->_shiAddAddAddres;
        $_shpAdd->_city = $this->_shiAddCity;
        $_shpAdd->_state = $sha_state['name'];
        $_shpAdd->_zip = $this->_shiAddPostalCode;
        $_shpAdd->_country = $sha_country['country'];
        $_shpAdd->_phoneNumber = $this->_shiAddPhoneNumber;

        #Llenamos los datos de billing

        $billa_state = $_state->getSubRegion($this->_billAddSubregionId);
        $billa_country = $_country->getRegion($this->_billAddRegionId);

        $_billInfo = $_customer->billingInformation();
        $_billInfo->_customerPaymentProfileId = $_customerPaymentProfileId;
        $_billInfo->_customerProfileId = $customerProfileId;
        $_billInfo->_firstName = $this->_billAddFirstName;
        $_billInfo->_lastName = $this->_billAddLastName;
        $_billInfo->_address = $this->_billAddAddAddres;
        $_billInfo->_city = $this->_billAddCity;
        $_billInfo->_state = $billa_state['name'];
        $_billInfo->_zip = $this->_billAddPostalCode;
        $_billInfo->_country = $billa_country['country'];
        $_billInfo->_phoneNumber = $this->_billAddPhoneNumber;
        $_billInfo->_cardNumber = $dataCard['cardNumber'];
        $_billInfo->_expirationDate = $dataCard['expirationDate'];
        $_billInfo->_cardCode = $dataCard['cardCode'];
        
        if($customerProfileId){
            if(!$_shpAdd->commit()){
                $this->_message = $error_message = Payment_Transaction_Authorize::getDescriptionCode($_shpAdd->getError());
                return false;
            }
            if(!$_billInfo->commit()){
                $this->_message = Payment_Transaction_Authorize::getDescriptionCode($_billInfo->getError());
                return false;
            }
            
            $_payment->_customerProfileId = $customerProfileId;
            $_payment->_customerPaymentProfileId = $_customerPaymentProfileId;
            $_payment->_customerShippingAddressId = $_customerAddressId;
            
        }else{
            $customerProfileId = $_customer->commit();

            if( !$customerProfileId ){
                //print_r($_transaction->getLastExecution());die();
                $error=$_customer->getError();
                $error_message = Payment_Transaction_Authorize::getDescriptionCode($error);
                $this->_message = 'We had a problem. '.$error_message;            
                return false;
            }else{
                $_member->setPropertie('_customerProfileId', $customerProfileId);
                $_member->update();
            }
            
            $customerProfileId = $_customer->_customerProfileId;
            $_customerPaymentProfileId = $_customer->_customerPaymentProfileIds[0];
            $_customerAddressId = $_customer->_customerShippingAddressIds[0];
            
        }
        
        $memberships = $this->listMemberships();
        
        /*
         * 
         *  Aqui se llenan los datos para el pago de los primero 3 meses
         * 
         */
        $_payment = $_customer->payment();   
        
        
        $_payment->_customerProfileId = $customerProfileId;
        $_payment->_customerPaymentProfileId = $_customerPaymentProfileId;
        $_payment->_customerShippingAddressId = $_customerAddressId;
        
        $_payment->addProduct('M02', 'WTA Membership', 'Recurring monthly membership',1, $memberships[0]['transaction_detail_final_price']*3);
        //$_id, $_name, $_description, $_quantity, $_unitPrice

        $_payment->_amount = $memberships[0]['transaction_detail_final_price']*3;
        
        if($_payment->commit()===true){
            $modelTransaction = new Application_Model_Transaction();

            $modelTransaction->update(array(
                'transaction_code_payment'=>$_payment->_profileTransactionId,
                'transaction_payment_date'=>date('Y-m-d H:i:s'),
                'tansaction_state_id'=>  self::TRANSACTION_PAID
            ), $this->_id);

            $this->identify($this->_id);

            $file =$this->createPdf();

            /*
             * Enviamos el correo con la nota de pago de la membresia
             */
            $this->sendPurchaseConfirmMail($file);

            $this->_message = 'Payment Successful';
            //return $_payment->_profileTransactionId;
        }else{
            $error=$_payment->getError();
            $error_message = Payment_Transaction_Authorize::getDescriptionCode($error);

            //echo print_r($_transaction->getLastExecution());
            
            if($error && $error_message){
                $this->_message = 'We had a problem. '.$error_message;
            }else{
                $this->_message = 'We had a problem width your card. Please enter a new one, or review your information and try again. (Error code: '.$error.')';
            }
            return false;
        }
        
        
        $_subscription = $_customer->subscription();
        
        $_subscription->_name = 'WTA Membership';
        $_subscription->_intervalLength = '1';
        $_subscription->_intervalUnit = 'months';
        $_subscription->_startDate = date('Y-m-d');
        $_subscription->_totalOccurrences = '9999';
        $_subscription->_trialOccurrences = '3';
        $_subscription->_amount = $this->_amount;
        $_subscription->_trialAmount = '0';
        
        $_subscription->_creditCardCardNumber = $dataCard['cardNumber'];
        $_subscription->_creditCardExpirationDate = $dataCard['expirationDate'];
        $_subscription->_creditCardCardCode = $dataCard['cardCode'];
        
        $_subscription->_orderInvoiceNumber = '';
        $_subscription->_orderDescription = 'Recurring monthly membership';
        $_subscription->_customerPhoneNumber = $this->_shiAddPhoneNumber;
        $_subscription->_customerFaxNumber = '';
        
        $_subscription->_billToFirstName =  $this->_billAddFirstName;
        $_subscription->_billToLastName = $this->_billAddLastName;
        $_subscription->_billToCompany = '';
        $_subscription->_billToAddress = $this->_billAddAddAddres;
        $_subscription->_billToCity = $this->_billAddCity;
        $_subscription->_billToState = $billa_state['name'];
        $_subscription->_billToZip = $this->_billAddPostalCode;
        $_subscription->_billToCountry = $billa_country['country'];;        
        
        $_subscription->_shipToFirstName = $this->_shiAddFirstName;
        $_subscription->_shipToLastName = $this->_shiAddLastName;
        $_subscription->_shipToCompany = '';
        $_subscription->_shipToAddress = $this->_shiAddAddAddres;
        $_subscription->_shipToCity =  $this->_shiAddCity;
        $_subscription->_shipToState = $sha_state['name'];
        $_subscription->_shipToZip = $this->_shiAddPostalCode;
        $_subscription->_shipToCountry = $sha_country['country'];
        
        if( $_subscription->commit() ){                       
            
            $inserted = $_member->addMembership(array(
                    'membership_id'=>$memberships[0]['membership_member_id'],
                    'membership_member_start_date'=>date('Y-m-d h:i:s'),
                    'membership_member_price'=>20,
                    'membership_member_status'=>1,
                    'membership_member_isfree'=>0,
                    'transaction_id'=>$_subscription->_subscriptionId                
                ));
            if( !$inserted ){
                $this->_message = 'We had a problem in the activation of the membership.';
                return false;
            }            
            return $_subscription->_subscriptionId;
        }else{
            $error=$_subscription->getError();
            $this->_message = 'Error: '.Payment_Transaction_Authorize::getDescriptionCode($error);
        }
        return false;             
    }

    /*
     * Send Order to payment gateway
     * Funciones para transaccion con banco, envia la orden
     */
    //public function sendToPaymentGateway($dataCard){
    public function sendOrder2PG($dataCard){

        $_country = new Application_Model_Regions();
        $_state = new Application_Model_SubRegions();

        $_customerAddressId = 0;
        $_customerPaymentProfileId = 0;
        $customerProfileId = 0;

        if($this->_member>0){                                                               # Si es un miembro entonces lo identificamos
            $modelMember = new Application_Entity_Member();
            $modelMember->identify($this->_member);

        }else{                                                                                           # Si no es un miembro lo tratamos de indentificar como usuario anonymo que ya hizo alguna compra
            $modelMember = new Application_Entity_MemberAnonymous();
            $modelMember->identifyByEmail(trim($this->_mail));
        }

        if($modelMember){                                                                     # Tomamos los datos de shipping, billing y Id de Customer
            $modelMember->loadProfile();

            $shippingAddress = $modelMember->getPropertie('_shippingAddress');
            $billingInformation = $modelMember->getPropertie('_billingInformation');

            if( !empty($shippingAddress)){
                $_customerAddressId =$shippingAddress[0]['_customerAddressId'];
            }
            if( !empty($billingInformation)){
                $_customerPaymentProfileId = $billingInformation[0]['_customerPaymentProfileId'];
            }

            $customerProfileId = $modelMember->getPropertie('_customerProfileId');
        }


        $_transaction = new Payment_Transaction(Payment_Transaction::PAYMENT_SERVICE_AUTHORIZE);

        #
        # Apartir de aqui llenamos los datos de customer, shipping y billing necesarios
        #

        $_customer = $_transaction->customer();

        #Llenamos los datos del comprador
        $_customer->_email = $this->_mail;

        #Llenamos los datos de shipping
        $a_state = $_state->getSubRegion($this->_shiAddSubregionId);
        $a_country = $_country->getRegion($this->_shiAddRegionId);

        $_shpAdd = $_customer->shippingAddress();
        $_shpAdd->_customerProfileId = $customerProfileId;
        $_shpAdd->_customerAddressId = $_customerAddressId;
        $_shpAdd->_firstName = $this->_shiAddFirstName;
        $_shpAdd->_lastName = $this->_shiAddLastName;
        $_shpAdd->_address = $this->_shiAddAddAddres;
        $_shpAdd->_city = $this->_shiAddCity;
        $_shpAdd->_state = $a_state['name'];
        $_shpAdd->_zip = $this->_shiAddPostalCode;
        $_shpAdd->_country = $a_country['country'];
        $_shpAdd->_phoneNumber = $this->_shiAddPhoneNumber;

        #Llenamos los datos de billing

        $a_state = $_state->getSubRegion($this->_billAddSubregionId);
        $a_country = $_country->getRegion($this->_billAddRegionId);

        $_billInfo = $_customer->billingInformation();
        $_billInfo->_customerPaymentProfileId = $_customerPaymentProfileId;
        $_billInfo->_customerProfileId = $customerProfileId;
        $_billInfo->_firstName = $this->_billAddFirstName;
        $_billInfo->_lastName = $this->_billAddLastName;
        $_billInfo->_address = $this->_billAddAddAddres;
        $_billInfo->_city = $this->_billAddCity;
        $_billInfo->_state = $a_state['name'];
        $_billInfo->_zip = $this->_billAddPostalCode;
        $_billInfo->_country = $a_country['country'];
        $_billInfo->_phoneNumber = $this->_billAddPhoneNumber;
        $_billInfo->_cardNumber = $dataCard['cardNumber'];
        $_billInfo->_expirationDate = $dataCard['expirationDate'];
        $_billInfo->_cardCode = $dataCard['cardCode'];


        #Llenamos los datos del pago
        $_payment = $_customer->payment();

        $products = $this->listProducts();                                              # Agregamos los productos a la orden
        foreach ($products as $prod) {
            $name = $prod['product_name'];
            $strsize = '';
            if($prod['product_size']){
                $strsize = ' - '.$prod['product_size'].'';
            }
            $name .= $strsize;
            $_payment->addProduct($prod['product_code'], substr(trim($name), 0, 30), substr($prod['product_description'], 0, 100),$prod['transaction_details_product_cant'], $prod['transaction_detail_final_price']);
            //$_id, $_name, $_description, $_quantity, $_unitPrice
        }

        // Costo del envio
        $_payment->_shippingAmount = $this->_shiAmount;
        $_payment->_shippingName = 'Shipping Cost';

        $_payment->_amount = $this->_amount;
        $_payment->_cardCode = $dataCard['cardCode'];




        if( $customerProfileId){                    # Validamos que tengamos un perfil creado

            if(!$_shpAdd->commit()){
                //$this->_message = print_r($_transaction->getLastExecution(), true);
                $this->_message = $_shpAdd->getError();
                return false;
            }
            if(!$_billInfo->commit()){
                $this->_message = $_billInfo->getError();
                return false;
            }

            $_payment->_customerProfileId = $customerProfileId;
            $_payment->_customerPaymentProfileId = $_customerPaymentProfileId;
            $_payment->_customerShippingAddressId = $_customerAddressId;
        }else{

            $customerProfileId = $_customer->commit();

            if(!$customerProfileId){
                $error=$_customer->getError();
                $this->_message = 'Order:'.Payment_Transaction_Authorize::getDescriptionCode($error);
                return false;
            }

            $_payment->_customerProfileId = $_customer->_customerProfileId;
            $_payment->_customerPaymentProfileId = $_customer->_customerPaymentProfileIds[0];
            $_payment->_customerShippingAddressId = $_customer->_customerShippingAddressIds[0];
        }


        if( $customerProfileId ){
            if( $this->_member >0){
                $modelMember->setPropertie('_customerProfileId', $customerProfileId);
                $modelMember->update();
            }else{
                if($modelMember->getPropertie('_id')>0){
                    $modelMember->setPropertie('_customerProfileId', $customerProfileId);
                    $modelMember->update();
                }else{
                    $modelMember->setPropertie('_name', $this->_shiAddFirstName);
                    $modelMember->setPropertie('_lastName', $this->_shiAddLastName);
                    $modelMember->setPropertie('_mail', $this->_mail);
                    $modelMember->setPropertie('_customerProfileId', $customerProfileId);
                    $modelMember->createMemberAnonymous();
                }
            }
            if($_payment->commit()===true){
                $modelTransaction = new Application_Model_Transaction();

                $modelTransaction->update(array(
                    'transaction_code_payment'=>$_payment->_profileTransactionId,
                    'transaction_payment_date'=>date('Y-m-d H:i:s'),
                    'tansaction_state_id'=>  self::TRANSACTION_PAID
                ), $this->_id);

                $this->identify($this->_id);

                $file =$this->createPdf();

                /*
                 * Enviamos el correo con la nota de pago
                 */
                $this->sendPurchaseConfirmMail($file);

                $this->_message = 'Payment Successful';
                return $_payment->_profileTransactionId;
            }else{
                //$this->_message = print_r($_transaction->getLastExecution(), true);
                //$this->_message = 'We had a problem width your card. Please enter a new one, or review your information and try again.';
                $error=$_payment->getError();
                $error_message = Payment_Transaction_Authorize::getDescriptionCode($error);

                if($error && $error_message){
                    $this->_message = 'We had a problem. '.$error_message;
                }else{
                    $this->_message = 'We had a problem width your card. Please enter a new one, or review your information and try again. (Error code: '.$error.')';
                }

                /*if($error=='Decline' || $error == 'Error'){ ESTA ES LA VALIDACION ANTERIOR
                    $this->_message = 'We had a problem width your card. Please enter a new one, or review your information and try again.';
                }else{
                    $this->_message = $_payment->getError();
                }*/
                return false;
            }
        }else{
            //die(print_r($_transaction->getLastExecution()));
            $error = $_customer->getError();
            $error_message = Payment_Transaction_Authorize::getDescriptionCode($error);
            $this->_message = 'We had a problem. Please review your information and try again. '.$error_message;
            return false;
        }

    }

    public function sendPurchaseConfirmMail($file){


        $objMail = new Core_Mail();
        $objMail->addDestinatario($this->_mail);
        $objMail->setAsunto('Thank you for your recent purchase from WeTheAdorned.');

        $objMail->addAdjunto($file);
        $mensaje = '<p>Thank you for your recent purchase from WeTheAdorned<p>';

        $objMail->setMensaje($mensaje);
        return $objMail->send();
    }


    public function createPdf(){

        $_country = new Application_Model_Regions();
        $_state = new Application_Model_SubRegions();

        $a_state_sh = $_state->getSubRegion($this->_shiAddSubregionId);
        $a_country_sh = $_country->getRegion($this->_shiAddRegionId);

        $a_state_bil = $_state->getSubRegion($this->_billAddSubregionId);
        $a_country_bil = $_country->getRegion($this->_billAddRegionId);

        $products = $this->listProducts();
        $memberships = $this->listMemberships();

        $html = '<html>
                        <head>
                            <title>WeTheAdorned</title>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                            <style>
                                html{margin: 0}
                                table {border-collapse: collapse; border-spacing: 0;margin: 0;padding: 0;}
                                th {font-weight: bold; vertical-align: bottom;margin: 0;padding: 0; border:0px;}
                                td {font-weight: normal; vertical-align: top;margin: 0;padding: 0; border:0px; font-family: Arial, georgia, sans-serif ; color: #3b261c}
                            </style>
                        </head>
                        <body>
                            <div style=" background: #231f20; width: 100%">
                                <table border="0" style="border-collapse: collapse; border-spacing: 0px " cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td align ="center" style=" background: #231f20; ">
                                            <img src="'.STATIC_URL .'/front-beta/images/order/header-order.jpg"  />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="  background: #FFF;  width:100%; padding: 0 30px">
                                <table border="0" style="border-collapse: collapse; border-spacing: 0px " cellpadding="0" cellspacing="0"  width="100%">
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 16px; padding: 0px 0px 10px 3px;">
                                            Thank you for your order!
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background: #231f20; color:#FFF; padding: 2px 5px;">Order Information</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px; font-size: 12px;">Merchant: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;WeTheAdorned</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="border-top: 2px solid #AAAAAA; background: #FFF; padding: 5px">
                                            <table  border="0" style="border-collapse: collapse; border-spacing: 0px; width: 100%; font-size: 12px;" cellpadding="0" cellspacing="0" >
                                                <tr>
                                                    <td>Billing Information</td>
                                                    <td>Shipping Information</td>
                                                </tr>
                                                <tr>
                                                    <td>'.$this->_billAddFirstName.' '.$this->_billAddLastName.'</td>
                                                    <td>'.$this->_shiAddFirstName.' '.$this->_shiAddLastName.'</td>
                                                </tr>
                                                <tr>
                                                    <td>'.$this->_billAddAddAddres.'</td>
                                                    <td>'.$this->_shiAddAddAddres.'</td>
                                                </tr>
                                                <tr>
                                                    <td>'.$this->_billAddCity.', '.$a_state_bil['name'].' '.$this->_billAddPostalCode. '</td>
                                                    <td>'.$this->_shiAddCity.', '.$a_state_sh['name'].' '.$this->_shiAddPostalCode. '</td>
                                                </tr>
                                                <tr>
                                                    <td>'.$a_country_bil['country'].'</td>
                                                    <td>'.$a_country_sh['country'].'</td>
                                                </tr>
                                                <tr>
                                                    <td>'.$this->_billAddPhoneNumber.'</td>
                                                    <td>'.$this->_shiAddPhoneNumber.'</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td style="background: #FFF; padding: 12px; border-top: 2px solid #AAAAAA;">
                                            <table  border="0" style="border-collapse: collapse; border-spacing: 0px; width: 100%; font-size: 12px;" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="width: 40px;">Item</td>
                                                    <td style="width: 100px;">Description</td>
                                                    <td style="width: 50px; text-align: center">Qty</td>
                                                    <td style="width: 50px; text-align: center">Taxable</td>
                                                    <td style="width: 60px; text-align: right">Unit Price</td>
                                                    <td style="width: 60px; text-align: right">Item Total</td>
                                                </tr>';                                                
                                                if ($memberships) {
                                                    foreach ($memberships as $mem) {
                                                        $html .= '<tr>
                                                                        <td style="width: 40px;">WTA Membership</td>
                                                                        <td style=" width: 190px; text-align: left" >Recurring monthly membership</td>
                                                                        <td style=" width: 50px; text-align: center">'.$mem['transaction_details_product_cant'] .'</td>
                                                                        <td style=" width: 40px; text-align: center">N</td>
                                                                        <td style="width: 40px; text-align: right">US $ '.number_format($mem['transaction_detail_final_price'],2) .'</td>
                                                                        <td style="width: 40px; text-align: right">US $ '.number_format($mem['transaction_details_amount'],2) .'</td>
                                                                    </tr>';
                                                    }
                                                }
        
                                                if ($products) {
                                                    foreach ($products as $prod) {                                                        
                                                        $html .= '<tr>
                                                                        <td style="width: 40px;">'.$prod['product_code'] .'</td>
                                                                        <td style=" width: 190px; text-align: left" >'.$prod['product_name'].' '.$prod['product_size'] .'</td>
                                                                        <td style=" width: 50px; text-align: center">'.$prod['transaction_details_product_cant'] .'</td>
                                                                        <td style=" width: 40px; text-align: center">N</td>
                                                                        <td style="width: 40px; text-align: right">US $ '.number_format($prod['transaction_detail_final_price'],2) .'</td>
                                                                        <td style="width: 40px; text-align: right">US $ '.number_format($prod['transaction_details_amount'],2) .'</td>
                                                                    </tr>';                                                        
                                                    }
                                                }
                                                $html .= '<tr><td>&nbsp;</td></tr>
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td style="color: #333; font-size: 12px; font-weight: normal; text-align: right">Shipping:</td>
                                                                <td style="color: #333; font-size: 12px; font-weight: bold; text-align: right">$ '.number_format($this->_shiAmount, 2) .'</td>
                                                            </tr>
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td style="color: #333; font-size: 12px; font-weight: normal; text-align: right">Total:</td>
                                                                <td style="color: #333; font-size: 12px; font-weight: bold; text-align: right">$ '.number_format($this->_amount, 2) .'</td>
                                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style=" font-size: 12px;">
                                            Transaction ID: &nbsp;&nbsp;&nbsp;&nbsp;'.$this->_codePayment.'
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <br/><br/>
                            <div style="  background: #231f20;  width:100%">
                                <table border="0" style="border-collapse: collapse; border-spacing: 0px " cellpadding="0" cellspacing="0"  width="100%">
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align ="center">
                                            <img src="'.STATIC_URL .'/front-beta/images/order/info-wetheadorned-com.jpg"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>

                        </body>
                    </html>';

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('a4', 'portrait');
        $dompdf->render();

        $strPdf = $dompdf->output();
        $file = APPLICATION_PUBLIC . '/dinamic/orders/' . $this->_codePayment . '-' . date('ynd-his') . '.pdf';
        file_put_contents($file, $strPdf);

        return $file;
    }




}