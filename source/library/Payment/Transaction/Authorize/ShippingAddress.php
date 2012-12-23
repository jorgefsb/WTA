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
    public function identify($customerShippingAddressId){
        $xml = '';
        return $xml;
    }

    
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
        if($this->_customer && $this->_customer->_customerProfileId){
            $xml .= '<customerProfileId>'.$this->_customer->_customerProfileId.'</customerProfileId>';
        }
        $xml .= '<address>';
        $xml .= $this->getXml();
        $xml .= '</address>';
        
        if(!$this->_shippingAddressId){
            $action = 'createCustomerShippingAddressRequest';
        }else{
            $action = 'updateCustomerShippingAddressRequest';
        }
        $response = $this->_authorize->commit($action, $xml);
        $xml_response = $this->_authorize->parse_api_response($response);
        $this->_isEdited = false;
    }
    
        
}

?>
