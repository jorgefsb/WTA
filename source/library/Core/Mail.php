<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mail
 *
 * @author Laptop
 */
class Core_Mail {

    //put your code here
    private $_mensaje;
    private $_destinatario;
    private $_transport;
    private $_subject;
    private $_from;
    private $_error;
    private $_adjuntos;

    function __construct() {
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini");
        $class = $config->get(APPLICATION_ENV)->toArray();
        $conf = $class['mail']['conf'];
        $smtp = $class['mail']['smtpServer'];
        $this->_transport = new Zend_Mail_Transport_Smtp($smtp, $conf);
        $this->setFrom($class['mail']['from']['email'], $class['mail']['from']['nameEmail']);
        $this->_destinatario = array();
    }

    function send() {
        $mail = new Zend_Mail();
        $mensaje = array();
        try {
            $mail->setFrom($this->_from['mail'], $this->_from['nameSend']);
            if (!empty($this->_destinatario)) {
                foreach ($this->_destinatario as $index => $value) {
                    $validate = new Zend_Validate_EmailAddress();
                    if (is_array($value)) {
                        if (array_key_exists('mail', $value)) {
                            if ($validate->isValid($value['mail'])) {
                                $mail->addTo($value['mail'], isset($value['name']) ? $value['name'] : '');
                            } else {
                                $mensaje['EmailInvalid'][] = $value;
                            }
                        } else {
                            $mensaje['formatInvalid'][] = $value;
                        }
                    } else {
                        if ($validate->isValid($value)) {
                            $mail->addTo($value, '');
                        } else {
                            $mensaje['EmailInvalid'][] = $value;
                        }
                    }
                }
            } else {
                $mensaje['errorAplication'][] = 'debe tener almenos un destinatario';
                $this->_error = $mensaje;
            }
            if (!empty($this->_adjuntos)) {
                foreach ($this->_adjuntos as $key => $val) {
                    $at = $mail->createAttachment(file_get_contents($val));                    
                    $at->filename = basename($val);
                }
            }
            
            if (!empty($mensaje)) {
                $this->_error = $mensaje;
                return false;
            }
            $mail->setSubject($this->_subject);
            $mail->setBodyHtml($this->_mensaje);
            $mail->send($this->_transport);
            return true;
        } catch (Exception $exc) {
            Core_Log::error($exc->__toString());
            $mensaje['errorAplication'][] = $exc->getMessage();
            $this->_error = $mensaje['errorAplication'];
            return false;
        }
    }

    function getError() {
        return $this->_error;
    }

    function setMensaje($mensaje) {
        $this->_mensaje = $mensaje;
    }

    function setAsunto($asunto) {
        $this->_subject = $asunto;
    }

    function addDestinatario($destinatario) {
        $this->_destinatario[] = $destinatario;
    }

    function setFrom($email, $nameSend) {
        $this->_from['mail'] = $email;
        $this->_from['nameSend'] = $nameSend;
    }
    function setDestinatarioCreateUser(){
        
    }
    
    function addAdjunto($file){
        $this->_adjuntos[] = $file;
    }
    

}

?>
