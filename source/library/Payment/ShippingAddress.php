<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment ShippingAddress
 *
 * @author Camacho
 */
abstract class Payment_ShippingAddress {
    
    protected $_data = array();
    
    public function __set($name, $value) {
        $this->_isEdited = true;
        $this->_data[$name] = $value;
    }
    
    public function __get($name) {
        if( isset($this->_data[$name]) ){
            return $this->_data[$name];
        }
    }
    
    /*
     * return Xml string for the request
     */
    abstract function identify($customerProfileId);
        
    /*
     * Guarda los cambios o crea un nuevo Customer
     * return Xml string for the request
     */    
    abstract public function commit();   
    
    
        
}

?>
