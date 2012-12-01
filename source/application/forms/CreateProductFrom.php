<?php

class Application_Form_CreateProductFrom extends Core_Form {

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
        
        
        
        $this->addElement(new Zend_Form_Element_Select('category',
                array(
                    'label' => 'Category * ',
                    'required' => true,
                    'multiOptions' => Core_Utils::fetchPairs(Application_Entity_Category::listingCategory())
                            )));

        $this->addElement(new Zend_Form_Element_Textarea('description',
                        array(
                            'label' => 'Description',
                )));


        $this->addElement(new Zend_Form_Element_Text('price',
                        array(
                            'required' => true,
                            'label' => 'Price'
                )));
        $this->addElement(new Zend_Form_Element_Checkbox('public',
                array(
                    'label'=>'Active ',
                    'value'=>'1',
                    'required'=>true,
                    )));
        
        $this->addElement(new Zend_Form_Element_Checkbox('limitedQuantity',
                array(
                    'label'=>'Limited Quantity',
                    'value'=>'1',
                    'required'=>true,
                    )));


        $this->addElement(new Zend_Form_Element_Submit('Create Product',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ))));
    }

    public function isValid($data) {
        return parent::isValid($data);
    }

}
