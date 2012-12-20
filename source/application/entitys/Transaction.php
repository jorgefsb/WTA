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
    protected $_shiAddFirstName;
    protected $_shiAddLastName;
    protected $_shiAddAddAddres;
    protected $_shiAddAddresContinued;
    protected $_shiAddPostalConde;
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
    protected $_billAddPostalConde;
    protected $_billAddPhoneNumber;
    protected $_cardNumber;
    protected $_cardtypeId;
    protected $_cardSergurityCode;
    protected $_cardExpirationMonth;
    protected $_cardExpirationYear;
    /**
     * __Construct         
     *
     */
    function __construct() {
        
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
        $this->_shiAddPostalConde = $data['transaction_shi_add_postal_code'];
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
        $this->_billAddPostalConde = $data['transaction_bill_add_postal_code'];
        $this->_billAddPhoneNumber = $data['transaction_bill_add_phone_number'];

        
        $this->_cardNumber = $data['transaction_card_number'];
        $this->_cardtypeId = $data['card_type_id'];
        $this->_cardSergurityCode = $data['transaction_card_segurity_code'];
        $this->_cardExpirationMonth = $data['transaction_card_expiration_month'];
        $this->_cardExpirationYear = $data['transaction_card_expiration_year'];
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
        $data['transaction_shi_add_postal_code'] = $this->_shiAddPostalConde;
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
        $data['transaction_bill_add_postal_code'] = $this->_billAddPostalConde;
        $data['transaction_bill_add_phone_number'] = $this->_billAddPhoneNumber;
        
        
        $data['transaction_card_number'] = $this->_cardNumber;
        $data['card_type_id'] = $this->_cardtypeId;
        $data['transaction_card_segurity_code'] = $this->_cardSergurityCode;
        $data['transaction_card_expiration_month'] = $this->_cardExpirationMonth;
        $data['transaction_card_expiration_year'] = $this->_cardExpirationYear;
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

    function addProduct(Application_Entity_Product $product) {
        $transactionDetails = new Application_Model_TransactionDetails();
        $data['product_id'] = $product->getPropertie('_id');
        $data['product_type_id'] = self::TYPE_PRODUCT;
        $data['amount'] = $membership->getPropertie('_price');
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
    

    static function listOrders($filtro=array()) {
        $modelTransaction = new Application_Model_Transaction();
        return $modelTransaction->listOrdens($filtro);
    }

    static function listOrdensUsers() {
        $modelTransaction = new Application_Model_Transaction();
        return $modelTransaction->listOrdensUsers();
    }
}