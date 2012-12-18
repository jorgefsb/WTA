<?php

class Application_Model_CreditCard extends Core_Model {

    protected $_tablecreditCard;
    

    public function __construct() {
        $this->_tablecreditCard = new Application_Model_DbTable_creditCard();
        
    }

    /**
     * metodo getcreditCard(), devuelve todos los datos de un creditCard
     * @param $idcreditCard    id de la creditCard  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getcreditCard($idcreditCard) {
        $smt = $this->_tablecreditCard->select()
                ->where('credit_card_id =?', $idcreditCard)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insert(), registra los datos de la creditCard 
     * @param array             $data   array con los datos de la creditCard array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tablecreditCard->insert($data)) {
            return $this->_tablecreditCard->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * metodo update(), registra los datos de la creditCard 
     * @param array     $data           array con los datos de la creditCard array('column'=>'valor')
     * @param int       $idcreditCard  id de la creditCard
     * @return bolean   
     */
    public function update($data, $idcreditCard) {
        if ($idcreditCard != '') {
            $where = $this->_tablecreditCard->getAdapter()
                    ->quoteInto('credit_card_id =?', $idcreditCard);
            return $this->_tablecreditCard->update($data, $where);
        } else {
            return false;
        }
    }

    public function listingForMember($idMember) {
        $smt = $this->_tablecreditCard
                ->select()
                ->where('member_id = ?',$idMember);
        $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

        


}



