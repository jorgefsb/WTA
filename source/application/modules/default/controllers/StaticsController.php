<?php

class Default_StaticsController extends Core_Controller_ActionDefault       
{
    
    
    public function init() {
        parent::init();
        
        if( !Zend_Auth::getInstance()->hasIdentity() && !$this->_session->authBeta){
            $this->redirect('/beta');
        }
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }          
    
    public function affiliatesAction(){
        if( $this->getRequest()->isXmlHttpRequest()  ){
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            
            $formValues = $this->getRequest()->getPost();
                        
            $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini");
            $class = $config->get(APPLICATION_ENV)->toArray();
            $destination = $class['mail']['affiliates']['email'];
            
            
            $objMail = new Core_Mail();
            $objMail->addDestinatario($destination);
            $objMail->setAsunto('Affiliate form');
            
            $validator_email = new Zend_Validate_EmailAddress();
            if( !$validator_email->isValid($formValues['email']) ){
                $response = array('error'=>1);
            }else{
                
                $mensaje = '<p><strong>First name:</strong> &nbsp; &nbsp; &nbsp; &nbsp;'.$formValues['firstname'].'<p>';
                $mensaje .= '<p><strong>Last name:</strong> &nbsp; &nbsp; &nbsp; &nbsp;'.$formValues['lastname'].'<p>';
                $mensaje .= '<p><strong>Website Name:</strong> &nbsp; &nbsp; &nbsp; &nbsp;'.$formValues['website'].'<p>';
                $mensaje .= '<p><strong>Url:</strong> &nbsp; &nbsp; &nbsp; &nbsp;'.$formValues['url'].'<p>';
                $mensaje .= '<p><strong>Email Address:</strong> &nbsp; &nbsp; &nbsp; &nbsp;'.$formValues['email'].'<p>';
                $mensaje .= '<p><strong>Country:</strong> &nbsp; &nbsp; &nbsp; &nbsp;'.$formValues['country'].'<p>';
                $mensaje .= '<p><strong>Site Information:</strong> &nbsp; &nbsp; &nbsp; &nbsp;'.$formValues['siteinformation'].'<p>';

                $objMail->setMensaje($mensaje);
                $objMail->send();

                $this->_helper->json(array('send'=>1));
            }
            
            $this->_helper->json($response);
            
        }
    } 
    
    public function aboutusindividualAction(){
        
    }
    
    
    public function smallaboutAction(){
        $this->_helper->layout->disableLayout();        
        
    }
    
    
    public function contactusAction(){
        $this->_helper->layout->disableLayout();        
    }
    
    public function comingsoonAction(){
        $this->_helper->layout->disableLayout();        
        
    }
    
    public function termsAction(){

        
    }    
    
    public function rewardingmembersAction(){

        
    }
    
    public function helpAction(){

        
    }   
    
    
    public function privacyAction(){

        
    }   
    
    public function howitworksAction(){
        $this->_helper->layout->disableLayout();        
        
    }   
    
    
    
}

