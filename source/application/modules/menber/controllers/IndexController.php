<?php

class Menber_IndexController extends Core_Controller_ActionMenber       
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    {           
        $entity = new Application_Entity_Menber();
        $entity->identify($this->_identity->menber_id);
        $this->view->dataMenber = $entity->getProperties();
    }
    public function setPasswordResetAction(){
        $entity = new Application_Entity_Menber();
        $entity->identify($this->_identity->menber_id);
        if($this->getRequest()->isPost()){
            $entity->sendPasswordReset();
            $this->getMessenger()->info('Sent an email with the temporary password');
            $this->_redirect('/menber/index/password-reset');
        }
    }
    
    public function passwordResetAction(){
        $form = new Application_Form_PasswordResetForm();
        $entityMenber = new Application_Entity_Menber();
        $entityMenber->identify($this->_identity->menber_id);
        if ($this->getRequest()->isPost()){
            if ($form->isValid($this->getRequest()->getParams())) {
                $entityMenber->passwordReset(
                        $form->getElement('password')->getValue(),
                        $form->getElement('passwordTemp')->getValue()
                        );
                $this->getMessenger()->info($entityMenber->getMessage());
                $this->_redirect('/menber/index/password-reset');
            }
        }
        $this->view->form = $form;
    }
    

}

