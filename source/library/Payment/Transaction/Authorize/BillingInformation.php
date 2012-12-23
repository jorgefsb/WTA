<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment Transaction Auhorize BillingInformation
 *
 * @author Camacho
 */
class Payment_Transaction_Authorize_BillingInformation extends Payment_BillingInformation{
    /*
     * _firstName
     * _lastName
     * _address
     * _city
     * _state
     * _zip
     * _country
     * _phoneNumber
     * _cardNumber  
     * _expirationDate - YYYY-MM
     * _cardCode
     */
   
    /*
     * return String xml for the request
     */
    public function identify($customerPaymentProfileId){
        $xml = '';
        return $xml;
    }

    
    /*
     * return String xml for the request
     */
    public function getXml(){
        
        if( !$this->_firstName && !$this->_cardNumber && !$this->_expirationDate){
            return false;
        }
        
        $xml = 
                    "<billTo>".
                        "<firstName>{$this->_firstName}</firstName>".
                        "<lastName>{$this->_lastName}</lastName>".
                        //"<company></company>".
                        "<address>{$this->_address}</address>".
                        "<city>{$this->_city}</city>".
                        "<state>{$this->_state}</state>".
                        "<zip>{$this->_zip}</zip>".
                        "<country>{$this->_country}</country>".
                        "<phoneNumber>{$this->_phoneNumber}</phoneNumber>".
                        //"<faxNumber></faxNumber>".
                    "</billTo>".
                    "<payment>".
                        "<creditCard>".
                            "<cardNumber>{$this->_cardNumber}</cardNumber>".
                            "<expirationDate>{$this->_expirationDate}</expirationDate>".
                            "<cardCode>{$this->_cardCode}</cardCode>".
                        "</creditCard>".
                    "</payment>";
        return $xml;
    }
    

    public function commit(){
        if($this->_isEdited == false){
            return true;
        }        
        $xml ='';
        if($this->_customer && $this->_customer->_customerProfileId){
            $xml .= '<customerProfileId>'.$this->_customer->_customerProfileId.'</customerProfileId>';
        }
        $xml = '<paymentProfile>';
        $xml .= $this->getXml();
        $xml .= '</paymentProfile>';
        
        if(!$this->_paymentProfileId){
            $action = 'createCustomerPaymentProfileRequest';
        }else{
            $action = 'updateCustomerPaymentProfileRequest';
        }
        $response = $this->_authorize->commit($action, $xml);
        $xml_response = $this->_authorize->parse_api_response($response);
        $this->_isEdited = false;
    }
    
    
        
}

?>
