<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Actress extends Core_Entity {

    protected $_id;
    protected $_name;
    protected $_description;
    protected $_public;
    protected $_slug;
    protected $_order;
    protected $_img;
    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['actress_id'];
        $this->_name = $data['actress_name'];
        $this->_description = $data['actress_description'];
        $this->_public = $data['actress_public'];
        $this->_slug = $data['actress_slug'];
        $this->_order = $data['actress_order'];
        $this->_img = $data['actress_img'];
        
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idActress
     * @return void
     */

    function identify($idActress) {
        $modelActress = new Application_Model_Actress();
        $data = $modelActress->getActress($idActress);
        
        if ($data != '') {
            $this->asocParams($data);
        }
    }
    
    
    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idActress
     * @return void
     */

    function identifyByName($nameActress) {
        $modelActress = new Application_Model_Actress();
        $data = $modelActress->getActressByName($nameActress);
        
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
        $data['actress_id'] = $this->_id;
        $data['actress_name'] = $this->_name;
        $data['actress_description'] = $this->_description;
        $data['actress_public'] = $this->_public;
        $data['actress_order'] = $this->_order;
        $data['actress_img'] = $this->_img;
        return $this->cleanArray($data);
    }

    function update() {
        $filter = new Core_SeoUrl();
        $modelActress = new Application_Model_Actress();
        $this->_message = 'satisfactory record';
        $data = $this->setParamsDataBase();
        $data['actress_slug'] = $filter->urlFriendly($this->_name,'-').'-  '.$this->_id  ;
        return $modelActress->update($data, $this->_id);
    }

    /*
     * metodo createActress()
     *
     * @param 
     * @return 
     */

    function createActress() {
        $modelActress = new Application_Model_Actress();
        $this->_order = $this->getSigOrder();
        $data = $this->setParamsDataBase();
        $id = $modelActress->insert($data);
        if ($id != false) {
            $this->_id = $id;
            $this->update();
            $this->_message = 'satisfactory record';
            return true;
        } else {
            $this->_message = 'registration fails';
            false;
        }
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
    function delete(){
        $modelActress = new Application_Model_Actress();
        $data['actress_delete'] = 1;
        return $modelActress->update($data, $this->_id);
    }
    
    static function listingActress(){
        $modelActress = new Application_Model_Actress();
        return $modelActress->listing();
    }
    static function listingActressPublicNotProduct($idProduct){
        $modelActress = new Application_Model_Actress();
        return $modelActress->listingPublicNotProduct($idProduct);
    }
    
    private function getSigOrder(){
        $modelactress = new Application_Model_Actress();
        $num = (int)$modelactress->getOrderlast();
        return ($num+1);
    }
    public function upOrder(){
        if($this->_order>1){
            $modelActress = new Application_Model_Actress();
            $data = $modelActress->getActressForOrder($this->_order-1);
            $entityActress = new Application_Entity_Actress();
            $entityActress->identify($data['actress_id']);
            $dataEntity['_id'] = $data['actress_id'];
            $dataEntity['_order'] = $data['actress_order']+1;
            $entityActress->setProperties($dataEntity);
            $entityActress->update();
            $this->_order = ($this->_order-1);
            $this->update();
        }
    }
    function addImage($temp,$name,$descripcion='') {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_CELEBRITY);
        $image->setPropertie('_name', $name);
        $image->setPropertie('_temp', $temp);
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id);
        $image->createImage();
        $image->redimensionImagen(Application_Entity_Image::$CELEBRITY_REDIMENCION_THUMBNAILS);
        $image->redimensionImagen(Application_Entity_Image::$CELEBRITY_REDIMENCION_MINI);
        $image->redimensionImagen(Application_Entity_Image::$CELEBRITY_REDIMENCION_MOBILE);
        $data['actress_img'] = $name;
        $modelactress = new Application_Model_Actress();
        $modelactress->update($data, $this->_id);
        
    }
 
    function editImg($idImage,$temp,$name,$descripcion='') {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_CELEBRITY);
        $image->identify($idImage);
        
        if($temp!='' && $name!=''){
            $image->setPropertie('_name', $name);
            $image->setPropertie('_temp', $temp);
        }
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id);
        $image->update();
        if($temp!='' && $name!=''){
        $image->redimensionImagen(Application_Entity_Image::$CELEBRITY_REDIMENCION_THUMBNAILS);
        $image->redimensionImagen(Application_Entity_Image::$CELEBRITY_REDIMENCION_MINI);
        $image->redimensionImagen(Application_Entity_Image::$CELEBRITY_REDIMENCION_MOBILE);
        $data['actress_img'] = $name;
        $modelactress = new Application_Model_Actress();
        $modelactress->update($data, $this->_id);
        }
        
        
    }
    public function downOrder(){
        $modelActress = new Application_Model_Actress();
        $lastorderActress = $modelActress->getOrderlast();
        if($this->_order<$lastorderActress){
            $data = $modelActress->getActressForOrder($this->_order+1);
            $entityActress = new Application_Entity_Actress();
            $entityActress->identify($data['actress_id']);
            $dataEntity['_id'] = $data['actress_id'];
            $dataEntity['_order'] = $data['actress_order']-1;
            $entityActress->setProperties($dataEntity);
            $entityActress->update();
            $this->_order = ($this->_order+1);
            $this->update();
        }
    }

    public function getProductsSimple(){
        $modelActress = new Application_Model_Actress();
        $res = $modelActress->listingProducts($this->_id);
        
        $result = array();
        foreach( $res as $row){
            $result [] = $row['product_actress_product_id'];
        }        
        
        $_product = new Application_Model_Product();
        $res = $_product->listingSimple(array('product_id in (?)'=>$result));
        return $res;
        
        return $result;
    }
    

}