<?php
/**
 * 
 * A module to provide a flash remoting interface to Chisimba based on amfphp Flash remoting library.
 * 
 * This module provides for the use of ampphp for Flash remoting in Chisimba. Amfphp is an RPC 
 * toolkit for PHP. Amfphp allows seamless communication between Php and Flash and Flex with 
 * Remoting, JavaScript and Ajax with JSON, and XML clients with XML-RPC.
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
 * @package   helloforms
 * @author    _AUTHORNAME _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 */
 
// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 * 
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *         
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
* 
* Class to pass user information to flash using amfphp
*
* @author Derek Keats
* @package flashremote
*
*/
class flashuser extends object
{
    
    /**
    * 
    * Intialiser for the flashuser class
    * @access public
    * 
    */
    public function init()
    {
        require_once($this->getResourcePath('core/amf/app/Gateway.php', 'flashremote'));
        $this->objUser = $this->getObject('user', 'security');
    }
    
}
?>
