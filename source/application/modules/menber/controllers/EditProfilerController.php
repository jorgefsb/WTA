<?php

class Menber_EditProfilerController extends Core_Controller_ActionMenber       
{
    public function init() {
        parent::init();
    }
    public function indexAction()
    {           
       $form = new Application_Form_EditProfilertForm();
       $menber = new Application_Entity_Menber();
       $menber->identify($this->_identity->menber_id);
       $values = array(
           'firstName'=>$menber->getPropertie('_name'),
           'lastName'=>$menber->getPropertie('_lastName'),
           'mail'=>$menber->getPropertie('_mail'),
               );
       $form->populate($values);
       if($this->getRequest()->isPost()){
           if($form->isValid($this->getRequest()->getParams(),$this->_identity->menber_id)){
               echo'registrado';
               $menber->setPropertie('_name', $form->getValue('firstName'));
               $menber->setPropertie('_lastName', $form->getValue('lastName'));
               $menber->setPropertie('_mail', $form->getValue('mail'));
               $menber->update();
               if($form->getValue('password') != ''){
                   $menber->passwordReset($form->getValue('password'));
               }
           }
       }
       $this->view->form = $form;
    }
}

