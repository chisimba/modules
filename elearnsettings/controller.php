<?php
/**
 * 
 * Elearning settings
 * 
 * This module provides no user interface elements, rather it provides some 
 * default settings for an eLearning instance of Chisimba. For example, it 
 * sets an eLearning toolbar and skin, and sets the system type to elearn. 
 * It should generally be installed when setting up Chisimba for eLearning.
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
 * @package   elearnsettings
 * @author    Derek Keats derek@localhost.local
 * @copyright 2011 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
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
* Controller class for Chisimba for the module elearnsettings
*
* @author Derek Keats
* @package elearnsettings
*
*/
class elearnsettings extends controller
{
    
    /**
    * 
    * Intialiser for the elearnsettings controller
    * @access public
    * 
    */
    public function init()
    {}
    
    
    /**
     * 
     * The standard dispatch method for the elearnsettings module.
     * This just redirects to the default page in case anyone tries to open
     * the module, since the module has no functionality.
     * 
     */
    public function dispatch()
    {
        $uri = $this->uri(array());
        header("Location: $uri");
    }
}
?>