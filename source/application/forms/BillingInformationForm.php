<?php

class Application_Form_BillingInformationForm extends Core_Form {

    //put your code here
    function init() {
        parent::init();
        $this->setMethod('Post');
        
        
        $this->addElement(new Zend_Form_Element_Hidden('id'));
        
        $this->addElement(new Zend_Form_Element_Text('firstName',
                        array(
                            'label' => 'First Name * ',
                            'required' => true,
      //                      'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));

        $this->addElement(new Zend_Form_Element_Text('lastName',
                        array(
                            'label' => 'Last Name * ',
                            'required' => true,
                    //        'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        
        $this->addElement(new Zend_Form_Element_Text('address',
                        array(
                            'label' => 'Address * ',
                            'required' => true,
                   //         'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Text('country',
                        array(
                            'label' => 'Country * ',
                            'required' => true,
                  //          'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Text('state',
                        array(
                            'label' => 'State * ',
                            'required' => true,
               //             'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Text('city',
                        array(
                            'label' => 'City * ',
                            'required' => true,
          //                  'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Text('zip',
                        array(
                            'label' => 'Zip * ',
                            'required' => true,
                            'validators' => array(new Zend_Validate_Int()),
                            'maxlength' => '200',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Text('phoneNumber',
                        array(
                            'label' => 'Phone Number ',
                            'required' => true,
                            'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '200',
                            'size' => '40'
                )));

        
        $this->addElement(new Zend_Form_Element_Text('cardNumber',
                        array(
                            'label' => 'Card Number',
                            'required' => true,
              //              'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '18',
                            'size' => '40'
                )));
        
        $this->addElement(new Zend_Form_Element_Text('expirationDate',
                        array(
                            'label' => 'Card Expiration Date (2017-05)',
                            'required' => true,
               //             'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '7',
                            'size' => '40'
                )));

        
        $this->addElement(new Zend_Form_Element_Text('cardCode',
                        array(
                            'label' => 'Card Code',
                            'required' => true,
                            'validators' => array(new Zend_Validate_Alnum(true)),
                            'maxlength' => '4',
                            'size' => '40'
                )));

        
        $this->addElement(new Zend_Form_Element_Submit('save',
                        array('attribs' => array(
                                'class' => 'submit-button'
                        ), 'label'=>'Save')));
    }

}
