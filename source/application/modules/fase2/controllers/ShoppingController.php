<?php

class Fase2_ShoppingController extends Core_Controller_ActionDefault
{

    public function init() {
        parent::init();

        //$this->view->isMember = Zend_Auth::getInstance()->hasIdentity();

        $this->_helper->layout->setLayout('layout-fase2');

        $this->_helper->contextSwitch()
                ->addActionContext('addtocart', 'json')
                ->addActionContext('removeitem', 'json')
                ->addActionContext('changeitem', 'json')
                ->addActionContext('countcart', 'json')
                ->addActionContext('addmembershiptocart', 'json')
                ->initContext();

        $this->view->shipping = 5;

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


    public function cartAction(){
        //$this->view->isMember = true;
        $this->_helper->layout->disableLayout();

        //$this->view->nprod = $this->_session->count;

        $this->view->cart = $this->_session->cart;

        $this->view->cartMembership = $this->_session->cartMembership;


        //print_r($this->view->cartMembership);die();

    }


    public function checkoutAction(){
        $this->view->headTitle('Checkout');
        if($this->view->isMember){
            $member = new Application_Entity_Member();
            $member->identify($this->_identity->member_id);
            $member->loadProfile();
            $shippingAddress = $member->getPropertie('_shippingAddress');
            $billingInformation = $member->getPropertie('_billingInformation');

            $this->view->member =  $this->_identity;
            $this->view->shippingAddress = $shippingAddress[0];
            $this->view->billingInformation = $billingInformation[0];
        }else{
            $this->view->shippingAddress = array();
            $this->view->billingInformation = array();
        }

        //$this->view->isMember = true;

        //$this->loadOptionsMenu();
        $this->view->cart = $this->_session->cart;
        $this->view->cartMembership = $this->_session->cartMembership;

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

    public function membershipAction(){
        $this->view->headTitle('Membership');
        if($this->view->isMember){
            $member = new Application_Entity_Member();
            $member->identify($this->_identity->member_id);
            $member->loadProfile();
            $shippingAddress = $member->getPropertie('_shippingAddress');
            $billingInformation = $member->getPropertie('_billingInformation');

            $this->view->member =  $this->_identity;
            $this->view->shippingAddress = $shippingAddress[0];
            $this->view->billingInformation = $billingInformation[0];
        }else{
            $this->view->shippingAddress = array();
            $this->view->billingInformation = array();
        }

        //$this->loadOptionsMenu();
        $this->view->cart = $this->_session->cart;

        $_regions = new Application_Model_Regions();
        $this->view->regions = $_regions->listing(array(840));

        $this->view->shipping = 5;
        $this->view->invitation = $this->_session->invitation;

        //print_r( $this->_session->cartMembership);die();

        if ( !$this->_session->cartMembership ){
            $this->_session->cartMembership = array(1);
        }

        //unset(   $this->_session->cartMembership);

        $this->view->cartMembership = $this->_session->cartMembership;

        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackData =array();
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    }

    public function checkoutcartAction(){
        //$this->view->isMember = true;
        $this->_helper->layout->disableLayout();
        //$this->loadOptionsMenu();
        $this->view->cart = $this->_session->cart;
        $this->view->cartMembership = $this->_session->cartMembership;
    }


    public function addtocartAction(){
        //$this->view->isMember = true;
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

        if( !empty($properties['sizes'])){
            $properties['size_prod'] = $properties['sizes'][0]['product_size_size_id'];
        }

        $properties['designer'] = $_designer->getProperties();

        $properties['quantity'] = 1;
        $properties['clave'] = rand(1, 100).date('s');
        $this->_session->cart[] = $properties;

        //$return = array('ok'=>true);
        //die(json_encode($return));
        $this->view->ok = 1;
        $this->view->product = $properties;

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
        //$this->view->isMember = true;
        $this->_helper->layout->disableLayout();
        //$this->_session->count++;

        $clave = $this->getRequest()->getParam('clave', 0);
        $membership = $this->getRequest()->getParam('membership', 0);

        if($clave){
            foreach($this->_session->cart as $key=>$item){
                if($item['clave'] == $clave){

                    unset($this->_session->cart[$key]);
                    break;
                }
            }
            $trackdata =array('code'=>$item['_id'], 'name'=>$item['_name']);
        }elseif($membership){
            $this->_session->cartMembership = null;
            $trackdata = '';
        }

        $this->view->ok = 1;

        /*
         * Tracking
         */
        $this->_tackName = 'REMOVEPROD';
        $this->_tackUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $this->_tackData =$trackdata;
        $this->_tackUrlRef = '';
        $this->_tackDate = '';

    }

    public function countcartAction(){
        //$this->view->isMember = true;
        $this->_helper->layout->disableLayout();
        $count = 0;
        foreach($this->_session->cart as $product){
            $product['quantity'] = $product['quantity'] ? $product['quantity'] : 1;
            $count += $product['quantity'];
        }
        $this->view->count = $count;
    }


    public function changeitemAction(){
        //$this->view->isMember = true;
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


    public function addmembershiptocartAction(){
        //$this->view->isMember = true;
        $this->_helper->layout->disableLayout();
        //$this->_session->count++;


        if ( !$this->_session->cartMembership ){
            $this->_session->cartMembership = array();
        }

        $this->_session->cartMembership = $this->getRequest()->getParams();

        $this->view->ok = 1;
        //$this->view->redirect = BASE_URL;

        /*
         * Tracking
         */
        $this->_tackName = 'ADDMEMBERSHIP2CART';
        $this->_tackUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $this->_tackUrlRef = '';
        $this->_tackDate = '';

    }
}

