<?php

class Member_MyAddressController extends Core_Controller_ActionMember       
{
    public function init() {
        parent::init();
    }
    public function indexAction(){           
        
        $member = new Application_Entity_Member();
        $member->identify($this->_identity->member_id);        
        if( $this->getRequest()->isPost()){
            $formValues = $this->getRequest()->getPost();
            $form = new Application_Form_ShippingAddressForm();
            
            if( $form->isValid($formValues) ){        
                $saved = $member->saveShippingAddress(array(
                                                                                                '_customerAddressId'=>$form->getValue('id'),
                                                                                                '_firstName'=>$form->getValue('firstName'),
                                                                                                '_lastName'=>$form->getValue('lastName'),
                                                                                                '_address'=>$form->getValue('address'),
                                                                                                '_city'=>$form->getValue('city'),
                                                                                                '_state'=>$form->getValue('state'),
                                                                                                '_zip'=>$form->getValue('zip'),
                                                                                                '_country'=>$form->getValue('country'),
                                                                                                '_phoneNumber'=>$form->getValue('phoneNumber')
                                                                                            ));
                
                if($saved){                    
                    $this->getMessenger()->info($member->getMessage());
                    $this->_redirect('/member/my-address');
                }else{
                    $this->getMessenger()->info($member->getMessage());                    
                }
                
            }else{
                $form->populate($formValues);            
            }
        }else{
            $member->loadProfile();

            $shippingAddress = $member->getPropertie('_shippingAddress');

            $populate = array();
            foreach($shippingAddress[0] as $key=>$value){
                $populate[preg_replace('/^_/', '', $key)] = $value;
            }
            
            $populate['id']=$shippingAddress[0]['_customerAddressId'];
            //print_r($populate);die();
            $form = new Application_Form_ShippingAddressForm();
            $form->populate($populate);
            
        }
        $this->view->form = $form;
    }
}

