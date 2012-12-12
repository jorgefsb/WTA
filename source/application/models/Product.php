<?php

class Application_Model_Product extends Core_Model {

    protected $_tableProduct;
    protected $_tableActress;
    protected $_tableProductActress;
    protected $_tableProductSize;
    protected $_tableDesignerType;
    protected $_tableDesigner;
    protected $_tableCollectionType;

    public function __construct() {
        $this->_tableProduct = new Application_Model_DbTable_Product();
        $this->_tableActress = new Application_Model_DbTable_Actress();
        $this->_tableProductActress = new Application_Model_DbTable_ProductActress();
        $this->_tableProductSize = new Application_Model_DbTable_ProductSize();
        $this->_tableDesignerType = new Application_Model_DbTable_DesignType();
        $this->_tableDesigner= new Application_Model_DbTable_Designer();
        $this->_tableCollectionType = new Application_Model_DbTable_CollectionType();
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
                        array('pr' => $this->_tableProduct->getName()), array(
                    'pr.product_id',
                    'pr.product_name',
                    'pr.product_code',
                    'pr.product_description',
                    'pr.product_publish_date',
                    'pr.product_price',
                    'pr.product_price_menber',
                    'pr.product_in_stock',
                    'pr.product_limited_quantity',
                    'pr.product_cant_limited_quantity',
                    'pr.product_cant_buy',
                    'pr.product_create_date',
                    'pr.product_public',
                    'ct.collection_type_name',
                    'dt.design_type_name',
                    'd.designer_name',
                    'product_actress' => new Zend_Db_Expr("GROUP_CONCAT(a.actress_name SEPARATOR ', ')"),
                        )
                )
                ->joinLeft(array('pra' => $this->_tableProductActress->getName()), 'pr.product_id=pra.product_actress_product_id', '')
                ->joinLeft(array('a' => $this->_tableActress->getName()), 'a.actress_id=pra.product_actress_actress_id', '')
                ->joinLeft(array('dt' => $this->_tableDesignerType->getName()), 'pr.product_design_type=dt.design_type_id', '')
                ->joinLeft(array('d' => $this->_tableDesigner->getName()), 'pr.product_designer=d.designer_id', '')
                ->joinLeft(array('ct' => $this->_tableCollectionType->getName()), 'pr.product_collection_type=ct.collection_type_id', '')

                ->order('product_order asc')
                ->where('product_delete = 0')
                ->group('pr.product_id');
        $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

    public function getOrderlast() {
        $sql = $this->_tableProduct->select()
                ->from($this->_tableProduct->getName(), 'product_order')
                ->where('product_order >= 0')
                ->order('product_order desc')
                ->limit(1);
        return $this->_tableProduct->getAdapter()->fetchOne($sql);
    }

    public function getProductForOrder($orden) {
        $smt = $this->_tableProduct
                ->select()
                ->where('product_order=?', $orden)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }

    function listingActrees($idProduct) {
        $smt = $this->_tableProduct
                ->getAdapter()
                ->select()
                ->from(
                        array('pr' => $this->_tableProduct->getName()), array(
                    'pra.product_actress_id',
                    'a.actress_name',
                    'pra.product_actress_commission',
                    'pra.product_actress_img',
                    'pra.product_actress_active',
                    'pra.product_actress_product_id',
                    'pra.product_actress_actress_id',
                        )
                )
                ->join(array('pra' => $this->_tableProductActress->getName()), 'pr.product_id=pra.product_actress_product_id', '')
                ->join(array('a' => $this->_tableActress->getName()), 'a.actress_id=pra.product_actress_actress_id', '')
                ->where('pr.product_id=?', $idProduct);
        $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

    function insertSize($data) {
        if ($this->_tableProductSize->insert($data)) {
            return $this->_tableProductSize->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }

    function getSize($idProduct) {
        $smt = $this->_tableProductSize->select()
                ->from(array($this->_tableProductSize->getName()),
                        array(
                            'product_size_size_id',
                            'product_size_product_id'
                            ))
                ->where('product_size_product_id =?', $idProduct)
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

    function existSize($idProduct, $size) {
        $smt = $this->_tableProductSize->select()
                ->where('product_size_product_id =?', $idProduct)
                ->where('product_size_size_id =?', $size)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        if ($result != FALSE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function deleteSize($idProduct, $size) {
        if ($idProduct == '' || $size == '')
            return false;

        $where[] = $this->_tableProductSize->getAdapter()
                ->quoteInto('product_size_product_id =?', $idProduct);
        $where[] = $this->_tableProductSize->getAdapter()
                ->quoteInto('product_size_size_id =?', $size);
        return $this->_tableProductSize->delete($where);
    }
    
    public function selectProductLowerPriority($order){
        $smt = $this->_tableProduct->select()
                ->where('product_order >?', $order)
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

}

