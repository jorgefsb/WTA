<?php

class Menber_CreateAccountController extends Core_Controller_ActionMenber 
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    {           
        $form = new Application_Form_CreateAccountForm();
        $entityMenber = new Application_Entity_Menber();
        if($this->_request->isPost()){
            if($form->isValid($this->_getAllParams())){
                $entityMenber->setPropertie('_name', 
                        $form->getElement('firstName')->getValue());
                $entityMenber->setPropertie('_lastName', 
                        $form->getElement('lastName')->getValue());
                $entityMenber->setPropertie('_mail', 
                        $form->getElement('mail')->getValue());
                $entityMenber->createMenber($form->getElement('password')->getValue());
                $this->getMessenger()->info($entityMenber->getMessage());
                $this->_redirect('/menber/create-account');
            }
        }
        $this->view->form = $form;
    }
    public function confirmAction() {
       $entityMenber = new Application_Entity_Menber();
       $entityMenber->confirmAccount($this->_getParam('id'));
       $this->view->message = $entityMenber->getMessage();
    }
}

