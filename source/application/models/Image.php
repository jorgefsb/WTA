<?php

class Application_Model_Image extends Core_Model {

    protected $_tableImage;

    public function __construct() {
        $this->_tableImage = new Application_Model_DbTable_Image();
        
    }
    /**
     * metodo getImage(), devuelve todos los datos de un Image
     * @param $idImage    id de la Image  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getImage($idImage) {
        $smt = $this->_tableImage->select()
                ->where('image_id =?', $idImage)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo getImage(), devuelve todos los datos de un Image
     * @param $idImage    id de la Image  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getImageTable($idTable,$tipo) {
        
        $smt = $this->_tableImage->select()
                ->where('image_id_table =?', $idTable)
                ->where('image_type =?', $tipo)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la Image 
     * @param array             $data   array con los datos de la Image array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableImage->insert($data)) {
            return $this->_tableImage->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Image 
     * @param array     $data           array con los datos de la Image array('column'=>'valor')
     * @param int       $idImage  id de la Image
     * @return bolean   
     */

    public function update($data, $idImage) {
        if ($idImage != '') {
            $where = $this->_tableImage->getAdapter()
                    ->quoteInto('image_id =?', $idImage);
            return $this->_tableImage->update($data, $where);
        } else {
            return false;
        }
    }
    public function delete($idImage) {
        if ($idImage != '') {
            $where = $this->_tableImage->getAdapter()
                    ->quoteInto('image_id =?', $idImage);
            return $this->_tableImage->delete($where);
        } else {
            return false;
        }
    }
    
    public function listing($tipeImagen,$idTable) {
        
         $smt = $this->_tableImage
                 ->select()
                 ->where('image_id_table =?',$idTable)
                 ->where('image_type =?',$tipeImagen)
                 ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    

}

