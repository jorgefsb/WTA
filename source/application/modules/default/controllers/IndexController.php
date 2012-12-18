<?php

class Default_IndexController extends Core_Controller_ActionDefault       
{
    private $_tackName = '';
    private $_tackUrl = '';
    private $_tackData = '';
    private $_tackUrlRef = '';
    private $_tackDate = '';
    
    
    public function init() {
        parent::init();
        
        $action = $this->_getParam('action','');
        if( Zend_Auth::getInstance()->hasIdentity() && $action !='signin' && 'forgotpass'){
            //$this->redirect('/beta');
        }
        
        $this->_helper->contextSwitch()
                ->addActionContext('addtocart', 'json')
                ->addActionContext('removeitem', 'json')
                ->addActionContext('changeitem', 'json')                
                ->addActionContext('countcart', 'json')
                ->initContext();
//        print_r($_SERVER);die($_SERVER['REQUEST_URI']);
        
        
        if( !isset($this->_session->_tracking) ){
            $this->_session->_tracking = new Core_Tracking();
        }
        
        
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
    
    public function signoutAction(){
        Zend_Auth::getInstance()->clearIdentity();
        $this->redirect('/');
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
    
    public function giftAction(){
        $this->loadOptionsMenu();
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }
    
    public function cartAction(){
        $this->_helper->layout->disableLayout();
        
        //$this->view->nprod = $this->_session->count;    
        
        $this->view->cart = $this->_session->cart;    
        
        //print_r($this->view->cart);die();
        
    }
    
     public function resetAction(){        
        $this->_session->cart = array();
        $this->_session->_tracking->clear();
    }
    
    
    public function checkoutAction(){
        $this->loadOptionsMenu();
        $this->view->cart = $this->_session->cart;
        
        $_regions = new Application_Model_Regions();
        $this->view->regions = $_regions->listing(array(840));
        
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackData =array();
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }
    
    
    public function checkoutcartAction(){
        $this->_helper->layout->disableLayout();
        //$this->loadOptionsMenu();
        $this->view->cart = $this->_session->cart;
        $this->view->cart = $this->_session->cart;
        
    }
    
    
    public function addtocartAction(){
        $this->_helper->layout->disableLayout();
        //$this->_session->count++;
        
        $code = $this->getRequest()->getParam('code', 0);
        
        
        if ( !$this->_session->cart ){
            $this->_session->cart = array();        
        }
        
        $_product = new Application_Entity_Product();
        $_product->identify($code);
        
        $properties = $_product->getProperties();
        
        $_designer = new Application_Entity_Designer();
        $_designer->identify($properties['_designer']);
        $properties['images'] = $_product->listingImg();
        $properties['sizes'] = $_product->getSize();
        $properties['designer'] = $_designer->getProperties();
        
        $properties['quantity'] = 1;        
        $properties['clave'] = rand(1, 100).date('s');
        $this->_session->cart[] = $properties;
        
        //$return = array('ok'=>true);
        //die(json_encode($return));
        $this->view->ok = 1;
        
        /*
         * Tracking
         */
        $this->_tackName = 'ADD2CART';
        $this->_tackUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $this->_tackData =array('code'=>$code, 'name'=>$properties['_name']);
        $this->_tackUrlRef = '';
        $this->_tackDate = '';
        
    }
    
    public function removeitemAction(){ 
        $this->_helper->layout->disableLayout();
        //$this->_session->count++;
        
        $clave = $this->getRequest()->getParam('clave', 0);
        
        foreach($this->_session->cart as $key=>$item){
            if($item['clave'] == $clave){
                
                unset($this->_session->cart[$key]);
                break;
            }
        }
        
        $this->view->ok = 1;
        
        /*
         * Tracking
         */
        $this->_tackName = 'REMOVEPROD';
        $this->_tackUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $this->_tackData =array('code'=>$item['_id'], 'name'=>$item['_name']);
        $this->_tackUrlRef = '';
        $this->_tackDate = '';
        
    }
    
    public function countcartAction(){
        $this->_helper->layout->disableLayout();
        $this->view->count = count($this->_session->cart);
    }
    
    
    public function changeitemAction(){
        $this->_helper->layout->disableLayout();
        //$this->_session->count++;
        
        $clave = $this->getRequest()->getParam('clave', 0);
        $quantity = (int)$this->getRequest()->getParam('quantity', 0);
        $size = (int)$this->getRequest()->getParam('size', 0);
        //print_r($this->getRequest()->getParams());die();
        
        if($quantity || $size){

            foreach($this->_session->cart as $key=>$item){
                if($item['clave'] == $clave){
                    
                    if($quantity){
                        $this->_session->cart[$key]['quantity'] = $quantity;
                    }else{
                        $this->_session->cart[$key]['size_prod'] = $size;
                    }
                    break;
                }
            }            

            $this->view->ok = 1;

            /*
            * Tracking
            */
            $this->_tackName = 'CHANGEPROD';
            $this->_tackUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $this->_tackData =array('code'=>$item['_id'], 'name'=>$item['_name'], 'field'=>($quantity ? 'quantity' : ($size ? 'size' : '')));
            $this->_tackUrlRef = '';
            $this->_tackDate = '';
        }
        
    }
    
    
    
    public function aboutusindividualAction(){
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }
    
    
    public function smallaboutAction(){
        $this->_helper->layout->disableLayout();        
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }
    
    
    public function contactusAction(){
        $this->_helper->layout->disableLayout();        
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }
    
    public function comingSoonAction(){
        $this->_helper->layout->disableLayout();        
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }
    
    public function signinAction(){
        $this->_helper->layout->disableLayout();  
                
    }
    
    public function forgotpassAction(){
        $this->_helper->layout->disableLayout();  
    }
    
    public function termsAction(){

        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }    
    
    public function rewardingMembersAction(){

        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }
    
    public function helpAction(){

        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }
        
    public function trackingAction(){
        Zend_Debug::dump($this->_session->_tracking->getLog());
        
    }
    
    
    public function postDispatch() {
        if($this->_tackName && $this->_tackUrl){
            $this->_session->_tracking->setAction($this->_tackName,
                                                                            $this->_tackUrl,
                                                                            $this->_tackData,
                                                                            $this->_tackUrlRef,
                                                                            $this->_tackDate
                                                                        );
        }
        //Zend_Debug::dump($this->_session->_tracking);
    }
    
    
}

