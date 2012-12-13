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
    protected $_descriptionDesigner;
    protected $_designer;
    protected $_price;
    protected $_priceMenber;
    protected $_inStock;
    protected $_limitedQuantity;
    protected $_cantLimitedQuantity;
    protected $_public;
    protected $_slug;
    protected $_order;
    protected $_designType;
    protected $_collectionType;
    protected $_cantBuy;
    protected $_code;

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
        $this->_descriptionDesigner = $data['product_description_designer'];
        $this->_designer = $data['product_designer'];
        $this->_price = $data['product_price'];
        $this->_priceMenber = $data['product_price_menber'];
        $this->_inStock = $data['product_in_stock'];
        $this->_limitedQuantity = $data['product_limited_quantity'];
        $this->_cantLimitedQuantity = $data['product_cant_limited_quantity'];
        $this->_public = $data['product_public'];
        $this->_slug = $data['product_slug'];
        $this->_order = $data['product_order'];
        $this->_designType = $data['product_design_type'];
        $this->_collectionType = $data['product_collection_type'];
        $this->_cantBuy = $data['product_cant_buy'];
        $this->_code = $data['product_code'];
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
        //print_r($data);
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
        $data['product_price'] = $this->_price;
        $data['product_price_menber'] = $this->_priceMenber;
        $data['product_in_stock'] = $this->_inStock;
        $data['product_limited_quantity'] = $this->_limitedQuantity;
        $data['product_cant_limited_quantity'] = $this->_cantLimitedQuantity;
        $data['product_public'] = $this->_public;
        $data['product_order'] = $this->_order;
        $data['product_description_designer'] = $this->_descriptionDesigner;
        $data['product_designer'] = $this->_designer;
        $data['product_design_type'] = $this->_designType;
        $data['product_collection_type'] = $this->_collectionType;
        $data['product_code'] = $this->_code;
        if ($data['product_limited_quantity'] != 1) {
            $data['product_cant_limited_quantity'] = 'NULL';
            $data['product_cant_buy'] = 'NULL';
        }
        return $this->cleanArray($data);
    }

    function update() {
        $filter = new Core_SeoUrl();
        $modelProduct = new Application_Model_Product();
        $data = $this->setParamsDataBase();
        $data['product_slug'] = $filter->urlFriendly($this->_name, '-') . '-' . $this->_id;
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
        $this->_order = $this->getSigOrder();
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

    static function listingProduct() {
        $modelProduct = new Application_Model_Product();
        return $modelProduct->listing();
    }

    function publish() {
        $this->_public = 1;
        $this->update();
        $this->_message = 'satisfactory record';
    }

    function unpublish() {
        $this->_public = '0';
        $this->update();
        $this->_message = 'satisfactory record';
    }

    function addImage($temp, $name, $descripcion='') {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_PRODUCT);
        $image->setPropertie('_name', $name);
        $image->setPropertie('_temp', $temp);
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id);
        $image->createImage();
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_THUMBNAILS);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_THUMBS);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_MINI);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_MOBILE);
        $this->_message = 'satisfactory record';
    }
    function editImage($idImagen, $temp='', $name='', $descripcion='') {
        
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_PRODUCT);
        $image->identify($idImagen);
        $image->setPropertie('_name', $name);
        if($temp!=''){
        $image->setPropertie('_temp', $temp);}
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id);
        $image->update();
        if($temp!=''){
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_THUMBNAILS);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_THUMBS);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_MINI);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCT_REDIMENCION_MOBILE);}
        $this->_message = 'satisfactory record';
    }

    function addImageActress($temp, $name, $idcelebrety, $descripcion='') {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_PRODUCTCELEBRITY);
        $image->setPropertie('_name', $name);
        $image->setPropertie('_temp', $temp);
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id . $idcelebrety);
        $image->createImage();
        $image->redimensionImagen(Application_Entity_Image::$PRODUCTCELEBRITY_REDIMENCION_THUMBNAILS);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCTCELEBRITY_REDIMENCION_MINI);
        $image->redimensionImagen(Application_Entity_Image::$PRODUCTCELEBRITY_REDIMENCION_MOBILE);
    }

    private function getSigOrder() {
        $modelProduct = new Application_Model_Product();
        $num = (int) $modelProduct->getOrderlast();
        return ($num + 1);
    }

    public function upOrder() {
        if ($this->_order > 1) {
            $modelProduct = new Application_Model_Product();
            $data = $modelProduct->getProductForOrder($this->_order - 1);
            $entityProduct = new Application_Entity_Product();
            $entityProduct->identify($data['product_id']);
            $dataEntity['_id'] = $data['product_id'];
            $dataEntity['_order'] = $data['product_order'] + 1;
            $entityProduct->setProperties($dataEntity);
            $entityProduct->update();
            $this->_order = ($this->_order - 1);
            $this->update();
        }
    }

    public function downOrder() {
        $modelProduct = new Application_Model_Product();
        $lastorderProduct = $modelProduct->getOrderlast();
        if ($this->_order < $lastorderProduct) {
            $data = $modelProduct->getProductForOrder($this->_order + 1);
            $entityProduct = new Application_Entity_Product();
            $entityProduct->identify($data['product_id']);
            $dataEntity['_id'] = $data['product_id'];
            $dataEntity['_order'] = $data['product_order'] - 1;
            $entityProduct->setProperties($dataEntity);
            $entityProduct->update();
            $this->_order = ($this->_order + 1);
            $this->update();
        }
    }

    public function del(){
        $model = new Application_Model_Product();
        $dataEntity['product_order'] = NULL;
        $dataEntity['product_delete'] = 1;
        $model->update($dataEntity, $this->_id);
        $orden = $this->_order;
        $products = $model->selectProductLowerPriority($orden);
        foreach($products as $index){
            $data = array('product_order'=>((int)$index['product_order']-1));
            $model->update($data, $index['product_id']);
        }
        
    }
    public function getProductActress($idActress) {
        $modelProductActress = new Application_Model_ProductActress();
        return $modelProductActress->getProductActress($this->_id, $idActress);
    }

    public function addActress($idActress, $comision, $active, $imagenTem='', $nameImage ='') {
        $modelProductActress = new Application_Model_ProductActress();
        $modelProduct = new Application_Model_Product();
        $product = $this->getProductActress($idActress);
        if ($product == FALSE) {
            $data['product_actress_product_id'] = $this->_id;
            $data['product_actress_actress_id'] = $idActress;
            $data['product_actress_active'] = $active;
            $data['product_actress_commission'] = $nameImage;
            $data['product_actress_img'] = $nameImage;
            if ($imagenTem != '' && $nameImage != '') {
                $this->addImageActress($imagenTem, $nameImage, $idcelebrety, $descripcion = '');
            }
            return $modelProductActress->insert($data);
        } else {
            $data['product_actress_product_id'] = $this->_id;
            $data['product_actress_actress_id'] = $idActress;
            $data['product_actress_active'] = $active;
            $data['product_actress_commission'] = $comision;
            $data['product_actress_img'] = $nameImage;
            if ($imagenTem != '' && $nameImage != '') {
                $this->addImageActress($imagenTem, $nameImage, $idcelebrety, $descripcion = '');
            }
            return $modelProductActress->update($data, $this->_id, $idActress);
        }
    }

    public function listingActrees() {
        $modelProduct = new Application_Model_Product();
        return $modelProduct->listingActrees($this->_id);
    }

    function publishCelebrity($idActress) {
        $modelProductActress = new Application_Model_ProductActress();
        $data['product_actress_active'] = 1;
        return $modelProductActress->update($data, $this->_id, $idActress);
    }

    function unpublishCelebrity($idActress) {
        $modelProductActress = new Application_Model_ProductActress();
        $data['product_actress_active'] = '0';
        return $modelProductActress->update($data, $this->_id, $idActress);
    }

    function addSize($size) {
        $modelProduct = new Application_Model_Product();
        if ($modelProduct->existSize($this->_id, $size)) {
            return false;
        }

        $data['product_size_product_id'] = $this->_id;
        $data['product_size_size_id'] = $size;
        return $modelProduct->insertSize($data);
    }

    function deleteSize($size) {
        $modelProduct = new Application_Model_Product();
        return $modelProduct->deleteSize($this->_id, $size);
    }

    function getSize() {
        $modelProduct = new Application_Model_Product();
        return $modelProduct->getSize($this->_id);
    }

    function listingImg() {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_PRODUCT);
        return $image->listingImage(
                        Application_Entity_Image::TIPE_IMAGE_PRODUCT, $this->_id);
    }
        
    function designersWithTypes(){
        $_product = new Application_Model_Product();
        $res = $_product->designersWithTypes();
        $result = array();
        foreach($res as $row){
            $result[$row['designer_name']][] = $row['design_type_name'];
        }
        return $result;
    }    
    
    function collectionsTypesAvailables(){
        $_product = new Application_Model_Product();
        $res = $_product->collectionsTypesAvailables();
        return $res;
    }
    
    
    function boutiquesAvailables(){
        $_product = new Application_Model_Product();
        $res = $_product->boutiquesAvailables();
        return $res;
    }
    
    
    function listingLimitedQuantity(){
        $_product = new Application_Model_Product();
        $res = $_product->listingLimitedQuantity();
        return $res;
    }
    
    function getProductsByDesignType($category){
        $_product = new Application_Model_Product();
        if( !empty($category)){
            $res = $_product->listingSimple(array('dt.design_type_name = ?'=>$category));
        }else{
            $res = $_product->listingSimple();
        }
        return $res;
        
    }
    
    
    function getProductsByCollectionType($category){
        $_product = new Application_Model_Product();
        if( !empty($category)){
            $res = $_product->listingSimple(array('ct.collection_type_name = ?'=>$category));
        }else{
            $res = $_product->listingSimple();
        }
        return $res;
        
    }
    
    
    
    function getProductsLimitedQuantity(){
        $_product = new Application_Model_Product();
        $res = $_product->listingSimple(array('product_limited_quantity = ?'=>1, '(product_cant_limited_quantity-product_cant_buy) > ?'=> 0));
        return $res;
        
    }
    
    function deleteActress($idActress){
        $modelProductActress = new Application_Model_ProductActress();
        $this->_message = 'satisfactory record';
        return $modelProductActress->deleteProductActress($this->_id, $idActress);
    }

    
}