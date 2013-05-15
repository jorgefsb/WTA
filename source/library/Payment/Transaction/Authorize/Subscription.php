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
class Payment_Transaction_Authorize_Subscription extends Payment_Subscription {
     
    protected $action = 'ARBCreateSubscriptionRequest';
    
    public function getXml(){
        
        if( is_object($this->_customer) ){
            $_customerProfileId = $this->_customer->_customerProfileId;
            $_customerEmail = $this->_customer->_email;
        }else{
            $_customerProfileId = $this->_customerProfileId;
            $_customerEmail = '';
        }
        
         $xml = "
                    <refId>{$this->refId}</refId>
                    <subscriptionId>$this->_subscriptionId</subscriptionId>
                    <subscription>
                        <name>{$this->_name}</name>
                        <paymentSchedule>
                            <interval>
                                <length>{$this->_intervalLength}</length>
                                <unit>{$this->_intervalUnit}</unit>
                            </interval>
                            <startDate>{$this->_startDate}</startDate>
                            <totalOccurrences>{$this->_totalOccurrences}</totalOccurrences>
                            <trialOccurrences>{$this->_trialOccurrences}</trialOccurrences>
                        </paymentSchedule>
                        <amount>{$this->_amount}</amount>
                        <trialAmount>{$this->_trialAmount}</trialAmount>
                        <payment>
                            <creditCard>
                                <cardNumber>{$this->_creditCardCardNumber}</cardNumber>
                                <expirationDate>{$this->_creditCardExpirationDate}</expirationDate>
                                <cardCode>{$this->_creditCardCardCode}</cardCode>
                            </creditCard>
                            <bankAccount>
                                <accountType>{$this->_bankAccountAccountType}</accountType>
                                <routingNumber>{$this->_bankAccountRoutingNumber}</routingNumber>
                                <accountNumber>{$this->_bankAccountAccountNumber}</accountNumber>
                                <nameOnAccount>{$this->_bankAccountNameOnAccount}</nameOnAccount>
                                <echeckType>{$this->_bankAccountEcheckType}</echeckType>
                                <bankName>{$this->_bankAccountBankName}</bankName>
                            </bankAccount>
                        </payment>
                        <order>
                            <invoiceNumber>{$this->_orderInvoiceNumber}</invoiceNumber>
                            <description>{$this->_orderDescription}</description>
                        </order>
                        <customer>
                            <id>{$_customerProfileId}</id>
                            <email>{$_customerEmail}</email>
                            <phoneNumber>{$this->_customerPhoneNumber}</phoneNumber>
                            <faxNumber>{$this->_customerFaxNumber}</faxNumber>
                        </customer>
                        <billTo>
                            <firstName>{$this->_billToFirstName}</firstName>
                            <lastName>{$this->_billToLastName}</lastName>
                            <company>{$this->_billToCompany}</company>
                            <address>{$this->_billToAddress}</address>
                            <city>{$this->_billToCity}</city>
                            <state>{$this->_billToState}</state>
                            <zip>{$this->_billToZip}</zip>
                            <country>{$this->_billToCountry}</country>
                        </billTo>
                        <shipTo>
                            <firstName>{$this->_shipToFirstName}</firstName>
                            <lastName>{$this->_shipToLastName}</lastName>
                            <company>{$this->_shipToCompany}</company>
                            <address>{$this->_shipToAddress}</address>
                            <city>{$this->_shipToCity}</city>
                            <state>{$this->_shipToState}</state>
                            <zip>{$this->_shipToZip}</zip>
                            <country>{$this->_shipToCountry}</country>
                        </shipTo>
                    </subscription>";
                            
        $xml_clean = "";
        // Remove any blank child elements
        foreach (preg_split("/(\r?\n)/", $xml) as $key => $line) {
            if (!preg_match('/><\//', $line)) {
                $xml_clean .= $line . "\n";
            }
        }
        
        // Remove any blank parent elements
        $element_removed = 1;
        // Recursively repeat if a change is made
        while ($element_removed) {
            $element_removed = 0;
            if (preg_match('/<[a-z]+>[\r?\n]+\s*<\/[a-z]+>/i', $xml_clean)) {
                $xml_clean = preg_replace('/<[a-z]+>[\r?\n]+\s*<\/[a-z]+>/i', '', $xml_clean);
                $element_removed = 1;
            }
        }
        
        
        
        // Remove any blank lines
        // $xml_clean = preg_replace('/\r\n[\s]+\r\n/','',$xml_clean);
        return $xml_clean;
    }
    
    public function commit(){
        
        $xml ='';
        $xml .= $this->getXml();
   //     $xml .= "<validationMode>{$this->_authorize->_config['validationMode']}</validationMode>".

        $xml_response = $this->_authorize->commit($this->action, $xml);
        
        if(is_object($xml_response)==false ){
            $this->_error = $this->_authorize->getError();
            $this->_errorMsg = $this->_authorize->getErrorMsg();
            return false; //Error
        }else{
            $this->_subscriptionId = $xml_response->subscriptionId;
            return true;
        }
        return false;
    }
    
    public function update(){
        
    }
    
    public function cancel(){
        $this->action = 'ARBCancelSubscriptionRequest  ';
        
        $this->commit();
    }
    
    public function getStatus(){
        $this->action = 'ARBGetSubscriptionStatusRequest  ';
        
        $this->commit();
    }


    
}

?>
