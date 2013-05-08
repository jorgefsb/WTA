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
                        "<faxNumber>{$this->_faxNumber}</faxNumber>".
                    "</billTo>".
                    "<payment>".
                        "<creditCard>".
                            "<cardNumber>{$this->_cardNumber}</cardNumber>";
        if($this->_expirationDate){
            $xml .=  "<expirationDate>{$this->_expirationDate}</expirationDate>";
        }else{
            $xml .=  "<expirationDate>XXXX</expirationDate>";
        }
        if($this->_cardCode){
            $xml .=  "<cardCode>{$this->_cardCode}</cardCode>";
        }
        
        $xml .=  "</creditCard>".
                    "</payment>";
        return $xml;
    }
    

    public function commit(){
        if($this->_isEdited == false){
            return true;
        }        
        $xml ='';
        
        if( $this->_customerProfileId>0 ){
            $xml .= '<customerProfileId>'.$this->_customerProfileId.'</customerProfileId>';
        }elseif(($this->_customer && $this->_customer->_customerProfileId>0)) {
            $xml .= '<customerProfileId>'.$this->_customer->_customerProfileId.'</customerProfileId>';
        }
        $xml .= '<paymentProfile>';
        $xml .= $this->getXml();
        if( $this->_customerPaymentProfileId >0 ){
            $xml .= '<customerPaymentProfileId>'.$this->_customerPaymentProfileId.'</customerPaymentProfileId>';
        }
        $xml .= '</paymentProfile>';
        
        if( $this->_customerPaymentProfileId>0 ){            
            $action = 'updateCustomerPaymentProfileRequest';
        }else{
            $action = 'createCustomerPaymentProfileRequest';
        }
        
        $xml_response = $this->_authorize->commit($action, $xml);
        
        if( is_object($xml_response)==false){
            $this->_error = $this->_authorize->getError();
            $this->_errorMsg = $this->_authorize->getErrorMsg();
            return false; //Error
        }else{
            return true;
        }
        $this->_isEdited = false;
    }
    
    public function loadFromXml($paymentProfile){
        if(is_object($paymentProfile)){            
                $this->_customerPaymentProfileId = (string)$paymentProfile->customerPaymentProfileId;
                $this->_firstName = (string)$paymentProfile->billTo->firstName;
                $this->_lastName = (string)$paymentProfile->billTo->lastName;
                $this->_address = (string)$paymentProfile->billTo->address;
                $this->_city = (string)$paymentProfile->billTo->city;                
                $this->_state = (string)$paymentProfile->billTo->state;
                $this->_zip = (string)$paymentProfile->billTo->zip;
                $this->_country = (string)$paymentProfile->billTo->country;
                $this->_phoneNumber =(string)$paymentProfile->billTo->phoneNumber;
                $this->_faxNumber =(string)$paymentProfile->billTo->faxNumber;
                $this->_cardNumber =(string)$paymentProfile->payment->creditCard->cardNumber;
                $this->_expirationDate =(string)$paymentProfile->payment->creditCard->expirationDate;

        }
    }
        
}

?>
