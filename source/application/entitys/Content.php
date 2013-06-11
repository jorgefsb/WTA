<?php

/**
 * entidad de los background
 *
 * @author ramiro vera
 */
class Application_Entity_Content extends Core_Entity {

    protected $_id;
    protected $_code;
    protected $_body;

    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['content_id'];
        $this->_code = $data['content_code'];
        $this->_body = $data['content_body'];
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idBackground
     * @return void
     */

    function identify($idContent) {
        $modelContent = new Application_Model_Content();
        $data = $modelContent->getContent($idContent);

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
        $data['content_id'] = $this->_id;
        $data['content_code'] = $this->_code;
        $data['content_body'] = $this->_body;
        return $this->cleanArray($data);
    }

    function update() {
        $filter = new Core_SeoUrl();
        $modelContent = new Application_Model_Content();
        $this->_message = 'satisfactory record';
        $data = $this->setParamsDataBase();
        return $modelContent->update($data, $this->_id);
    }

    /*
     * metodo createContent()
     *
     * @param 
     * @return 
     */

    function createContent() {
        $modelContent = new Application_Model_Content();
        $data = $this->setParamsDataBase();
        $id = $modelContent->insert($data);
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

    function delete() {
        $modelContent = new Application_Model_Content();
        $this->_message = 'deleted record';
        return $modelContent->delete($this->_id);
    }

    static function listing() {
        $modelContent = new Application_Model_Content();
        return $modelContent->listing();
    }
    
    static function getByCode($code) {
        $modelContent = new Application_Model_Content();
        
        $content = $modelContent->getContentByCode($code);
        $content["content_body"] = str_replace("<div", "<p", $content["content_body"]);
        $content["content_body"] = str_replace("</div", "</p", $content["content_body"]);
        $content["content_body"] = "<p>".$content["content_body"]."</p>";
        
        return $content;
    }

}