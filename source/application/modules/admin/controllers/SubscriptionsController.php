<?php

class Admin_SubscriptionsController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function downloadAction(){
        
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $subscriptions = Application_Entity_SubscriptionMail::listingSubscription();
        
        require_once APPLICATION_PATH.'/../library/phpexcel/PHPExcel.php';
        require_once APPLICATION_PATH.'/../library/phpexcel/PHPExcel/Writer/Excel2007.php';
        
        $objPHPExcel = new PHPExcel();
        
        $negrita = array('font' => array('bold' => true), 'alignment' => array('wrap' => true,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));

        $normal = array('alignment' => array('wrap' => false));
        
        $style_num = array(
                            'font' => array(
                                                'color' => array('rgb' => '000000'),
                                                'bold' => false,
                                           ),
                            'alignment' => array(
                                                    'wrap'       => true,
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                ),
                          );
        
        //Definir propiedades del objeto
        $properties = $objPHPExcel->getProperties();
        $properties->setCreator("WTA System");
        $properties->setLastModifiedBy("WTA System");
        $properties->setTitle("Subscriptions Report");
        $properties->setSubject("Subscriptions Report");
        $properties->setDescription("This document is a list of exported email subscription of WTA system.");
        
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
            
        $letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");
        $titles = array(
                            "id" => "Id",
                            "email" => "Email"
                        );
                        
        $n = 0;
        if($titles) foreach($titles as $key => $value){
            $sheet->SetCellValue($letters[$n]."1", $value);
            $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
            $sheet->getStyle($letters[$n]."1")->applyFromArray($negrita);
            $n++;
        }
        
        $row = 2;
        if(!empty($subscriptions)) foreach($subscriptions as $subscription){
            $n = 0;
            
            if($titles) foreach($titles as $key => $value){
                $sheet->SetCellValue($letters[$n].$row, $subscription[$key]);
                $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                $n++;
            }
            
            $row++;
        }
        
        $sheet->setTitle('Subcriptions');
        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        
        header('content-type: application/zip');
        header('content-disposition: inline; filename="subscriptions.xlsx"');
        
        $objWriter->save('php://output');
        
        //if(!empty($members)) foreach($members as $member){
        //    echo $member["member_name"]." ".$member["member_last_name"]."<br>";
        //}
        
        die();
    }
    
}

