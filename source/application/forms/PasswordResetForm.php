<?php
class Application_Form_PasswordResetForm extends Core_Form {
    
    function init() {
        parent::init();
        $this->setMethod('Post');
        $this->addElement(new Zend_Form_Element_Password('passwordTemp',
                array(
                    'required' => true,
                    'Label' => 'Authorization Code',
                    'maxlength' => '200',
                    'size' => '40'                    
                    )));   
        $this->addElement(new Zend_Form_Element_Password('password',
                array(
                    'required' => true,
                    'label' => 'Password',
                    'maxlength' => '200',
                    'size' => '40'
                    )));
        $this->addElement(new Zend_Form_Element_Submit('send',
                array('label'=>' Send ')));
    }
    
}

