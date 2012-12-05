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
                $product->setPropertie('_description', $form->getValue('description'));
                $product->setPropertie('_descriptionDesigner', $form->getValue('descriptionDesigner'));
                $product->setPropertie('_designer', $form->getValue('designer'));
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
        $arrayPopulate['description'] = $properties['_description'];
        $arrayPopulate['descriptionDesigner'] = $properties['_descriptionDesigner'];
        $arrayPopulate['designer'] = $properties['_designer'];
        $arrayPopulate['limitedQuantity'] = $properties['_limitedQuantity'];
        $arrayPopulate['public'] = $properties['_public'];
        $arrayPopulate['price'] = $properties['_price'];
        $form->populate($arrayPopulate);
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $product->setPropertie('_name', $form->getValue('name'));
                $product->setPropertie('_description', $form->getValue('description'));
                $product->setPropertie('_descriptionDesigner', $form->getValue('descriptionDesigner'));
                $product->setPropertie('_designer', $form->getValue('designer'));
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
    public function upAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('id'));
        $product->upOrder();
        $this->_flashMessenger->addMessage($product->getMessage());
        $this->_redirect('/admin/product');
    }
    

    public function downAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('id'));
        $product->downOrder();
        $this->_flashMessenger->addMessage($product->getMessage());
        $this->_redirect('/admin/product');
    }
    
    public function celebrityAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $this->view->product = $product->getPropertie('_id');
        $this->view->name = $product->getPropertie('_name');
        $paginator = Zend_Paginator::factory($product->listingActrees());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingActrees = $paginator;
        
    }
    public function addCelebrityAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $form = new Application_Form_CreateProductCelebrityFrom();
        
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $product->addActress(
                        $form->getValue('actress'), 
                        '', 
                        $form->getValue('commission'), 
                        $form->getValue('active')
                        );
                $this->_flashMessenger->addMessage($product->getMessage());
                $this->_redirect('/admin/product/celebrity/product/'.$product->getPropertie('_id'));
            }
        }
        $this->view->form = $form;
    }
    public function editCelebrityAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $productActress = $product->getProductActress($this->getRequest()->getParam('celebrity'));
        $actress = new Application_Entity_Actress();
        $actress->identify($this->getRequest()->getParam('celebrity'));
        $form = new Application_Form_EditProductCelebrityFrom();
        $dataForm['nameActress']=$actress->getPropertie('_name');
        $dataForm['actress']=$actress->getPropertie('_id');
        $dataForm['commission']=$productActress['product_actress_commission'];
        $dataForm['active']=$productActress['product_actress_active'];
        $form->populate($dataForm);
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $product->addActress(
                        $form->getValue('actress'), 
                        '', 
                        $form->getValue('commission'), 
                        $form->getValue('active')
                        );
                $this->_flashMessenger->addMessage($product->getMessage());
                $this->_redirect('/admin/product/celebrity/product/'.$product->getPropertie('_id'));
            }
        }
        $this->view->form = $form;
    }
    
    public function publishCelebrityAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $product->publishCelebrity($this->getRequest()->getParam('id'));
        $this->_flashMessenger->addMessage($product->getMessage());
        $this->_redirect('/admin/product/celebrity/product/'.$product->getPropertie('_id'));
    }

    public function unpublishCelebrityAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $product->unpublishCelebrity($this->getRequest()->getParam('id'));
        $this->_flashMessenger->addMessage($product->getMessage());
        $this->_redirect('/admin/product/celebrity/product/'.$product->getPropertie('_id'));
    }
    

}

