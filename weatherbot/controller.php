<?php

/**
 * Weather Bot Controller Class
 *
 * Class to control the Weather Bot module.
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
 * @package   weatherbot
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 AVOIR
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
 * Weather Bot Controller Class
 *
 * Class to control the Weather Bot module.
 *
 * @category  Chisimba
 * @package   weatherbot
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class weatherbot extends controller
{
    /**
     * Instance of the curl module in the utilities class.
     *
     * @access private
     * @var    object
     */
    private $objCurl;

    /**
     * Initialises the object properties.
     *
     * @access public
     */
    public function init()
    {
        $this->objCurl = $this->getObject('curl', 'utilities');
    }

    /**
     * Handles incoming HTTP requests.
     *
     * @access public
     */
    public function dispatch()
    {
        $location = $this->getParam('body');
        $uri = 'http://www.google.com/ig/api?weather='.urlencode($location);
        $xml = $this->objCurl->exec($uri);
        $dom = new SimpleXMLElement($xml);

        if (is_object($dom)) {
            $weather = $dom->weather->current_conditions;
            if ($weather) {
                echo $dom->weather->forecast_information->city['data'];
                echo ': ';
                echo $weather->temp_c['data'];
                echo '°C / ';
                echo $weather->temp_f['data'];
                echo '°F, ';
                echo $weather->condition['data'];
                echo ', ';
                echo $weather->humidity['data'];
                echo ', ';
                echo $weather->wind_condition['data'];
            } else {
                echo 'Could not find weather data for your location.';
            }
        } else {
            echo 'Could not contact the weather service.';
        }
    }

    /**
     * Removes the necessity to login in order to use this module.
     *
     * @access public
     */
    public function requiresLogin()
    {
        return FALSE;
    }
}
