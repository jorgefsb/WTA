<?php

class Application_Model_TransactionDetails extends Core_Model {

    protected $_tableTransactionDetails;

    public function __construct() {
        $this->_tableTransactionDetails = new Application_Model_DbTable_TransactionDetails();
    }
    /**
     * metodo getTransactionDetails(), devuelve todos los datos de un TransactionDetails
     * @param $idTransactionDetails    id de la TransactionDetails  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getTransactionDetails($idTransactionDetails) {
        $smt = $this->_tableTransactionDetails->select()
                ->where('transaction_details_id =?', $idTransactionDetails)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insert(), registra los datos de la TransactionDetails 
     * @param array             $data   array con los datos de la TransactionDetails array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function listing($idTransaction) {
        $smt = $this->_tableTransactionDetails
                ->select()
                ->where('transaction_id =?', $idTransaction)
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la TransactionDetails 
     * @param array             $data   array con los datos de la TransactionDetails array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableTransactionDetails->insert($data)) {
            return $this->_tableTransactionDetails->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la TransactionDetails 
     * @param array     $data           array con los datos de la TransactionDetails array('column'=>'valor')
     * @param int       $idTransactionDetails  id de la TransactionDetails
     * @return bolean   
     */

    public function update($data, $idTransactionDetails) {
        if ($idTransactionDetails != '') {
            $where = $this->_tableTransactionDetails->getAdapter()
                    ->quoteInto('transaction_details_id =?', $idTransactionDetails);
            return $this->_tableTransactionDetails->update($data, $where);
        } else {
            return false;
        }
    }
    
    
    

}

