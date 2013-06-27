<?php

class Fase2_BetaController extends Core_Controller_ActionDefault
{

    public function init() {
        parent::init();

        $this->_helper->layout->setLayout('layout-fase2');

        $this->_helper->contextSwitch()
                ->addActionContext('code', 'json')
                ->initContext();

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

        if( Zend_Auth::getInstance()->hasIdentity() || $this->_session->authBeta){
            $this->redirect('/');
        }
    }


    public function welcomeAction(){
        $this->_helper->layout->disableLayout();
    }


    public function codeAction(){

        $this->_helper->layout->disableLayout();

        $code = $this->getParam('password',0);


        if( 'EARLY BIRD' == strtoupper($code)){
            $this->view->ok =1;
            $this->_session->authBeta = 1;
        }else{
            $this->view->message ='Oops, there\'s something wrong with your code. Please try again.';
        }

        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        //$this->_tackData =array('code'=>$code);
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';


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

