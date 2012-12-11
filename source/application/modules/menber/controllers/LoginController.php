<?php

class Menber_LoginController extends Core_Controller_ActionMenber       
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
                        $loginForm->getElement('login')->getValue(),
                        $loginForm->getElement('password')->getValue())) {
                $this->getNavigationMenber();  
                $this->getMessenger()->info('Login Correct');
                $this->_redirect('/menber/dashboard');
                }
            }
        }
        $this->view->form = $loginForm;
    }

}

