<?php

class Application_Model_Product extends Core_Model {

    protected $_tableProduct;
    protected $_tableActress;

    public function __construct() {
        $this->_tableProduct = new Application_Model_DbTable_Product();
        $this->_tableActress = new Application_Model_DbTable_Actress();
    }
    /**
     * metodo getProduct(), devuelve todos los datos de un Product
     * @param $idProduct    id de la Product  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getProduct($idProduct) {
        $smt = $this->_tableProduct->select()
                ->where('product_id =?', $idProduct)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la Product 
     * @param array             $data   array con los datos de la Product array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableProduct->insert($data)) {
            return $this->_tableProduct->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Product 
     * @param array     $data           array con los datos de la Product array('column'=>'valor')
     * @param int       $idProduct  id de la Product
     * @return bolean   
     */

    public function update($data, $idProduct) {
        if ($idProduct != '') {
            $where = $this->_tableProduct->getAdapter()
                    ->quoteInto('product_id =?', $idProduct);
            return $this->_tableProduct->update($data, $where);
        } else {
            return false;
        }
    }
    
    public function listing() {
        
         $smt = $this->_tableProduct
                 ->getAdapter()
                 ->select()
                 ->from(
                         array('pr'=>$this->_tableProduct->getName()),
                         array(
                             'pr.product_id',
                             'pr.product_name',
                             'pr.product_actress',
                             'pr.product_description',
                             'pr.product_publish_date',
                             'pr.product_price',
                             'pr.product_in_stock',
                             'pr.product_limited_quantity',
                             'pr.product_create_date',
                             'pr.product_public',
                             'ac.actress_name',
                             )
                        )
                 ->joinLeft(array('ac'=>$this->_tableActress->getName()), 'ac.actress_id=pr.product_actress','')
                 ->order('product_order asc')
                 ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
     public function getOrderlast(){
        $sql = $this->_tableProduct->select()
                ->from($this->_tableProduct->getName(),'product_order')
                ->where('product_order >= 0')
                ->order('product_order desc')
                ->limit(1);
        return $this->_tableProduct->getAdapter()->fetchOne($sql);
    }
    
    public function getProductForOrder($orden){
        $smt = $this->_tableProduct
                ->select()
                ->where('product_order=?', $orden)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    

}

