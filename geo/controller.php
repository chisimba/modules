<?php
/**
 * geo controller class
 *
 * Class to control the geo module
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
 * @package   geo
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2011 Paul Scott
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
 * geo controller class
 *
 * Class to control the geo module.
 *
 * @category  Chisimba
 * @package   geo
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2011 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */
class geo extends controller
{
    public $objLanguage;
    public $objSysConfig;
    public $objUser;
    public $objConfig;
    
    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            $this->objLanguage      = $this->getObject ( 'language', 'language' );
            $this->objConfig        = $this->getObject('altconfig', 'config');
            $this->objSysConfig     = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser          = $this->getObject('user', 'security');
            $this->objMongo         = $this->getObject('geomongo', 'mongo');
            $this->objOps           = $this->getObject('geoops');
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
                echo "nothing to do";
                break;
                
            case 'getwikipedia' :
                $lon          = $this->getParam('lon', NULL);
                $lat          = $this->getParam('lat', NULL);
                $radius       = $this->getParam('radius', 1500);
                $objWikipedia = $this->objOps->getWikipedia($lon, $lat, $radius);
                // parse wikipedia data
                $this->objMongo->mongoWikipedia($objWikipedia);
                break;
                
            case 'getflickr' :
                $lon          = $this->getParam('lon', NULL);
                $lat          = $this->getParam('lat', NULL);
                $radius       = $this->getParam('radius', 1.5);
                $objFlickr    = $this->objOps->getFlickr($lon, $lat, $radius);
                // parse Flickr data
                $this->objMongo->mongoFlickr($objFlickr);
                break;
            
            case 'getbylonlat' :
                $lon   = $this->getParam('lon', NULL);
                $lat   = $this->getParam('lat', NULL);
                $limit = $this->getParam('limit', 10); 
                $res = NULL;
                if($lat == NULL || $lon == NULL) {
                    $res = array($this->objLanguage->languageText("mod_geo_notenoughparams", "geo"));
                    $res = json_encode($res);
                }
                else {
                    $res = $this->objMongo->getByLonLat(floatval($lon), floatval($lat), $limit);
                    
                }
                header('Cache-Control: no-cache, must-revalidate');
                header('Content-type: application/json');
                echo $res;
                break;
                
            case 'getbyplacename' : 
                $placename = $this->getParam('placename');
                $placename = urldecode($placename);
                $limit = $this->getParam('limit', 10);
                $res = $this->objMongo->getByPlacename($placename, $limit);
                
                header('Cache-Control: no-cache, must-revalidate');
                header('Content-type: application/json');
                echo $res;
                break;
                
            case 'getradiuskm' :
                $lon   = $this->getParam('lon', NULL);
                $lat   = $this->getParam('lat', NULL);
                $radius = $this->getParam('radius');
                
                $res = $this->objMongo->getRadiusKm($lon, $lat, $radius);
                header('Cache-Control: no-cache, must-revalidate');
                header('Content-type: application/json');
                echo $res;
                break;
            
            case 'getradiusmi' :
                $lon   = $this->getParam('lon', NULL);
                $lat   = $this->getParam('lat', NULL);
                $radius = $this->getParam('radius');
                
                $res = $this->objMongo->getRadiusMiles($lon, $lat, $radius);
                header('Cache-Control: no-cache, must-revalidate');
                header('Content-type: application/json');
                echo $res;
                break;
                
            case 'getbycountrycode' : 
                $cc = $this->getParam('cc');
                $res = $this->objMongo->getAllByCountryCode($cc);
                header('Cache-Control: no-cache, must-revalidate');
                header('Content-type: application/json');
                echo $res;
                break;
                
            default:
                $this->nextAction('');
                break;
        }
    }

    /**
     * Method to turn off login for selected actions
     *
     * @access public
     * @param string $action Action being run
     * @return boolean Whether the action requires the user to be logged in or not
     */
    function requiresLogin($action='') {
        $allowedActions = array('', 'getdata', 'getbylonlat', 'getbyplacename', 'getradiuskm', 'getradiusmi', 'getbycountrycode', 'getwikipedia', NULL);

        if (in_array($action, $allowedActions)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
?>