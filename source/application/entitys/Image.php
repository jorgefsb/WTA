<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Image extends Core_Entity {
    const TIPE_IMAGE_PRODUCT = 'product';
    static $PRODUCT_REDIMENCION_ORIGIN = array(
        'name' => 'product',
        'width' => 150,
        'height' => 150
    );
    static $PRODUCT_REDIMENCION_THUMBNAILS = array(
        'name' => 'thumbnails',
        'width' => 80,
        'height' => 150
    );
    static $PRODUCT_REDIMENCION_THUMBS = array(
        'name' => 'thumbs',
        'width' => 100,
        'height' => 50
    );
    static $PRODUCT_REDIMENCION_SMALL = array(
        'name' => 'small',
        'width' => 80,
        'height' => 50
    );
    static $PRODUCT_REDIMENCION_CARRUSEL = array(
        'name' => 'carrusel',
        'width' => 100,
        'height' => 100
    );

    const TIPE_IMAGE_CELEBRITY = 'celebrity';
    static $CELEBRITY_REDIMENCION_THUMBNAILS = array(
        'name' => 'thumbnails',
        'width' => 20,
        'height' => 50
    );
    static $CELEBRITY_REDIMENCION_ORIGIN = array(
        'name' => 'celebrity',
        'width' => 20,
        'height' => 50
    );
    protected $_id;
    protected $_name;
    protected $_description;
    protected $_temp;
    protected $_idTable;
    protected $_type;
    private $_redimencionOrigin;

    /**
     * __Construct         
     *
     */
    function __construct($typeImage) {
        switch ($typeImage) {
            case self::TIPE_IMAGE_PRODUCT:
                $this->_type = self::TIPE_IMAGE_PRODUCT;
                $this->_redimencionOrigin = self::$PRODUCT_REDIMENCION_ORIGIN;
                break;
            case self::TIPE_IMAGE_CELEBRITY:
                $this->_type = self::TIPE_IMAGE_CELEBRITY;
                $this->_redimencionOrigin = self::$CELEBRITY_REDIMENCION_ORIGIN;
                break;
        }
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idImage
     * @return void
     */

    function identify($idImage) {
        $modelImage = new Application_Model_Image();
        $data = $modelImage->getImage($idImage);
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
        $data['image_id'] = $this->_id;
        $data['image_name'] = $this->_name;
        $data['image_description'] = $this->_description;
        $data['image_id_table'] = $this->_idTable;
        $data['image_type'] = $this->_type;
        return $this->cleanArray($data);
    }

    function update() {
        $modelImage = new Application_Model_Image();
        return $modelImage->update($this->setParamsDataBase(), $this->_id);
    }

    /*
     * metodo createImage()
     *
     * @param 
     * @return 
     */

    function createImage() {
        if (!$this->existFile($this->_temp)) {
            $this->_message = 'file not found';
            return false;
        }
        $modelImage = new Application_Model_Image();
        $data = $this->setParamsDataBase();
        $id = $modelImage->insert($data);
        if ($id != false) {
            $this->_message = 'satisfactory record';
            $this->_id = $id;
            $this->redimensionImagen($this->_redimencionOrigin, 1);
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }

    static function listingImage($tipeImagen, $idTable) {
        $modelImage = new Application_Model_Image();
        return $modelImage->listing($tipeImagen, $idTable);
    }

    function delete($id) {
        if ($this->existFile()) {
            unlink(APPLICATION_PUBLIC . '/dinamic/' . $this->_type . '/' . $this->_name);
        }
        $modelImage = new Application_Model_Image();
        $modelImage->delete($this->_id);
    }

    function existFile($file='') {
        if ($file == '')
            return file_exists(APPLICATION_PUBLIC . '/dinamic/' . $this->_type . '/' . $this->_name);
        else
            return file_exists($file);
    }

    function renameImagen($name) {
        
    }

    function redimensionImagen($redimension, $origin=0) {

        $coreImage = new Core_Image();
        if ($origin == 0) {
            $filename = APPLICATION_PUBLIC . '/dinamic/' . $this->_type . '/' . $this->_name;
            $fileSave = APPLICATION_PUBLIC . '/dinamic/' . $this->_type . '/' . $redimension['name'] . '/' . $this->_name;
        } else {
            $fileSave = APPLICATION_PUBLIC . '/dinamic/' . $redimension['name'] . '/' . $this->_name;
            $filename = $this->_temp;
        }

        $coreImage->load($filename);
        $coreImage->resize($redimension['width'], $redimension['height'],1);
        $coreImage->save($fileSave);
    }

}