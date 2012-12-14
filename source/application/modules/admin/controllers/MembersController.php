<?php

class Admin_MembersController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        if($this->_getParam('search','')==''){
            $members = Application_Entity_Member::listing();
        }else{
            $members = Application_Entity_Member::searchMember($this->_getParam('search',''));
        }
        
        $this->view->search = $this->_getParam('search','');
        $paginator = Zend_Paginator::factory($members);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingMember = $paginator;
    }

    public function activeAction() {
        $Member = new Application_Entity_Member();
        $Member->identify($this->getRequest()->getParam('id'));
        $Member->active();
        $this->_redirect('/admin/members/');
    }

    public function inactiveAction() {
        $Member = new Application_Entity_Member();
        $Member->identify($this->getRequest()->getParam('id'));
        $Member->inactive();
        $this->_redirect('/admin/members/');
    }

    
}

