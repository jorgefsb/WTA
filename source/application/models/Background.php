<?php

class Application_Model_Background extends Core_Model {

    protected $_tableBackground;

    public function __construct() {
        $this->_tableBackground = new Application_Model_DbTable_Background();
    }
    
    /**
     * metodo getBackground(), devuelve todos los datos de un Background
     * @param $idBackground    id de la Background  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getBackground($idBackground) {
        $smt = $this->_tableBackground->select()
                ->where('background_id =?', $idBackground)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
    /**
     * metodo insert(), registra los datos del Background 
     * @param array             $data   array con los datos de la Background array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableBackground->insert($data)) {
            return $this->_tableBackground->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Background 
     * @param array     $data           array con los datos de la Background array('column'=>'valor')
     * @param int       $idBackground  id de la Background
     * @return bolean   
     */

    public function update($data, $idBackground) {
        if ($idBackground != '') {
            $where = $this->_tableBackground->getAdapter()
                    ->quoteInto('background_id =?', $idBackground);
            return $this->_tableBackground->update($data, $where);
        } else {
            return false;
        }
    }
    
    public function listing(){
        $smt = $this->_tableBackground->select()
                ->order('background_order asc')
                ->where('background_delete=?', '0')
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    public function listingActives(){
        $smt = $this->_tableBackground->select()
                ->order('background_order asc')
                ->where('background_delete=?', '0')
                ->where('background_public=?', '1')
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    public function getOrderlast(){
        $sql = $this->_tableBackground->select()
                ->from($this->_tableBackground->getName(),'background_order')
                ->where('background_order >= 0')
                ->order('background_order desc')
                ->limit(1);
        return $this->_tableBackground->getAdapter()->fetchOne($sql);
    }
    
    public function getBackgroundForOrder($orden){
        $smt = $this->_tableBackground
                ->select()
                ->where('background_order=?', $orden)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }

}

