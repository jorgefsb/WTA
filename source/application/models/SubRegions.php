<?php

class Application_Model_SubRegions extends Core_Model {

    protected $_tableSubRegions;

    public function __construct() {
        $this->_tablesubRegions = new Application_Model_DbTable_SubRegions();
    }
    
    public function getSubRegion($idsubregion){        
        $smt = $this->_tablesubRegions->select()
                ->where('id =?', $idsubregion)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    
}

