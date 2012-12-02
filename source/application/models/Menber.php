<?php

class Application_Model_Menber extends Core_Model {

    protected $_tableMenber;

    public function __construct() {
        $this->_tableMenber = new Application_Model_DbTable_Menber();
    }
    /**
     * metodo getMenber(), devuelve todos los datos de un Menber
     * @param $idMenber    id de la Menber  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getMenber($idMenber) {
        $smt = $this->_tableMenber->select()
                ->where('menber_id =?', $idMenber)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo getMenber(), devuelve todos los datos de un Menber
     * @param $idMenber    id de la Menber  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getMenberForIdConfirm($idConfirm) {
        $smt = $this->_tableMenber->select()
                ->where('menber_id_confirm =?', $idConfirm)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la Menber 
     * @param array             $data   array con los datos de la Menber array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableMenber->insert($data)) {
            return $this->_tableMenber->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la Menber 
     * @param array     $data           array con los datos de la Menber array('column'=>'valor')
     * @param int       $idMenber  id de la Menber
     * @return bolean   
     */

    public function update($data, $idMenber) {
        if ($idMenber != '') {
            $where = $this->_tableMenber->getAdapter()
                    ->quoteInto('menber_id =?', $idMenber);
            return $this->_tableMenber->update($data, $where);
        } else {
            return false;
        }
    }
    
    function getpassword($usuario){
        $sql = $this->_tableMenber->select()
                ->from($this->_tableMenber->getName(),
                        array('menber_password'))
                ->where('menber_mail = ?',$usuario);
       return $this->_tableMenber->getAdapter()->fetchOne($sql);
    }
    function getpasswordTemp($menberId){
        $sql = $this->_tableMenber->select()
                ->from($this->_tableMenber->getName(),
                        array('menbar_password_reset'))
                ->where('menber_id = ?',$menberId);
       return $this->_tableMenber->getAdapter()->fetchOne($sql);
    }
    

}

