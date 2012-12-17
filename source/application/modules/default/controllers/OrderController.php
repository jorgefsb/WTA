<?php

class Default_OrderController extends Core_Controller_ActionDefault       
{
    
    
    public function init() {
        parent::init();
        
        $this->_helper->contextSwitch()
                ->addActionContext('create', 'json')
                ->initContext();
        
    }
    
    public function createAction(){
      
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $formValues = $this->_getAllParams();
            
            print_r($formValues);die();
            
            $transacction = new Application_Entity_Transaction();
            $transacction->setPropertie('_state', Application_Entity_Transaction::TRANSACTION_OUTSTANDING);
            $transacction->createTransaction();

            /*agrega el producto a la transaccion*/
            $transacction->addProduct($product);
            /* $product tiene que ser una entidad typo Application_Entity_Product

            una vez  la transacción se pague, esto se haría de la siguiente manera
            $transacction->confirmPayment();*/
        }
        
    }
    
    
    public function processedAction(){
        $this->_helper->layout->disableLayout();
    }
        
    
}

