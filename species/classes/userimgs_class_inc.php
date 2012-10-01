<?php
/**
 *
 * Access to user images for Species
 *
 * Access to user images for users identified by CONFIG: species_userlist 
 * in order to show them in certain locations.
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
 * @package   species
 * @author    Derek Keats derek@localhost.local
 * @copyright 2007 AVOIR
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
 * Access to user images for Species
 *
 * Access to user images for users identified by CONFIG: species_userlist 
 * in order to show them in certain locations.
*
* @package   species
* @author    Derek Keats derek@localhost.local
*
*/
class userimgs extends object
{
    
    /**
     *
     * @var string Object $objLanguage String for the language object
     * @access public
     *
     */
    public $objLanguage;
    
    /**
     *
     * @var string Object The config object 
     * @access public
     * 
     */
    public $objConfig;

    /**
    *
    * Intialiser for the species operations class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // Get an instance of the language object.
        $this->objLanguage = $this->getObject('language', 'language');
        // Get an instance of the config object
        $this->objConfig = $this->getObject('dbsysconfig', 'sysconfig');
    }
    
    /**
     * 
     * Show the user contributed images for this species.
     * 
     * @param string $id The species ID
     * @return string The formated images
     * @access public
     * 
     */
    public function showSpecies($id)
    {
        $objDbspecies = & $this->getObject('dbspecies', 'species');
        $fullName = strtolower($objDbspecies->getFullName($id));
        $fullName = str_replace("  ", "_", $fullName);
        $fullName = str_replace(" ", "_", $fullName);
        $arUsers = $this->getArUsers();
        $type = 'birds'; //change to read from sesssion
        $ret = "";
        foreach ($arUsers as $user) {
            $lookDir = 'usrfiles/users/' . $user . '/' . $type . '/' . $fullName;
            if (file_exists($lookDir)) {
                if(is_dir($lookDir)) {
                    $images = simplexml_load_file($lookDir . '/captions.xml');
                    foreach ($images as $image) {
                        $imgFile = $lookDir . '/' . $image->filename;
                        $mFile = $lookDir . '/m_' . $image->filename;
                        if (file_exists($mFile)) {
                            $useImage = $mFile;
                        } else {
                            $useImage = $imgFile;
                        }
                        $caption = $image->caption;
                        $photographer = "Photo by: " . $image->photographer->fullname .', ';
                        $license = "Licence: " . $image->licence;
                        $subcaption = $photographer . "   " . $license;
                        // Build up the display image using the DOM object.
                        $doc = new DOMDocument('UTF-8');
                        $img = $doc->createElement('img');
                        $img->setAttribute('src', $useImage);
                        $div = $doc->createElement('div');
                        $div->setAttribute('class', 'species_contrimages');
                        $div->appendChild($img);
                        $br = $doc->createElement('br');
                        $div->appendChild($br);
                        $div->appendChild($doc->createTextNode($caption));
                        //$br = $doc->createElement('br');
                        $div->appendChild($br);
                        $div->appendChild($doc->createTextNode($subcaption));
                        $doc->appendChild($div);
                        $ret .= $doc->saveHTML();
                    }
                }
            }
        }
        if ($ret=="") {
            $ret = $this->objLanguage->languageText('mod_species_nousrfls', 'species', "There are no userfiles for this species.");
        }
        return $ret;
    }
    
    /**
     * 
     * Get the contributing user list from the config setting species_userlist
     * 
     * @return string array
     * @Access public
     * 
     */
    public function getArUsers()
    {
        $userList = $this->objConfig->getValue('species_userlist', "species");
        if (strstr($userList, ",")) {
            // There are more than one
            return array($userList);
        } else {
            return explode(",", $userList);
        }
    }
    
  

    
}
?>