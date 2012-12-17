<?php

class Application_Model_Regions extends Core_Model {

    protected  $_tableRegions;
    protected $_tableSubRegions;

    public function __construct() {
        $this->_tableRegions = new Application_Model_DbTable_Regions();
        $this->_tablesubRegions = new Application_Model_DbTable_SubRegions();
    }
    
    public function listing($regions=array()){
        $where = '';
        if( !empty($regions)){
            $where = $this->_tableRegions->getAdapter()->quoteInto('id in (?)', $regions);
        }
        
        $select= $this->_tableRegions->select()
                ->order('country asc');
        if($where){
            $select->where($where);
        }
                
        $smt =$select->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
    public function listingSubregions($idRegion){
        $smt = $this->_tablesubRegions
                ->getAdapter()
                ->select()
                ->from(array('sr'=>$this->_tablesubRegions->getName()),
                array(
                    'sr.id',
                    'sr.name',
                    )
                )
                ->where('region_id = ?', $idRegion)
                ->order('name asc')
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    

}

