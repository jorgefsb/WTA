<?php

class Application_Form_CreateDesignTypeForm extends Core_Form {

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

        $this->addElement(new Zend_Form_Element_Submit('Save',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ))));
    }

    public function isValid($data) {
        return parent::isValid($data);
    }

}
