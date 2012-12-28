<?php

class Member_PaymentMethodController extends Core_Controller_ActionMember       
{
    public function init() {
        parent::init();
    }
    public function indexAction(){           
        
        $member = new Application_Entity_Member();
        $member->identify($this->_identity->member_id);        
        
        $form = new Application_Form_BillingInformationForm();
        
        if( $this->getRequest()->isPost()){
            $formValues = $this->getRequest()->getPost();
            
            if(!$form->getValue('id')>0){
                $form->getElement('cardNumber')->setRequired(false);
                $form->getElement('expirationDate')->setRequired(false);
                $form->getElement('cardCode')->setRequired(false);
            }
            
            if( $form->isValid($formValues) ){
                $saved = $member->saveBillingInformation(array(
                                                                                                '_customerPaymentProfileId'=>$form->getValue('id'),
                                                                                                '_firstName'=>$form->getValue('firstName'),
                                                                                                '_lastName'=>$form->getValue('lastName'),
                                                                                                '_address'=>$form->getValue('address'),
                                                                                                '_city'=>$form->getValue('city'),
                                                                                                '_state'=>$form->getValue('state'),
                                                                                                '_zip'=>$form->getValue('zip'),
                                                                                                '_country'=>$form->getValue('country'),
                                                                                                '_phoneNumber'=>$form->getValue('phoneNumber'),
                                                                                                '_cardNumber'=>$form->getValue('cardNumber'),
                                                                                                '_expirationDate'=>$form->getValue('expirationDate')
                                                                                                //'_cardCode'=>$form->getValue('cardCode')
                                                                                            ));
                
                if($saved){                    
                    $this->getMessenger()->info($member->getMessage());
                    $this->_redirect('/member/payment-method');
                }else{
                    echo $member->getMessage();
                    $this->getMessenger()->info($member->getMessage());                    
                }
                
            }else{
                $form->populate($formValues);            
            }
        }else{
            $form = new Application_Form_BillingInformationForm();
            
            $member->loadProfile();

            $billingInformation = $member->getPropertie('_billingInformation');

            $populate = array();
            if(!empty($billingInformation)){
                foreach($billingInformation[0] as $key=>$value){
                    $populate[preg_replace('/^_/', '', $key)] = $value;
                }

                $populate['id']=$billingInformation[0]['_customerPaymentProfileId'];
                
                $form->getElement('cardNumber')->setAttrib('style', 'display:none')->setLabel('');
                $form->getElement('expirationDate')->setAttrib('style', 'display:none')->setLabel('');
                $form->getElement('cardCode')->setAttrib('style', 'display:none')->setLabel('');
            }else{
            }
            //print_r($populate);die();
            
            $form->populate($populate);
            
        }
        $this->view->form = $form;
    }
}