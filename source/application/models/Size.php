<?php

class Application_Model_Size extends Core_Model {

    protected $_tableSize;

    public function __construct() {
        $this->_tableSize = new Application_Model_DbTable_Size();
    }
    /**
     * metodo getSize(), devuelve todos los datos de un Size
     * @param $idSize    id de la Size  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getSize($idSize) {
        $smt = $this->_tableSize->select()
                ->where('size_id =?', $idSize)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la Size 
     * @param array             $data   array con los datos de la Size array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableSize->insert($data)) {
            return $this->_tableSize->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Size 
     * @param array     $data           array con los datos de la Size array('column'=>'valor')
     * @param int       $idSize  id de la Size
     * @return bolean   
     */

    public function update($data, $idSize) {
        if ($idSize != '') {
            $where = $this->_tableSize->getAdapter()
                    ->quoteInto('size_id =?', $idSize);
            return $this->_tableSize->update($data, $where);
        } else {
            return false;
        }
    }
    
    public function listing() {
        
         $smt = $this->_tableSize
                 ->select()
                 ->order('size_name asc');
                 $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    
    

}

