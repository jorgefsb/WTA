<?php

class Application_Form_CreateAccountForm extends Core_Form {

    //put your code here
    function init() {
        parent::init();
        $this->setMethod('Post');
        $this->addElement(new Zend_Form_Element_Text('firstname',
                        array(
                            'label' => 'First Name * ',
                            'required' => true,
                            'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));

        $this->addElement(new Zend_Form_Element_Text('lastname',
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

        $this->addElement(new Zend_Form_Element_Text('email',
                        array(
                            'label' => 'Email * ',
                            'required' => true,
                            'validators' => $validatorsEmail
                )));
        $this->addElement(new Zend_Form_Element_Password('password1',
                        array(
                            'label' => 'Password ',
                            'required' => true,
                            'validators' => array(
                                new Zend_Validate_StringLength(array('min'=>8)))
                )));
        $this->addElement(new Zend_Form_Element_Password('password2',
                        array(
                            'label' => 'Comfirm Password ',
                            'required' => true,
                            'validators' => array(
                                new Zend_Validate_Identical(),
                                new Zend_Validate_StringLength(array('min'=>8)))
                )));
        
        $this->addElement(new Zend_Form_Element_Submit('Create Account',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ))));
    }

    public function isValid($data) {
        
            $passwordConfirm = $this->getElement('password2');
            $validator = $passwordConfirm->getValidator('Identical')
                  ->setToken($data['password1']);
        
        return parent::isValid($data);
    }

}
