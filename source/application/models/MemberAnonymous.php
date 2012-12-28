<?php

class Application_Model_MemberAnonymous extends Core_Model {

    protected $_tableMemberAnonymous;

    public function __construct() {
        $this->_tableMemberAnonymous = new Application_Model_DbTable_MemberAnonymous();
    }

    /**
     * metodo getMember(), devuelve todos los datos de un Member
     * @param $idMember    id de la Member  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getMember($idMember) {
        $smt = $this->_tableMemberAnonymous->select()
                ->where('member_anonymous_id =?', $idMember)
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
        $smt = $this->_tableMemberAnonymous->select()
                ->where('member_anonymous_mail =?', $email)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    

    /**
     * metodo insert(), registra los datos del MemberAnonymous 
     * @param array             $data   array con los datos de la Member array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableMemberAnonymous->insert($data)) {
            return $this->_tableMemberAnonymous->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * metodo update(), registra los datos del MemberAnonymous 
     * @param array     $data           array con los datos de la Member array('column'=>'valor')
     * @param int       $idMember  id de la Member
     * @return bolean   
     */
    public function update($data, $idMember) {
        if ($idMember != '') {
            $where = $this->_tableMemberAnonymous->getAdapter()
                    ->quoteInto('member_anonymous_id =?', $idMember);
            return $this->_tableMemberAnonymous->update($data, $where);
        } else {
            return false;
        }
    }

}

