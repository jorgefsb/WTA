<?php

class Admin_MenbersController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        if($this->_getParam('search','')==''){
            $menbers = Application_Entity_Menber::listing();
        }else{
            $menbers = Application_Entity_Menber::searchMenber($this->_getParam('search',''));
        }
        
        $this->view->search = $this->_getParam('search','');
        $paginator = Zend_Paginator::factory($menbers);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingMenber = $paginator;
    }

    public function activeAction() {
        $Menber = new Application_Entity_Menber();
        $Menber->identify($this->getRequest()->getParam('id'));
        $Menber->active();
        $this->_redirect('/admin/menbers/');
    }

    public function inactiveAction() {
        $Menber = new Application_Entity_Menber();
        $Menber->identify($this->getRequest()->getParam('id'));
        $Menber->inactive();
        $this->_redirect('/admin/menbers/');
    }

    
}

