<?php

class Application_Model_Tracking extends Core_Model {

    protected $_tableTraking;

    public function __construct() {
        $this->_tableTraking = new Application_Model_DbTable_Tracking();
    }
    /**
     * metodo getTraking(), devuelve todos los datos de un Tracking
     * @param $idTracking    id de la Tracking  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getTraking($idTracking) {
        $smt = $this->_tableTraking->select()
                ->where('tracking_id =?', $idTracking)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    
    /**
     * metodo getTrakingByTransaction(), devuelve todos los datos de un Tracking
     * @param $idTracking    id de la Transaccion  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getTrakingByTransaction($idTransaction) {
        $smt = $this->_tableTraking->select()
                ->where('tracking_transaction_id =?', $idTransaction)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    
    /**
     * metodo insert(), registra los datos de la Tracking 
     * @param array             $data   array con los datos del Tracking array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableTraking->insert($data)) {
            return $this->_tableTraking->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }     

}

