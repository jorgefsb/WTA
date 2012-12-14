<?php

class Member_IndexController extends Core_Controller_ActionMember       
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    {           
        $entity = new Application_Entity_Member();
        $entity->identify($this->_identity->member_id);
        $this->view->dataMember = $entity->getProperties();
    }
    public function setPasswordResetAction(){
        $entity = new Application_Entity_Member();
        $entity->identify($this->_identity->member_id);
        if($this->getRequest()->isPost()){
            $entity->sendPasswordReset();
            $this->getMessenger()->info('Sent an email with the temporary password');
            $this->_redirect('/member/index/password-reset');
        }
    }
    
    public function passwordResetAction(){
        $form = new Application_Form_PasswordResetForm();
        $entityMember = new Application_Entity_Member();
        $entityMember->identify($this->_identity->member_id);
        if ($this->getRequest()->isPost()){
            if ($form->isValid($this->getRequest()->getParams())) {
                $entityMember->passwordReset(
                        $form->getElement('password')->getValue(),
                        $form->getElement('passwordTemp')->getValue()
                        );
                $this->getMessenger()->info($entityMember->getMessage());
                $this->_redirect('/member/index/password-reset');
            }
        }
        $this->view->form = $form;
    }
    

}

