<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment  ShippingAddress
 *
 * @author Camacho
 */
class Payment_Transaction_Authorize_ShippingAddress extends Payment_ShippingAddress {
    /*
     * firstName
     * lastName
     * company
     * address
     * city
     * state
     * zip
     * country
     * phoneNumber
     * faxNumber
     */
        
    /*
     * return String xml for the request
     */
    public function getXml(){
        
        if( !$this->_firstName && !$this->_lastName && !$this->_address){
            return false;
        }
        
        $xml = 
                    "<firstName>{$this->_firstName}</firstName>".
                    "<lastName>{$this->_lastName}</lastName>".
                    //<company></company>",
                    "<address>{$this->_address}</address>".
                    " <city>{$this->_city}</city>".
                    "<state>{$this->_state}</state>".
                    "<zip>{$this->_zip}</zip>".
                    "<country>{$this->_country}</country>".
                    "<phoneNumber>{$this->_phoneNumber}</phoneNumber>";
                    //"<faxNumber></faxNumber>".        
        return $xml;
    }
    
    public function commit(){
        if($this->_isEdited == false){
            return true;
        }
        $xml ='';
        if( $this->_customerProfileId>0){
            $xml .= '<customerProfileId>'.$this->_customerProfileId.'</customerProfileId>';
        }elseif($this->_customer && $this->_customer->_customerProfileId>0){
            $xml .= '<customerProfileId>'.$this->_customer->_customerProfileId.'</customerProfileId>';
        }
        
        $xml .= '<address>';
        $xml .= $this->getXml();
        if( $this->_customerAddressId >0 ){
            $xml .= '<customerAddressId>'.$this->_customerAddressId.'</customerAddressId>';
        }
        $xml .= '</address>';
        
        
        if( $this->_customerAddressId>0 ){            
            $action = 'updateCustomerShippingAddressRequest';
        }else{
            $action = 'createCustomerShippingAddressRequest';
        }
        
        $xml_response = $this->_authorize->commit($action, $xml);
        
        if( is_object($xml_response)==false){
            $this->_error = $this->_authorize->getError();
            return false; //Error
        }else{
            return true;
        }
        $this->_isEdited = false;
    }
        
    public function loadFromXml($shipToList){
        if(is_object($shipToList)){
            $this->_customerAddressId = (string)$shipToList->customerAddressId;
            $this->_firstName = (string)$shipToList->firstName;
            $this->_lastName = (string)$shipToList->lastName;
            $this->_address = (string)$shipToList->address;
            $this->_city = (string)$shipToList->city;
            $this->_state = (string)$shipToList->state;
            $this->_zip = (string)$shipToList->zip;
            $this->_country = (string)$shipToList->country;
            $this->_phoneNumber = (string)$shipToList->phoneNumber;
        }
    }
    
        
}

?>
