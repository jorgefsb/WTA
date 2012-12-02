<?php

/**
 * S view Helpers is a Loader for Static files
 * providing ability to read prefix from config 
 * and suffix a versioning query string 
 *
 * @author eanaya
 */
class Core_View_Helper_GetMessegerAdmin extends Zend_View_Helper_Abstract {

    /**
     * @param  String
     * @return string
     */
    public function getMessegerAdmin() {

        $message = new Core_Controller_Action_Helper_FlashMessengerCustom();
        $array = $message->getMessages();
        $arrayClass = array(
            'info' => 'alert alert-info',
            'success' => 'alert alert-success',
            'warning' => 'alert alert-block',
            'error' => 'alert alert-error',
            'debug' => 'alert alert-block',);
        if (count($array) > 0) {
            echo '<div class="box-content alerts">';
            
            foreach ($array as $index) {
                echo'<div class="' . $arrayClass[$index->level] . '">';
                echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                echo $index->message . '<p>';
                echo'</div>';
            }
            echo '</div>';
        }
    }

}