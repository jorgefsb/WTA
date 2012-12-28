<?php

class Application_Model_Member extends Core_Model {

    protected $_tableMember;

    public function __construct() {
        $this->_tableMember = new Application_Model_DbTable_Member();
    }

    /**
     * metodo getMember(), devuelve todos los datos de un Member
     * @param $idMember    id de la Member  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getMember($idMember) {
        $smt = $this->_tableMember->select()
                ->where('member_id =?', $idMember)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }

    
    /**
     * metodo getMemberByEmail(), devuelve todos los datos de un Member
     * @param $email    email de la Member Anonymous
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getMemberByEmail($email) {
        $smt = $this->_tableMember->select()
                ->where('member_mail =?', $email)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    
    /**
     * metodo getMember(), devuelve todos los datos de un Member
     * @param $idMember    id de la Member  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getMemberForIdConfirm($idConfirm) {
        $smt = $this->_tableMember->select()
                ->where('member_id_confirm =?', $idConfirm)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo getMember(), devuelve todos los datos de un Member
     * @param $idMember    id de la Member  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getMembers() {
        $smt = $this->_tableMember->select()
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo getMember(), devuelve todos los datos de un Member
     * @param $idMember    id de la Member  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function searchMember($value) {
        $smt = $this->_tableMember->select();
        if ($value!='') {
            $where = $this->_tableMember->getAdapter()->quoteInto('member_name like ?', '%'.$value.'%');
            $smt->orWhere($where);
            $where = $this->_tableMember->getAdapter()->quoteInto('member_mail like ?', '%'.$value.'%');
            $smt->orWhere($where);
            $where = $this->_tableMember->getAdapter()->quoteInto('member_last_name like ?', '%'.$value.'%');
            $smt->orWhere($where);
        }
        $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insert(), registra los datos de la Member 
     * @param array             $data   array con los datos de la Member array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableMember->insert($data)) {
            return $this->_tableMember->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * metodo update(), registra los datos de la Member 
     * @param array     $data           array con los datos de la Member array('column'=>'valor')
     * @param int       $idMember  id de la Member
     * @return bolean   
     */
    public function update($data, $idMember) {
        if ($idMember != '') {
            $where = $this->_tableMember->getAdapter()
                    ->quoteInto('member_id =?', $idMember);
            return $this->_tableMember->update($data, $where);
        } else {
            return false;
        }
    }

    function getpassword($usuario) {
        $sql = $this->_tableMember->select()
                ->from($this->_tableMember->getName(), array('member_password'))
                ->where('member_mail = ?', $usuario);
        return $this->_tableMember->getAdapter()->fetchOne($sql);
    }

    /**
     * metodo insert(), registra los datos de la Member 
     * @param array             $data   array con los datos de la Member array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insertPasswordReset($passwordTemp, $mail) {
        if ($passwordTemp != '' && $mail != '') {
            $data['member_password_reset'] = $passwordTemp;
            $where = $this->_tableMember->getAdapter()
                    ->quoteInto('member_mail =?', $mail);
            return $this->_tableMember->update($data, $where);
        } else {
            return false;
        }
    }

    public function isTokenPasswordReset($token) {
        if ($token == '') {
            return FALSE;
        }
        $sql = $this->_tableMember->select()
                ->from($this->_tableMember->getName(), array('member_id'))
                ->where('member_password_reset = ?', $token);
        return $this->_tableMember->getAdapter()->fetchOne($sql);
    }

}

