<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment Transaction Auhorize Subscription
 *
 * @author Camacho
 */
abstract class Payment_Subscription{
    
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
    
    public function getError(){
        return $this->_error;
    }
    
    /*
     * Guarda los cambios o crea un nuevo Pago
     * return Xml string for the request
     */    
    abstract public function commit();   
    
        
}

?>
