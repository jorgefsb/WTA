<?php

class Default_IndexController extends Core_Controller_ActionDefault       
{
    
    public function init() {
        parent::init();
        
        $this->view->isMember = Zend_Auth::getInstance()->hasIdentity();
        
        //$action = $this->_getParam('action','');
        
        if( !Zend_Auth::getInstance()->hasIdentity() && !$this->_session->authBeta){
            $this->redirect('/beta');
        }
        
        $this->_helper->contextSwitch()
                ->addActionContext('addtocart', 'json')
                ->addActionContext('removeitem', 'json')
                ->addActionContext('changeitem', 'json')                
                ->addActionContext('countcart', 'json')
                ->initContext();
//        print_r($_SERVER);die($_SERVER['REQUEST_URI']);
        
                
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
        
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    } 
    
    
    public function designersAction(){
        $menu = $this->loadOptionsMenu();
        
        $menu_designers = $menu['menu_designers'];    
    
        $categorys = array();
        foreach($menu_designers as $designer){
            foreach($designer as $type){    
                if( !in_array($type, $categorys)){
                    $categorys[] = $type;
                }
            }
        }
        
        $category_active = $this->getParam('type', '');        // Obtenemos la categoria de la URL        
        sort($categorys);
        $this->view->categorys = $categorys;        // Contiene las opciones para escoger categoria        
        if( !$category_active ){
            $category_active = $categorys[0];
        }
        $this->view->category_active = $category_active;        
        $_product = new Application_Entity_Product();
        $this->view->sliderProducts = $_product->getProductsByDesignType($category_active);
        
        $product_active = $this->getParam('product', '');  // Obtenemos el producto de la URL
        $id_prod = preg_replace('/^.*-(\d+).*$/', '$1', $product_active);
        
        if( !$id_prod ){
            $id_prod = $this->view->sliderProducts[0]['product_id'];
        }
        
        $_product->identify($id_prod);
        
        $properties = $_product->getProperties();
        $properties['images'] = $_product->listingImg();
        $_designer = new Application_Entity_Designer();
        $_designer->identify($properties['_designer']);
        
        $properties['designer'] = $_designer->getProperties();
                
        $this->view->product = $properties;
        $this->view->urlBase = '/designers/';
                
        
        /*
         * Tracking
         */
        $this->_tackName = 'PRODUCT';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackData =array('product'=>$product_active, 'code'=>$properties['_id'], 'name'=>$properties['_name']);
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    } 
    
    
    public function itemlistAction(){
        
        $menu = $this->loadOptionsMenu();
        
        $menu_collections = $menu['menu_collections_types'];    
        
        $categorys = array();
        foreach($menu_collections as $collection){
                $categorys[] = $collection['collection_type_name'];
        }

        $category_active = $this->getParam('type', '');        // Obtenemos la categoria de la URL        
        sort($categorys);
        $this->view->categorys = $categorys;        // Contiene las opciones para escoger categoria        
        if( !$category_active ){
            $category_active = $categorys[0];
        }
        $this->view->category_active = $category_active;        
        $_product = new Application_Entity_Product();
        $this->view->sliderProducts = $_product->getProductsByCollectionType($category_active);
                        
        $this->view->urlBase = '/exclusive-collections/';
                
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackData =array('category'=>$category_active);
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }        
    
    
    public function exclusiveAction(){
        
        $menu = $this->loadOptionsMenu();
        
        $menu_collections = $menu['menu_collections_types'];    
        
        $categorys = array();
        foreach($menu_collections as $collection){
                $categorys[] = $collection['collection_type_name'];
        }

        $category_active = $this->getParam('type', '');        // Obtenemos la categoria de la URL        
        sort($categorys);
        $this->view->categorys = $categorys;        // Contiene las opciones para escoger categoria        
        if( !$category_active ){
            $category_active = $categorys[0];
        }
        $this->view->category_active = $category_active;        
        $_product = new Application_Entity_Product();
        $this->view->sliderProducts = $_product->getProductsByCollectionType($category_active);
        
        
        $product_active = $this->getParam('product', '');  // Obtenemos el producto de la URL
        $id_prod = preg_replace('/^.*-(\d+).*$/', '$1', $product_active);
        
        if( !$id_prod ){
            $id_prod = $this->view->sliderProducts[0]['product_id'];
        }
        
        $_product->identify($id_prod);
        
        $properties = $_product->getProperties();
        $properties['images'] = $_product->listingImg();
        $_designer = new Application_Entity_Designer();
        $_designer->identify($properties['_designer']);
        
        $properties['designer'] = $_designer->getProperties();
                
        $this->view->product = $properties;
        
        
        // Obtenemos el prev y next
        $prev = null;
        $next = null;
        $length = count($this->view->sliderProducts);
        foreach($this->view->sliderProducts as $key=>$product){
            if( $product['product_id'] == $id_prod){
                if($key>0){
                    $prev = urlencode(strtolower($this->view->sliderProducts[$key-1]['collection_type_name'])).'/'.$this->view->sliderProducts[$key-1]['product_slug'];
                }elseif( $length > 1){
                    $prev = urlencode(strtolower($this->view->sliderProducts[$length-1]['collection_type_name'])).'/'.$this->view->sliderProducts[$length-1]['product_slug'];
                }
                
                if($key<($length-2)){
                    $next = urlencode(strtolower($this->view->sliderProducts[$key+1]['collection_type_name'])).'/'.$this->view->sliderProducts[$key+1]['product_slug'];
                }elseif( $length > 1){
                    $next = urlencode(strtolower($this->view->sliderProducts[0]['collection_type_name'])).'/'.$this->view->sliderProducts[0]['product_slug'];
                }
                
                break;
            }
        }
        
        $this->view->prev = $prev;
        $this->view->next = $next;
        $this->view->urlBase = '/exclusive-collections/';
        
        
        /*
         * Tracking
         */
        $this->_tackName = 'PRODUCT';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackData =array('product'=>$product_active, 'code'=>$properties['_id'], 'name'=>$properties['_name']);
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }        
    
    
    public function boutiqueAction(){
        $this->loadOptionsMenu();
        
        $celebrity = $this->getParam('celebrity', '');
        
        $_product = new Application_Entity_Product();
        
        $_actress = new Application_Entity_Actress();
        $_actress->identifyByName(preg_replace('/-+/', ' ', $celebrity));        
                
        $this->view->sliderProducts = $_actress->getProductsSimple();
        
        
        $product_active = $this->getParam('product', '');  // Obtenemos el producto de la URL
        $id_prod = preg_replace('/^.*-(\d+).*$/', '$1', $product_active);
        
        if( !$id_prod ){
            $id_prod = $this->view->sliderProducts[0]['product_id'];
        }
        
        $_product->identify($id_prod);
        
        $properties = $_product->getProperties();
        $properties['images'] = $_product->listingImg();
        $properties['celebrity'] = $_product->getProductActress($_actress->getPropertie('_id'));
        //print_r( $_product->getProductActress($_actress->getPropertie('_id')) );die();
        $_designer = new Application_Entity_Designer();
        $_designer->identify($properties['_designer']);
        
        $properties['designer'] = $_designer->getProperties();
        $properties['actress'] = $_actress->getProperties();
                
        $this->view->product = $properties;
        
        
                
        $this->view->urlBase = '/boutique/'.preg_replace('/\s+/', '-',trim($properties['actress']['_name'])).'/';
        
        
        /*
         * Tracking
         */
        $this->_tackName = 'PRODUCT';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackData =array('product'=>$product_active, 'code'=>$properties['_id'], 'name'=>$properties['_name']);
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }
    
    
    public function celebritysAction(){
        $this->loadOptionsMenu();
        
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];        
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }
    
    public function limitedAction(){
        
        $this->loadOptionsMenu();
             
        $_product = new Application_Entity_Product();
        
        $this->view->sliderProducts = $_product->getProductsLimitedQuantity();
        
        
        $product_active = $this->getParam('product', '');  // Obtenemos el producto de la URL
        $id_prod = preg_replace('/^.*-(\d+).*$/', '$1', $product_active);
        
        if( !$id_prod ){
            $id_prod = $this->view->sliderProducts[0]['product_id'];
        }
        
        $_product->identify($id_prod);
        
        $properties = $_product->getProperties();
        $properties['images'] = $_product->listingImg();
        $_designer = new Application_Entity_Designer();
        $_designer->identify($properties['_designer']);
        
        $properties['designer'] = $_designer->getProperties();
                
        $this->view->product = $properties;
        
        // Obtenemos el prev y next
        $prev = null;
        $next = null;
        $length = count($this->view->sliderProducts);
        foreach($this->view->sliderProducts as $key=>$product){
            if( $product['product_id'] == $id_prod){
                if($key>0){
                    $prev = $this->view->sliderProducts[$key-1]['product_slug'];
                }elseif( $length > 1){
                    $prev = $this->view->sliderProducts[$length-1]['product_slug'];
                }
                
                if($key<($length-2)){
                    $next = $this->view->sliderProducts[$key+1]['product_slug'];
                }elseif( $length > 1){
                    $next = $this->view->sliderProducts[0]['product_slug'];
                }
                
                break;
            }
        }
        
        $this->view->prev = $prev;
        $this->view->next = $next;
        $this->view->urlBase = '/limited-quantity/';
        
        
        /*
         * Tracking
         */
        $this->_tackName = 'PRODUCT';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackData =array('product'=>$product_active, 'code'=>$properties['_id'], 'name'=>$properties['_name']);
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
        
    }
    
    
    
    public function limitedcountAction(){
        $this->_helper->layout->disableLayout();
        $_product = new Application_Entity_Product();
        
        $this->view->sliderProducts = $_product->getProductsLimitedQuantity();
        
        
        $idProduct = $this->getParam('product', '');  // Obtenemos el producto de la URL        
                
        $_product->identify((int)$idProduct);
        $this->view->product = $_product->getProperties();
    }
    
    
    
     public function resetAction(){        
        $this->_session->cart = array();
        $this->_session->_tracking->clear();
        $this->_session->authBeta = 0;
    }
    
    
        
        
    public function trackingAction(){
        Zend_Debug::dump($this->_session->_tracking->getLog());
        
    }
       
    
}

