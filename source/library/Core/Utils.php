<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utils
 *
 * @author Laptop
 */
class Core_Utils {

    static function arrayAsoccForFirstItem($array, $key='') {
        $arrayResponse = array();
        if ($key == '') {
            foreach ($array as $index => $data) {
                $arrayResponse[$data[key($data)]][] = $data;
            }
        } else {
            foreach ($array as $index => $data) {
                $arrayResponse[$data[$key]][] = $data;
            }
        }
        return $arrayResponse;
    }

    static function fetchPairs($array = array(),$key=1) {
        
        if(!is_array($array))
            return array();
        $arrayResponse = array();
        foreach ($array as $index => $datos) {
            $keys = array_keys($datos);
            $arrayResponse[$datos[$keys[0]]] = $datos[$keys[$key]];
        }
        return $arrayResponse;
    }

    static function parseUrlVimeo($uri){
        $curl = curl_init('http://vimeo.com/api/oembed.json?url=' . $uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($curl);
        if($result=='404 Not Found'){
            return FALSE;
        }else{
            return $result;
        }
    }
    static function validateUrlVimeo($uri) {
        $result = array();
        $response = self::parseUrlVimeo($uri);
        if ($response != FALSE) {
            $result = Zend_Json::decode($response);
            $result['message'] = '';
            $result['html'] = '<iframe width="590" height="332" src="http://player.vimeo.com/video/'.$result['video_id'].'" frameborder="0" allowfullscreen></iframe>';
            $result['validate'] = TRUE;
            $result['type'] = 'Vimeo';
        } else {
            $result['message'] = 'no se encontro la ruta';
            $result['validate'] = FALSE;
        }
        return $result;
    }

    static function convertUrlQuery($query) {
        if ($query != '') {
            $queryParts = explode('&', $query);
            $params = array();
            foreach ($queryParts as $param) {
                $item = explode('=', $param);
                $params[$item[0]] = $item[1];
            }
            return $params;
        }
    }

    static function validateUrlYoutube($uri='') {
        $arrayReturn = array();
        if ($uri == '') {
            $arrayReturn['validate'] = false;
            return $arrayReturn;
        }
        $array = parse_url($uri);
        if (isset($array['query']))
            $array = self::convertUrlQuery($array['query']);

        $yt = new Zend_Gdata_YouTube();
        //$objUri = Zend_Uri::check($uri);
        //exit;
        $arrayReturn['validate'] = TRUE;
        
        if (Zend_Uri::check($uri)) {
            try {
                $videoEntry = $yt->getVideoEntry($array['v']);
                $obj = new ReflectionClass($videoEntry);
               // print_r($obj->getMethods());
                $arrayReturn['video'] = $videoEntry->getVideoTitle();
                $arrayReturn['videoID'] = $videoEntry->getVideoId();
                $arrayReturn['updated'] = $videoEntry->getUpdated();
                $arrayReturn['description'] = $videoEntry->getVideoDescription();
                $arrayReturn['thumbnails'] = $videoEntry->getVideoThumbnails();
                $arrayReturn['thumbnail_url'] = $arrayReturn['thumbnails'][0]['url'];
                $arrayReturn['source'] = $videoEntry->getVideoResponsesLink();
                $arrayReturn['category'] = $videoEntry->getVideoCategory();
                $arrayReturn['tags'] = implode(", ", $videoEntry->getVideoTags());
                $arrayReturn['watchPage'] = $videoEntry->getVideoWatchPageUrl();
                $arrayReturn['flashPlayerUrl'] = $videoEntry->getFlashPlayerUrl();
                $arrayReturn['duration'] = $videoEntry->getVideoDuration();
                $arrayReturn['viewCount'] = $videoEntry->getVideoViewCount();
                $arrayReturn['message'] = $videoEntry->getVideoViewCount();
                $arrayReturn['html'] = '<iframe width="590" height="332" src="'.$arrayReturn['flashPlayerUrl'].'" frameborder="0" allowfullscreen></iframe>';
                $arrayReturn['type'] = 'Youtube';
            } catch (Exception $e) {
                $arrayReturn['validate'] = FALSE;
                $arrayReturn['message'] = $e->getMessage();
            }
        } else {
            $arrayReturn['validate'] = FALSE;
            $arrayReturn['message'] = 'La ruta no es valida';
        }

        return $arrayReturn;
    }
    static function getRandomString($length = 10){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen( $chars );
        $str='';
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}
	return md5($str);
    }

}

