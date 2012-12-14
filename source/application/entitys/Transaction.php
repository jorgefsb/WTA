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

    protected $_id;
    protected $_member;
    protected $_amount;
    protected $_state;
    protected $_codePayment;
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
        $data['transaction_amount'] = $this->_amount ;
        $data['tansaction_state_id'] = $this->_state;
        $data['member_id'] = $this->_member;
        $data['transaction_code_payment'] = $this->_codePayment;
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
    
    function addMembership(Application_Entity_Membership $membership){
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
    
    function confirmPayment(){
        
    }

    

}