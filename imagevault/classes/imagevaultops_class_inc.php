<?php
/**
 *
 * imagevault helper class
 *
 * PHP version 5.1.0+
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
 * @package   imagevault
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2011 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (! /**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
// end security check


/**
 *
 * imagevault helper class
 *
 * PHP version 5.1.0+
 *
 * @author Paul Scott
 * @package imagevault
 *
 */
class imagevaultops extends object {

    /**
     * @var string $objLanguage String object property for holding the language object
     *
     * @access public
     */
    public $objLanguage;

    /**
     * @var string $objConfig String object property for holding the config object
     *
     * @access public
     */
    public $objConfig;

    /**
     * @var string $objSysConfig String object property for holding the sysconfig object
     *
     * @access public
     */
    public $objSysConfig;

    /**
     * @var string $objUser String object property for holding the user object
     *
     * @access public
     */
    public $objUser;
    
    /**
     * @var array $data Object property for holding the data
     *
     * @access public
     */
    public $data = array();

    /**
     * Constructor
     *
     * @access public
     */
    public function init() {
        $this->objLanguage   = $this->getObject('language', 'language');
        $this->objConfig     = $this->getObject('altconfig', 'config');
        $this->objSysConfig  = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $this->objUser       = $this->getObject('user', 'security');
        $this->objCC         = $this->getObject('displaylicense', 'creativecommons');
        $this->objDbVault    = $this->getObject('dbvault');
        $this->objDateTime   = $this->getObject('dateandtime', 'utilities');
        $this->objExif       = $this->getObject('exifmeta', 'metadata');
        $this->objIPTC       = $this->getObject('iptcmeta', 'metadata');
    }
    
    /**
     * Method to display the login box for prelogin operations
     *
     * @param  bool   $featurebox
     * @return string
     */
    public function loginBox($featurebox = FALSE)
    {
        $objBlocks = $this->getObject('blocks', 'blocks');
        if ($featurebox == FALSE) {
            return $objBlocks->showBlock('login', 'security') . "<br />" . $objBlocks->showBlock('register', 'security');
        } else {
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            return $objFeatureBox->show($this->objLanguage->languageText("word_login", "system") , $objBlocks->showBlock('login', 'security', 'none')
              . "<br />" . $objBlocks->showBlock('register', 'security', 'none') );
        }
    }
    
    public function insertImageData($userid, $image) {
        $meta = $this->getMetaFromImage($image);
        $data = file_get_contents($image);
        $hash = sha1($data);
        $thumb = $this->objExif->getExifThumb($image, 200, 200);
        $tarr = explode(",", $thumb);
        if($tarr[1] == "'>") {
            $thumb = "<img  width='200' height='200' src='data:image;base64,".base64_encode(file_get_contents($this->objConfig->getsiteRootPath()."skins/_common/icons/noimage200.jpg"))."'>";
        }
        $license = $this->objDbVault->getLicense($userid);
        if($license === FALSE) {
            $license = 'copyright';
        }
        $insarr = array('userid' => $userid,
                   'filename' => $meta['FILEFileName'] ,
                   'thumbnail' => $thumb,
                   'hash' => $hash,
                   'license' => $license,
                   'metadataid' => NULL,
                   );
                   
        $imgid = $this->objDbVault->insertImage($userid, $insarr);

        // now set up all the metadata
        // do all the checks (I am sure there is a better way to do this)
        $arrofkeys = array('FILEFileSize', 'FILEFileName', 'IFD0Make', 'IFD0Model', 'EXIFExposureTime', 'COMPUTEDApertureFNumber', 'COMPUTEDUserComment', 'EXIFISOSpeedRatings', 'FILEFileDateTime',
                           'EXIFFocalLength', 'IFD0Orientation', 'IFD0XResolution', 'IFD0YResolution', 'IFD0Software', 'IFD0YCbCrPositioning', 'EXIFExifVersion', 'EXIFDateTimeDigitized',
                           'EXIFShutterSpeedValue', 'EXIFApertureValue', 'EXIFExposureBiasValue', 'EXIFMaxApertureValue', 'EXIFMeteringMode', 'EXIFFlash', 'EXIFColorSpace', 'EXIFSensingMethod');
        foreach($arrofkeys as $key) {
            if(!array_key_exists($key, $meta)) {
                $meta[$key] = NULL;
            }
        }
        
        $metains = array('imageid' => $imgid,
                         'userid' => $userid,
                         'filesize' => $meta['FILEFileSize'],
                         'filename' => $meta['FILEFileName'],
                         'cam_man' => $meta['IFD0Make'],
                         'cam_model' => $meta['IFD0Model'],
                         'exp_time' => $meta['EXIFExposureTime'],
                         'fnumber' => $meta['COMPUTEDApertureFNumber'],
                         'usercomment' => $meta['COMPUTEDUserComment'],
                         'iso' => $meta['EXIFISOSpeedRatings'],
                         'picdatetime' => $meta['FILEFileDateTime'],
                         'focallength' => $meta['EXIFFocalLength'],
                         'orientation' => $meta['IFD0Orientation'],
                         'xres' => $meta['IFD0XResolution'],
                         'yres' => $meta['IFD0YResolution'],
                         'software' => $meta['IFD0Software'],
                         // 'modificationdatetime' => $meta[''],
                         'ycpos' => $meta['IFD0YCbCrPositioning'],
                         'exifver' => $meta['EXIFExifVersion'],
                         'digidatetime' => $meta['EXIFDateTimeDigitized'],
                         'shutterspeed' => $meta['EXIFShutterSpeedValue'],
                         'aperture' => $meta['EXIFApertureValue'],
                         'ev' => $meta['EXIFExposureBiasValue'],
                         'maxlandaperture' => $meta['EXIFMaxApertureValue'],
                         'meteringmode' => $meta['EXIFMeteringMode'],
                         'flash' => $meta['EXIFFlash'],
                         'colourspace' => $meta['EXIFColorSpace'],
                         'sensingmethod' => $meta['EXIFSensingMethod'],
                         );
        
        $metaid = $this->objDbVault->insertMeta($metains);        
        // update the images table with the metaid
        $this->objDbVault->updateImageWithMetaId($imgid, $metaid);
        
        // finally we need the all important IPTC keywords with the cookie
        $kwordsarr = $meta['iptc'];
        // var_dump($kwordsarr);
        foreach($kwordsarr as $keys) {
            $kinsarr = array('userid' => $userid,
                             'metadataid' => $metaid,
                             'imageid' => $imgid,
                             'keyword' => $keys,
                            );
            $this->objDbVault->insertKeywords($kinsarr);
        }
                   
    }
    
    public function getMetaFromImage($image) {
        $this->objIPTC->setImage($image);
        $valid = $this->objIPTC->isValid();
        //var_dump($valid);
        if($valid == TRUE) {
            // EXIF
            $imagetype = $this->objExif->getImageType($image);
            $headers = $this->objExif->readHeaders($image);
            $thumb = $this->objExif->getExifThumb($image, 200, 200);
            
            // move through the headers array and make a resonable array from it
            $edata = array();
            foreach($headers as $header) {
                $edata[$header[0]] = $header[1];
            }
            // build up the array of data we want
            $insarr = array();
            // IPTC
            
            $tagarr = $this->objIPTC->getAllTags();
            $copyarr = $tagarr['2#116'];
            $keywords = $tagarr['2#025'];
            $iptc = array('iptc' => array_merge($copyarr, $keywords));
            $data = array_merge($edata, $iptc);
            
            return $data;
        }
        
        
        
    }
    
    public function createthumb($name, $new_w, $new_h)
    {
        $filename = $name."_tn";
	    $system = explode(".", $name);
	    if(count($system) == 3) {
	        $sys = $system[2];
	    }
	    else {
	        $sys = $system[1];
	    }
	    if (preg_match("/jpg|jpeg|JPG/", $sys)) {
	        $src_img = imagecreatefromjpeg($name);
	    }
	    if (preg_match("/png/", $sys)) {
	        $src_img = imagecreatefrompng($name);
	    }
	    $old_x = imageSX($src_img);
	    $old_y = imageSY($src_img);
	    if ($old_x > $old_y) 
	    {
		    $thumb_w = $new_w;
		    $thumb_h = $old_y*($new_h/$old_x);
	    }
	    if ($old_x < $old_y) 
	    {
		    $thumb_w = $old_x*($new_w/$old_y);
		    $thumb_h = $new_h;
	    }
	    if ($old_x == $old_y) 
	    {
		    $thumb_w = $new_w;
		    $thumb_h = $new_h;
	    }
	    $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
	    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y); 
	    if (preg_match("/png/", $system[1]))
	    {
		    imagepng($dst_img, $filename); 
	    } else {
		    imagejpeg($dst_img, $filename); 
	    }
	    imagedestroy($dst_img); 
	    imagedestroy($src_img); 
	    
	    return file_get_contents($filename);
    }  
}
?>
