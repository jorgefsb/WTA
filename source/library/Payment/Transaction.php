<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment
 *
 * @author Camacho
 */
class Payment_Transaction {

    # Case sensitive
    const PAYMENT_SERVICE_AUTHORIZE = 'Authorize';
    const PAYMENT_SERVICE_PAYPAL = 'Paypal';
    
    protected $_payment = null;      # objeto  de transaction

    public function __construct($payment_service=PAYMENT_SERVICE_AUTHORIZE) {
        
        $config = $this->getConfig($payment_service);

        if( !$config ){
            throw new Exception('Invalid payment service');
            return false;
        }
        
        $config_payment = $config;
        
        if(class_exists('Payment_Transaction_'.$payment_service)){
            $class = 'Payment_Transaction_'.$payment_service;
            $this->_payment = new  $class($config_payment);           
        }else{
            throw new Exception('Not found class Payment_Transaction_'.$payment_service);
            return false;
        }
        
    }
    
    static public function getConfig($payment_service){
        $config_application = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini");
        $config = $config_application->get(APPLICATION_ENV)->toArray();
        
        if( !$config['payment'][strtolower($payment_service)] ){
            throw new Exception('Invalid payment service');
            return false;
        }
        
        return $config['payment'][strtolower($payment_service)];
    }
    
    
    public function __call($name, $arguments) {
        if($this->_payment){
            if(method_exists($this->_payment, $name)){
                return call_user_func(array($this->_payment,$name), $arguments);
                //return call_user_method($name, $this->_payment, $arguments);
            }
            throw new Exception('Method not found:'.$name);
        }
        return false;
    }
        
    
    public function error(){
        if(method_exists($this->_payment, 'error')){
            return $this->_payment->error();
        }
        return false;
    }
    
}

?>
