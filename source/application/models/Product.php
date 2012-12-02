<?php

class Application_Model_Product extends Core_Model {

    protected $_tableProduct;
    protected $_tableCategory;

    public function __construct() {
        $this->_tableProduct = new Application_Model_DbTable_Product();
        $this->_tableCategory = new Application_Model_DbTable_Category();
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
                             'pr.product_category',
                             'pr.product_description',
                             'pr.product_publish_date',
                             'pr.product_price',
                             'pr.product_in_stock',
                             'pr.product_limited_quantity',
                             'pr.product_create_date',
                             'pr.product_public',
                             'cat.category_name',
                             'cat.category_id',
                             'cat.category_public',
                             )
                        )
                 ->join(array('cat'=>$this->_tableCategory->getName()), 'cat.category_id=pr.product_category','')
                 ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    

}

