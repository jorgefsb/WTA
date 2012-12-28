<?php

class Member_PaymentMethodController extends Core_Controller_ActionMember       
{
    public function init() {
        parent::init();
    }
    public function indexAction(){           
        
        $member = new Application_Entity_Member();
        $member->identify($this->_identity->member_id);        
        if( $this->getRequest()->isPost()){
            $formValues = $this->getRequest()->getPost();
            $form = new Application_Form_BillingInformationForm();
            
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
            $member->loadProfile();

            $billingInformation = $member->getPropertie('_billingInformation');

            $populate = array();
            foreach($billingInformation[0] as $key=>$value){
                $populate[preg_replace('/^_/', '', $key)] = $value;
            }
            
            $populate['id']=$billingInformation[0]['_customerPaymentProfileId'];
            //print_r($populate);die();
            $form = new Application_Form_BillingInformationForm();
            $form->populate($populate);
            
        }
        $this->view->form = $form;
    }
}