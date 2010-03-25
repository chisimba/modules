<?php
/**
 * PANSA controller class
 *
 * Class to control the PANSA Maps module
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
 * @package   pansamaps
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
 * PANSA controller class
 *
 * Class to control the PANSA maps module.
 *
 * @category  Chisimba
 * @package   pansamaps
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class pansamaps extends controller
{
    public $objLanguage;
    public $objSysConfig;
    public $objUser;
    public $objConfig;
    public $objOps;
    public $objCurl;
    public $objDbTags;
    public $objUtils;
    public $ip2Country;
    public $objWashout;
    public $objTwtOps;
    public $objTeeny;
    public $objSocial;
    public $dbFoaf;
    public $objModuleCat;
    public $objActStream;
    public $eventsEnabled;

    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            $this->requiresLogin();
            $this->objLanguage   = $this->getObject ( 'language', 'language' );
            $this->objConfig     = $this->getObject('altconfig', 'config');
            $this->objSysConfig  = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser       = $this->getObject('user', 'security');
            $this->objCurl       = $this->getObject('curlwrapper', 'utilities');
            $this->objDbPansa    = $this->getObject('dbpansa');
            $this->objOps        = $this->getObject('pansaops');
            $this->ip2Country    = $this->getObject('iptocountry', 'utilities');
            $this->objWashout    = $this->getObject('washout', 'utilities');
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
                
                return 'mapview_tpl.php';
                break;
                
            case 'getmapdata':
                header('Content-type: text/xml');
                echo $this->objDbPansa->getData();
                break;
                          
            default:
                $this->nextAction('');
                break;
        }
    }

    public function requiresLogin() {
        return FALSE;
    }
}
?>
