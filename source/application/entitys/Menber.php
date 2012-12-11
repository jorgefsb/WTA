<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Menber extends Core_Entity {

    protected $_id;
    protected $_name;
    protected $_lastName;
    protected $_mail;
    protected $_linkConfirm;
    protected $_idConfirm;
    protected $_active;
    protected $_confirm;
    protected $_avatar;

    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    private function asocParams($data) {
        $this->_id = $data['menber_id'];
        $this->_name = $data['menber_name'];
        $this->_lastName = $data['menber_last_name'];
        $this->_mail = $data['menber_mail'];
        $this->_linkConfirm = $data['menber_link_confirm'];
        $this->_idConfirm = $data['menber_id_confirm'];
        $this->_active = $data['menber_active'];
        $this->_confirm = $data['menber_confirm'];
        $this->_avatar = $data['menber_avatar'];
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idMenber
     * @return void
     */

    function identify($idMenber) {
        $modelMenber = new Application_Model_Menber();
        $data = $modelMenber->getMenber($idMenber);
        if ($data != '') {
            $this->asocParams($data);
        }
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idMenber
     * @return void
     */

    private function identifyForIdConfirm($idConfirm) {
        $modelMenber = new Application_Model_Menber();
        $data = $modelMenber->getMenberForIdConfirm($idConfirm);
        if ($data != '') {
            $this->asocParams($data);
        }
        return $data;
    }

    /*
     * metodo setParamsDataBase()
     *
     * @param 
     * @return array
     */

    function setParamsDataBase() {
        $data['menber_id'] = $this->_id;
        $data['menber_name'] = $this->_name;
        $data['menber_last_name'] = $this->_lastName;
        $data['menber_mail'] = $this->_mail;
        $data['menber_link_confirm'] = $this->_linkConfirm;
        $data['menber_id_confirm'] = $this->_idConfirm;
        $data['menber_active'] = $this->_active;
        $data['menber_confirm'] = $this->_confirm;
        $data['menber_avatar'] = $this->_avatar;
        return $this->cleanArray($data);
    }

    function update() {
        $modelMenber = new Application_Model_Menber();

        if ($modelMenber->update($this->setParamsDataBase(), $this->_id) !== FALSE) {
            $this->_message = 'Registration was successful';
            return true;
        } else {
            $this->_message = 'registration fails';
            return false;
        }
    }

    /*
     * metodo createMenber()
     *
     * @param 
     * @return 
     */

    function createMenber($password) {
        $modelMenber = new Application_Model_Menber();
        $this->_active = 1;
        $this->_confirm = 0;
        $data = $this->setParamsDataBase();
        $data['menber_create_date'] = date('Y-m-d H:i:s');
        $data['menber_id_confirm'] = $this->createIdConfirm();
        $data['menber_password'] = $this->encriptaPassword($password);
        $id = $modelMenber->insert($data);
        if ($id != false) {
            $this->_id = $id;
            $this->setMailAdmin();
            $this->setMailMenberConfirmAccount($data['menber_id_confirm']);
            $this->_message = 'Registration was successful, 
                    we will send an email to ' .
                    $this->_mail .
                    ' to confirm your account';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }

    /*
     * metodo confirmAccount()
     *
     * @param $idConfirm
     * @return 
     */

    function confirmAccount($idConfirm) {
        $modelMenber = new Application_Model_Menber();
        $data = $this->identifyForIdConfirm($idConfirm);
        if ($data == false) {
            $this->_message = 'Token invalido';
            return false;
        }
        if ($this->_confirm == 0 && $this->_active == 1) {
            $this->_confirm = 1;
            $data = $this->setParamsDataBase();
            $data['menber_confirm_date'] = date('Y-m-d H:i:s');
            $modelMenber->update($data, $this->_id);
            $this->_message = 'Su cuenta ha sido activada';
            //$this->login($user, $password);
        } else {
            if ($this->_active == 0) {
                $this->_message = 'El usuario no se encuentra activo';
            } else {
                $this->_message = 'El usuario ya ha confirmado esta cuenta';
            }
        }
    }

    /*
     * metodo setMailMenberConfirmAccount(), envia correo al usuario para la confirmacion de su cuenta
     *
     * @param $idConfirm, codigo autogenerado para la confirmacion de la cuenta
     * @return void
     */

    function setMailMenberConfirmAccount($idConfirm) {
        $mail = new Core_Mail();
        $mail->addDestinatario($this->_mail);
        $mail->setAsunto('confirmar cuenta');
        $mensaje = '<b>Hello ' . $this->_name . '</b><p>';
        $mensaje.= 'need to confirm your account</p>';
        $mensaje.= 'click the following ';
        $mensaje.= '<a href="' . BASE_URL . '/menber/create-account/confirm?id=' . $idConfirm . '">link</a>';
        $mail->setMensaje($mensaje);
        $mail->send();
    }

    /*
     * metodo setMailAdmin(), envia correo al administrador especificando los datos de usuario registrado
     *
     * @param 
     * @return void
     */

    function setMailAdmin() {
        $mail = new Core_Mail();
        $mail->setDestinatarioCreateUser();
        $mail->setAsunto('nuevo usuario');
        $mail->setMensaje('se ha reado un nuevo usuario ');
        $mail->send();
    }

    /*
     * metodo createIdConfirm(), generador del codigo autogenrado 
     *
     * @param int $length, tamaño de la cadena generada
     * @return string
     */

    function createIdConfirm($length = 10) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return md5($str);
    }

    function generaPasswordTemp() {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        $str = '';
        for ($i = 0; $i < 7; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    function encriptaPassword($value) {
        $valueHash = hash('md5', $value);
        $value = rand(1, 1000) . '$$' . rand(1, 1000) . '$$' . $valueHash;
        return $value;
    }

    /*
     * metodo login()
     *
     * @param $user,$password
     * @return 
     */

    function autentificate($usuario, $password) {
        $auth = Zend_Auth::getInstance();
        $adapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('multidb'),
                        'menberautentificate',
                        'menber_mail',
                        'menber_password');
        $adapter->setIdentity($usuario);
        $contrasenia = $this->getPassword($usuario);
        $valueSegurity = $this->getValueSegurityPassword($contrasenia);

        $password = $valueSegurity . $this->setPassword(
                        $this->encriptaPassword($password));
        $adapter->setCredential($password);
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $data = $adapter->getResultRowObject(null, 'menber_password');
            $auth->getStorage()->write($data);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function setPassword($value) {
        return substr(strrchr($value, '$$'), 1);
    }

    protected function getPassword($mail) {
        $modelMenber = new Application_Model_Menber();
        return $modelMenber->getpassword($mail);
    }

    function getValueSegurityPassword($value) {
        return substr($value, 0, strrpos($value, '$$')) . '$$';
    }

    function passwordReset($password) {
        $modelMenber = new Application_Model_Menber();
        $data['menber_password'] = $this->encriptaPassword($password);
        $modelMenber->update($data, $this->_id);
    }

    function sendPasswordRecovery($mail) {
        $modelMenber = new Application_Model_Menber();
        $passwordTemp = $this->generaPasswordTemp();
        $data['menbar_password_reset'] = $passwordTemp;
        if ($modelMenber->insertPasswordReset($passwordTemp, $mail)) {
            $objMail = new Core_Mail();
            $objMail->addDestinatario($mail);
            $objMail->setAsunto('Password Recovery');
            $mensaje = '<b>Password Recovery</b> <p>';
            $mensaje.= '<a href="' . BASE_URL . '/menber/recovery-account/confirm?id=' . $passwordTemp . '">link</a>';
            $objMail->setMensaje($mensaje);
            $objMail->send();
            return true;
        } else {
            $this->_message = 'Email Not Found';
            return false;
        }
    }

    function passwordRecovery($token, $password) {
        $modelMenber = new Application_Model_Menber();
        if ($this->confirmTokenRecoveryAccount($token)) {
            $data['menber_password'] = $this->encriptaPassword($password);
            $data['menbar_password_reset'] = Null;
            $modelMenber->update($data, $this->_id);
            $this->_message = 'The password is reset correctly';
            return TRUE;
        } else {
            $this->_message = 'Registration fails';
            return FALSE;
        }
    }

    function confirmTokenRecoveryAccount($token) {
        $modelMenber = new Application_Model_Menber();
        if ($id = $modelMenber->isTokenPasswordReset($token)) {
            $this->identify($id);
            return true;
        } else {
            return false;
        }
    }

    static function listing() {
        $modelMenber = new Application_Model_Menber();
        return $modelMenber->getMenbers();
    }
    static function searchMenber($value) {
        $modelMenber = new Application_Model_Menber();
        return $modelMenber->searchMenber($value);
    }

    function active() {
        $modelMenber = new Application_Model_Menber();
        $data['menber_active'] = '1';
        return $modelMenber->update($data, $this->_id);
    }

    function inactive() {
        $modelMenber = new Application_Model_Menber();
        $data['menber_active'] = '0';
        return $modelMenber->update($data, $this->_id);
    }

}