<?php

class Application_Form_CreateUserFrom extends Core_Form {

    //put your code here
    function init() {
        parent::init();
        $this->setMethod('Post');
        $this->addElement(new Zend_Form_Element_Text('name',
                        array(
                            'label' => 'Name * ',
                            'required' => true,
                            'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Text('user',
                        array(
                            'label' => 'Mail * ',
                            'required' => true,
                            'validators' => array(new Zend_Validate_EmailAddress()),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Select('userType',
                array(
                    'label' => 'Type * ',
                    'multiOptions' => Core_Utils::fetchPairs(Application_Entity_User::getUserType())
                            )));
        
        $this->addElement(new Zend_Form_Element_Password('password',
                        array(
                            'label' => 'Password * ',
                            'required' => true,
                            'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Checkbox('active',
                array(
                    'label'=>'Active ',
                    'value'=>'1',
                    'required'=>true,
                    )));


        $this->addElement(new Zend_Form_Element_Submit('Create User',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ))));
    }

    public function isValid($data) {
        return parent::isValid($data);
    }

}
