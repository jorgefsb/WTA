<?php

class Application_Model_Transaction extends Core_Model {

    protected $_tableTransaction;

    public function __construct() {
        $this->_tableTransaction = new Application_Model_DbTable_Transaction();
    }
    /**
     * metodo getTransaction(), devuelve todos los datos de un Transaction
     * @param $idTransaction    id de la Transaction  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getTransaction($idTransaction) {
        $smt = $this->_tableTransaction->select()
                ->where('transaction_id =?', $idTransaction)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insert(), registra los datos de la Transaction 
     * @param array             $data   array con los datos de la Transaction array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function listing() {
        $smt = $this->_tableTransaction
                ->select()
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la Transaction 
     * @param array             $data   array con los datos de la Transaction array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableTransaction->insert($data)) {
            return $this->_tableTransaction->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Transaction 
     * @param array     $data           array con los datos de la Transaction array('column'=>'valor')
     * @param int       $idTransaction  id de la Transaction
     * @return bolean   
     */

    public function update($data, $idTransaction) {
        if ($idTransaction != '') {
            $where = $this->_tableTransaction->getAdapter()
                    ->quoteInto('transaction_id =?', $idTransaction);
            return $this->_tableTransaction->update($data, $where);
        } else {
            return false;
        }
    }
    
    
    

}

