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
        
$this->view->headScript()->appendScript(
"
        if($('input[name=limitedQuantity]').is(':checked')){
            $('input[name=cantLimitedQuantity]').removeAttr('disabled');
            $('input[name=cantLimitedQuantity]').attr('disabled', 'disabled');
        }
        $('input[name=limitedQuantity]').click(function() {  
            if($('input[name=limitedQuantity]').is(':checked')){
                $('input[name=cantLimitedQuantity]').removeAttr('disabled');
            } else {
                $('input[name=cantLimitedQuantity]').attr('value', '');
                $('input[name=cantLimitedQuantity]').attr('disabled', 'disabled');
            }
        });      
"
);
        $form = new Application_Form_CreateProductFrom();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $product = new Application_Entity_Product();
                $product->setPropertie('_name', $form->getValue('name'));
                $product->setPropertie('_code', $form->getValue('code'));
                $product->setPropertie('_description', $form->getValue('description'));
                $product->setPropertie('_descriptionDesigner', $form->getValue('descriptionDesigner'));
                $product->setPropertie('_designer', $form->getValue('designer'));
                $product->setPropertie('_limitedQuantity', $form->getValue('limitedQuantity'));
                $product->setPropertie('_cantLimitedQuantity', $form->getValue('cantLimitedQuantity'));
                $product->setPropertie('_public', $form->getValue('public'));
                $product->setPropertie('_price', $form->getValue('price'));
                $product->setPropertie('_designType', $form->getValue('designType'));
                $product->setPropertie('_collectionType', $form->getValue('collectionType'));
                $product->setPropertie('_priceMenber', $form->getValue('priceMenber'));
                $product->createProduct();
                foreach ($form->getValue('size') as $index) {
                    $product->addSize($index);
                }
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
        $arrayPopulate['code'] = $properties['_code'];
        $arrayPopulate['description'] = $properties['_description'];
        $arrayPopulate['descriptionDesigner'] = $properties['_descriptionDesigner'];
        $arrayPopulate['designer'] = $properties['_designer'];
        $arrayPopulate['limitedQuantity'] = $properties['_limitedQuantity'];
        $arrayPopulate['cantLimitedQuantity'] = $properties['_cantLimitedQuantity'];
        $arrayPopulate['public'] = $properties['_public'];
        $arrayPopulate['price'] = $properties['_price'];
        $arrayPopulate['collectionType'] = $properties['_collectionType'];
        $arrayPopulate['priceMenber'] = $properties['_priceMenber'];
        $arrayPopulate['designType'] = $properties['_designType'];
        $arrayPopulate['size'] = array_keys(Core_Utils::fetchPairs($product->getSize()));

        $form->populate($arrayPopulate);
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $product->setPropertie('_name', $form->getValue('name'));
                $product->setPropertie('_code', $form->getValue('code'));
                $product->setPropertie('_description', $form->getValue('description'));
                $product->setPropertie('_descriptionDesigner', $form->getValue('descriptionDesigner'));
                $product->setPropertie('_designer', $form->getValue('designer'));
                $product->setPropertie('_limitedQuantity', $form->getValue('limitedQuantity'));
                $product->setPropertie('_cantLimitedQuantity', $form->getValue('cantLimitedQuantity'));
                $product->setPropertie('_public', $form->getValue('public'));
                $product->setPropertie('_price', $form->getValue('price'));
                $product->setPropertie('_priceMenber', $form->getValue('priceMenber'));
                $product->setPropertie('_designType', $form->getValue('designType'));
                $product->setPropertie('_collectionType', $form->getValue('collectionType'));
                $product->update();
                $sizesProduct = $product->getSize();
                $valueSizes = $form->getValue('size');
                $eliminar = array();
                foreach ($sizesProduct as $index) {
                    if (!in_array($index['product_size_size_id'], $valueSizes)) {
                        $eliminar[] = $index['product_size_size_id'];
                        unset($valueSizes[array_search($index['product_size_size_id'], $valueSizes)]);
                    }
                }
                if (!empty($eliminar)) {
                    foreach ($eliminar as $index) {
                        $product->deleteSize($index);
                    }
                }
                if (!empty($valueSizes)) {
                    foreach ($valueSizes as $index) {
                        $product->addSize($index);
                    }
                }
                $this->_flashMessenger->addMessage($product->getMessage());
                $this->_redirect('/admin/product/edit/id/' . $this->getRequest()->getParam('id'));
            }
        }
        $this->view->form = $form;
        
        if ($product->getPropertie('_cantBuy')>0) {
        $scriptCantLimit = "$('#cantLimitedQuantity-element').
            append('<p><strong>Amount Buy: ".$product->getPropertie('_cantBuy')." </strong></p>');";
        }else{
            $scriptCantLimit='';
        }
         $this->view->headScript()->appendScript(
                 
"
        if($('input[name=limitedQuantity]').is(':checked')){
            $('input[name=cantLimitedQuantity]').removeAttr('disabled');
            
        }
        $('input[name=limitedQuantity]').click(function() {  
            if($('input[name=limitedQuantity]').is(':checked')){
                $('input[name=cantLimitedQuantity]').removeAttr('disabled');
            } else {
                $('input[name=cantLimitedQuantity]').attr('value', '');
                $('input[name=cantLimitedQuantity]').attr('disabled', 'disabled');
            }
        });  
        
        ".$scriptCantLimit
);
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
    
    public function imageAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $this->view->product = $product->getPropertie('_id');
        $this->view->name = $product->getPropertie('_name');
        $paginator = Zend_Paginator::factory($product->listingImg());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingImage = $paginator;
    }

    public function addCelebrityAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $form = new Application_Form_CreateProductCelebrityFrom();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $filter = new Core_SeoUrl();
                $extension = pathinfo($form->getElement('img')->getFileName(), PATHINFO_EXTENSION);
                $nameImg = mt_rand(10, 999) . '_' . urlencode($filter->urlFriendly($product->getPropertie('_name').'-'.$form->getValue('actress'), '-'));
                $element = $form->getElement('img');
                if ($extension != '') {
                    $element->addFilter(
                            'Rename', array(
                        'target' =>
                        $element->getDestination() . '/' . $nameImg . '.' . $extension
                            )
                    );
                    $element->receive();
                }
                $product->addActress(
                        $form->getValue('actress'), 
                        $form->getValue('commission'), 
                        $form->getValue('active'), 
                        $extension == '' ? '' : ($element->getDestination() . '/' . $nameImg . '.' . $extension), 
                        $extension == '' ? '' : ($nameImg . '.' . $extension)
                );
                $this->_flashMessenger->addMessage($product->getMessage());
                $this->_redirect('/admin/product/celebrity/product/' . $product->getPropertie('_id'));
            }
        }
        $this->view->form = $form;
    }
    
    public function addImageAction(){
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $form = new Application_Form_CreateProductImageFrom();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $filter = new Core_SeoUrl();
                $extension = pathinfo($form->getElement('img')->getFileName(), PATHINFO_EXTENSION);
                $nameImg = mt_rand(10, 999) . '_' . urlencode($filter->urlFriendly($product->getPropertie('_name'), '-'));
                $element = $form->getElement('img');
                if ($extension != '') {
                    $element->addFilter(
                            'Rename', array(
                        'target' =>
                        $element->getDestination() . '/' . $nameImg . '.' . $extension
                            )
                    );
                    $element->receive();
                }
                $product->addImage(
                        $element->getDestination() . '/' . $nameImg . '.' . $extension,
                        $nameImg . '.' . $extension,
                        $form->getElement('description')->getValue()
                        );
                $this->_flashMessenger->addMessage($product->getMessage());
                $this->_redirect('/admin/product/image/product/' . $product->getPropertie('_id'));
            }
        }
        $this->view->form = $form;
    }
    public function editImageAction(){
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $form = new Application_Form_CreateProductImageFrom();
        $form->getElement('img')->setRequired(FALSE);
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_PRODUCT);
        $image->identify($this->getRequest()->getParam('image'));
        $form->getElement('description')->setValue($image->getPropertie('_description')) ;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $filter = new Core_SeoUrl();
                
                if(is_string($form->getElement('img')->getFileName()) &&
                        $form->getElement('img')->getFileName()!=''){
                $extension = pathinfo($form->getElement('img')->getFileName(), PATHINFO_EXTENSION);
                }else{
                    $extension='';
                }
                $nameImg = mt_rand(10, 999) . '_' . urlencode($filter->urlFriendly($product->getPropertie('_name'), '-'));
                $element = $form->getElement('img');
                if ($extension != '') {
                    $element->addFilter(
                            'Rename', array(
                        'target' =>
                        $element->getDestination() . '/' . $nameImg . '.' . $extension
                            )
                    );
                    $element->receive();
                }
                $product->editImage(
                        $this->getRequest()->getParam('image'),
                        $extension!=''?($element->getDestination() . '/' . $nameImg . '.' . $extension):'',
                        $extension!=''?($nameImg . '.' . $extension):'',
                        $form->getElement('description')->getValue()
                        );
                $this->_flashMessenger->addMessage($product->getMessage());
                $this->_redirect('/admin/product/image/product/' . $product->getPropertie('_id'));
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
        echo $actress->getPropertie('_id');
        $form = new Application_Form_EditProductCelebrityFrom();
        $dataForm['nameActress'] = $actress->getPropertie('_name');
        $dataForm['actress'] = $actress->getPropertie('_id');
        $dataForm['commission'] = $productActress['product_actress_commission'];
        $dataForm['active'] = $productActress['product_actress_active'];
        $form->populate($dataForm);
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $filter = new Core_SeoUrl();
                $extension = pathinfo($form->getElement('img')->getFileName(), PATHINFO_EXTENSION);
                $nameImg = mt_rand(10, 999) . '_' . urlencode($filter->urlFriendly($product->getPropertie('_nombre'), '-'));
                $element = $form->getElement('img');
                if ($extension != '') {
                    $element->addFilter(
                            'Rename', array(
                        'target' =>
                        $element->getDestination() . '/' . $nameImg . '.' . $extension
                            )
                    );
                    $element->receive();
                }
                $product->addActress(
                        $form->getValue('actress'), 
                        $form->getValue('commission'), 
                        $form->getValue('active'), 
                        $extension == '' ? '' : ($element->getDestination() . '/' . $nameImg . '.' . $extension), 
                        $extension == '' ? '' : ($nameImg . '.' . $extension)
                );

                $this->_flashMessenger->addMessage($product->getMessage());
                $this->_redirect('/admin/product/celebrity/product/' . $product->getPropertie('_id'));
            }
        }
        $this->view->form = $form;
    }

    public function publishCelebrityAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $product->publishCelebrity($this->getRequest()->getParam('id'));
        $this->_flashMessenger->addMessage($product->getMessage());
        $this->_redirect('/admin/product/celebrity/product/' . $product->getPropertie('_id'));
    }

    public function unpublishCelebrityAction() {
        $product = new Application_Entity_Product();
        $product->identify($this->getRequest()->getParam('product'));
        $product->unpublishCelebrity($this->getRequest()->getParam('id'));
        $this->_flashMessenger->addMessage($product->getMessage());
        $this->_redirect('/admin/product/celebrity/product/' . $product->getPropertie('_id'));
    }

}

