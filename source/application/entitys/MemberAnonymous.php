<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_MemberAnonymous extends Core_Entity {

    protected $_id;
    protected $_name;
    protected $_lastName;
    protected $_mail;

    protected $_customerProfileId;
    
    protected $_shippingAddress;
    protected $_billingInformation;
    
    
    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['member_anonymous_id'];
        $this->_name = $data['member_anonymous_first_name'];
        $this->_lastName = $data['member_anonymous_last_name'];
        $this->_mail = $data['member_anonymous_mail'];
                       
        $this->_customerProfileId = $data['member_anonymous_customerProfileId'];
                
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idMember
     * @return void
     */

    function identify($idMember) {
        $modelMember = new Application_Model_MemberAnonymous();
        $data = $modelMember->getMember($idMember);
        if ($data != '') {
            $this->asocParams($data);
        }
        return $data;
    }
    
    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idMember
     * @return void
     */

    function identifyByEmail($email) {
        $modelMember = new Application_Model_MemberAnonymous();
        $data = $modelMember->getMemberByEmail($email);
        if ($data != '') {
            $this->asocParams($data);
        }
        return $data;
    }

    


    /*
     * metodo setParamsDataBase()
     *
     * @param 
     * @return array
     */

    function setParamsDataBase() {
        $data['member_anonymous_id'] = $this->_id;
        $data['member_anonymous_first_name'] = $this->_name;
        $data['member_anonymous_last_name'] = $this->_lastName;
        $data['member_anonymous_mail'] = $this->_mail;
        $data['member_anonymous_customerProfileId'] = $this->_customerProfileId;
        
        return $this->cleanArray($data);
    }

    function update() {
        $modelMember = new Application_Model_MemberAnonymous();

        if ($modelMember->update($this->setParamsDataBase(), $this->_id) !== FALSE) {
            $this->_message = 'Profile update was successful';
            return true;
        } else {
            $this->_message = 'update fails';
            return false;
        }
    }

    /*
     * metodo createMember()
     *
     * @param 
     * @return 
     */

    function createMemberAnonymous() {
        $modelMember = new Application_Model_MemberAnonymous();
        $data = $this->setParamsDataBase();
        $data['member_anonymous_create_date'] = date('Y-m-d H:i:s');
        $id = $modelMember->insert($data);
        if ($id != FALSE) {
            $this->_id = $id;
            $this->_message = 'Registration was successful';
            return TRUE;
        } else {
            $this->_message = 'Registration to failure';
            return FALSE;
        }
    }

    public function loadProfile(){
        
        $shpAdd = array();
        $paymeth = array();
        
        if($this->_customerProfileId){
            $_transaction = new Payment_Transaction(Payment_Transaction::PAYMENT_SERVICE_AUTHORIZE );
            $customer = $_transaction->customer();
            $customer->identify($this->_customerProfileId);
            
            $shippings = $customer->getListShippingAddress();
            $billings = $customer->getListBillingInformation();
            
            if(is_array($shippings)){
                foreach($shippings as $ship){ 
                    $shpAdd[] = $ship->getAllProperties();
                }
            }
            if(is_array($billings)){
                foreach($billings as $bill) {
                    $paymeth[] = $bill->getAllProperties();
                }
            }
            
        }
        
        $this->_shippingAddress = $shpAdd;
        $this->_billingInformation= $paymeth;
        
    }
    
    public function saveShippingAddress($data){
        $_transaction = new Payment_Transaction(Payment_Transaction::PAYMENT_SERVICE_AUTHORIZE );
        $customer = $_transaction->customer();
        
        $_shipping = $customer->shippingAddress();
        $_shipping->_customerProfileId = $this->_customerProfileId;
        foreach($data as $key=>$value){
            $_shipping->$key = $value;
        }
        if(!$_shipping->commit()){ 
            $this->_message = $_shipping->getError();
            return false;
        }
        $this->_message = 'The Shipping Address was successfully saved';
        return true;
    }
    
    public function saveBillingInformation($data){
        $_transaction = new Payment_Transaction(Payment_Transaction::PAYMENT_SERVICE_AUTHORIZE );
        $customer = $_transaction->customer();
        
        $_billing = $customer->billingInformation();
        $_billing->_customerProfileId = $this->_customerProfileId;
        foreach($data as $key=>$value){
            $_billing->$key = $value;
        }
        if(!$_billing->commit()){ 
            $this->_message = $_billing->getError();
            return false;
        }
        $this->_message = 'The Billing Information was successfully saved';
        return true;
    }
    
    
}