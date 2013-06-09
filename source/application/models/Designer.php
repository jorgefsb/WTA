<?php

class Application_Model_Designer extends Core_Model {

    protected $_tableDesigner;

    public function __construct() {
        $this->_tableDesigner = new Application_Model_DbTable_Designer();
    }
    /**
     * metodo getDesigner(), devuelve todos los datos de un Designer
     * @param $idDesigner    id de la Designer  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getDesigner($idDesigner) {
        $smt = $this->_tableDesigner->select()
                ->where('designer_id =?', $idDesigner)
                ->query();
        $result = $smt->fetch();
        
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insert(), registra los datos de la Designer 
     * @param array             $data   array con los datos de la Designer array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function listing() {
        $smt = $this->_tableDesigner
                ->select()
                ->where('designer_delete = 0')
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la Designer 
     * @param array             $data   array con los datos de la Designer array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableDesigner->insert($data)) {
            return $this->_tableDesigner->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Designer 
     * @param array     $data           array con los datos de la Designer array('column'=>'valor')
     * @param int       $idDesigner  id de la Designer
     * @return bolean   
     */

    public function update($data, $idDesigner) {
        if ($idDesigner != '') {
            $where = $this->_tableDesigner->getAdapter()
                    ->quoteInto('designer_id =?', $idDesigner);
            return $this->_tableDesigner->update($data, $where);
        } else {
            return false;
        }
    }

}

