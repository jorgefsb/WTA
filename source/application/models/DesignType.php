<?php

class Application_Model_DesignType extends Core_Model {

    protected $_tableDesignType;

    public function __construct() {
        $this->_tableDesignType = new Application_Model_DbTable_DesignType();
    }
    /**
     * metodo getDesignType(), devuelve todos los datos de un DesignType
     * @param $idDesignType    id de la DesignType  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getDesignType($idDesignType) {
        $smt = $this->_tableDesignType->select()
                ->where('design_type_id =?', $idDesignType)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la DesignType 
     * @param array             $data   array con los datos de la DesignType array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableDesignType->insert($data)) {
            return $this->_tableDesignType->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la DesignType 
     * @param array     $data           array con los datos de la DesignType array('column'=>'valor')
     * @param int       $idDesignType  id de la DesignType
     * @return bolean   
     */

    public function update($data, $idDesignType) {
        if ($idDesignType != '') {
            $where = $this->_tableDesignType->getAdapter()
                    ->quoteInto('design_type_id =?', $idDesignType);
            return $this->_tableDesignType->update($data, $where);
        } else {
            return false;
        }
    }
    
    public function delete($idDesignType) {
        if ($idDesignType != '') {
            $where = $this->_tableDesignType->getAdapter()
                    ->quoteInto('design_type_id =?', $idDesignType);
            return $this->_tableDesignType->delete($where);
        } else {
            return false;
        }
    }
    
    public function listing() {
        
         $smt = $this->_tableDesignType
                 ->select()
                 ->order('design_type_name asc');
                 $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    
    

}

