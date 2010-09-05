<?php
/**
 * QRCreator controller class
 *
 * Class to control the QRCreator module
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
 * @category  chisimba
 * @package   qrcreator
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * qrcreator controller class
 *
 * Class to control the qrcreator module.
 *
 * @category  Chisimba
 * @package   qrcreator
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class qrcreator extends controller
{
    public $objLanguage;
    public $objSysConfig;
    public $objUser;
    public $objConfig;
    public $objCurl;
    public $objDbQr;

    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            // $this->requiresLogin();
            $this->objLanguage   = $this->getObject ( 'language', 'language' );
            $this->objConfig     = $this->getObject('altconfig', 'config');
            $this->objSysConfig  = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser       = $this->getObject('user', 'security');
            $this->objModuleCat  = $this->getObject('modules', 'modulecatalogue');
            $this->objCurl       = $this->getObject('curl', 'utilities');
            $this->objDbQr       = $this->getObject('dbqr');
			
            if($this->objModuleCat->checkIfRegistered('activitystreamer'))
            {
                $this->objActStream = $this->getObject('activityops','activitystreamer');
                $this->eventDispatcher->addObserver(array($this->objActStream, 'postmade' ));
                $this->eventsEnabled = TRUE;
            } else {
                $this->eventsEnabled = FALSE;
            }
        }
        catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }
    }

    /**
     * Standard dispatch method
     *
     * @access public
     * @param void
     * @return void
     */
    public function dispatch() {
        $action = $this->getParam ( 'action' );
        switch ($action) {
            case NULL:

            case 'main' :
                echo "form elements to enter a latlon and a message to print to qr code";
                break;

            case 'create' :
                $this->requiresLogin(TRUE);
                $msg = $this->getParam('msg', $this->objLanguage->languageText('mod_qrcreator_defaultmessage', 'qrcreator'));
                $latlon = $this->getParam('latlon', $this->objLanguage->languageText('mod_qrcreator_defaultlocation', 'qrcreator'));
                $ll = explode(',', $latlon);
                $userid = $this->objUser->userId();
                $lon = $ll[0];
                $lat = trim($ll[1]);
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
                // display the code or something...
                echo $gmapsurl."<br /><br />";
                echo '<img src="'.$imgsrc.'"/>';
                break;

            default:
                $this->nextAction('');
                break;
        }
    }

    public function requiresLogin($param) {
        return $param;
    }
}
?>
