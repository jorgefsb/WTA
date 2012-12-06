<?php

class Admin_ActressController extends Core_Controller_ActionAdmin
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    {                   
        $paginator = Zend_Paginator::factory(Application_Entity_Actress::listingActress());
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(6);
        $this->view->listingActress  = $paginator;
    } 
    public function newAction(){
        $form = new Application_Form_CreateActressFrom();
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $actress = new Application_Entity_Actress();
                $temp = $form->getElement('img')->getDestination().'/'.$form->getValue('img');
                $actress->setPropertie('_name', $form->getValue('name'));
                $actress->setPropertie('_description', $form->getValue('description'));
                $actress->setPropertie('_public', $form->getValue('public'));
                $actress->createActress();
                $actress->addImage($temp, $form->getValue('img'));
                $this->_flashMessenger->addMessage($actress->getMessage());
                $this->_redirect('/admin/actress/');
            }
        }
        $this->view->form = $form;
    }
    public function editAction(){
        $Actress = new Application_Entity_Actress();
        $Actress->identify($this->getRequest()->getParam('id'));
        $form = new Application_Form_CreateActressFrom();
        $form->setAction('/admin/actress/edit/id/'.$this->getRequest()->getParam('id'));
        $properties = $Actress->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $arrayPopulate['description'] = $properties['_description'];
        $arrayPopulate['public'] = $properties['_public'];
        $form->populate($arrayPopulate);
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $Actress->setPropertie('_name', $form->getValue('name'));
                $Actress->setPropertie('_description', $form->getValue('description'));
                $Actress->setPropertie('_public', $form->getValue('public'));
                $Actress->update();
                $this->_flashMessenger->addMessage($Actress->getMessage());
                $this->_redirect('/admin/actress/edit/id/'.$this->getRequest()->getParam('id'));
            }
        }
        $this->view->form = $form;
    }
    
    public function publishAction(){
        $Actress = new Application_Entity_Actress();
        $Actress->identify($this->getRequest()->getParam('id'));
        $Actress->publish();
        $this->_flashMessenger->addMessage($Actress->getMessage());
        $this->_redirect('/admin/actress');
    }
    public function unpublishAction(){
        $Actress = new Application_Entity_Actress();
        $Actress->identify($this->getRequest()->getParam('id'));
        $Actress->unpublish();
        $this->_flashMessenger->addMessage($Actress->getMessage());
        $this->_redirect('/admin/actress');
    }
     public function upAction() {
        $actress = new Application_Entity_Actress();
        $actress->identify($this->getRequest()->getParam('id'));
        $actress->upOrder();
        $this->_flashMessenger->addMessage($actress->getMessage());
        $this->_redirect('/admin/actress');
    }

    public function downAction() {
        $actress = new Application_Entity_Actress();
        $actress->identify($this->getRequest()->getParam('id'));
        $actress->downOrder();
        $this->_flashMessenger->addMessage($actress->getMessage());
        $this->_redirect('/admin/actress');
    }
    
    
}

