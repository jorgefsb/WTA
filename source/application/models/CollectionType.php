<?php

class Application_Model_CollectionType extends Core_Model {

    protected $_tableCollectionType;

    public function __construct() {
        $this->_tableCollectionType = new Application_Model_DbTable_CollectionType();
    }
    /**
     * metodo getCollectionType(), devuelve todos los datos de un CollectionType
     * @param $idCollectionType    id de la CollectionType  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getCollectionType($idCollectionType) {
        $smt = $this->_tableCollectionType->select()
                ->where('collection_type_id =?', $idCollectionType)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la CollectionType 
     * @param array             $data   array con los datos de la CollectionType array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableCollectionType->insert($data)) {
            return $this->_tableCollectionType->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la CollectionType 
     * @param array     $data           array con los datos de la CollectionType array('column'=>'valor')
     * @param int       $idCollectionType  id de la CollectionType
     * @return bolean   
     */

    public function update($data, $idCollectionType) {
        if ($idCollectionType != '') {
            $where = $this->_tableCollectionType->getAdapter()
                    ->quoteInto('collection_type_id =?', $idCollectionType);
            return $this->_tableCollectionType->update($data, $where);
        } else {
            return false;
        }
    }
    
    public function listing() {
        
         $smt = $this->_tableCollectionType
                 ->select()
                 ->order('collection_type_name asc');
                 $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    
    

}

