<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Designer extends Core_Entity {

    protected $_id;
    protected $_name;
    protected $_description;
    protected $_image;
    protected $_delete;
    protected $_public;
    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    static function listing() {
        $modelDesigner = new Application_Model_Designer();
        return $modelDesigner->listing();
    }

    private function asocParams($data) {
        $this->_id = $data['designer_id'];
        $this->_name = $data['designer_name'];
        $this->_description = $data['designer_description'];
        $this->_image = $data['designer_image'];
        $this->_delete = $data['designer_delete'];
        $this->_public = $data['designer_public'];
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idDesigner
     * @return void
     */

    function identify($idDesigner) {
        $modelDesigner = new Application_Model_Designer();
        $data = $modelDesigner->getDesigner($idDesigner);
        
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
        $data['designer_id'] = $this->_id;
        $data['designer_name'] = $this->_name;
        $data['designer_description'] = $this->_description;
        $data['designer_image'] = $this->_image;
        $data['designer_delete'] = $this->_delete;
        $data['designer_public'] = $this->_public;
        return $this->cleanArray($data);
    }

    function update() {
        $data = $this->setParamsDataBase();
        $modelDesigner = new Application_Model_Designer();
        $this->_message = 'Registration ok';
        return $modelDesigner->update($data, $this->_id);
    }

    /*
     * metodo createDesigner()
     *
     * @param 
     * @return 
     */

    function createDesigner() {
        $modelDesigner = new Application_Model_Designer();
        
        $data = $this->setParamsDataBase();
        $id = $modelDesigner->insert($data);
        
        if ($id != false) {
            $this->_id = $id;
            $this->update();
            $this->_message = 'Registration ok';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }

    function delete() {
        $modelDesigner = new Application_Model_Designer();
        $data['designer_delete'] = 1;
        $this->_message = 'satisfactory record';
        return $modelDesigner->update($data, $this->_id);
    }
    
    function addImage($temp, $name, $descripcion='') {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_DESIGNER);
        $image->setPropertie('_name', $name);
        $image->setPropertie('_temp', $temp);
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id);
        $image->createImage();
        $image->redimensionImagen(Application_Entity_Image::$DESIGNER_REDIMENCION_THUMBNAILS);
        $image->redimensionImagen(Application_Entity_Image::$DESIGNER_REDIMENCION_MINI);
        $image->redimensionImagen(Application_Entity_Image::$DESIGNER_REDIMENCION_MOBILE);
        $data['designer_image'] = $name;
        $modelDesigner = new Application_Model_Designer();
        $modelDesigner->update($data, $this->_id);
    }

    function editImg($idImage, $temp, $name, $descripcion='') {
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_DESIGNER);
        $image->identify($idImage);

        if ($temp != '' && $name != '') {
            $image->setPropertie('_name', $name);
            $image->setPropertie('_temp', $temp);
        }
        $image->setPropertie('_description', $descripcion);
        $image->setPropertie('_idTable', $this->_id);
        $image->update();
        if ($temp != '' && $name != '') {
            $image->redimensionImagen(Application_Entity_Image::$DESIGNER_REDIMENCION_THUMBNAILS);
            $image->redimensionImagen(Application_Entity_Image::$DESIGNER_REDIMENCION_MINI);
            $image->redimensionImagen(Application_Entity_Image::$DESIGNER_REDIMENCION_MOBILE);
            $data['designer_image'] = $name;
            $modelDesigner = new Application_Model_Designer();
            $modelDesigner->update($data, $this->_id);
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
}