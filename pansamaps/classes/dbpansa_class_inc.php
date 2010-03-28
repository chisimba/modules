<?php
/**
 *
 * PANSA database class
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
 * @package   pansamaps
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
 * PANSA database class
 *
 * PHP version 5.1.0+
 *
 * @author Paul Scott
 * @package pansamaps
 *
 */
class dbpansa extends dbtable {

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
     * @var string $objWashout String object property for holding the washout object
     *
     * @access public
     */
    public $objWashout;

    /**
     * @var string $objUser String object property for holding the user object
     *
     * @access public
     */
    public $objUser;

    /**
     * @var string $objCurl String object property for holding the curl object
     *
     * @access public
     */
    public $objCurl;

    public $objLangCode;
    public $objUtils;
    public $objTwitOps;

    /**
     * Constructor
     *
     * @access public
     */
    public function init() {
        parent::init('tbl_pansa_venues');
        $this->objLanguage  = $this->getObject('language', 'language');
        $this->objConfig    = $this->getObject('altconfig', 'config');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objWashout   = $this->getObject('washout', 'utilities');
        $this->objUser      = $this->getObject('user', 'security');
        $this->objCurl      = $this->getObject('curlwrapper', 'utilities');
        $this->objLangCode  = $this->getObject('languagecode', 'language');
        $this->objTags      = $this->getObject('dbtags', 'tagging');
    }
    
    public function getData() {
        $dataArray = $this->getAll();
        return $this->makeMapMarkers($dataArray);
    }
    
    public function makeMapMarkers($dataArray) {
        // build up a set of markers for a google map
        $head = "<markers>";
        $body = NULL;
        foreach($dataArray as $data) {
            if($data['geolat'] == "" || $data['geolon'] == "") {
                continue;
            }
            else {
                $body .= '<marker lat="'.$data['geolat'].'" lng="'.$data['geolon'].'" info="'.htmlentities($data['venuename']."<br />".$data['venuedescription']).'" />';
            }
        }
        $tail = "</markers>";
        $data = $head.$body.$tail;
        $path = $this->objConfig->getModulePath()."pansamaps/markers.xml";
        if(!file_exists($path)) {
            touch($path);
            chmod($path, 0777);
        }
        else {
            unlink($path);
            touch($path);
            chmod($path, 0777);
        }
        file_put_contents($path, $data);
        
        return $data;
    }
    
    public function addRecord($data) {
        $this->insert($data, 'tbl_pansa_venues');
    }
    
    public function editRecord() {
    
    }
    
    public function deleteRecord($recid) {
        return $this->delete('id', $recid, 'tbl_pansa_venues');
    }
    
    public function searchRecords($keyword) {
        return $this->getAll("WHERE venuename LIKE '%%$keyword%%'");
    }
    
}
?>
