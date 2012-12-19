<?php

class Application_Model_Country extends Core_Model {

    protected $_tablecountry;
    

    public function __construct() {
        $this->_tablecountry = new Application_Model_DbTable_country();
        
    }

    /**
     * metodo getcountry(), devuelve todos los datos de un country
     * @param $idcountry    id de la country  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getCountry($idcountry) {
        $smt = $this->_tablecountry->select()
                ->where('country_id =?', $idcountry)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo getcountry(), devuelve todos los datos de un country
     * @param $idcountry    id de la country  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function listingCountry() {
        $smt = $this->_tablecountry->select()
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insert(), registra los datos de la country 
     * @param array             $data   array con los datos de la country array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tablecountry->insert($data)) {
            return $this->_tablecountry->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * metodo update(), registra los datos de la country 
     * @param array     $data           array con los datos de la country array('column'=>'valor')
     * @param int       $idcountry  id de la country
     * @return bolean   
     */
    public function update($data, $idcountry) {
        if ($idcountry != '') {
            $where = $this->_tablecountry->getAdapter()
                    ->quoteInto('country_id =?', $idcountry);
            return $this->_tablecountry->update($data, $where);
        } else {
            return false;
        }
    }

}



