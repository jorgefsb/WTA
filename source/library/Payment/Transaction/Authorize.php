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
            $this->_error[] = (string)$parsedresponse->messages->message->text;
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
    
    public function getHistory(){
        return $this->_history;
    }
    
    
    public function getLastExecution(){
        if( !empty($this->_history)){
            return end($this->_history);
        }
    }
    
}

?>
