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
                $actress = new Application_Entity_Actress();
                $temp = $element->getDestination() . '/' . $nameImg . '.' . $extension;
                $actress->setPropertie('_name', $form->getValue('name'));
                $actress->setPropertie('_description', $form->getValue('description'));
                $actress->setPropertie('_public', $form->getValue('public'));
                $actress->createActress();
                $actress->addImage($temp, $nameImg . '.' . $extension);
                $this->_flashMessenger->addMessage($actress->getMessage());
                $this->_redirect('/admin/actress/');
            }
        }
        $this->view->form = $form;
    }
    public function editAction(){
        $actress = new Application_Entity_Actress();
        $actress->identify($this->getRequest()->getParam('id'));
        $form = new Application_Form_CreateActressFrom();
        $form->setAction('/admin/actress/edit/id/'.$this->getRequest()->getParam('id'));
        $properties = $actress->getProperties();
        $arrayPopulate['name'] = $properties['_name'];
        $arrayPopulate['description'] = $properties['_description'];
        $arrayPopulate['public'] = $properties['_public'];
        $form->populate($arrayPopulate);
        $image = new Application_Entity_Image(Application_Entity_Image::TIPE_IMAGE_CELEBRITY);
        $image->setPropertie('_idTable', $actress->getPropertie('_id'));
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
                $actress->setPropertie('_name', $form->getValue('name'));
                $actress->setPropertie('_description', $form->getValue('description'));
                $actress->setPropertie('_public', $form->getValue('public'));
                $actress->update();
                $actress->editImg(
                        $image->getPropertie('_id'), 
                        $extension!=''?($element->getDestination() . '/' . $nameImg . '.' . $extension):'',
                        $extension!=''?($nameImg . '.' . $extension):''
                    );
                $this->_flashMessenger->addMessage($actress->getMessage());
                $this->_redirect('/admin/actress/edit/id/'.$this->getRequest()->getParam('id'));
            }
        }
        $this->view->form = $form;
    }
    
    public function publishAction(){
        $actress = new Application_Entity_Actress();
        $actress->identify($this->getRequest()->getParam('id'));
        $actress->publish();
        $this->_flashMessenger->addMessage($actress->getMessage());
        $this->_redirect('/admin/actress');
    }
    public function unpublishAction(){
        $actress = new Application_Entity_Actress();
        $actress->identify($this->getRequest()->getParam('id'));
        $actress->unpublish();
        $this->_flashMessenger->addMessage($actress->getMessage());
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

