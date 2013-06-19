<?php

class Admin_BackgroundsController extends Core_Controller_ActionAdmin
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    {
        $this->view->headScript()->appendScript('
            $(document).ready(function(){
                $(".deleteAction").click(function(evento){
                    $("#yesDelete").attr("data",$(this).attr("href"));    
                });
                
                $("#yesDelete").click(function(evento){
                    window.location.href = $(this).attr("data");
                });
            });
        ');
        
        $paginator = Zend_Paginator::factory(Application_Entity_Background::listingBackground());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingBackgrounds  = $paginator;
    } 
    
    public function newAction(){
        $form = new Application_Form_CreateBackgroundForm();
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $filter = new Core_SeoUrl();
                $extension = pathinfo($form->getElement('img')->getFileName(), PATHINFO_EXTENSION);
                $nameImg = mt_rand(10, 999) . '_' . urlencode($filter->urlFriendly($form->getValue('name'), '-'));
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
                $background = new Application_Entity_Background();
                $temp = $element->getDestination() . '/' . $nameImg . '.' . $extension;
                $background->setPropertie('_name', $form->getValue('name'));
                $background->setPropertie('_public', $form->getValue('public'));
                $background->createBackground();
                $background->addImage($temp, $nameImg . '.' . $extension);
                $this->_flashMessenger->addMessage($background->getMessage());
                $this->_redirect('/admin/backgrounds/');
            }
        }
        $this->view->form = $form;
    }
    public function editAction(){
        $background = new Application_Entity_Background();
        $background->identify($this->getRequest()->getParam('id'));
        
        $form = new Application_Form_CreateBackgroundForm();
        $form->setAction('/admin/backgrounds/edit/id/'.$this->getRequest()->getParam('id'));
        
        $properties = $background->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $arrayPopulate['public'] = $properties['_public'];
        $form->populate($arrayPopulate);
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_BACKGROUND);
        $image->setPropertie('_idTable', $background->getPropertie('_id'));
        $image->identify();
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $filter = new Core_SeoUrl();
                if(is_string($form->getElement('img')->getFileName()) &&
                        $form->getElement('img')->getFileName()!=''){
                $extension = pathinfo($form->getElement('img')->getFileName(), PATHINFO_EXTENSION);
                }else{
                    $extension='';
                }
                $nameImg = mt_rand(10, 999) . '_' . urlencode($filter->urlFriendly($form->getValue('name'), '-'));
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
                
                //Zend_Debug::dump($image); die();
                $background->setPropertie('_name', $form->getValue('name'));
                $background->setPropertie('_public', $form->getValue('public'));
                $background->update();
                
                $background->editImg(
                        $image->getPropertie('_id'), 
                        $extension!=''?($element->getDestination() . '/' . $nameImg . '.' . $extension):'',
                        $extension!=''?($nameImg . '.' . $extension):''
                    );
                $this->_flashMessenger->addMessage($background->getMessage());
                $this->_redirect('/admin/backgrounds');
            }
        }
        $this->view->form = $form;
    }
    
    public function publishAction(){
        $background = new Application_Entity_Background();
        $background->identify($this->getRequest()->getParam('id'));
        $background->publish();
        $this->_flashMessenger->addMessage($background->getMessage());
        $this->_redirect('/admin/backgrounds');
    }
    public function unpublishAction(){
        $background = new Application_Entity_Background();
        $background->identify($this->getRequest()->getParam('id'));
        $background->unpublish();
        $this->_flashMessenger->addMessage($background->getMessage());
        $this->_redirect('/admin/backgrounds');
    }
     public function upAction() {
        $background = new Application_Entity_Background();
        $background->identify($this->getRequest()->getParam('id'));
        $background->upOrder();
        $this->_flashMessenger->addMessage($background->getMessage());
        $this->_redirect('/admin/backgrounds');
    }

    public function downAction() {
        $background = new Application_Entity_Background();
        $background->identify($this->getRequest()->getParam('id'));
        $background->downOrder();
        $this->_flashMessenger->addMessage($background->getMessage());
        $this->_redirect('/admin/backgrounds');
    }
    public function deleteAction() {
        $background = new Application_Entity_Background();
        $background->identify($this->getRequest()->getParam('id'));
        $background->delete();
        $this->_flashMessenger->addMessage($background->getMessage());
        $this->_redirect('/admin/backgrounds');
    }
    
    
}

