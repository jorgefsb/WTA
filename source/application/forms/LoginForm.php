<?php
class Application_Form_LoginForm extends Core_Form {
    
    function init() {
        parent::init();
        $this->setMethod('Post');
        $this->addElement(new Zend_Form_Element_Text('mail',
                array(
                    'required' => true,
                    'label' => 'User',
                    'maxlength' => '200',
                    'size' => '40'
                    )));
        
        $this->addElement(new Zend_Form_Element_Password('password',
                array(
                    'required' => true,
                    'Label' => 'Password',
                    'maxlength' => '200',
                    'size' => '40'                    
                    )));   
        $this->addElement(new Zend_Form_Element_Submit('send',
                array('label'=>' Send ')));
    }
    
}

