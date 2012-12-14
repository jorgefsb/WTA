<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Membership extends Core_Entity {
    const MENBERSHIPT_ACTIVE = 1;
    const MENBERSHIPT_INACTIVE = 2;
    const MENBERSHIPT_PENDING = 3;

    protected $_id;
    protected $_membershipId;
    protected $_memberId;
    protected $_startDate;
    protected $_endDate;
    protected $_price;
    protected $_paymentDate;
    protected $_pasarela;
    protected $_status;
    protected $_isfree;

    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['membership_member_id'];
        $this->_membershipId = $data['membership_id'];
        $this->_memberId = $data['member_id'];
        $this->_startDate = $data['membership_member_start_date'];
        $this->_endDate = $data['membership_member_end_date'];
        $this->_price = $data['membership_member_price'];
        $this->_paymentDate = $data['membership_member_payment_date'];
        $this->_pasarela = $data['membership_member_pasarela'];
        $this->_status = $data['membership_member_status'];
        $this->_isfree = $data['membership_member_isfree'];
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idMember
     * @return void
     */

    function identify($idMembership) {
        $modelMemberShip = new Application_Model_Membership();
        $data = $modelMemberShip->getMembership($idMembership);
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
        $data['membership_member_id'] = $this->_id;
        $data['membership_id'] = $this->_membershipId;
        $data['member_id'] = $this->_memberId;
        $data['membership_member_start_date'] = $this->_startDate;
        $data['membership_member_end_date'] = $this->_endDate;
        $data['membership_member_price'] = $this->_price;
        $data['membership_member_payment_date'] = $this->_paymentDate;
        $data['membership_member_pasarela'] = $this->_pasarela;
        $data['membership_member_status'] = $this->_status;
        $data['membership_member_isfree'] = $this->_isfree;

        return $this->cleanArray($data);
    }

    function update() {
        $modelMemberShip = new Application_Model_Membership();
        $date = $this->setParamsDataBase();

        if ($modelMemberShip->updateMembershipMember($date, $this->_id) !== FALSE) {
            $this->_message = 'Registration was successful';
            return true;
        } else {
            $this->_message = 'registration fails';
            return false;
        }
    }

    /**
     * metodo insert(), registro de la menbresia
     * @param   void    sin parametros
     * @return  bolean  si se registro devuelve true en caso contrario false
     */
    function insert() {
        $modelMemberShip = new Application_Model_Membership();
        if ($this->_isfree == 1) {
            $this->_status = self::MENBERSHIPT_PENDING;
        }
        $data = $this->setParamsDataBase();
        $date['membership_member_register_date'] = date('Y-m-d H:i:s');
        $id = $modelMemberShip->insertMembershipMember($data);
        if ($id != FALSE) {
            $this->_id = $id;
            if ($this->_isfree == 1) {
                $this->active();
            }
            return TRUE;
        } else {
            $this->_message = 'Registration to failure';
            return FALSE;
        }
    }

    function active() {
        $modelMemberShip = new Application_Model_Membership();
        $data['membership_member_status'] = self::MENBERSHIPT_ACTIVE;
        return $modelMemberShip->updateMembershipMember($data, $this->_id);
    }

    function inactive() {
        $modelMemberShip = new Application_Model_Membership();
        $data['membership_member_status'] = self::MENBERSHIPT_INACTIVE;
        return $modelMemberShip->updateMembershipMember($data, $this->_id);
    }

    function getMembershipActive() {
        $modelMemberShip = new Application_Model_Membership();
        return $modelMemberShip->getMembershipActive();
    }

}