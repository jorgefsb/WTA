<?php

class Admin_ProductController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $paginator = Zend_Paginator::factory(Application_Entity_Product::listingProduct());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingProduct = $paginator;
    }

    public function newAction() {
        $form = new Application_Form_CreateProductFrom();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $product = new Application_Entity_Product();
                $product->setPropertie('_name', $form->getValue('name'));
                $product->setPropertie('_category', $form->getValue('category'));
                $product->setPropertie('_description', $form->getValue('description'));
                $product->setPropertie('_limitedQuantity', $form->getValue('limitedQuantity'));
                $product->setPropertie('_public', $form->getValue('public'));
                $product->setPropertie('_price', $form->getValue('price'));
                $product->createProduct();
               // echo APPLICATION_PUBLIC.'/dinamic/temp/1.jpg';
                $product->addImage(APPLICATION_PUBLIC.'/dinamic/temp/1.jpg', '1.jpg');
                $this->_flashMessenger->addMessage($product->getMessage());
                $this->_redirect('/admin/product/');
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('id'));
        $form = new Application_Form_CreateProductFrom();
        $properties = $product->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $arrayPopulate['category'] = $properties['_category'];
        $arrayPopulate['description'] = $properties['_description'];
        $arrayPopulate['limitedQuantity'] = $properties['_limitedQuantity'];
        $arrayPopulate['public'] = $properties['_public'];
        $arrayPopulate['price'] = $properties['_price'];
        $form->populate($arrayPopulate);
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $product->setPropertie('_name', $form->getValue('name'));
                $product->setPropertie('_category', $form->getValue('category'));
                $product->setPropertie('_description', $form->getValue('description'));
                $product->setPropertie('_limitedQuantity', $form->getValue('limitedQuantity'));
                $product->setPropertie('_public', $form->getValue('public'));
                $product->setPropertie('_price', $form->getValue('price'));
                $product->update();
                $this->_flashMessenger->addMessage($product->getMessage());
                $this->_redirect('/admin/product/edit/id/'.$this->getRequest()->getParam('id'));
            }
        }
        $this->view->form = $form;
    }

//    public function deleteAction(){
//        
//    }
    public function publishAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('id'));
        $product->publish();
        $this->_flashMessenger->addMessage($product->getMessage());
        $this->_redirect('/admin/product');
    }

    public function unpublishAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('id'));
        $product->unpublish();
        $this->_flashMessenger->addMessage($product->getMessage());
        $this->_redirect('/admin/product');
    }

}

