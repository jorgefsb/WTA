<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Image extends Core_Entity {
    const TIPE_IMAGE_BACKGROUND = 'background';
    static $BACKGROUND_REDIMENCION_THUMBNAILS = array(
        'name' => 'thumbnails',
        'width' => 368,
        'height' => 419
    );
    static $BACKGROUND_REDIMENCION_MOBILE = array(
        'name' => 'mobile',
        'width' => 311,
        'height' => 354
    );
    static $BACKGROUND_REDIMENCION_MINI = array(
        'name' => 'mini',
        'width' => 50,
        'height' => 50
    );
    static $BACKGROUND_REDIMENCION_ORIGIN = array(
        'name' => 'background',
        'width' => 0,
        'height' => 0
    );
        
    const TIPE_IMAGE_PRODUCT = 'product';
    static $PRODUCT_REDIMENCION_ORIGIN = array(
        'name' => 'product',
        'width' => 0,
        'height' => 0
    );
    static $PRODUCT_REDIMENCION_MOBILE = array(
        'name' => 'mobile',
        'width' => 311,
        'height' => 354
    );
    static $PRODUCT_REDIMENCION_THUMBNAILS = array(
        'name' => 'thumbnails',
        'width' => 192,
        'height' => 203
    );
    static $PRODUCT_REDIMENCION_THUMBS = array(
        'name' => 'thumbs',
        'width' => 102,
        'height' => 116
    );
    static $PRODUCT_REDIMENCION_MINI = array(
        'name' => 'mini',
        'width' => 50,
        'height' => 50
    );


    const TIPE_IMAGE_CELEBRITY = 'celebrity';
    static $CELEBRITY_REDIMENCION_THUMBNAILS = array(
        'name' => 'thumbnails',
        'width' => 368,
        'height' => 419
    );
    static $CELEBRITY_REDIMENCION_MOBILE = array(
        'name' => 'mobile',
        'width' => 311,
        'height' => 354
    );
    static $CELEBRITY_REDIMENCION_MINI = array(
        'name' => 'mini',
        'width' => 50,
        'height' => 50
    );
    static $CELEBRITY_REDIMENCION_ORIGIN = array(
        'name' => 'celebrity',
        'width' => 0,
        'height' => 0
    );

    const TIPE_IMAGE_PRODUCTCELEBRITY = 'productCelebrity';
    static $PRODUCTCELEBRITY_REDIMENCION_THUMBNAILS = array(
        'name' => 'thumbnails',
        'width' => 645,
        'height' => 594
    );
    static $PRODUCTCELEBRITY_REDIMENCION_MOBILE = array(
        'name' => 'mobile',
        'width' => 311,
        'height' => 354
    );
    static $PRODUCTCELEBRITY_REDIMENCION_MINI = array(
        'name' => 'mini',
        'width' => 50,
        'height' => 50
    );
    static $PRODUCTCELEBRITY_REDIMENCION_ORIGIN = array(
        'name' => 'productCelebrity',
        'width' => 0,
        'height' => 0
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
            case self::TIPE_IMAGE_BACKGROUND:
                $this->_type = self::TIPE_IMAGE_BACKGROUND;
                $this->_redimencionOrigin = self::$BACKGROUND_REDIMENCION_ORIGIN;
                break;
            case self::TIPE_IMAGE_PRODUCTCELEBRITY:
                $this->_type = self::TIPE_IMAGE_PRODUCTCELEBRITY;
                $this->_redimencionOrigin = self::$PRODUCTCELEBRITY_REDIMENCION_ORIGIN;
                break;
        }
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idImage
     * @return void
     * si la tabla tiene un solo elemento se puede obtener 
     * los datos con el id de la tabla y el tipo; en caso contrario
     * se identifica con el id de la tabla Imagen
     */

    function identify($idImage='') {
        $data = '';
        $modelImage = new Application_Model_Image();
        if ($idImage != '') {
            $data = $modelImage->getImage($idImage);
        } else {
            if ($this->_idTable != '') {
                $data = $modelImage->getImageTable($this->_idTable, $this->_type);
            }
        }
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

    function asocParams($data) {
        $this->_id = $data['image_id'];
        $this->_name = $data['image_name'];
        $this->_description = $data['image_description'];
        $this->_idTable = $data['image_id_table'];
        $this->_type = $data['image_type'];
    }

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
        $data = $this->setParamsDataBase();
        if ($this->_temp != '' && $this->existFile($this->_temp)) {
            $this->redimensionImagen($this->_redimencionOrigin, 1);
            $img = new Application_Entity_Image($this->_type);
            $img->identify($this->_id);
            $img->deleteImg();
        }
        return $modelImage->update($data, $this->_id);
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

    function delete($id='') {
        if ($this->existFile()) {
            unlink(APPLICATION_PUBLIC . '/dinamic/' . $this->_type . '/' . $this->_name);
        }
        $modelImage = new Application_Model_Image();
        $modelImage->delete($this->_id);
    }

    function deleteImgRed($rd) {
        unlink(APPLICATION_PUBLIC . '/dinamic/' . $this->_type . '/' . $rd['name'] . '/' . $this->_name);
    }

    function deleteImg() {
        if ($this->existFile()) {
            unlink(APPLICATION_PUBLIC . '/dinamic/' . $this->_type . '/' . $this->_name);
            switch ($this->_type) {
                case self::TIPE_IMAGE_PRODUCT:
                    $this->deleteImgRed(self::$PRODUCT_REDIMENCION_THUMBNAILS);
                    $this->deleteImgRed(self::$PRODUCT_REDIMENCION_MINI);
                    $this->deleteImgRed(self::$PRODUCT_REDIMENCION_THUMBS);
                    $this->deleteImgRed(self::$PRODUCT_REDIMENCION_MOBILE);
                    break;
                case self::TIPE_IMAGE_CELEBRITY:
                    $this->deleteImgRed(self::$CELEBRITY_REDIMENCION_THUMBNAILS);
                    $this->deleteImgRed(self::$CELEBRITY_REDIMENCION_MINI);
                    $this->deleteImgRed(self::$CELEBRITY_REDIMENCION_MOBILE);
                    break;
                case self::TIPE_IMAGE_PRODUCTCELEBRITY:
                    $this->deleteImgRed(self::$PRODUCTCELEBRITY_REDIMENCION_MINI);
                    $this->deleteImgRed(self::$PRODUCTCELEBRITY_REDIMENCION_THUMBNAILS);
                    $this->deleteImgRed(self::$PRODUCTCELEBRITY_REDIMENCION_MOBILE);
                    break;
            }
        }
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
        if ($redimension['width'] == 0 && $redimension['height'] == 0) {
            copy($filename, $fileSave);
        } else {
            $coreImage->load($filename);
            $coreImage->resize($redimension['width'], $redimension['height']);
            $coreImage->save($fileSave);
        }
    }

}