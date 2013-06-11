<?php

class Admin_OrdersController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->view->headScript()->appendScript('
            $(document).ready(function(){
                $(".btn-setting").click(function(evento){
                var request = $(this).attr("id");
                var requestTitle = $(this).attr("title");
                    $.ajax({
                        type: "POST",
                        url: "/admin/orders/get-order-information?transaction="+request,
                        data: { id: $(this).id }
                    }).done(function(response) {
                        $("#myModal").find(".modal-body").html(response);
                    });
                });
                
                $(".btn-tracking").click(function(e){
                    e.preventDefault();
                    $("#myModalTracking").modal("show");
                    var request = $(this).attr("id");
                    var requestTitle = $(this).attr("title");
                    $.ajax({
                        type: "POST",
                        url: "/admin/orders/get-tracking?transaction="+request,
                        data: { id: $(this).id }
                    }).done(function(response) {
                        $("#myModalTracking").find(".modal-body").html(response);
                    });
                }); 
        
                $(".btn-returning").click(function(e){
                    e.preventDefault();
                    $("#myModalReturn").modal("show");
                });
            });
        ');
        $this->view->headScript()->appendScript('
            $(document).ready(function(){
            
                $("#countries").change(function(evento){
                
                $.ajax({
                        type: "POST",
                        url: "/admin/orders/get-state?country="+$(this).attr("value"),
                        data: { id: $(this).id }
                    }).done(function(data) {
                        $("#state").empty();
                        $.each(data, function(index, value) { 
                            $("#state").append("<option value=\'"+value["id"]+"\'>"+value["name"]+"</option>");
                        });
                        $("#state").trigger("liszt:updated");
                    });
                    
                });
            });
        ');
        
        $this->view->headScript()->appendScript('
            $(document).ready(function(){
            $("#filter").css("display", "none");
            $("#plusFilter").click(function () {        
                $("#filter").toggle("slow",function(){
                 if($("#filter").css("display") == "none"){
                $("#plusFilter").attr("class", "icon-plus");
                }else{
                $("#plusFilter").attr("class", "icon-minus");
                }
              });
            }); 
            });
        ');
        
        $this->view->headScript()->appendScript('
            $(document).ready(function(){
                $(".btn-returning").click(function(evento){
                    $("#yesReturn").attr("data",$(this).attr("href"));    
                });
                
                $("#yesReturn").click(function(evento){
                    window.location.href = $(this).attr("data");
                });
            });
        ');
        
        $modelRegions = new Application_Model_Regions();
        $request = $this->getRequest();
        $arrayData = $this->getRequest()->getQuery();
        
        $this->view->urlDownload = str_replace("orders", "orders/download", $_SERVER['REQUEST_URI']);
        
        if( empty($arrayData) ){
            $arrayData['status'][] = Application_Entity_Transaction::TRANSACTION_PAID;
        }
        
        $this->view->fromDate = (isset($arrayData['fromDate']) && $arrayData['fromDate'] != '') ? $arrayData['fromDate'] : '';
        $this->view->toDate = (isset($arrayData['toDate']) && $arrayData['toDate'] != '') ? $arrayData['toDate'] : '';
        $this->view->menbers = isset($arrayData['menbers']) ? $arrayData['menbers'] : array();
        $this->view->status = isset($arrayData['status']) ? $arrayData['status'] : array();
        $this->view->stateDelivered = isset($arrayData['stateDelivered']) ? $arrayData['stateDelivered'] : array();
        $this->view->countrySelect = isset($arrayData['countries']) ? $arrayData['countries'] : array();
        if (!empty($this->view->countrySelect)) {
            $this->view->subRegions = $modelRegions->listingSubregions($arrayData['countries']);
            $this->view->subRegionsSelect = isset($arrayData['state']) ? $arrayData['state'] : array();
        } else {
            $this->view->subRegions = array();
            $this->view->subRegionsSelect = '';
        }
        if (isset($arrayData['fromDate']) && $arrayData['fromDate'] != '') {
            $data = explode('/', $arrayData['fromDate']);
            $arrayData['fromDate'] = $data[2] . '-' . $data[0] . '-' . $data[1];
        } else {
            unset($arrayData['fromDate']);
        }
        if (isset($arrayData['toDate']) && $arrayData['toDate'] != '') {
            $data = explode('/', $arrayData['toDate']);
            $arrayData['toDate'] = $data[2] . '-' . $data[0] . '-' . $data[1];
        } else {
            unset($arrayData['toDate']);
        }
        if (!(isset($arrayData['countries']) && $arrayData['countries'] != '')) {
            unset($arrayData['countries']);
        }
        
        $this->view->orders = Application_Entity_Transaction::listOrders($arrayData);
        $this->view->userOrders = Application_Entity_Transaction::listOrdensUsers();
        $this->view->country = $modelRegions->listing(array(840));
    }

    public function getOrderInformationAction() {
        $this->_helper->layout()->disableLayout();
        
        $idTransaction = $this->getParam('transaction');
        
        $transsaction = new Application_Entity_Transaction();
        $data = $transsaction->identify($idTransaction);
        $nameMember = $this->getParam('member');
        $modelRegions = new Application_Model_Regions();
        $modelsubRegion = new Application_Model_SubRegions();
        $this->view->country = Core_Utils::fetchPairs($modelRegions->listing(), 4);
        //$subregions = Core_Utils::fetchPairs($modelRegions->listingSubregions());
        
        $data['contact'] = 'Member: ' . $nameMember;
        $data['mail'] = 'Mail: ' . $data['transaction_mail'];

        if($data['transaction_shi_add_subregion_id']){
            $subregion = $modelsubRegion->getSubRegion($data['transaction_shi_add_subregion_id']);
            $this->view->subregion_shp = $subregion['name'];
        }
        
        if($data['transaction_bill_add_region_id']){
            $subregion = $modelsubRegion->getSubRegion($data['transaction_bill_add_subregion_id']);
            $this->view->subregion_bill = $subregion['name'];
        }
        
        $this->view->data = $data;
    }
    
    public function getTrackingAction(){
        $this->_helper->layout()->disableLayout();
        
        $idTransaction = $this->getParam('transaction');
        
        $modelTraking = new Application_Model_Tracking();
        $data = $modelTraking->getTrakingByTransaction($idTransaction);
        
        $data = unserialize($data['tracking_code']);
        
        $this->view->data = $data;
        
        $_product = new Application_Entity_Product();
        
        $productos_vistos = array();
        if(is_array($data)){
            foreach($data as $action){
                if( isset($action['data']['code']) && !empty($action['data']['code']) ){
                    if( !isset($productos_vistos[$action['data']['code']]) && (int)$action['data']['code']>0){
                        $_product->identify($action['data']['code']);
                        $tmpProperties = $_product->getProperties();
                        $tmpProperties['images'] = $_product->listingImg();
                        $productos_vistos[$action['data']['code']] = $tmpProperties;
                    }
                }
            }
        }
        
        $this->view->productos_vistos = $productos_vistos;
        
        
    }
    

    public function getStateAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $modelRegions = new Application_Model_Regions();
        $idRegion = $this->getParam('country');
        $array = $modelRegions->listingSubregions($idRegion);
        $this->_helper->json($array);
    }
    
    public function returningAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->identify($this->getParam('id'));
        $transaction->returned();
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }

    public function deliveredAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->identify($this->getParam('id'));
        $transaction->delivered();
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }

    public function undeliveredAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->identify($this->getParam('id'));
        $transaction->undelivered();
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }

    public function shippingAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->shipping($this->getParam('id'));
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }

    public function unshippingAction() {
        $transaction = new Application_Entity_Transaction();
        $transaction->unshipping($this->getParam('id'));
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function createExcel(){
        
    }
    
    public function downloadAction() {
        
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $modelRegions = new Application_Model_Regions();
        $request = $this->getRequest();
        $arrayData = $this->getRequest()->getQuery();
        
        if( empty($arrayData) ){
            $arrayData['status'][] = Application_Entity_Transaction::TRANSACTION_PAID;
        }
        
        if (isset($arrayData['fromDate']) && $arrayData['fromDate'] != '') {
            $data = explode('/', $arrayData['fromDate']);
            $arrayData['fromDate'] = $data[2] . '-' . $data[0] . '-' . $data[1];
        } else {
            unset($arrayData['fromDate']);
        }
        if (isset($arrayData['toDate']) && $arrayData['toDate'] != '') {
            $data = explode('/', $arrayData['toDate']);
            $arrayData['toDate'] = $data[2] . '-' . $data[0] . '-' . $data[1];
        } else {
            unset($arrayData['toDate']);
        }
        if (!(isset($arrayData['countries']) && $arrayData['countries'] != '')) {
            unset($arrayData['countries']);
        }
        
        $orders = Application_Entity_Transaction::listOrders($arrayData);
        
        require_once APPLICATION_PATH.'/../library/phpexcel/PHPExcel.php';
        require_once APPLICATION_PATH.'/../library/phpexcel/PHPExcel/Writer/Excel2007.php';
        
        $excel = new PHPExcel();
        
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
                          
        $styleBorder = array(
          'borders' => array(
            'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
        
        //Definir propiedades del objeto
        $properties = $excel->getProperties();
        
        $properties->setCreator("WTA System");
        $properties->setLastModifiedBy("WTA System");
        $properties->setTitle("Orders Report");
        $properties->setSubject("Orders Report");
        $properties->setDescription("This document is a list of exported orders of WTA system.");
        $sheet = $excel->getActiveSheet();
            
        $letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");
        
        $order_titles = array(
                            "transaction_id" => "Order Id",
                            "transaction_payment_date" => "Date",
                            "transaction_amount" => "Amount",
                            //"transaction_user_menbership" => "User Membership",
                            //"member_id" => "Member Id",
                            "member_name" => "Member Name",
                            "member_last_name" => "Member Last Name",
                            "transaction_register_date" => "Created Date",
                            //"tansaction_state_id" => "State Id",
                            "tansaction_state_name" => "State Name",
                            //"transaction_delivered" => "Delivered",
                            "transaction_delivered_date" => "Delivered Date"
                        );
        
        $n = 0;
        $row = 1;
        
        if($order_titles) foreach($order_titles as $key => $value){
            $sheet->SetCellValue($letters[$n].$row, $value);
            $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
            $sheet->getStyle($letters[$n].$row)->applyFromArray($negrita);
            $n++;
        }
        
        $product_titles = array(
                            5 => "Code",
                            0 => "Product",
                            1 => "Quantity",
                            2 => "G. Price",
                            3 => "M. Price",
                            4 => "Size",
                            6 => "Shipped"
                        );
        
        if($product_titles) foreach($product_titles as $key => $value){
            $sheet->SetCellValue($letters[$n].$row, $value);
            $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
            $sheet->getStyle($letters[$n].$row)->applyFromArray($negrita);
            $n++;
        }
        
        $row++;
        
        if(!empty($orders)) foreach($orders as $order){
            $n = 0;
            $arrayProducts = explode('[]', $order['product']);
            foreach ($arrayProducts as $p) {
                if($order_titles) foreach($order_titles as $key => $value){
                    $sheet->SetCellValue($letters[$n].$row, $order[$key]);
                    $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                    $n++;
                }
                
                $product = explode('|', $p);
                
                if($product_titles) foreach($product_titles as $key => $value){
                    $sheet->SetCellValue($letters[$n].$row, $product[$key]);
                    $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                    $n++;
                }
                
                $last = $n;
                
                $n = 0;
                $row++;    
            }
        }
 
        $sheet->getStyle('A1:'.$letters[$last - 1].($row - 1))->applyFromArray($styleBorder);
        
        $sheet->setTitle('Orders');
        
        $excel->createSheet();
        
        $excel->setActiveSheetIndex(1);
        $sheet = $excel->getActiveSheet();
            
        $row = 2;
        if(!empty($orders)) foreach($orders as $order){
            $n = 1;
            
            $start = $letters[$n].$row;
            
            if($order_titles) foreach($order_titles as $key => $value){
                $sheet->SetCellValue($letters[$n].$row, $value);
                $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                $sheet->getStyle($letters[$n].$row)->applyFromArray($negrita);
                
                $sheet->SetCellValue($letters[$n].($row + 1), $order[$key]);
                $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                $n++;
            }
            
            $row++;
            $row++;
            
            $product_titles = array(
                            5 => "Code",
                            0 => "Product",
                            1 => "Quantity",
                            2 => "G. Price",
                            3 => "M. Price",
                            4 => "Size",
                            6 => "Shipped"
                        );
        
            $row++;
            
            $n = 2;
            if($product_titles) foreach($product_titles as $key => $value){
                $sheet->SetCellValue($letters[$n].$row, $value);
                $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                $sheet->getStyle($letters[$n].$row)->applyFromArray($negrita);
                $n++;
            }
            
            $row++;
            
            $n = 2;
            $arrayProducts = explode('[]', $order['product']);
            foreach ($arrayProducts as $p) {
                $product = explode('|', $p);
                
                if($product_titles) foreach($product_titles as $key => $value){
                    $sheet->SetCellValue($letters[$n].$row, $product[$key]);
                    $sheet->getColumnDimension($letters[$n])->setAutoSize(true);
                    $n++;
                }
                
                $last = $n;
                
                $n = 2;
                $row++;    
            }
            
            $end = $letters[$last - 1].$row;
            
            $sheet->getStyle($start.':'.$end)->applyFromArray($styleBorder);
            
            $row++;
            $row++;
        }
        
        $sheet->setTitle('Detailed Orders');
        
        $excel->setActiveSheetIndex(0);
        $objWriter = new PHPExcel_Writer_Excel2007($excel);
        
        header('content-type: application/zip');
        header('content-disposition: inline; filename="orders.xlsx"');
        
        $objWriter->save('php://output');
        
        //if(!empty($members)) foreach($members as $member){
        //    echo $member["member_name"]." ".$member["member_last_name"]."<br>";
        //}
        
        die();
    }
    
}