<?php

class Application_Form_CreateMemberForm extends Core_Form {

    //put your code here
    function init() {
        parent::init();
        $this->setMethod('Post');
        
        $this->addElement(new Zend_Form_Element_Text('name',
                        array(
                            'label' => 'Name * ',
                            'required' => true,
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Text('last_name',
                        array(
                            'label' => 'Last Name * ',
                            'required' => true,
                            'maxlength' => '200',
                            'size' => '40'
                )));
                
        $this->addElement(new Zend_Form_Element_Text('mail',
                        array(
                            'label' => 'E-mail * ',
                            'required' => true,
                            'maxlength' => '200',
                            'size' => '40'
                )));
                
        $this->addElement(new Zend_Form_Element_Checkbox('active',
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
