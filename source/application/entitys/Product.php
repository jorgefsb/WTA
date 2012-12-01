<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Product extends Core_Entity {

    protected $_id;
    protected $_name;
    protected $_description;
    protected $_category;
    protected $_price;
    protected $_inStock;
    protected $_limitedQuantity;
    protected $_public;
    protected $_slug;
    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['product_id'];
        $this->_name = $data['product_name'];
        $this->_description = $data['product_description'];
        $this->_category = $data['product_category'];
        $this->_price = $data['product_price'];
        $this->_inStock = $data['product_in_stock'];
        $this->_limitedQuantity = $data['product_limited_quantity'];
        $this->_public = $data['product_public'];
        $this->_slug = $data['product_slug'];
        
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idProduct
     * @return void
     */

    function identify($idProduct) {
        $modelProduct = new Application_Model_Product();
        $data = $modelProduct->getProduct($idProduct);
        if ($data != '') {
            $this->asocParams($data);
        }
    }

    /*
     * metodo setParamsDataBase()
     *
     * @param 
     * @return array
     */

    function setParamsDataBase() {
        $data['product_id'] = $this->_id;
        $data['product_name'] = $this->_name;
        $data['product_description'] = $this->_description;
        $data['product_category'] = $this->_category;
        $data['product_price'] = $this->_price;
        $data['product_in_stock'] = $this->_inStock;
        $data['product_limited_quantity'] = $this->_limitedQuantity;
        $data['product_public'] = $this->_public;
        return $this->cleanArray($data);
    }

    function update() {
        $modelProduct = new Application_Model_Product();
        $data = $this->setParamsDataBase();
        $data['product_slug'] = $filter->urlFriendly($this->_name,'-').'-  '.$this->_id  ;
        return $modelProduct->update($data, $this->_id);
    }

    /*
     * metodo createProduct()
     *
     * @param 
     * @return 
     */

    function createProduct() {
        $modelProduct = new Application_Model_Product();
        $data = $this->setParamsDataBase();
        $data['product_create_date'] = date('Y-m-d H:i:s');
        $id = $modelProduct->insert($data);
        if ($id != false) {
            $this->_id = $id;
            $this->update();
            $this->_message = 'satisfactory record';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }
    
    static function listingProduct(){
        $modelProduct = new Application_Model_Product();
        return $modelProduct->listing();
    }
    function publish(){
        $this->_public = 1;
        $this->update();
        $this->_message = 'satisfactory record';
    }
    function unpublish(){
        $this->_public = '0';
        $this->update();
        $this->_message = 'satisfactory record';
    }
    
    function addImage($temp,$name,$descripcion='') {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_PRODUCT);
        $image->setPropertie('_name', $name);
        $image->setPropertie('_temp', $temp);
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id);
        $image->createImage();
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_THUMBNAILS);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_THUMBS);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_CARRUSEL);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_SMALL);
    }

    

}