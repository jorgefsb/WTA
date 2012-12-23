<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_CreditCard extends Core_Entity {

    protected $_id;
    protected $_name;
    protected $_number;
    protected $_typeId;
    protected $_segurityCode;
    protected $_expireMonth;
    protected $_expireYear;
    protected $_memberId;
    
    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['credit_card_id'];
        $this->_name = $data['credit_card_name'];
        $this->_number = $data['credit_card_number'];
        $this->_memberId = $data['member_id'];
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idCreditCard
     * @return void
     */

    function identify($idCreditCard) {
        $modelCreditCard = new Application_Model_CreditCard();
        $data = $modelCreditCard->getCreditCard($idCreditCard);
        //print_r($data);
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
        $data['credit_card_id'] = $this->_id;
        $data['credit_card_name'] = $this->_name;
        $data['credit_card_number'] = $this->_number;
        $data['member_id'] = $this->_memberId;
        return $this->cleanArray($data);
    }

    function update() {
        $filter = new Core_SeoUrl();
        $modelCreditCard = new Application_Model_CreditCard();
        $data = $this->setParamsDataBase();
        return $modelCreditCard->update($data, $this->_id);
    }

    /*
     * metodo createCreditCard()
     *
     * @param 
     * @return 
     */

    function createCreditCard() {
        $modelCreditCard = new Application_Model_CreditCard();
        $this->_order = $this->getSigOrder();
        $data = $this->setParamsDataBase();
        $data['credit_card_create_date'] = date('Y-m-d H:i:s');
        $id = $modelCreditCard->insert($data);
        if ($id != false) {
            $this->_id = $id;
            $this->update();
            $this->_message = 'satisfactory record';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }
    
    static function listingForMember($idMember){
        $modelCreditCard = new Application_Model_CreditCard();
        return $modelCreditCard->listingForMember($idMember);
    }
}