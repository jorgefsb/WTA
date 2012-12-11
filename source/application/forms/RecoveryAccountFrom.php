<?php

class Application_Form_RecoveryAccountFrom extends Core_Form {

    //put your code here
    function init() {
        parent::init();
        $this->setMethod('Post');
         $this->addElement(new Zend_Form_Element_Password('password',
                        array(
                            'label' => 'Password ',
                            'required' => true,
                            'validators' => array(
                                new Zend_Validate_StringLength(array('min'=>8)))
                )));
         $this->addElement(new Zend_Form_Element_Password('confirmPassword',
                        array(
                            'label' => 'Comfirm Password ',
                            'required' => true,
                            'validators' => array(
                                new Zend_Validate_Identical(),
                                new Zend_Validate_StringLength(array('min'=>8)))
                )));

        $this->addElement(new Zend_Form_Element_Submit('Send',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ))));
    }

   public function isValid($data) {
        
        if($data['password']!=''){
            $passwordConfirm = $this->getElement('confirmPassword');
            $passwordConfirm->setRequired();
            $validator = $passwordConfirm->getValidator('Identical')
                  ->setToken($data['password']);
            
        }
        return parent::isValid($data);
    }

}
