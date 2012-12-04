<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_User extends Core_Entity {

    protected $_id;
    protected $_name;
    protected $_mail;
    protected $_active;
    protected $_userType;
    
    
    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    static function listing() {
        $modelUser = new Application_Model_User();
        return $modelUser->listing();
    }

    private function asocParams($data) {
        $this->_id = $data['user_id'];
        $this->_name = $data['user_name'];
        $this->_mail = $data['user_mail'];
        $this->_active = $data['user_active'];
        $this->_userType = $data['user_type_id'];
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idUser
     * @return void
     */

    function identify($idUser) {
        $modelUser = new Application_Model_User();
        $data = $modelUser->getUser($idUser);
        if ($data != '') {
            $this->asocParams($data);
        }
    }

    /*
     * metodo setParamsDataBase()
     *
     * @param 
     * @return array
     */

    function setParamsDataBase() {
        $data['user_id'] = $this->_id;
        $data['user_name'] = $this->_name;
        $data['user_mail'] = $this->_mail;
        $data['user_login'] = $this->_mail;
        $data['user_active'] = $this->_active;
        $data['user_type_id'] = $this->_userType;
        return $this->cleanArray($data);
    }

    function update() {
        $data = $this->setParamsDataBase();
        $modelUser = new Application_Model_User();
        
        if ($modelUser->userExistEmailOther($data['user_mail'], $this->_id)) {
            $this->_message = 'the mail "' . $data['user_mail'] . '" is being used by another user';
            return false;
        } else {
            $userAnt = new Application_Entity_User();
            $userAnt->identify($this->_id);
            if($userAnt->_userType == 1){
                $data['user_type_id'] = 1;
            }
            $modelUser = new Application_Model_User();
            $this->_message = 'Registration ok';
            return $modelUser->update($data, $this->_id);
        }
    }

    /*
     * metodo createUser()
     *
     * @param 
     * @return 
     */

    function createUser($password) {
        $modelUser = new Application_Model_User();
        $this->_active = 1;
        $data = $this->setParamsDataBase();
        if ($modelUser->userExistEmail($data['user_mail'])) {
            $this->_message = 'the mail "' . $data['user_mail'] . '" is being used by another user';
            return false;
        }
        $data['user_create_date'] = date('Y-m-d H:i:s');
        $data['user_password'] = $this->encriptaPassword($password);
        $id = $modelUser->insert($data);
        if ($id != false) {
            $this->_id = $id;
            $this->_message = 'Registration ok';
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
        $modelUser = new Application_Model_User();
        $data = $this->identifyForIdConfirm($idConfirm);
        if ($data == false) {
            $this->_message = 'Token invalido';
            return false;
        }
        if ($this->_confirm == 0 && $this->_active == 1) {
            $this->_confirm = 1;
            $data = $this->setParamsDataBase();
            $data['user_confirm_date'] = date('Y-m-d H:i:s');
            $modelUser->update($data, $this->_id);
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
                        'userautentificate',
                        'user_login',
                        'user_password');
        $adapter->setIdentity($usuario);
        $contrasenia = $this->getPassword($usuario);
        $valueSegurity = $this->getValueSegurityPassword($contrasenia);

        $password = $valueSegurity . $this->setPassword(
                        $this->encriptaPassword($password));
        $adapter->setCredential($password);
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $data = $adapter->getResultRowObject(null, 'user_password');
            $auth->getStorage()->write($data);
            
            return TRUE;
        } else {
            $this->_message = 'Autentificate Error';
            return FALSE;
        }
    }

    function setPassword($value) {
        return substr(strrchr($value, '$$'), 1);
    }

    protected function getPassword($usuario) {
        $modelUser = new Application_Model_User();
        return $modelUser->getpassword($usuario);
    }

    function getValueSegurityPassword($value) {
        return substr($value, 0, strrpos($value, '$$')) . '$$';
    }

    function reserPassword($password) {
        $modelUser = new Application_Model_User();
        $data['user_password'] = $this->encriptaPassword($password);
        return $modelUser->update($data, $this->_id);
    }

    function passwordReset($password, $passwordTemp) {
        $modelUser = new Application_Model_User();
        $passwordTempPresent = $modelUser->getpasswordTemp($this->_id);
        if ($passwordTempPresent == '') {
            $this->_message = 'Do not have an authorization code for this action';
            return FALSE;
        }
        if ($passwordTemp == $passwordTempPresent) {
            $data['user_password'] = $this->encriptaPassword($password);
            $data['menbar_password_reset'] = '';
            $modelUser->update($data, $this->_id);
            $this->_message = 'The password is reset correctly';
            return TRUE;
        } else {
            $this->_message = 'The value of the authorization code is not correct';
            return FALSE;
        }
    }
    static function getUserType(){
         $modelUser = new Application_Model_User();
         return $modelUser->getUserType();
    }
    
    function active(){
        $modelUser = new Application_Model_User();
        $data['user_active'] = '1';
        return $modelUser->update($data, $this->_id);
    }
    function inactive(){
        $modelUser = new Application_Model_User();
        $data['user_active'] = '0';
        return $modelUser->update($data, $this->_id);
    }

}