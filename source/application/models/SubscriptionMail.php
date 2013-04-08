<?php

class Application_Model_SubscriptionMail extends Core_Model {

    protected $_tableSubscription;

    public function __construct() {
        $this->_tableSubscription = new Application_Model_DbTable_SubscriptionMail();
    }
   
    /**
     * metodo insert(), registra los datos de la SubscriptionMail 
     * @param array             $data   array con los datos de la SubscriptionMail array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableSubscription->insert($data)) {
            return $this->_tableSubscription->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
    
    
    public function listing() {
        
         $smt = $this->_tableSubscription
                 ->select()
                 ->order('email');
                 $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    
    

}

