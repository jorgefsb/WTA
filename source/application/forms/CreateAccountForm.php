<?php

class Application_Form_CreateAccountForm extends Core_Form {

    //put your code here
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
                'table' => 'menber',
                'field' => 'menber_mail')),
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
                            'required' => true,
                            'label' => 'Password '
                )));



        $this->addElement(new Zend_Form_Element_Submit('Create Account',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ))));
    }

    public function isValid($data) {
        return parent::isValid($data);
    }

}
