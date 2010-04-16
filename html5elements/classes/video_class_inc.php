<?php
/**
 * video_class_inc.php
 *
 * This is a class to create and manipulate the HTML5 Video tag for displaying video files in browser
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   html5elements
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

// Include the HTML base class
//require_once("abhtmlbase_class_inc.php");
// Include the HTML interface class
//require_once("ifhtml_class_inc.php");

/**
 * Video tag class.
 *
 *
 * @category  Chisimba
 * @package   html5elements
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: $
 * @link      http://avoir.uwc.ac.za
 *
 * @example
 * $objVideo = $this->getObject('video', 'html5elements');
 * $objVideo->setVideo(198, 192, 'http://173.203.201.87:8000/theora.ogg', 'ogg', TRUE, FALSE, FALSE); // for streaming video from a URL
 * 
 * @example 
 * $objVideo = $this->getObject('video', 'html5elements');
 * $objVideo->setVideo(198, 192, 'theora.ogg', 'ogg', TRUE, TRUE, FALSE); // for a file
 */
class video extends object
{
    public $width;
    public $height;
    public $controls;
    public $preload = "none";
    public $autoplay = "none";
    public $src;
    public $videoId = "movie";
    
    private $oggType = 'video/ogg; codecs="theora, vorbis"';
    private $mp4Type = 'video/mp4; codecs="avc1.42E01E, mp4a.40.2"';
    private $h264Type = 'video/mp4; codecs="avc1.42E01E, mp4a.40.2"';
    private $ieCss;
    private $type;
    
    public function init() {
    
    }
    
    public function setVideo($height, $width, $src, $format = 'ogg', $controls = TRUE, $preload = TRUE, $autoplay = FALSE ) {
        $this->_addCSS();
        $this->_addFlowplayer();
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setSrc($src);
        $this->setType($format);
        $this->setControls($controls);
        $this->setPreload($preload);
        $this->setAutoplay($autoplay);
    }
    
    public function setHeight($height) {
        $this->height = $height;
    }
    
    public function setWidth($width) {
        $this->width = $width;
    }
    
    public function setSrc($src) {
        $this->src = $src;
    }
    
    public function setControls($controls) {
        if($controls == TRUE) {
            $this->controls = "controls";
        }
    }
    
    public function setId($id) {
        $this->videoId = $id;
    }
    
    public function setPreload($preload) {
        if($preload == TRUE) {
            $this->preload = "preload";
        }
        else {
            $this->preload = "none";
        }
    }
    
    public function setAutoplay($autoplay) {
        if($autoplay == TRUE) {
            $this->autoplay = "autoplay";
        }
        else {
            $this->autoplay = "none";
        }
    }
    
    public function setType($format) {
        switch($format) {
            case 'ogg':
                $this->type = $this->oggType;
                break;
            case 'mp4':
                $this->type = $this->mp4Type;
                break;
            case 'h264':
                $this->type = $this->h264Type;
                break;
        }
    }
    
    public function show() {
        $vid = NULL;
        $vid .= '<video id="'.$this->videoId.'" width="'.$this->width.'" height="'.$this->height.'" '.$this->preload.' '.$this->controls.'>';
        $vid .= '<source src="'.$this->src.'" type=\''.$this->type.'\'>';
        $vid .= '</video>';
        
        return $vid;
    }
    
    private function _addCSS() {
        $this->css = '<!--[if IE]>'.$this->getJavascriptfile('html5.js', 'html5elements').'<![endif]-->';
        $this->appendArrayVar('headerParams', $this->css);
    }
    
    private function _addFlowplayer() {
        $this->getJavascriptfile('flowplayer-3.1.4.min.js', 'html5elements');
        $this->getJavascriptfile('html5-video.js', 'html5elements');
    }

}
