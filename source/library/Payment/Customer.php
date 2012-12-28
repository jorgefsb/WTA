<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment Customer
 *
 * @author Camacho
 */
abstract  class Payment_Customer {
    
    protected $_data = array();
    protected $_shippingsAddress = array();
    protected $_billingsInformation = array();
    protected $_payments = array();
    protected $_isEdited = false;
    protected $_error;
    
    public function __construct() {
        
    }
    
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
     * return Xml string for the request
     */
    abstract function identify($customerProfileId);
        
    /*
     * Guarda los cambios o crea un nuevo Customer
     * return Xml string for the request
     */    
    abstract public function commit();
        
    /*
     * regresa un listado de objetos
     */
    abstract public function getListShippingAddress();
    
    abstract public function getListBillingInformation();
        
    /*
     * return new Object Payment_ShippingAddress
     */
    abstract public function shippingAddress();
    
    /*
     * return new Object Payment_BillingInformation
     */
    abstract public function billingInformation();
        
    /*
     * return new Object Payment_Payment
     */
    abstract public function payment();
    
        
}

?>
