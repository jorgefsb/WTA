<?php

class Admin_IndexController extends Core_Controller_ActionAdmin {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        
    }

    function redimensionarImatge($imatge, $extensio, $amplada=FALSE, $alcada=FALSE) {
        $original = imagecreatefromstring($imatge);
        $mides[] = imagesx($original); // $mides[0] ANCHURA
        $mides[] = imagesy($original); //  $mides[1] ALTURA
//echo '&lt;br&gt;AMPLADA R:'.$amplada;
//echo '&lt;br&gt;AMPLADA R:'.$alcada;
//echo '&lt;br&gt;AMPLADA:'.$mides[0];
//echo '&lt;br&gt;AMPLADA:'.$mides[1];

        if (!( ($mides[0] == $amplada or $amplada == FALSE) AND ($mides[1] == $alcada or $alcada == FALSE) )) { //comprueba Que no se redimendsione al mismo tamaño Critian 19-08-2008
            if ($amplada || $alcada) {
                $midaHoritzontal = $amplada;
                $midaVertical = $alcada;

                if ($alcada &&
                        ($mides[0] ==
                        (2 * $mides[1]))) {
                    unset($alcada);
                    $midaHoritzontal = 100;
                } elseif (!$alcada) {
                    $midaVertical = $mides[1];
                }
            } elseif ($mides[0] >= MIDA_IMATGE_AMPLADA && $mides[1] >= MIDA_IMATGE_ALCADA) {
                $midaHoritzontal = $mides[0];
                $midaVertical = $mides[1];
                $noRedimensionar = true;
            } else {
                $midaHoritzontal = MIDA_IMATGE_AMPLADA;
                $midaVertical = MIDA_IMATGE_ALCADA;
            }
        } else {
            $midaHoritzontal = $mides[0];
            $midaVertical = $mides[1];
            $noRedimensionar = true;
        }

        if (!isset($noRedimensionar)) {
            unset($imatge);

            if ($midaHoritzontal) {
                $ratio = ($mides[0] / $midaHoritzontal);
                $midaVertical = round($mides[1] / $ratio);
            } else {
                $ratio = ($mides[1] / $midaVertical);
                $midaHoritzontal = round($mides[0] / $ratio);
            }

            $thumb = imagecreatetruecolor($midaHoritzontal, $midaVertical);

            switch ($extensio) {
                case 'image/pjpeg':
                case 'image/jpeg':
                    imagecopyresampled($thumb, $original, 0, 0, 0, 0, $midaHoritzontal, $midaVertical, $mides[0], $mides[1]);
                    imagejpeg($thumb, '../tempImage');
                    break;

                case 'image/gif':
                case 'image/png':
                    $colorTransparancia = imagecolortransparent($original); // devuelve el color usado como transparencia o -1 si no tiene transparencias
                    if ($colorTransparancia != -1) { //TIENE TRANSPARENCIA
//unset($thumb);
//$thumb = imagecreatetruecolor($midaHoritzontal,$midaVertical);
                        $colorTransparente = imagecolorsforindex($original, $colorTransparancia); //devuelve un array con las componentes de lso colores RGB + alpha
                        $idColorTransparente = imagecolorallocatealpha($thumb, $colorTransparente['red'], $colorTransparente['green'], $colorTransparente['blue'], $colorTransparente['alpha']); // Asigna un color en una imagen retorna identificador de color o FALSO o -1 apartir de la version 5.1.3

                        imagefill($thumb, 0, 0, $idColorTransparente); // rellena de color desde una cordenada, en este caso todo rellenado del color que se definira como transparente
                        imagecolortransparent($thumb, $idColorTransparente); //Ahora definimos que en el nueva imagen el color transparente será el que hemos pintado el fondo.
                        imagecopyresampled($thumb, $original, 0, 0, 0, 0, $midaHoritzontal, $midaVertical, $mides[0], $mides[1]); // copia y redimensiona un trozo de imagen
//imagecopyresized
                    }

                    switch ($extensio) {
                        case 'image/gif':
                            imagegif($thumb, '../tempImage');
                            break;
                        case 'image/png':
                            imagepng($thumb, '../tempImage');
                            break;
                    }

                default:
                    imagecopyresampled($thumb, $original, 0, 0, 0, 0, $midaHoritzontal, $midaVertical, $mides[0], $mides[1]);
                    imagejpeg($thumb, '../tempImage');

                    break;
            }

//Lliurem la memòria
            unset($original);
            unset($thumb);

            if (function_exists('file_get_contents')) {
                $imatge = file_get_contents('../tempImage');
            } elseif ($file_to_insert_size = filesize('../tempImage')) {
                $imatge = fread(fopen('../tempImage', 'rb'), $file_to_insert_size);
            }

//eliminació de la imatge del servidor
            @unlink('../tempImage');
        }

        return $imatge;
    }

}

