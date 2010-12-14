<?php

/**
 * Currency Bot Controller Class
 *
 * Class to control the Currency Bot module.
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
 * @package   currencybot
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
 * Currency Bot Controller Class
 *
 * Class to control the Currency Bot module.
 *
 * @category  Chisimba
 * @package   currencybot
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class currencybot extends controller
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
        $input = explode(' ', trim(preg_replace('/\s+/', ' ', $this->getParam('body'))));

        if (count($input) == 3) {
            $uri = 'http://www.google.com/ig/calculator?q=' . urlencode("$input[0] $input[1] =? $input[2]");
            $json = $this->objCurl->exec($uri);

            if ($json) {
                $json = preg_replace('/([a-z]+):/', '"$1":', $json);
                $data = json_decode($json);

                if (is_object($data) && $data->error == '') {
                    echo "$data->lhs = $data->rhs";
                } else {
                    echo 'Unable to convert - please check your query.';
                }
            } else {
                echo 'Unable to proxy your request to the Google Currency API. Please try again later.';
            }
        } else {
            echo "Please use the following format: amount from to\nExample: 100 euro rand";
        }
        exit;
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
