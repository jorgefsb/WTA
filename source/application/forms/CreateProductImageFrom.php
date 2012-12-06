<?php

class Application_Form_CreateProductImageFrom extends Core_Form {

    //put your code here
    function init() {
        parent::init();
        $this->setMethod('Post');
        

        $this->addElement(new Zend_Form_Element_Textarea('description',
                        array(
                            'label' => 'Description * ',
                            'required' => true,
                )));
        $element = new Zend_Form_Element_File('img');
        $element->setLabel('Upload an image:')
                ->setDestination(APPLICATION_PATH.
                        '/../public/dinamic/temp/');
        $element->addValidator('Count', false, 1);
        $element->addValidator('Size', false, 502400);
        $element->addValidator('Extension', false, 'jpg,png');
        $this->addElement($element);
        $this->addElement(new Zend_Form_Element_Submit('Send',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ))));
    }

    public function isValid($data) {
        return parent::isValid($data);
    }

}
