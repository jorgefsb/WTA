<?php

class Application_Form_CreateProductCelebrityFrom extends Core_Form {

    private $_product;
    //put your code here
    function __construct($options = null,$idProduct='') {
        
        $this->_product = $idProduct;
        parent::__construct($options);
        
    }


    function init() {
        parent::init();
        $this->setMethod('Post');
        
        $this->addElement(new Zend_Form_Element_Select('actress',
                array(
                    'label' => 'Celebrity * ',
                    'multiOptions' => Core_Utils::fetchPairs(Application_Entity_Actress::listingActressPublicNotProduct($this->_product))
                            )));
        $this->addElement(new Zend_Form_Element_Text('commission',
                        array(
                            'label' => 'Commission ',
                         //   'required' => true,
                            'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        $element = new Zend_Form_Element_File('img');
        $element->setLabel('Upload an image:')
                ->setDestination(APPLICATION_PATH.
                        '/../public/dinamic/temp/');
        $element->addValidator('Count', false, 1);
      //  $element->addValidator('Size', false, 502400);
        $element->addValidator('Extension', false, 'jpg,png');
        $this->addElement($element);
        $this->addElement(new Zend_Form_Element_Checkbox('active',
                array(
                    'label'=>'Active ',
                    'value'=>'1',
                    'required'=>true,
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
