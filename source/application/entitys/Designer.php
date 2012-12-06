<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Designer extends Core_Entity {

    protected $_id;
    protected $_name;
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

    function createDesigner($password) {
        $modelDesigner = new Application_Model_Designer();
        $id = $modelDesigner->insert($data);
        if ($id != false) {
            $this->_id = $id;
            $this->_message = 'Registration ok';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }

    

}