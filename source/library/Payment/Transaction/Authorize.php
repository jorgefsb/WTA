<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment Transaction Auhorize
 *
 * @author Camacho
 */
class Payment_Transaction_Authorize {

    public $_config = array();
    protected $_customers = null;
    protected $_error = '';
    protected $_history = array();


    public function __construct($config = array()) {
        if (!$config) {
            throw new Exception('Payment configuration is necessary');
        }
        $this->_config = $config;
    }

    /*
     * return Array;
     */

    public function getListCustomers() {
        
    }

    /*
     * return new Object Payment_Transaction_Authorize_Customer
     */

    public function customer() {
        $_customer = new Payment_Transaction_Authorize_Customer();
        $_customer->_authorize = $this;
        $this->_customers[] = $_customer;
        return $_customer;
    }

    /*
     * Ejecuta la transaccion
     * 
     * @param $data Customer information
     */

    public function exec($xml, $new_config = null) {
        $config = $new_config;
        if (!$new_config) {
            $config = $this->_config;
        }
        
        $posturl = "https://" . $config['host'] . $config['path'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $posturl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        
        $this->_history[] = array('send'=>$xml, 'response'=>$response, 'url'=>$posturl);
        
        return trim($response);
    }

    public function commit($action, $content) {
        
        if (empty($content)) {
            return false;
        }

        $xml =
                "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
                "<$action xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
                "<merchantAuthentication>" .
                "<name>" . $this->_config['loginId'] . "</name>" .
                "<transactionKey>" . $this->_config['transactionKey'] . "</transactionKey>" .
                "</merchantAuthentication>" .
                $content.                
                "</$action>";
        
        $response = $this->exec($xml);
        
        $parsedresponse = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOWARNING);
        
        if ("Ok" != $parsedresponse->messages->resultCode) {
            //$error =  "The operation failed with the following errors:";
            //foreach ($parsedresponse->messages->message as $msg) {
                //$error .= "[" . htmlspecialchars($msg->code) . "] " . htmlspecialchars($msg->text) . ". ";
            //}
            $this->_error[] = (string)$parsedresponse->messages->message->code;
            $this->_errorMsg[] = (string)$parsedresponse->messages->message->text;
            return false;
        }
        return $parsedresponse;
    }

    public function error() {
        return $this->_error;
    }
    
    public function parse_api_response($content){
        $parsedresponse = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOWARNING);
        if ("Ok" != $parsedresponse->messages->resultCode) {
            $error = array();
            $error[] = "The operation failed with the following errors:<br>";
            foreach ($parsedresponse->messages->message as $msg) {
                $error[] .= htmlspecialchars($msg->code) . ": " . htmlspecialchars($msg->text);
            }
            return $error;
        }
        return $parsedresponse;
    }
    
    public function getError(){
        if( !empty($this->_error)){
            return end($this->_error);
        }
    }
    
    public function getErrorMsg(){
        if( !empty($this->_errorMsg)){
            return end($this->_errorMsg);
        }
    }
    
    public function getHistory(){
        return $this->_history;
    }
    
    
    public function getLastExecution(){
        if( !empty($this->_history)){
            return end($this->_history);
        }
    }
    
    static function getDescriptionCode($code){
        switch ($code){
            case 'I00001':
                return 'Successful';
                break;
            case 'I00003':
                return 'The record has already been deleted.';
                break;
            case 'E00001':
                return 'An error occurred during processing. Please try again';
                break;
            case 'E00002':
                return 'The content-type specified is not supported';
                break;
            case 'E00003':
                return 'An error occurred while parsing the XML request.';
                break;
            case 'E00004':
                return 'The name of the requested API method is invalid';
                break;
            case 'E00005':
                return 'The merchantAuthentication.transactionKey is invalid or not present';
                break;
            case 'E00006':
                return 'The merchantAuthentication.name is invalid or not present';
                break;
            case 'E00007':
                return 'User authentication failed due to invalid authentication values.';
                break;
            case 'E00008':
                return 'User authentication failed. The payment gateway account or user is inactive';
                break;
            case 'E00009':
                return 'The payment gateway account is in Test Mode. The request cannot be processed.';
                break;
            case 'E00010':
                return 'User authentication failed. You do not have the appropriate permissions.';
                break;
            case 'E00011':
                return 'Access denied. You do not have the appropriate permissions.';
                break;
            case 'E00013':
                return 'The field is invalid';
                break;
            case 'E00014':
                return 'A required field is not present.';
                break;
            case 'E00015':
                return 'The field length is invalid';
                break;            
            case 'E00016':
                return 'The field type is invalid.';
                break;   
            case 'E00019':
                return 'The customer taxId or driversLicense information is required';
                break;   
            case 'E00027':
                return 'The transaction was unsuccessful.';
                break;   
            case 'E00029':
                return 'Payment information is required.';
                break;   
            case 'E00039':
                return 'A duplicate record already exists.';
                break;   
            case 'E00040':
                return 'The record cannot be found.';
                break;   
            case 'E00041':
                return 'One or more fields must contain a value.';
                break;   
            case 'E00042':
                return 'The maximum number of payment profiles allowed for the customer profile is {0}';
                break;   
            case 'E00043':
                return 'The maximum number of shipping addresses allowed for the customer profile is {0}';
                break;   
            case 'E00044':
                return 'Customer Information Manager is not enabled.';
                break;   
            case 'E00045':
                return 'The root node does not reference a valid XML namespace';
                break;   
            case 'E00051':
                return 'The original transaction was not issued for this payment profile';
                break;   
           // default: 
         //       return 'We had a problem. Please review your information and try again'; // Error desconocido
        }
        return false;
    }
    
    
}

?>
