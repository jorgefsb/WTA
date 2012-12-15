<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mail
 *
 * @author Laptop
 */
class Core_Tracking {

    protected $_log = array();

    public function __construct() {
        
    }
    
    /*
     * El metodo setAction() agrega un registro al historial de eventos
     * 
     * @param $name     Nombre de la accion
     * @param $url          Url donde se genero la accion
     * @param $date       Fecha cuando ocurrio  
     * @param $data       Datos relevantes en el evento
     * @param $urlRef     Url de donde vino y se realizo la accion
     */
    public function setAction($name, $url, $data='', $urlRef='', $date=null){
        $this->_log[] =array(
          'name' => $name,
          'url' => $url,
          'data' => $data,
          'urlRef' => $urlRef,
          'date' => ($date ? $date : date('Y-n-d H:i:s'))
        );
    }
    
    public function getLog(){
        return $this->_log;
    }
    
    
    public function clear(){
        $this->_log = array();
    }
    
    
        
}

?>
