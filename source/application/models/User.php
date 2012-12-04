<?php

class Application_Model_User extends Core_Model {

    protected $_tableUser;
    protected $_tableUserType;

    public function __construct() {
        $this->_tableUser = new Application_Model_DbTable_User();
        $this->_tableUserType = new Application_Model_DbTable_UserType();
    }
    /**
     * metodo getUser(), devuelve todos los datos de un User
     * @param $idUser    id de la User  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getUser($idUser) {
        $smt = $this->_tableUser->select()
                ->where('user_id =?', $idUser)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo getUser(), devuelve todos los datos de un User
     * @param $idUser    id de la User  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function userExistEmail($email,$id) {
        $sql = $this->_tableUser->select()
                ->from($this->_tableUser->getName(),'user_mail')
                ->where('user_mail =?', $email);
        $mailConfirm = $this->_tableUser->getAdapter()->fetchOne($sql);
        if($mailConfirm){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    /**
     * metodo getUser(), devuelve todos los datos de un User
     * @param $idUser    id de la User  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function userExistEmailOther($email,$id) {
        $sql = $this->_tableUser->select()
                ->from($this->_tableUser->getName(),'user_mail')
                ->where('user_mail =?', $email)
                ->where('user_id !=?', $id);
        $mailConfirm = $this->_tableUser->getAdapter()->fetchOne($sql);
        if($mailConfirm){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    /**
     * metodo insert(), registra los datos de la User 
     * @param array             $data   array con los datos de la User array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function listing() {
        $smt = $this->_tableUser
                ->getAdapter()
                ->select()
                ->from(array('u'=>$this->_tableUser->getName()),array(
                    'u.user_id',
                    'u.user_name',
                    'u.user_login',
                    'u.user_password',
                    'u.user_mail',
                    'u.user_active',
                    'u.user_create_date',
                    'u.user_type_id',
                    'ut.user_type_name'
                ))
                ->join(array('ut'=>$this->_tableUserType->getName()),'ut.user_type_id=u.user_type_id','')
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    /**
     * metodo insert(), registra los datos de la User 
     * @param array             $data   array con los datos de la User array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableUser->insert($data)) {
            return $this->_tableUser->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }
     /**
     * metodo update(), registra los datos de la User 
     * @param array     $data           array con los datos de la User array('column'=>'valor')
     * @param int       $idUser  id de la User
     * @return bolean   
     */

    public function update($data, $idUser) {
        if ($idUser != '') {
            $where = $this->_tableUser->getAdapter()
                    ->quoteInto('user_id =?', $idUser);
            return $this->_tableUser->update($data, $where);
        } else {
            return false;
        }
    }
    
    function getpassword($usuario){
        $sql = $this->_tableUser->select()
                ->from($this->_tableUser->getName(),
                        array('user_password'))
                ->where('user_mail = ?',$usuario);
       return $this->_tableUser->getAdapter()->fetchOne($sql);
    }
    
    function getUserType(){
        $smt = $this->_tableUserType->select()
                ->where('user_type_id != ?',1)
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    

}

