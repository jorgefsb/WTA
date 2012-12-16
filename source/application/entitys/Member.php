<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Member extends Core_Entity {

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
        $this->_id = $data['member_id'];
        $this->_name = $data['member_name'];
        $this->_lastName = $data['member_last_name'];
        $this->_mail = $data['member_mail'];
        $this->_linkConfirm = $data['member_link_confirm'];
        $this->_idConfirm = $data['member_id_confirm'];
        $this->_active = $data['member_active'];
        $this->_confirm = $data['member_confirm'];
        $this->_avatar = $data['member_avatar'];
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idMember
     * @return void
     */

    function identify($idMember) {
        $modelMember = new Application_Model_Member();
        $data = $modelMember->getMember($idMember);
        if ($data != '') {
            $this->asocParams($data);
        }
    }

    /*
     * metodo identify(), obtiene los datos de un mienbro
     *
     * @param $idMember
     * @return void
     */

    private function identifyForIdConfirm($idConfirm) {
        $modelMember = new Application_Model_Member();
        $data = $modelMember->getMemberForIdConfirm($idConfirm);
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
        $data['member_id'] = $this->_id;
        $data['member_name'] = $this->_name;
        $data['member_last_name'] = $this->_lastName;
        $data['member_mail'] = $this->_mail;
        $data['member_link_confirm'] = $this->_linkConfirm;
        $data['member_id_confirm'] = $this->_idConfirm;
        $data['member_active'] = $this->_active;
        $data['member_confirm'] = $this->_confirm;
        $data['member_avatar'] = $this->_avatar;
        return $this->cleanArray($data);
    }

    function update() {
        $modelMember = new Application_Model_Member();

        if ($modelMember->update($this->setParamsDataBase(), $this->_id) !== FALSE) {
            $this->_message = 'Registration was successful';
            return true;
        } else {
            $this->_message = 'registration fails';
            return false;
        }
    }

    /*
     * metodo createMember()
     *
     * @param 
     * @return 
     */

    function createMember($password) {
        $modelMember = new Application_Model_Member();
        $this->_active = 1;
        $this->_confirm = 0;
        $data = $this->setParamsDataBase();
        $data['member_create_date'] = date('Y-m-d H:i:s');
        $data['member_id_confirm'] = $this->createIdConfirm();
        $data['member_password'] = $this->encriptaPassword($password);
        $id = $modelMember->insert($data);
        if ($id != FALSE) {
            $this->_id = $id;
            $this->setMailAdmin();
            $this->setMailMemberConfirmAccount($data['member_id_confirm']);
            $this->_message = 'Registration was successful, 
                    we will send an email to ' .
                    $this->_mail .
                    ' to confirm your account';
            return TRUE;
        } else {
            $this->_message = 'Registration to failure';
            return FALSE;
        }
    }

    /*
     * metodo confirmAccount()
     *
     * @param $idConfirm
     * @return 
     */

    function confirmAccount($idConfirm) {
        $modelMember = new Application_Model_Member();
        $data = $this->identifyForIdConfirm($idConfirm);
        if ($data == false) {
            $this->_message = 'Token invalido';
            return false;
        }
        if ($this->_confirm == 0 && $this->_active == 1) {
            $this->_confirm = 1;
            $data = $this->setParamsDataBase();
            $data['member_confirm_date'] = date('Y-m-d H:i:s');
            $modelMember->update($data, $this->_id);
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
     * metodo setMailMemberConfirmAccount(), envia correo al usuario para la confirmacion de su cuenta
     *
     * @param $idConfirm, codigo autogenerado para la confirmacion de la cuenta
     * @return void
     */

    function setMailMemberConfirmAccount($idConfirm) {
        $mail = new Core_Mail();
        $mail->addDestinatario($this->_mail);
        $mail->setAsunto('confirmar cuenta');
        $mensaje = '<b>Hello ' . $this->_name . '</b><p>';
        $mensaje.= 'need to confirm your account</p>';
        $mensaje.= 'click the following ';
        $mensaje.= '<a href="' . BASE_URL . '/member/create-account/confirm?id=' . $idConfirm . '">link</a>';
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
                        'memberautentificate',
                        'member_mail',
                        'member_password');
        $adapter->setIdentity($usuario);
        $contrasenia = $this->getPassword($usuario);
        $valueSegurity = $this->getValueSegurityPassword($contrasenia);

        $password = $valueSegurity . $this->setPassword(
                        $this->encriptaPassword($password));
        $adapter->setCredential($password);
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $data = $adapter->getResultRowObject(null, 'member_password');
            $auth->getStorage()->write($data);
            $this->_message = 'Successful Authentication';
            return TRUE;
        } else {
            $this->_message = 'Incorrect Authentication';
            return FALSE;
        }
    }

    function setPassword($value) {
        return substr(strrchr($value, '$$'), 1);
    }

    protected function getPassword($mail) {
        $modelMember = new Application_Model_Member();
        return $modelMember->getpassword($mail);
    }

    function getValueSegurityPassword($value) {
        return substr($value, 0, strrpos($value, '$$')) . '$$';
    }

    function passwordReset($password) {
        $modelMember = new Application_Model_Member();
        $data['member_password'] = $this->encriptaPassword($password);
        $modelMember->update($data, $this->_id);
    }

    function sendPasswordRecovery($mail) {
        $modelMember = new Application_Model_Member();
        $passwordTemp = $this->generaPasswordTemp();
        $data['member_password_reset'] = $passwordTemp;
        if ($modelMember->insertPasswordReset($passwordTemp, $mail)) {
            $objMail = new Core_Mail();
            $objMail->addDestinatario($mail);
            $objMail->setAsunto('Password Recovery');
            $mensaje = '<b>Password Recovery</b> <p>';
            $mensaje.= '<a href="' . BASE_URL . '/member/recovery-account/confirm?id=' . $passwordTemp . '">link</a>';
            $objMail->setMensaje($mensaje);
            $objMail->send();
            return true;
        } else {
            $this->_message = 'Email Not Found';
            return false;
        }
    }

    function passwordRecovery($token, $password) {
        $modelMember = new Application_Model_Member();
        if ($this->confirmTokenRecoveryAccount($token)) {
            $data['member_password'] = $this->encriptaPassword($password);
            $data['member_password_reset'] = Null;
            $modelMember->update($data, $this->_id);
            $this->_message = 'The password is reset correctly';
            return TRUE;
        } else {
            $this->_message = 'Registration fails';
            return FALSE;
        }
    }

    function confirmTokenRecoveryAccount($token) {
        $modelMember = new Application_Model_Member();
        if ($id = $modelMember->isTokenPasswordReset($token)) {
            $this->identify($id);
            return true;
        } else {
            return false;
        }
    }

    static function listing() {
        $modelMember = new Application_Model_Member();
        return $modelMember->getMembers();
    }

    static function searchMember($value) {
        $modelMember = new Application_Model_Member();
        return $modelMember->searchMember($value);
    }

    function active() {
        $modelMember = new Application_Model_Member();
        $data['member_active'] = '1';
        return $modelMember->update($data, $this->_id);
    }

    function inactive() {
        $modelMember = new Application_Model_Member();
        $data['member_active'] = '0';
        return $modelMember->update($data, $this->_id);
    }

    function addMembership() {
        $membership = new Application_Entity_Membership();
        $dataMembership = $membership->getMembershipActive();
        $transacction = new Application_Entity_Transaction();
        $transacction->setPropertie('_member', $this->_id);
        if ($dataMembership['membership_isfree'] == 1) {
            $transacction->setPropertie('_state', Application_Entity_Transaction::TRANSACTION_PAID);
            $transacction->setPropertie('_amount', '0');
        } else {
            $transacction->setPropertie('_state', Application_Entity_Transaction::TRANSACTION_OUTSTANDING);
            $transacction->setPropertie('_amount', $dataMembership['membership_price']);
        }
        $transacction->createTransaction();
        $membership->setPropertie('_membershipId', $dataMembership['membership_id']);
        $membership->setPropertie('_price', $dataMembership['membership_price']);
        $membership->setPropertie('_memberId', $this->_id);
        $membership->setPropertie('_isfree', $dataMembership['membership_isfree']);
        $membership->insert();
        if ($dataMembership['membership_isfree'] == 1) {
            $modelMember = new Application_Model_Member();
            $data['member_membership_free'] = '1';
            $modelMember->update($data, $this->_id);
        }
        $transacction->addMembership($membership);
    }

}