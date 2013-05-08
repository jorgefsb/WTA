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
    
    public function getXml(){
        
        $_customerProfileId = $this->_customerProfileId;
        $_customerPaymentProfileId = $this->_customerPaymentProfileId;
        $_customerShippingAddressId = $this->_customerShippingAddressId;
        
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
                    "</shipping>";                    
         }
         //$xml .= "<lineItems>";
         foreach($this->_items as $item){
            $xml .=
                            "<lineItems>".
                                "<itemId>{$item['itemId']}</itemId>".
                                "<name>{$item['name']}</name>".
                                //"<description>{$item['description']}</description>".
                                "<quantity>{$item['quantity']}</quantity>".
                                "<unitPrice>{$item['unitPrice']}</unitPrice>".
                            "</lineItems>";
         }
         $xml .=
     //               "</lineItems>".
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
        if($this->_cardCode){
             $xml .=
                    "<cardCode>{$this->_cardCode}</cardCode>";
        }
                    
        return $xml;
    }
    
    public function commit(){
        
        $xml ='';
        $xml .= '<transaction>';
        $xml .= '<profileTransAuthCapture>';
        $xml .= $this->getXml();
        $xml .= '</profileTransAuthCapture>';
        $xml .= '</transaction>';
   //     $xml .= "<validationMode>{$this->_authorize->_config['validationMode']}</validationMode>".
        $action = 'createCustomerProfileTransactionRequest';
        
        $xml_response = $this->_authorize->commit($action, $xml);
        
        if(is_object($xml_response)==false ){
            $this->_error = $this->_authorize->getError();
            $this->_errorMsg = $this->_authorize->getErrorMsg();
            return false; //Error
        }else{
            $strdirectResponse = $xml_response->directResponse;
            $directResponse= explode(',', $strdirectResponse);
            $this->_isEdited = false;
            
            if($directResponse[0]==1){
                $this->_profileTransactionId = $directResponse[6];
                return true;
            }elseif($directResponse[0]==2){
                $this->_error = 'Decline';
                $this->_errorMsg = $directResponse[3];
                return false;
            }elseif($directResponse[0]==3){
                $this->_error = 'Error';
                $this->_errorMsg = $directResponse[3];
                return false;
            }elseif($directResponse[0]==4){
                $this->_error = 'Held for Review';
                $this->_errorMsg = $directResponse[3];
                return false;
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
