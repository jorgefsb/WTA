<?php

class Application_Form_CreateActressFrom extends Core_Form {

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
        $element = new Zend_Form_Element_File('img');
        $element->setLabel('Upload an image:')
                ->setDestination(APPLICATION_PATH.
                        '/../public/dinamic/temp/');
        $element->addValidator('Count', false, 1);
        //$element->addValidator('Size', false, 502400);
        $element->addValidator('Extension', false, 'jpg,png');
        $this->addElement($element);
        
        $this->addElement(new Zend_Form_Element_Textarea('description',
                        array(
                            'label' => 'Description',
                            'rows'=>4,'cols'=>24,
                            'style'=>'margin: 0px 0px 9px; width: 391px; height: 91px;',
                )));
        $this->addElement(new Zend_Form_Element_Checkbox('public',
                array(
                    'label'=>'Active ',
                    'value'=>'1',
                    'required'=>true,
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
