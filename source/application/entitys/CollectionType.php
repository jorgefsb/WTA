<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_CollectionType extends Core_Entity {

    protected $_id;
    protected $_name;
    

    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['collection_type_id'];
        $this->_name = $data['collection_type_name'];
        
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idCollectionType
     * @return void
     */

    function identify($idCollectionType) {
        $modelCollectionType = new Application_Model_CollectionType();
        $data = $modelCollectionType->getCollectionType($idCollectionType);
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
        $data['collection_type_id'] = $this->_id;
        $data['collection_type_name'] = $this->_name;
        
        return $this->cleanArray($data);
    }

    function update() {
        $modelCollectionType = new Application_Model_CollectionType();
        $data = $this->setParamsDataBase();
        return $modelCollectionType->update($data, $this->_id);
    }

    /*
     * metodo createCollectionType()
     *
     * @param 
     * @return 
     */

    function createCollectionType() {
        $modelCollectionType = new Application_Model_CollectionType();
        $data = $this->setParamsDataBase();
        $id = $modelCollectionType->insert($data);
        if ($id != false) {
            $this->_message = 'satisfactory record';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }

    static function listingCollectionType() {
        $modelCollectionType = new Application_Model_CollectionType();
        return $modelCollectionType->listing();
    }

    

}