<?php
/**
 *
 * QRcode helper class
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
 * @package   qrcreator
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 AVOIR
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
 * QRCode helper class
 *
 * PHP version 5.1.0+
 *
 * @author Paul Scott
 * @package qrcreator
 *
 */
class qrops extends object {

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
     * @var string $objCurl String object property for holding the cURL object
     *
     * @access public
     */
    public $objCurl;
    
    /**
     * @var string $objDbQr String object property for holding the QR db object
     *
     * @access public
     */
    public $objDbQr;
    

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
        $this->objCurl       = $this->getObject('curl', 'utilities');
        $this->objDbQr       = $this->getObject('dbqr');
    }
    
    /**
     * Method to generate a QR Code and some additional linkeddata
     *
     * @access public
     * @param string userid A Chisimba userid, got from objUser->userId()
     * @param string $msg a message up to 4MB long to encode
     * @param string lat a latitude
     * @param string lon a longitude
     * @return array $ret
     */
    public function genQr($userid, $msg, $lat = 0, $lon = 0) {
        $code = urlencode($msg.'|'.$lon.','.$lat);
        $gmapsurl = "http://maps.google.com/maps?q=$lon,$lat+%28$msg%29&iwloc=A&hl=en";
        // insert the message to the database and generate a url to use via a browser
        $recordid = $this->objDbQr->insert(array('userid' => $userid, 'msg' => $msg, 'lat' => $lat, 'lon' => $lon, 'gmapsurl' => $gmapsurl));
        // curl the Google Charts API to create the code
        $url = 'http://chart.apis.google.com/chart?chs=200x180&cht=qr&chl='.$code;
        $image = $this->objCurl->exec($url);
        $basename = 'qr'.$recordid.'.png';
        $filename = $this->objConfig->getcontentBasePath().'users/'.$userid.'/'.$basename;
        $file = file_put_contents($filename, $image);
        // get the image path now
        $imgsrc = $this->objConfig->getsiteRoot().$this->objConfig->getcontentPath().'users/'.$this->objUser->userId().'/'.$basename;
        // return an array of useful stuff
        $ret = array('image' => $imgsrc, 'gmapsurl' => $gmapsurl);
        
        return $ret;
    }
}
?>
