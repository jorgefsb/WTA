<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment  BillingInformation
 *
 * @author Camacho
 */
abstract class Payment_BillingInformation {
    
    protected $_data = array();
    protected $_isEdited = false;
    protected $_error;
    
    public function __set($name, $value) {
        $this->_isEdited = true;
        $this->_data[$name] = $value;
    }
    
    public function __get($name) {
        if( isset($this->_data[$name]) ){
            return $this->_data[$name];
        }
    }
    
    public function getAllProperties(){
        $_data = array();
        foreach($this->_data as $key =>$propertie){
            if(is_object($propertie)){
                continue;
            }
            if( !empty($propertie)){
                if(is_array($propertie) ) {
                    if(!is_object($propertie[0])){
                        $_data[$key] = $propertie;
                    }
                }else{
                    $_data[$key] = $propertie;
                }
            }
        }
        return $_data;
    }
    
    public function getError(){
        return $this->_error;
    }
    
    /*
     * Guarda los cambios o crea un nuevo Billing Information
     * return Xml string for the request
     */    
    abstract public function commit();
        
    
    
        
}

?>
