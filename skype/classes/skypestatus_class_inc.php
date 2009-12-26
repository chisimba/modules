<?php

/**
 * Skype Status
 * 
 * Skype user presence status helper
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
 * @package   skype
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2009 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
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
 * Skype Status
 * 
 * Skype user presence status helper 
 * 
 * @category  Chisimba
 * @package   skype
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2009 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za/
 */

class skypestatus extends object
{
    /**
     * Standard constructor to load the necessary resources
     * and populate the new object's instance variables.
     *
     * @access public
     */
    public function init()
    {
    }

    /**
     * Retrieve the current presence status of a user.
     *
     * @access public
     * @param  string $username The username of the user to retrieve the status of.
     * @return array  Associative array with the language codes as keys.
     */
    public function getStatus($username)
    {
        // Compile the URI to retrieve.
        $uri = sprintf('http://mystatus.skype.com/%s.xml', rawurlencode($username));

        // Create the DOM document and populate it from the URI.
        $document = new DOMDocument();
        $document->load($uri);

        // Retrieve the presence elements.
        $elements = $document->getElementsByTagName('presence');

        // Initialise the array to be returned by this method.
        $status = array();

        // Loop through each presence element and populate the $status array accordingly.
        foreach ($elements as $element) {
            $lang = $element->getAttribute('xml:lang');
            $status[$lang] = $element->textContent;
        }

        // Return the status array.
        return $status;
    }
}
