<?php

class Menber_LoginController extends Core_Controller_ActionDefault       
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    {           
        $loginForm = new Application_Form_LoginForm();
        $entityMenber = new Application_Entity_Menber();
        if($this->getRequest()->isPost()){
            if($loginForm->isValid($this->_getAllParams())){
                if($entityMenber->autentificate(
                        $loginForm->getElement('mail')->getValue(),
                        $loginForm->getElement('password')->getValue())) {
                $this->_redirect('/menber');
                }
            }
        }
        $this->view->form = $loginForm;
    }

}

