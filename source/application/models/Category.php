<?php

class Application_Model_Category extends Core_Model {

    protected $_tableCategory;

    public function __construct() {
        $this->_tableCategory = new Application_Model_DbTable_Category();
    }
    /**
     * metodo getCategory(), devuelve todos los datos de un Category
     * @param $idCategory    id de la Category  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getCategory($idCategory) {
        $smt = $this->_tableCategory->select()
                ->where('category_id =?', $idCategory)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la Category 
     * @param array             $data   array con los datos de la Category array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableCategory->insert($data)) {
            return $this->_tableCategory->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Category 
     * @param array     $data           array con los datos de la Category array('column'=>'valor')
     * @param int       $idCategory  id de la Category
     * @return bolean   
     */

    public function update($data, $idCategory) {
        if ($idCategory != '') {
            $where = $this->_tableCategory->getAdapter()
                    ->quoteInto('category_id =?', $idCategory);
            return $this->_tableCategory->update($data, $where);
        } else {
            return false;
        }
    }
    
    public function listing(){
        $smt = $this->_tableCategory->select()
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    

}

