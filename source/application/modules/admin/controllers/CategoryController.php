<?php

class Admin_CategoryController extends Core_Controller_ActionAdmin
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    {                   
        $paginator = Zend_Paginator::factory(Application_Entity_Category::listingCategory());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingCategory  = $paginator;
    } 
    public function newAction(){
        $form = new Application_Form_CreateCategoryFrom();
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $category = new Application_Entity_Category();
                $category->setPropertie('_name', $form->getValue('name'));
                $category->setPropertie('_description', $form->getValue('description'));
                $category->setPropertie('_public', $form->getValue('public'));
                $category->createCategory();
                $this->_flashMessenger->addMessage($category->getMessage());
                $this->_redirect('/admin/category/');
            }
        }
        $this->view->form = $form;
    }
    public function editAction(){
        $category = new Application_Entity_Category();
        $category->identify($this->getRequest()->getParam('id'));
        $form = new Application_Form_CreateCategoryFrom();
        $form->setAction('/admin/category/edit/id/'.$this->getRequest()->getParam('id'));
        $properties = $category->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $arrayPopulate['description'] = $properties['_description'];
        $arrayPopulate['public'] = $properties['_public'];
        $form->populate($arrayPopulate);
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $category->setPropertie('_name', $form->getValue('name'));
                $category->setPropertie('_description', $form->getValue('description'));
                $category->setPropertie('_public', $form->getValue('public'));
                $category->update();
                $this->_flashMessenger->addMessage($category->getMessage());
                $this->_redirect('/admin/category/edit/id/'.$this->getRequest()->getParam('id'));
            }
        }
        $this->view->form = $form;
    }
    
    public function publishAction(){
        $category = new Application_Entity_Category();
        $category->identify($this->getRequest()->getParam('id'));
        $category->publish();
        $this->_flashMessenger->addMessage($category->getMessage());
        $this->_redirect('/admin/category');
    }
    public function unpublishAction(){
        $category = new Application_Entity_Category();
        $category->identify($this->getRequest()->getParam('id'));
        $category->unpublish();
        $this->_flashMessenger->addMessage($category->getMessage());
        $this->_redirect('/admin/category');
    }
    
    
}

