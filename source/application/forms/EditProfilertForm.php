<?php

class Application_Form_EditProfilertForm extends Core_Form {

    function init() {
        parent::init();
        $this->setMethod('Post');
        $this->addElement(new Zend_Form_Element_Text('firstName',
                        array(
                             'label' => 'First Name * ',
                            'required' => true,
                            'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));

        $this->addElement(new Zend_Form_Element_Text('lastName',
                        array(
                            'label' => 'Last Name * ',
                            'required' => true,
                            'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        $validatorsEmail = array(
            new Zend_Validate_Db_NoRecordExists(array(
                'table' => 'member',
                'field' => 'member_mail')),
            new Zend_Validate_EmailAddress()
        );
        $this->addElement(new Zend_Form_Element_Text('mail',
                        array(
                            'label' => 'Email * ',
                            'required' => true,
                            'validators' => $validatorsEmail
                )));


        $this->addElement(new Zend_Form_Element_Password('password',
                        array(
                            'label' => 'Password ',
                            'validators' => array(
                                new Zend_Validate_StringLength(array('min'=>8)))
                )));
        $this->addElement(new Zend_Form_Element_Password('confirmPassword',
                        array(
                            'label' => 'Comfirm Password ',
                            'validators' => array(
                                new Zend_Validate_Identical(),
                                new Zend_Validate_StringLength(array('min'=>8)))
                )));
        
        $this->addElement(new Zend_Form_Element_Submit('Create Account',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ))));
    }

   public function isValid($data, $id='') {
        if ($id != '') {
            $elementMail = $this->getElement('mail');
            $validator = $elementMail->getValidator('Db_NoRecordExists');
            $validator->setExclude(array(
                'field' => 'member_id',
                'value' => $id
            ));
        }
        if($data['password']!=''){
            $passwordConfirm = $this->getElement('confirmPassword');
            $passwordConfirm->setRequired();
            $validator = $passwordConfirm->getValidator('Identical')
                  ->setToken($data['password']);
            
        }
        return parent::isValid($data);
    }

}
