<?php

/**
 * Geo Helper Class
 *
 * Convenience class for interacting with MongoDB
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
 * @package   geo
 * @author    Paul Scott <pscott209@gmail.com>
 * @copyright 2011 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: mongoops_class_inc.php 19535 2010-10-28 18:22:39Z charlvn $
 * @link      http://avoir.uwc.ac.za/
 * @seealso   http://www.mongodb.org/
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
 * Geo Helper Class
 *
 * Convenience class for interacting with MongoDB.
 *
 * @category  Chisimba
 * @package   geo
 * @author    Paul Scott <pscott209@gmail.com>
 * @copyright 2011 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: mongoops_class_inc.php 19535 2010-10-28 18:22:39Z charlvn $
 * @link      http://avoir.uwc.ac.za/
 * @seealso   http://www.mongodb.org/
 */
class geoops extends object
{
    
    /**
     * Instance of the dbsysconfig class of the sysconfig module.
     *
     * @access private
     * @var    object
     */
    private $objSysConfig;
    
    private $geowikiBase;
    
    private $flickrBase;

    /*
     * Initialises some of the object's properties.
     *
     * @access public
     */
    public function init()
    {
        // Objects
        $this->objSysConfig    = $this->getObject('dbsysconfig', 'sysconfig');
        $this->geowikiBase     = "http://api.wikilocation.org/articles?";
        $this->flickrBase      = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=db8df9a1b93963aed7a5fdea50c718b0&license=cc+by+sa&accuracy=16&";
        $this->objProxy        = $this->getObject('proxyparser', 'utilities');
    }
    
    public function getWikipedia($lon, $lat, $radius=1500) {
        $url = $this->geowikiBase."lat=".$lat."&lng=".$lon."&radius=".$radius;
        $proxyArr = $this->objProxy->getProxy();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($proxyArr) && $proxyArr['proxy_protocol'] != '') {
            curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'] . ":" . $proxyArr['proxy_port']);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'] . ":" . $proxyArr['proxy_pass']);
        }
        $articlesjson = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($articlesjson);
    }
    
    public function getFlickr($lon, $lat, $fradius=0.5) {
        $url = $this->flickrBase."lat=".$lat."&lon=".$lon."&radius=".$fradius."&radius_units=km&format=json&nojsoncallback=1";
        $proxyArr = $this->objProxy->getProxy();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($proxyArr) && $proxyArr['proxy_protocol'] != '') {
            curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'] . ":" . $proxyArr['proxy_port']);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'] . ":" . $proxyArr['proxy_pass']);
        }
        $flickrjson = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($flickrjson);
    }
}
?>
