<?php

class Application_Form_CreateContentForm extends Core_Form {

    //put your code here
    function init() {
        parent::init();
        $this->setMethod('Post');
        $this->addElement(new Zend_Form_Element_Text('code',
                        array(
                            'label' => 'Code Dynamic Page ',
                            'required' => true,
                            'maxlength' => '200',
                            'size' => '40',
                            'readonly' => 'readonly'
                )));
                    
        $this->addElement(new Zend_Form_Element_Textarea('body',
                        array(
                            'label' => 'Body Content',
                            'class'=>'cleditor',
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
