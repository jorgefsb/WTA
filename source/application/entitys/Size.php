<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Size extends Core_Entity {

    protected $_id;
    protected $_name;
    

    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['size_id'];
        $this->_name = $data['size_name'];
        
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idSize
     * @return void
     */

    function identify($idSize) {
        $modelSize = new Application_Model_Size();
        $data = $modelSize->getSize($idSize);
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
        $data['size_id'] = $this->_id;
        $data['size_name'] = $this->_name;
        
        return $this->cleanArray($data);
    }

    function update() {
        $modelSize = new Application_Model_Size();
        $data = $this->setParamsDataBase();
        return $modelSize->update($data, $this->_id);
    }

    /*
     * metodo createSize()
     *
     * @param 
     * @return 
     */

    function createSize() {
        $modelSize = new Application_Model_Size();
        $data = $this->setParamsDataBase();
        $id = $modelSize->insert($data);
        if ($id != false) {
            $this->_message = 'satisfactory record';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }

    static function listingSize() {
        $modelSize = new Application_Model_Size();
        return $modelSize->listing();
    }

    

}