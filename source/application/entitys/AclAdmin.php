<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_AclAdmin extends Zend_Acl {

    static $arrayRole = array(1=>'SuperAdmin',2=>'Admin',3=>'ContentManager',0=>'Gest');
    
    public function __construct() {
        
// define Roles        
        $this->addRole(new Zend_Acl_Role('Gest')); // not authenicated
        $this->addRole(new Zend_Acl_Role('ContentManager'),'Gest'); // not authenicated
        $this->addRole(new Zend_Acl_Role('Admin'), 'ContentManager'); // authenticated as member inherit guest privilages
        $this->addRole(new Zend_Acl_Role('SuperAdmin'),'Admin'); // authenticated as admin inherit member privilages

// define Resources
        $this->add(new Zend_Acl_Resource('profiler'));
        $this->add(new Zend_Acl_Resource('logout'));
        $this->add(new Zend_Acl_Resource('index'));
        $this->add(new Zend_Acl_Resource('product'));
        $this->add(new Zend_Acl_Resource('backgrounds'));
        $this->add(new Zend_Acl_Resource('actress'));
        $this->add(new Zend_Acl_Resource('designer'));
        $this->add(new Zend_Acl_Resource('designtypes'));
        $this->add(new Zend_Acl_Resource('contents'));
        $this->add(new Zend_Acl_Resource('subscriptions'));
        $this->add(new Zend_Acl_Resource('orders'));
        $this->add(new Zend_Acl_Resource('members'));
        $this->add(new Zend_Acl_Resource('shopping-reports'));
        $this->add(new Zend_Acl_Resource('user-management'));

// assign privileges
        $this->allow('ContentManager', array('logout','index','product','actress','designer','designtypes','backgrounds','contents','subscriptions','profiler'));
        $this->allow('Admin', array('orders','members','shopping-reports','user-management'));
        $this->allow('SuperAdmin', array('orders','members','shopping-reports','user-management'));
    }

}