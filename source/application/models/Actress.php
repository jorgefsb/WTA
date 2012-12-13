<?php

class Application_Model_Actress extends Core_Model {

    protected $_tableActress;
    protected $_tableProductActress;

    public function __construct() {
        $this->_tableActress = new Application_Model_DbTable_Actress();
        $this->_tableProductActress = new Application_Model_DbTable_ProductActress();
    }
    /**
     * metodo getActress(), devuelve todos los datos de un Actress
     * @param $idActress    id de la Actress  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getActress($idActress) {
        $smt = $this->_tableActress->select()
                ->where('actress_id =?', $idActress)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    /**
     * metodo getActressByName(), devuelve todos los datos de un Actress
     * @param $nameActress    nombre de la Actress  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getActressByName($nameActress) {
        $smt = $this->_tableActress->select()
                ->where('actress_name = ?', $nameActress)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    /**
     * metodo insert(), registra los datos de la Actress 
     * @param array             $data   array con los datos de la Actress array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableActress->insert($data)) {
            return $this->_tableActress->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Actress 
     * @param array     $data           array con los datos de la Actress array('column'=>'valor')
     * @param int       $idActress  id de la Actress
     * @return bolean   
     */

    public function update($data, $idActress) {
        if ($idActress != '') {
            $where = $this->_tableActress->getAdapter()
                    ->quoteInto('actress_id =?', $idActress);
            return $this->_tableActress->update($data, $where);
        } else {
            return false;
        }
    }
    
    public function listing(){
        $smt = $this->_tableActress->select()
                ->order('actress_order asc')
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    public function listingProduct($product){
        $smt = $this->_tableActress
                ->getAdapter()
                ->select()
                ->from(array('a'=>$this->_tableActress->getName()),
                array(
                    'a.actress_id',
                    'a.actress_name',
                    )
                )
                ->join(array('pa'=>$this->_tableProductActres->getName()), 
                        'a.actress_id!=pa.product_actress_product_id','')
                ->where('actress_order=?', $orden)
                ->order('actress_order asc')
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    public function getOrderlast(){
        $sql = $this->_tableActress->select()
                ->from($this->_tableActress->getName(),'actress_order')
                ->where('actress_order >= 0')
                ->order('actress_order desc')
                ->limit(1);
        return $this->_tableActress->getAdapter()->fetchOne($sql);
    }
    
    public function getactressForOrder($orden){
        $smt = $this->_tableActress
                ->select()
                ->where('actress_order=?', $orden)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    public function listingPublicNotProduct($idProduct){
        $subSelect = $this->_tableProductActress
                ->select()
                ->from($this->_tableProductActress->getName(),array('product_actress_actress_id'))
                ->where('product_actress_product_id=?',$idProduct);
        $smt = $this->_tableActress
                ->getAdapter()
                ->select()
                ->from(array('a'=>$this->_tableActress->getName()),
                        array(
                            'a.actress_id',
                            'a.actress_name',
                            'a.actress_public',
                            ))
                ->where("actress_id NOT IN ? ", $subSelect);
        $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    
    public function listingProducts($idActress){
        $smt = $this->_tableProductActress->select()
                ->from($this->_tableProductActress->getName(),array('product_actress_product_id'))
                ->where('product_actress_actress_id =?', $idActress)
                ->where('product_actress_active = 1')
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

}

