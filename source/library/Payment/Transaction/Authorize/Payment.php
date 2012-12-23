<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment Transaction Auhorize Payment
 *
 * @author Camacho
 */
class Payment_Transaction_Authorize_Payment extends Payment_Payment {
    /*
     * _amount
     * tax
     *      amount         _taxAmount
     *      name            _taxName
     *      description     _taxDescription
     * shipping
     *      amount          _shippingAmount
     *      name            _shippingName
     *      description     _shippingDescription
     * lineItems
     *      lineItem
     *          itemId
     *          name
     *          description
     *          quantity
     *          unitPrice
     * customerProfileId
     * customerPaymentProfileId
     * customerShippingAddressId
     * order
     *      invoiceNumber                   _orderInvoiceNumber
     *      description                         _orderDescription
     *      purchaseOrderNumber     _orderPurchaseOrderNumber
     * taxExempt true/false
     * recurringBilling true/false
     * cardCode
     */
    
    protected $_items = array();
    protected $_error;
    
    public function getXml(){
        
        if( is_object($this->customer) ){
            $_customerProfileId =$this->customer->_customerProfileId;
            $_customerPaymentProfileId = $this->customer->_customerPaymentProfileIds[0]; // Por defult toma el primer valor
            $_customerShippingAddressId = $this->customer->_customerShippingAddressIds[0];
        }else{
            $_customerProfileId = $this->_customerProfileId;
            $_customerPaymentProfileId = $this->_customerPaymentProfileId;
            $_customerShippingAddressId = $this->_customerShippingAddressId;
        }
        
         $xml =
                    "<amount>{$this->_amount}</amount>";
         if($this->_taxAmount){
             $xml .=
                    "<tax>".
                        "<amount>{$this->_taxAmount}</amount>".
                        "<name>{$this->_taxName}</name>".
                        "<description>{$this->_taxDescription}</description>".
                    "</tax>";
         }
         if($this->_shippingAmount){
             $xml .=
                    "<shipping>".
                        "<amount>{$this->_shippingAmount}</amount>".
                        "<name>{$this->_shippingName}</name>".
                        "<description>{$this->_shippingDescription}</description>".
                    "</shipping>".
                    "<lineItems>";
         }
         foreach($this->_items as $item){
            $xml .=
                            "<lineItem>".
                                "<itemId>{$item['itemId']}</itemId>".
                                "<name>{$item['name']}</name>".
                                "<description>{$item['description']}</description>".
                                "<quantity>{$item['quantity']}</quantity>".
                                "<unitPrice>{$item['unitPrice']}</unitPrice>".
                            "</lineItem>";
         }
         $xml .=
                    "</lineItems>";
                    "<customerProfileId>{$_customerProfileId}</customerProfileId>".
                    "<customerPaymentProfileId>{$_customerPaymentProfileId}</customerPaymentProfileId>".
                    "<customerShippingAddressId>{$_customerShippingAddressId}</customerShippingAddressId>";
        if($this->_orderInvoiceNumber && $this->_orderPurchaseOrderNumber){
             $xml .=
                    "<order>".
                        "<invoiceNumber>{$this->_orderInvoiceNumber}</invoiceNumber>".
                        "<description>{$this->_orderDescription}</description>".
                        "<purchaseOrderNumber>{$this->_orderPurchaseOrderNumber}</purchaseOrderNumber>".
                    "</order>";
        }
        if($this->_taxExempt){
             $xml .=
                    "<taxExempt>{$this->_taxExempt}</taxExempt>";
        }
        if($this->_recurringBilling){
             $xml .=
                    "<recurringBilling>{$this->_recurringBilling}</recurringBilling>";
        }
             $xml .=
                    "<cardCode>{$this->_cardCode}</cardCode>";
                    
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
        $xml = '<transaction>';
        $xml .= '<profileTransAuthCapture>';
        $xml .= $this->getXml();
        $xml .= '</profileTransAuthCapture>';
        $xml .= '</transaction>';
        $xml .= "<validationMode>{$this->_authorize->_config['validationMode']}</validationMode>".
        $action = 'createCustomerProfileTransactionRequest';
        
        $response = $this->_authorize->commit($action, $xml);
        $xml_response = $this->_authorize->parse_api_response($response);
        
        if(is_array($xml_response )){
            $this->_error = $xml_response;
            return false; //Error
        }else{
            if ("Ok" == $xml_response->messages->resultCode) {
                $this->_customerProfileId = $xml_response->customerProfileId;
                $_customerPaymentProfileIds = array();
                $_customerShippingAddressIds = array();
                
                $this->_profileTransactionId = $xml_response->messages->message->msg->code;
                
                return $this->_profileTransactionId;
                
                $this->_isEdited = false;
            }
        }
        return false;
    }
        
    public function addProduct($_id, $_name, $_description, $_quantity, $_unitPrice){
        $product = array(
            'itemId'=>$_id,
            'name'=>$_name,
            'description'=>$_description,
            'quantity'=>$_quantity,
            'unitPrice'=>$_unitPrice
        );
        $this->_items[] = $product;
    }
    
    
}

?>
