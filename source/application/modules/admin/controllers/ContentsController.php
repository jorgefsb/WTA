<?php

class Admin_ContentsController extends Core_Controller_ActionAdmin
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
        
        $paginator = Zend_Paginator::factory(Application_Entity_Content::listing());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingContents  = $paginator;
    } 
    
    public function editAction(){
        $content = new Application_Entity_Content();
        $content->identify($this->getRequest()->getParam('id'));
        
        $form = new Application_Form_CreateContentForm();
        $form->setAction('/admin/contents/edit/id/'.$this->getRequest()->getParam('id'));
        
        $properties = $content->getProperties();
        $arrayPopulate['code'] = $properties['_code'];
        $arrayPopulate['body'] = $properties['_body'];
        $form->populate($arrayPopulate);
        
        
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $content->setPropertie('_code', $form->getValue('code'));
                $content->setPropertie('_body', $form->getValue('body'));
                $content->update();
                
                $this->_flashMessenger->addMessage($content->getMessage());
                $this->_redirect('/admin/contents');
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

