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
        $this->addElement(new Zend_Form_Element_Select('actress',
                array(
                    'label' => 'Actress * ',
                    'multiOptions' => Core_Utils::fetchPairs(Application_Entity_Actress::listingActress())
                            )));

        $this->addElement(new Zend_Form_Element_Textarea('description',
                        array(
                            'label' => 'Product Description',
                            'class'=>'cleditor',
                )));
        
        $this->addElement(new Zend_Form_Element_Textarea('descriptionDesigner',
                        array(
                            'label' => 'Designer Description',
                            'class'=>'cleditor',
                )));


        $this->addElement(new Zend_Form_Element_Text('price',
                        array(
                            'required' => true,
                            'label' => 'Price'
                )));
        $this->addElement(new Zend_Form_Element_Text('size',
                        array(
                            'required' => true,
                            'label' => 'Size'
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
