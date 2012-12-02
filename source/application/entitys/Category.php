<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Category extends Core_Entity {

    protected $_id;
    protected $_name;
    protected $_description;
    protected $_public;
    protected $_slug;
    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['category_id'];
        $this->_name = $data['category_name'];
        $this->_description = $data['category_description'];
        $this->_public = $data['category_public'];
        $this->_slug = $data['category_slug'];
        
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idCategory
     * @return void
     */

    function identify($idCategory) {
        $modelCategory = new Application_Model_Category();
        $data = $modelCategory->getCategory($idCategory);
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
        $data['category_id'] = $this->_id;
        $data['category_name'] = $this->_name;
        $data['category_description'] = $this->_description;
        $data['category_public'] = $this->_public;
        return $this->cleanArray($data);
    }

    function update() {
        $filter = new Core_SeoUrl();
        $modelCategory = new Application_Model_Category();
        $this->_message = 'satisfactory record';
        $data = $this->setParamsDataBase();
        $data['category_slug'] = $filter->urlFriendly($this->_name,'-').'-  '.$this->_id  ;
        return $modelCategory->update($data, $this->_id);
    }

    /*
     * metodo createCategory()
     *
     * @param 
     * @return 
     */

    function createCategory() {
        $modelCategory = new Application_Model_Category();
        $data = $this->setParamsDataBase();
        $id = $modelCategory->insert($data);
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
    
    static function listingCategory(){
        $modelCategory = new Application_Model_Category();
        return $modelCategory->listing();
        
    }

    

}