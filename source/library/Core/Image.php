<?php
 require_once APPLICATION_PATH . '/../library/phpthumbs/ThumbLib.inc.php';
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class Core_Image {
 
   var $image;
   var $file;
 
   function load($filename) {
       $this->file = $filename;
       $this->image = PhpThumbFactory::create($filename);
   }
   function save($filename) {
        $this->image->save($filename);
   }
   function resize($width,$height,$proportional) {
       $this->image->resize(100, 100);
   }      
 
}
