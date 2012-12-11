<?php

class Application_Form_RecoveryAccountSendForm extends Core_Form {

    //put your code here
    function init() {
        parent::init();
        $this->setMethod('Post');
        $this->addElement(new Zend_Form_Element_Text('mail',
                        array(
                            'label' => 'Enter Your Email',
                            'required' => true,
                            'validators' => array(new Zend_Validate_EmailAddress()),
                )));

        $this->addElement(new Zend_Form_Element_Submit('Send',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ))));
    }

    public function isValid($data) {
        return parent::isValid($data);
    }

}
