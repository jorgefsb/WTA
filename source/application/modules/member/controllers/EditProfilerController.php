<?php

class Member_EditProfilerController extends Core_Controller_ActionMember       
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    {           
       $form = new Application_Form_EditProfilertForm();
       $form->getElement('submit')->setLabel('Update');
       $member = new Application_Entity_Member();
       $member->identify($this->_identity->member_id);
       $values = array(
           'firstName'=>$member->getPropertie('_name'),
           'lastName'=>$member->getPropertie('_lastName'),
           'mail'=>$member->getPropertie('_mail'),
               );
       $form->populate($values);
       if($this->getRequest()->isPost()){
           if($form->isValid($this->getRequest()->getParams(),$this->_identity->member_id)){
               $member->setPropertie('_name', $form->getValue('firstName'));
               $member->setPropertie('_lastName', $form->getValue('lastName'));
               $member->setPropertie('_mail', $form->getValue('mail'));
               $member->update();
               $this->getMessenger()->info($member->getMessage());
               if($form->getValue('password') != '') {
                   $member->passwordReset($form->getValue('password'));
               }
               $this->_redirect('/member/edit-profiler');
           }
       }
       $this->view->form = $form;
    }
}

