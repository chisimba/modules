<?
/**
* Class to provier reusable view logic to the webpresent module
*
* This class takes functionality for viewing and creates reusable methods
* based on it so that the code can be reused in different templates
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
* @package   webpresent
* @author    Derek Keats <dkeats[AT]uwc[DOT]ac[DOT]za>
* @copyright 2007 UWC and AVOIR
* @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License 
* @version   CVS: $Id$
* @link      http://avoir.uwc.ac.za
*/


// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global string $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
*
* Class for rendering email messages into the
* webpresent template
*
* @author Derek Keats
* @category Chisimba
* @package webpresent
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class viewer extends object 
{
    /**
    * 
    * @var $objLanguage String object property for holding the 
    * language object
    * @access private
    * 
    */
    public $objLanguage;
    
    /**
    * 
    * @var $objUser String object property for holding the 
    * user object
    * @access private
    * 
    */    
    public $objUser;
    
    /**
    * 
    * @var $objUser String object property for holding the 
    * cobnfiguration object
    * @access private
    * 
    */    
    public $objConfig;
    
    /**
    *
    * Standard init method  
    *
    */
    public function init()
    {
        // Instantiate the language object. 
        $this->objLanguage = $this->getObject('language', 'language');
        // Instantiate the user object.
        $this->objUser = $this->getObject("user", "security");
        // Instantiate the config object
        $this->objConfig = $this->getObject('altconfig', 'config');
    }
    
    /**
     * 
     * A method to return the flash presentation for rendering in the page
     * @param string $id The file id of the flash file to show
     * @return string the flash file rendered for viewing within a div
     * @access public
     * 
     */
    public function showFlash($id)
    {
         $flashFile = $this->objConfig->getcontentBasePath().'webpresent/'. $id .'/' . $id.'.swf';
         if (file_exists($flashFile)) {
             $flashFile = $this->objConfig->getcontentPath().'webpresent/' .$id .'/'. $id.'.swf';
             $flashContent = '
             <div style="border: 1px solid #000; width: 534px; height: 402px; text-align: center;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="540" height="400">
             <param name="movie" value="'.$flashFile.'">
             <param name="quality" value="high">
             <embed src="'.$flashFile.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="534" height="402"></embed>
            </object></div>';
        } else {
            $flashContent = '<div class="noRecordsMessage" style="border: 1px solid #000; width: 540px; height: 302px; text-align: center;">Flash Version of Presentation being converted</div>';
        }
        return $flashContent;
    }
}
?>
