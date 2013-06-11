<?php

class Application_Model_Content extends Core_Model {

    protected $_tableContent;

    public function __construct() {
        $this->_tableContent = new Application_Model_DbTable_Content();
    }
    
    /**
     * metodo getContent(), devuelve todos los datos de un Content
     * @param $idContent    id del Content  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getContent($idContent) {
        $smt = $this->_tableContent->select()
                ->where('content_id =?', $idContent)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    /**
     * metodo getContent(), devuelve todos los datos de un Content
     * @param $idContent    id del Content  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getContentByCode($codeContent) {
        $smt = $this->_tableContent->select()
                ->where('content_code =?', $codeContent)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    /**
     * metodo insert(), registra los datos del Content 
     * @param array             $data   array con los datos del Content array('column'=>'valor')
     * @return boolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableContent->insert($data)) {
            return $this->_tableContent->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Content 
     * @param array     $data           array con los datos del Content array('column'=>'valor')
     * @param int       $idContent  id del Content
     * @return bolean   
     */

    public function update($data, $idContent) {
        if ($idContent != '') {
            $where = $this->_tableContent->getAdapter()
                    ->quoteInto('content_id =?', $idContent);
            return $this->_tableContent->update($data, $where);
        } else {
            return false;
        }
    }
    
    public function listing(){
        $smt = $this->_tableContent->select()
                ->order('content_id asc')
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

}

