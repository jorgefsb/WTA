<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_DesignType extends Core_Entity {

    protected $_id;
    protected $_name;
    

    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['design_type_id'];
        $this->_name = $data['design_type_name'];
        
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idDesignType
     * @return void
     */

    function identify($idDesignType) {
        $modelDesignType = new Application_Model_DesignType();
        $data = $modelDesignType->getDesignType($idDesignType);
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
        $data['design_type_id'] = $this->_id;
        $data['design_type_name'] = $this->_name;
        
        return $this->cleanArray($data);
    }

    function update() {
        $modelDesignType = new Application_Model_DesignType();
        $data = $this->setParamsDataBase();
        return $modelDesignType->update($data, $this->_id);
    }

    /*
     * metodo createDesignType()
     *
     * @param 
     * @return 
     */

    function createDesignType() {
        $modelDesignType = new Application_Model_DesignType();
        $data = $this->setParamsDataBase();
        $id = $modelDesignType->insert($data);
        if ($id != false) {
            $this->_message = 'satisfactory record';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }

    static function listingDesignType() {
        $modelDesignType = new Application_Model_DesignType();
        return $modelDesignType->listing();
    }

    

}