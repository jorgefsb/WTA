<?php

/**
 * entidad de las suscripciones
 *
 * @author camacho
 */
class Application_Entity_SubscriptionMail extends Core_Entity {

    protected $_id;
    protected $_email;
    

    /**
     * __Construct         
     *
     */
    function __construct() {
        
    }

    /*
     * metodo setParamsDataBase()
     *
     * @param 
     * @return array
     */

    function setParamsDataBase() {
        $data['id'] = $this->_id;
        $data['email'] = $this->_email;
        
        return $this->cleanArray($data);
    }


    /*
     * metodo createSubscription()
     *
     * @param 
     * @return 
     */

    function createSubscription() {
        $modelSubs = new Application_Model_SubscriptionMail();
        $data = $this->setParamsDataBase();
        $id = $modelSubs->insert($data);
        if ($id != false) {
            $this->_message = 'satisfactory record';
            return true;
        } else {
            $this->_message = 'Registration to failure';
            false;
        }
    }

    static function listingSubscription() {
        $modelSubs = new Application_Model_SubscriptionMail();
        return $modelSubs->listing();
    }

    

}