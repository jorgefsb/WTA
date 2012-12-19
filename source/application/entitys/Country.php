<?php

/**
 * entidad de lo mienbros
 *
 * @author nazart jara
 */
class Application_Entity_Country extends Core_Entity {

    
    static function listingCountry(){
        $modelCountry = new Application_Model_Country();
        return $modelCountry->listingCountry();
    }
}