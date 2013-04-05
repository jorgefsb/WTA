<?php

class Special_RegionsController extends Core_Controller_ActionDefault       
{
    
    
    public function init() {
        parent::init();
        
        $this->_helper->contextSwitch()
                ->addActionContext('states', 'json')
                ->initContext();
        
    }
    
    
    public function statesAction(){        
        
        $region = (int)$this->getRequest()->getParam('region', 0);
        
        $_regions = new Application_Model_Regions();
        
        
        $this->view->subregions = $_regions->listingSubregions($region);
        
    } 
    
        
    
}

