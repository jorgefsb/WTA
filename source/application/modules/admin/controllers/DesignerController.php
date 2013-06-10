<?php

class Admin_DesignerController extends Core_Controller_ActionAdmin
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
        
        $paginator = Zend_Paginator::factory(Application_Entity_Designer::listing());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listing  = $paginator;
    } 
    
    public function newAction(){
        $form = new Application_Form_CreateDesignerForm();
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                
                $designer = new Application_Entity_Designer();
                $designer->setPropertie('_name', $form->getValue('name'));
                $designer->setPropertie('_description', $form->getValue('description'));
                $designer->setPropertie('_public', $form->getValue('public'));
                $designer->createDesigner();
                
                $this->_flashMessenger->addMessage($designer->getMessage());
                $this->_redirect('/admin/designer/');
            }
        }
        $this->view->form = $form;
    }
    
    public function editAction(){
        $designer = new Application_Entity_Designer();
        $designer->identify($this->getRequest()->getParam('id'));
        
        $form = new Application_Form_CreateDesignerForm();
        $form->setAction('/admin/designer/edit/id/'.$this->getRequest()->getParam('id'));
        
        $properties = $designer->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $arrayPopulate['description'] = $properties['_description'];
        $arrayPopulate['public'] = $properties['_public'];
        $form->populate($arrayPopulate);
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $designer->setPropertie('_name', $form->getValue('name'));
                $designer->setPropertie('_description', $form->getValue('description'));
                $designer->setPropertie('_public', $form->getValue('public'));
                $designer->update();
                
                $this->_flashMessenger->addMessage($designer->getMessage());
                $this->_redirect('/admin/designer');
            }
        }

        $this->view->form = $form;
    }
    
    public function deleteAction() {
        $designer = new Application_Entity_Designer();
        $designer->identify($this->getRequest()->getParam('id'));
        $designer->delete();
        $this->_flashMessenger->addMessage($designer->getMessage());
        $this->_redirect('/admin/designer');
    }
    
    public function publishAction(){
        $designer = new Application_Entity_Designer();
        $designer->identify($this->getRequest()->getParam('id'));
        $designer->publish();
        $this->_flashMessenger->addMessage($designer->getMessage());
        $this->_redirect('/admin/designer');
    }
    public function unpublishAction(){
        $designer = new Application_Entity_Designer();
        $designer->identify($this->getRequest()->getParam('id'));
        $designer->unpublish();
        $this->_flashMessenger->addMessage($designer->getMessage());
        $this->_redirect('/admin/designer');
    }
}

