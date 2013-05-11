<?php

class Application_Model_Membership extends Core_Model {

    protected $_tableMembership;
    protected $_tableMembershipMember;

    public function __construct() {
        $this->_tableMembership = new Application_Model_DbTable_Membership();
        $this->_tableMembershipMember = new Application_Model_DbTable_MembershipMember();
    }

    /**
     * metodo getMembership(), devuelve todos los datos de un Membership
     * @param $idMembership    id de la Membership
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor
     */
    function getMembership($idMembership) {
        $smt = $this->_tableMembership->select()
                ->where('membership_id =?', $idMembership)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }


    /**
     * metodo  getMembership(),  devuelve todos los datos de la membresia
     * @param  void
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor
     */
    function getMemberships() {
        $smt = $this->_tableMembership->select()
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insert(),         registra los datos de la Membresia
     * @param array             $data   array con los datos de la Menbresia array('campo'=>'valor del campo')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos
     */
    public function insert($data) {
        if ($this->_tableMembership->insert($data)) {
            return $this->_tableMembership->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * metodo update(), actualiza los datos de la menbresia
     * @param array     $data          array con los datos de la menbresia array('column'=>'valor')
     * @param int       $idMembership  id de la menbresia
     * @return bolean
     */
    public function update($data, $idMembership) {
        if ($idMembership != '') {
            $where = $this->_tableMembership->getAdapter()
                    ->quoteInto('membership_id =?', $idMembership);
            return $this->_tableMembership->update($data, $where);
        } else {
            return false;
        }
    }


    /**
     * metodo getMembershipActive(), devuelve la menbresia activa
     * @param void
     * @return array            devuelve un array asociativo con las
     *                          columnas y su respectivo valor de la menbresia
     */
    function getMembershipActive() {
        $smt = $this->_tableMembership->select()
                ->where('membership_active=?',1)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insertMembershipMember(), registra las menbresias de los usuarios
     * @param array             $data   array con los datos de la Menbresia array('campo'=>'valor del campo')
     * @return array            devuelve un array asociativo con las
     *                          columnas y su respectivo valor de la menbresia
     */
    function insertMembershipMember($data) {
        if ($this->_tableMembershipMember->insert($data)) {
            return $this->_tableMembershipMember->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
    /**
     * metodo insertMembershipMember(), registra las menbresias de los usuarios
     * @param array             $data   array con los datos de la Menbresia array('campo'=>'valor del campo')
     * @return array            devuelve un array asociativo con las
     *                          columnas y su respectivo valor de la menbresia
     */
    function updateMembershipMember($data,$idMembershipMember) {
        if ($idMembershipMember != '') {
            $where = $this->_tableMembershipMember->getAdapter()
                    ->quoteInto('membership_member_id =?', $idMembershipMember);
            return $this->_tableMembershipMember->update($data, $where);
        } else {
            return false;
        }
    }

    function getMembershipByUser($idMember){
        if($idMember){
            $smt = $this->_tableMembershipMember->select()
                            ->where('member_id =?', $idMember)
                            ->where('membership_member_status = ?', Application_Entity_Membership::MENBERSHIPT_ACTIVE)
                           ->query();
            $result = $smt->fetch();
            $smt->closeCursor();
            return $result;
        }
        return false;
    }



}

