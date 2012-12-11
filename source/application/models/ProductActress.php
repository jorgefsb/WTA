<?php

class Application_Model_ProductActress extends Core_Model {

    protected $_tableProductActress;
    protected $_tableActress;
    protected $_tableProduct;

    public function __construct() {
        $this->_tableProductActress = new Application_Model_DbTable_ProductActress();
        $this->_tableActress = new Application_Model_DbTable_Actress();
        $this->_tableProduct = new Application_Model_DbTable_Product();
    }
    /**
     * metodo getProductActress(), devuelve todos los datos de un ProductActress
     * @param $idProductActress    id de la ProductActress  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getProductActress($idProduct,$idActress) {
        //print_r(func_get_args());
        $smt = $this->_tableProductActress->select()
                ->where('product_actress_product_id =?', $idProduct)
                ->where('product_actress_actress_id =?', $idActress)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la ProductActress 
     * @param array             $data   array con los datos de la ProductActress array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableProductActress->insert($data)) {
            return $this->_tableProductActress->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la ProductActress 
     * @param array     $data           array con los datos de la ProductActress array('column'=>'valor')
     * @param int       $idProductActress  id de la ProductActress
     * @return bolean   
     */

    public function update($data, $idProduct,$idActress) {
        if ($idProduct != '' && $idActress != '') {
            $where[] = $this->_tableProductActress->getAdapter()
                    ->quoteInto('product_actress_product_id =?', $idProduct);
            $where[] = $this->_tableProductActress->getAdapter()
                    ->quoteInto('product_actress_actress_id =?', $idActress);
            return $this->_tableProductActress->update($data, $where);
        } else {
            return false;
        }
    }
    
    
    
    
    
    

}

