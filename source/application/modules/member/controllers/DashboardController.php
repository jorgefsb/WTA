<?php

class Member_DashboardController extends Core_Controller_ActionMember       
{
    public function init() {
        parent::init();
    }
    
    
    private function loadOptionsMenu(){
        $_product = new Application_Entity_Product();
        $menu = array(        
                'menu_designers' => $_product->designersWithTypes(),
                'menu_collections_types' => $_product->collectionsTypesAvailables(),
                'menu_boutiques' => $_product->boutiquesAvailables(),
                'menu_limitedq' => $_product->listingLimitedQuantity()
            );
        //echo '<pre>';print_r($_product->listingLimitedQuantity());die();
        
        $this->view->menu = $menu;
        return $menu;;
    }    
    
    public function indexAction(){
        $this->loadOptionsMenu();
        
        
        $entity = new Application_Entity_Member();
        $entity->identify($this->_identity->member_id);
        $this->view->dataMember = $entity->getProperties();
       
    }
    
}

