<?php

/**
 * entidad de los background
 *
 * @author ramiro vera
 */
class Application_Entity_Background extends Core_Entity {

    protected $_id;
    protected $_name;
    protected $_public;
    protected $_order;
    protected $_delete;
    protected $_image;

    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['background_id'];
        $this->_name = $data['background_name'];
        $this->_public = $data['background_public'];
        $this->_order = $data['background_order'];
        $this->_slug = $data['background_delete'];
        $this->_image = $data['background_image'];
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idBackground
     * @return void
     */

    function identify($idBackground) {
        $modelBackground = new Application_Model_Background();
        $data = $modelBackground->getBackground($idBackground);

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
        $data['background_id'] = $this->_id;
        $data['background_name'] = $this->_name;
        $data['background_public'] = $this->_public;
        $data['background_delete'] = $this->_delete;
        $data['background_order'] = $this->_order;
        $data['background_image'] = $this->_image;
        return $this->cleanArray($data);
    }

    function update() {
        $filter = new Core_SeoUrl();
        $modelBackground = new Application_Model_Background();
        $this->_message = 'satisfactory record';
        $data = $this->setParamsDataBase();
        return $modelBackground->update($data, $this->_id);
    }

    /*
     * metodo createBackground()
     *
     * @param 
     * @return 
     */

    function createBackground() {
        $modelBackground = new Application_Model_Background();
        $this->_order = $this->getSigOrder();
        $data = $this->setParamsDataBase();
        $id = $modelBackground->insert($data);
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

    function delete() {
        $modelBackground = new Application_Model_Background();
        $data['background_delete'] = 1;
        $data['background_public'] = 0;
        $this->_message = 'satisfactory record';
        return $modelBackground->update($data, $this->_id);
    }

    static function listingBackground() {
        $modelBackground = new Application_Model_Background();
        return $modelBackground->listing();
    }
    
    static function listingActiveBackgrounds() {
        $modelBackground = new Application_Model_Background();
        return $modelBackground->listingActives();
    }
    
    private function getSigOrder() {
        $modelBackground = new Application_Model_Background();
        $num = (int) $modelBackground->getOrderlast();
        return ($num + 1);
    }

    public function upOrder() {
        if ($this->_order > 1) {
            $modelBackground = new Application_Model_Background();
            $data = $modelBackground->getBackgroundForOrder($this->_order - 1);
            $entityBackground = new Application_Entity_Background();
            $entityBackground->identify($data['background_id']);
            $dataEntity['_id'] = $data['background_id'];
            $dataEntity['_order'] = $data['background_order'] + 1;
            $entityBackground->setProperties($dataEntity);
            $entityBackground->update();
            $this->_order = ($this->_order - 1);
            $this->update();
        }
    }

    function addImage($temp, $name, $descripcion='') {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_BACKGROUND);
        $image->setPropertie('_name', $name);
        $image->setPropertie('_temp', $temp);
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id);
        $image->createImage();
        $image->redimensionImagen(Application_Entity_Image::$BACKGROUND_REDIMENCION_THUMBNAILS);
        $image->redimensionImagen(Application_Entity_Image::$BACKGROUND_REDIMENCION_MINI);
        $image->redimensionImagen(Application_Entity_Image::$BACKGROUND_REDIMENCION_MOBILE);
        $data['background_image'] = $name;
        $modelbackground = new Application_Model_Background();
        $modelbackground->update($data, $this->_id);
    }

    function editImg($idImage, $temp, $name, $descripcion='') {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_CELEBRITY);
        $image->identify($idImage);

        if ($temp != '' && $name != '') {
            $image->setPropertie('_name', $name);
            $image->setPropertie('_temp', $temp);
        }
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id);
        $image->update();
        if ($temp != '' && $name != '') {
            $image->redimensionImagen(Application_Entity_Image::$BACKGROUND_REDIMENCION_THUMBNAILS);
            $image->redimensionImagen(Application_Entity_Image::$BACKGROUND_REDIMENCION_MINI);
            $image->redimensionImagen(Application_Entity_Image::$BACKGROUND_REDIMENCION_MOBILE);
            $data['background_image'] = $name;
            $modelbackground = new Application_Model_Background();
            $modelbackground->update($data, $this->_id);
        }
    }

    public function downOrder() {
        $modelBackground = new Application_Model_Background();
        $lastorderBackground = $modelBackground->getOrderlast();
        if ($this->_order < $lastorderBackground) {
            $data = $modelBackground->getBackgroundForOrder($this->_order + 1);
            $entityBackground = new Application_Entity_Background();
            $entityBackground->identify($data['background_id']);
            $dataEntity['_id'] = $data['background_id'];
            $dataEntity['_order'] = $data['background_order'] - 1;
            $entityBackground->setProperties($dataEntity);
            $entityBackground->update();
            $this->_order = ($this->_order + 1);
            $this->update();
        }
    }

}