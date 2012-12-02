<?php
/**
* @autor @edyluisrey
* descripcion: class REST(GET,POST,PUT,DELETE)
*/
class App_Controller_Action_Helper_GetResultApi extends Zend_Controller_Action_Helper_Abstract
{
    /**
    * consume datos desde un URI por get.
    *
    * @return string result xml si es correcto y error en caso de falla 
    * @param string $url la direccion url de la api
    * @param string $username credencial para acceder a la proteccion http autentification
    * @param string $password credencial para acceder a la proteccion http autentification
    */
    public function _getApiResult($url,$username,$password)
    {          
        $ip = $_SERVER["SERVER_ADDR"];
        /** Ubicación del certificado digital del servidor web con SSL del API */    
        $browser_id = "Mozilla/5.0 (Windows; U; Windows NT 5.1; es-ES; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); // direccion a capturar
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($curl, CURLOPT_USERAGENT, $browser_id);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_REFERER, $ip);
               
        /*if(API_USE_SSL) {
            curl_setopt($curl, CURLOPT_PORT , 443); 
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSLVERSION, API_SSL_VERSION);
            curl_setopt($curl, CURLOPT_CAINFO, API_SSL_CERTIFICADO_PATH);
        }
        if(API_USE_PROXY) {
            curl_setopt($curl, CURLOPT_PROXY, API_PROXY); 
            curl_setopt($curl, CURLOPT_PROXYPORT, API_PROXY_PORT); 
        }*/
        $result = curl_exec($curl);
        $error = curl_error($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if( $http_status != "200" ) {
            return $error;
        }        
        return $result;       
    }
       
    /**
    * Envio de datos por POST a una url REST.
    *
    * @return string result xml si es correcto y error en caso de falla 
    * @param string $url la direccion url de la api
    * @param string $username credencial para acceder a la proteccion http autentification
    * @param string $password credencial para acceder a la proteccion http autentification
    * @param string $dataxml data que se envia, en formato xml
    */
    public  function _postApiResult($url,$username,$password,$dataxml){

        //$dataxml= urlencode($dataxml);
        $ip = $_SERVER["SERVER_ADDR"];
        /** Ubicación del certificado digital del servidor web con SSL del API */    
        $browser_id = "Mozilla/5.0 (Windows; U; Windows NT 5.1; es-ES; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3";
        $curl = curl_init();        
        curl_setopt($curl, CURLOPT_URL,$url );        
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($curl, CURLOPT_USERAGENT, $browser_id);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$dataxml);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: text/xml'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_REFERER, $ip);
               
        /*if(API_USE_SSL) {
            curl_setopt($curl, CURLOPT_PORT , 443); 
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSLVERSION, API_SSL_VERSION);
            curl_setopt($curl, CURLOPT_CAINFO, API_SSL_CERTIFICADO_PATH);
        }
        if(API_USE_PROXY) {
            curl_setopt($curl, CURLOPT_PROXY, API_PROXY); 
            curl_setopt($curl, CURLOPT_PROXYPORT, API_PROXY_PORT); 
        }*/
         
        $result = curl_exec($curl);
        //Cerramos nuesta sesión
        $error = curl_error($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if( $http_status != "200" ) {
            return $error;
        }
        
        return $result;        
    }
    
    /**
    * convierte el xml en formato  array.
    *
    * @return array  retorna un array 
    * @param $contents xml el xml a convertir
    */
    public function xml2array($contents, $get_attributes=1, $priority = 'tag') 
    {
        if(!$contents) return array();

        if(!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if(!$xml_values) return;//Hmm...

        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference

        //Go through the tags.
        $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
        foreach($xml_values as $data) {
            unset($attributes,$value);//Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if(isset($value)) {
                if($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if(isset($attributes) and $get_attributes) {
                foreach($attributes as $attr => $val) {
                    if($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;
                if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag.'_'.$level] = 1;

                    $current = &$current[$tag];

                } else { //There was another element with the same tag name

                    if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                        $repeated_tag_index[$tag.'_'.$level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag.'_'.$level] = 2;

                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                    }
                    $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if(!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

                } else { //If taken, put all things inside a list(array)
                    if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                        if($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag.'_'.$level]++;

                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag.'_'.$level] = 1;
                        if($priority == 'tag' and $get_attributes) {
                            if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                                $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                                unset($current[$tag.'_attr']);
                            }

                            if($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                    }
                }

            } elseif($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }

        return($xml_array);
    }  
   
 }
