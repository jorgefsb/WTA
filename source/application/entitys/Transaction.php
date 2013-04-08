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

    function addMembership(Application_Entity_Membership $membership) {
        $transactionDetails = new Application_Model_TransactionDetails();
        $data['membership_member_id'] = $membership->getPropertie('_id');
        $data['product_type_id'] = self::TYPE_MENBERSHIP;
        $data['amount'] = $membership->getPropertie('_price');
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
    
    /* Funciones para transaccion con banco */
    
    public function sendToPaymentGateway($dataCard){        
            
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
            
            if($customerProfileId){
                if(!$this->_member){
                    
                }
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
            if($_payment->commit()){
                $modelTransaction = new Application_Model_Transaction();
                                
                $modelTransaction->update(array(
                    'transaction_code_payment'=>$_payment->_profileTransactionId,
                    'transaction_payment_date'=>date('Y-m-d H:i:s'),
                    'tansaction_state_id'=>  self::TRANSACTION_PAID
                ), $this->_id);
                
                $this->_message = 'Payment Successful';
                return $_payment->_profileTransactionId;
            }else{                
                //$this->_message = print_r($_transaction->getLastExecution(), true);
                //$this->_message = 'We had a problem width your card. Please enter a new one, or review your information and try again.';
                $error=$_payment->getError();
                if($error=='Decline' || $error == 'Error'){
                    $this->_message = 'We had a problem width your card. Please enter a new one, or review your information and try again.';
                }else{
                    $this->_message = $_payment->getError();
                }
                return false;
            }
        }else{
            $this->_message = 'We had a problem. Please review your information and try again.';
            return false;
        }
        
    }
    
}