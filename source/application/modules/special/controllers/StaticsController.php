<?php

class Special_StaticsController extends Core_Controller_ActionDefault       
{
    
    
    public function init() {
        parent::init();
        
        $this->_helper->layout->setLayout('layout-special');
        
        //if( !Zend_Auth::getInstance()->hasIdentity() && !$this->_session->authBeta){
          //  $this->redirect('/beta');
        //}
        
        /*
         * Tracking
         */
        $this->_tackName = 'PAGE';
        $this->_tackUrl = $_SERVER['REQUEST_URI'];
        $this->_tackUrlRef = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
    }          
    
    
    private function loadOptionsMenu(){
        $_product = new Application_Entity_Product();
        $menu = array(        
                'menu_designers' => $_product->designersWithTypes(),
                'menu_collections_types' => $_product->collectionsTypesAvailables(),
                'menu_boutiques' => $_product->boutiquesAvailables(),
                'menu_limitedq' => $_product->listingLimitedQuantity()
            );
        //echo '<pre>';print_r($_product->listingLimitedQuantity());die();
        
        $this->view->menu = $menu;
        return $menu;;
    }   
    
    public function affiliatesAction(){
        
        $this->view->headTitle('Affiliates');
        $this->view->headMeta()->appendName('description', ' WeTheAdorned is thrilled to partner with websites and blogs on the vanguard of innovation in fashion, lifestyle, designer, and beauty. ');
        
        
        
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
        $this->view->headTitle('About WTA');
        $this->view->headMeta()->appendName('description', 'Fashion jewelry designers Tim McElwee and Cyia Batten joined forces in 2004 to create the coveted jewelry line T.Cyia that has been featured on celebs and style icons worldwide. They’re at it again with a dynamic vision to change the way designers and fashion aficionados worldwide shop for and purchase jewelry online');
        
        $this->view->visible = true;
        $this->view->headScript()->appendScript('document.location.href = "'.BASE_URL.'#about-wta";');
        
        
        if( $this->getRequest()->isXmlHttpRequest()  ){
            $this->_helper->layout->disableLayout();
            $this->view->visible = false;
        }
        
    }
    
    
    public function contactusAction(){

        $this->view->headTitle('Contact Us');
        $this->view->headMeta()->appendName('description', ucwords('INFORMATION, CAREER INQUIRIES, CUSTOMER CARE, DESIGNER INQUIRIES, PERSONAL STYLISTS, TECHNICAL SUPPORT, PRESS INQUIRIES, AFFILIATE PROGRAM INQUIRIES'));
        
        $this->view->visible = true;
        $this->view->headScript()->appendScript('document.location.href = "'.BASE_URL.'#contact-us";');
        
        
        if( $this->getRequest()->isXmlHttpRequest()  ){
            $this->_helper->layout->disableLayout();
            $this->view->visible = false;
        }
        
        
    }
    
    public function comingsoonAction(){
        $this->_helper->layout->disableLayout();        
        
    }
    
    public function termsAction(){

        $this->view->headTitle('Terms');
        $this->view->headMeta()->appendName('description', 'Please Read This Document Carefully. It Contains Very Important Information Regarding Your Rights and Obligations, Including Limitations and Exclusions That Might Apply to You.');
        
        
    }    
    
    public function rewardingmembersAction(){

        $this->view->headTitle('Rewarding Our Members');
        $this->view->headMeta()->appendName('description', 'WeTheAdorned places a serious emphasis on rewarding its members for referral memberships. 1. Exclusive Access. 2. Member Pricing. 3. Only Members get paid for sharing. 4. Referral Rewards. 5. And don’t forget about the free gift.');
        
        
    }
    
    public function helpAction(){

        $this->view->headTitle('Help');
        $this->view->headMeta()->appendName('description', 'Customer Care. For questions about your order or inquiries about our site, please email: care@wetheadorned.com  or call: ');
        
        
    }   
    
    
    public function privacyAction(){

        $this->view->headTitle('Privacy');
        $this->view->headMeta()->appendName('description', 'We at WeTheAdorned have created this Privacy Policy because we know you care about how the information you provide us with is used and shared. This Privacy Policy describes how we gather information from you when you visit the WeTheAdorned website located at www.WeTheAdorned.com (the “Site”), and how we may use and share that information. By visiting our Site, you are accepting the practices described in this Privacy Policy and the accompanying Terms of Use. In this Privacy Policy, the words “we,” “us” and “our” refer to WeTheAdorned. ');
        
    }   
    
    public function howitworksAction(){
        
        $this->view->headTitle('how it works');
        $this->view->headMeta()->appendName('description', 'WE OFFER MEMBERS THE MOST EXCLUSIVE JEWELRY COLLECTIONS FROM THE HOTTEST INDEPENDENT DESIGNERS CURATED BY THE WORLD’S MOST FASHIONABLE CELEBRITIES ….AT EXTRAORDINARY PRICES. How can we afford to do this? Easy: we cut out the middleman. All he was doing was taking up space anyway. Let’s be honest, it’s 2013, if you aren’t over last season’s leftovers or paying retail prices – you should be. ');
        
        $this->view->visible = true;
        $this->view->headScript()->appendScript('document.location.href = "'.BASE_URL.'#how-it-works";');
        
        
        if( $this->getRequest()->isXmlHttpRequest()  ){
            $this->_helper->layout->disableLayout();
            $this->view->visible = false;
        }
        
    }   
    
    public function giftAction(){
        $this->view->headTitle('Get a free gift');
        $this->view->headMeta()->appendName('description', 'BECOME A MEMBER TODAY & start enjoying the BENEFITS OF MEMBERSHIP. T.Cyia’s larger ram skull ring features horns that wrap around the finger, creating the top of two bands. Made of bronze with 24 kt gold plating this ring has been E-coated to protect the integrity of the finish');
        $this->loadOptionsMenu(); 
   }
   
   
    public function beforeAction(){
        $this->_helper->layout->disableLayout();
    }
    
    
}

