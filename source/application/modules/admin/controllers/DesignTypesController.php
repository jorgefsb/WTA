<?php

class Admin_DesignTypesController extends Core_Controller_ActionAdmin
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
        
        $paginator = Zend_Paginator::factory(Application_Entity_DesignType::listingDesignType());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listing  = $paginator;
    } 
    
    public function newAction(){
        $form = new Application_Form_CreateDesignTypeForm();
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $filter = new Core_SeoUrl();
                $designtype = new Application_Entity_DesignType();
                $designtype->setPropertie('_name', $form->getValue('name'));
                $designtype->createDesignType();
                $this->_flashMessenger->addMessage($designtype->getMessage());
                $this->_redirect('/admin/designtypes/');
            }
        }
        $this->view->form = $form;
    }
    
    public function editAction(){
        $designtype = new Application_Entity_DesignType();
        $designtype->identify($this->getRequest()->getParam('id'));
        
        $form = new Application_Form_CreateDesignTypeForm();
        $form->setAction('/admin/designtypes/edit/id/'.$this->getRequest()->getParam('id'));
        
        $properties = $designtype->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $form->populate($arrayPopulate);
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $filter = new Core_SeoUrl();
                
                $designtype->setPropertie('_name', $form->getValue('name'));
                
                $designtype->update();
                $this->_flashMessenger->addMessage($designtype->getMessage());
                $this->_redirect('/admin/designtypes');
            }
        }
        $this->view->form = $form;
    }
    
    public function deleteAction() {
        $designtype = new Application_Entity_DesignType();
        $designtype->identify($this->getRequest()->getParam('id'));
        $designtype->delete();
        $this->_flashMessenger->addMessage($designtype->getMessage());
        $this->_redirect('/admin/designtypes');
    }
}

