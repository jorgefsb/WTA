<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment Transaction Auhorize Customer
 *
 * @author Camacho
 */
class Payment_Transaction_Authorize_Customer extends Payment_Customer{    
    /*
     * Datos que guarda
     * _merchantCustomerId - Opcional: int
     * _description- Opcional: int
     * _email - Opcional: string
     * 
     * Debe de especificarse almenos 1 de los datos anteriores
     * 
     * _customerProfileId - Opcional. Si se define entonces se actualiza el perfil
     * 
     * _shippingsAddress - Opcional: Array con object Payment_ShippingAddress
     * _billingsInformation - Opcional: Array con object Payment_BillingInformation
     * _payments - Opcional: Array con object Payment_Payment
     * 
     * Otros
     * _customerPaymentProfileIds Array Contiene los id de los billings information
     * _customerShippingAddressIds Array Contiene los id de los shippings information
     * 
     */
    
    /*
     * return Xml string for the request
     */    
    protected $_error;
    protected $_customerProfile;
    
    public function identify($customerProfileId){
        $this->_customerProfileId = $customerProfileId;
        
        $xml_customer = $this->_authorize->commit('getCustomerProfileRequest', '<customerProfileId>'.$customerProfileId.'</customerProfileId>');       
        
        $_customerPaymentProfileIds = array();
        if( is_object($xml_customer)){            
            
            $this->_customerProfile  = $xml_customer->profile;
            
            $_customerPaymentProfileIds = array();            
            foreach ($this->_customerProfile->paymentProfiles as $paymentProfile){
                $_customerPaymentProfileIds[] = (string)$paymentProfile->customerPaymentProfileId;
                $billinfo = $this->billingInformation();
                $billinfo->loadFromXml($paymentProfile);
            }
            
            $_customerShippingAddressIds = array();
            foreach ($this->_customerProfile->shipToList as $shipToList){
                $_customerShippingAddressIds[] = (string)$shipToList->customerAddressId;
                $shp = $this->shippingAddress();
                $shp->loadFromXml($shipToList);
            }
            
        }
        
        $this->_customerPaymentProfileIds = $_customerPaymentProfileIds;
        $this->_customerShippingAddressIds = $_customerShippingAddressIds;
        
    }
    
    public function getListShippingAddress(){
        return $this->_shippingsAddress;
    }

    public function getListBillingInformation(){
        return $this->_billingsInformation;
    }
    
    /*
     * return Xml string for the request
     */
    public function getXml(){
        if( !$this->_merchantCustomerId && !$this->_description && !$this->_email){
            return false;
        }
        $xml = 
                    "<profile>".
                    "<merchantCustomerId>{$this->_merchantCustomerId}</merchantCustomerId>". // Your own identifier for the customer.
                    "<description>{$this->_description}</description>".
                    "<email>{$this->_email}</email>";
        if($this->_customerProfileId){
            $xml .= "<customerProfileId>{$this->_customerProfileId}</customerProfileId>";
        }else{
            
            foreach($this->_billingsInformation as $billInf){
                $xml .= '<paymentProfiles>';
                $xml .= $billInf->getXml();
                $xml .= '</paymentProfiles>';
            }
            
            foreach($this->_shippingsAddress as $shpAd){
                $xml .= '<shipToList>';
                $xml .= $shpAd->getXml();
                $xml .= '</shipToList>';
            }
            /*
            foreach($this->_payments as $pay){
           //     $xml .= $pay->getXml();
            } */           
        }
                    
        $xml .= "</profile>";
        return $xml;
    }
        
    /*
     * Guarda los cambios o crea un nuevo Customer
     * return Xml string for the request
     */    
    public function commit(){
        if($this->_isEdited == false){
            return true;
        }
        $xml = $this->getXml();
        
        if(!$this->_customerProfileId){
            $action = 'createCustomerProfileRequest';
        }else{
            $action = 'updateCustomerProfileRequest';
        }
        
        $xml_response = $this->_authorize->commit($action, $xml);
        
        
        if( is_object($xml_response)==false){
            $this->_error = $this->_authorize->getError();
            return false; 
        }else{
            $this->_customerProfileId = $xml_response->customerProfileId;
            $_customerPaymentProfileIds = array();
            $_customerShippingAddressIds = array();
            foreach ($xml_response->customerPaymentProfileIdList as $customerPaymentProfileId){
                $_customerPaymentProfileIds[] = $customerPaymentProfileId->numericString;
            }
            foreach ($xml_response->customerShippingAddressIdList as $customerShippingAddressId){
                $_customerShippingAddressIds[] = $customerShippingAddressId->numericString;
            }

            $this->_customerPaymentProfileIds = $_customerPaymentProfileIds;
            $this->_customerShippingAddressIds = $_customerShippingAddressIds;

            return $this->_customerProfileId;
            
            $this->_isEdited = false;
        }
        return false;
        
    }
             
    /*
     * return new Object Payment_Transaction_Authorize_ShippingAddress
     */
    public function shippingAddress(){
        $_shippingAddress = new Payment_Transaction_Authorize_ShippingAddress();
        $_shippingAddress->_customer = $this;
        $_shippingAddress->_authorize = $this->_authorize;
        $this->_shippingsAddress[] = $_shippingAddress;
        return $_shippingAddress;
    }
    
    /*
     * return new Object Payment_Transaction_Authorize_BillingInformation
     */
    public function billingInformation(){        
        $_billingInformation = new Payment_Transaction_Authorize_BillingInformation();
        $_billingInformation->_customer = $this;
        $_billingInformation->_authorize = $this->_authorize;
        $this->_billingsInformation[] = $_billingInformation;
        return $_billingInformation;
    }
        
    /*
     * return new Object Payment_Transaction_Authorize_Payment
     */
    public function payment(){        
        $_payment = new Payment_Transaction_Authorize_Payment();
        $_payment->_customer = $this;
        $_payment->_authorize = $this->_authorize;
        $this->_payments = $_payment;
        return $_payment;
    }
    
    //public function getCustomerProfile(){
      //  return $this->_customerProfile;
    //}
    
        
}

?>
