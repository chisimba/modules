<?php
/**
 *
 * A wikioverview block for Species.
 *
 * A wikioverview block for Species to show block from wikipedia
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
 * @version    0.001
 * @package    species
 * @author     Derek Keats derek@localhost.local
 * @copyright  2010 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 * 
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
 * 
 * A right block for Species.
 *
 * A right block for Species. Manage a database of information about species within a group, for example birds. Store common name, Latin name, a link to one or more free content images from Flickr, as well as imported text from Wikipedia where it exists..
 *
 * @category  Chisimba
 * @package    species
 * @author     Derek Keats derek@localhost.local
 * @version   0.001
 * @copyright 2010 AVOIR
 *
 */
class block_wikioverview extends object
{
    /**
     * The title of the block
     *
     * @var    object
     * @access public
     */
    public $title;
    
    /**
     *
     * @var string Object $objLanguage String for the language object
     * @access public
     *
     */
    public $objLanguage;
    
    /**
     * Standard init function
     *
     * Create title
     *
     * @return NULL
     */
    public function init() 
    {
        // Get an instance of the languate object
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = $this->objLanguage->languageText(
                "mod_species_wikipovrv", "species",
                "Wikipedia overview");
    }
    
    /**
     * Standard block show method.
     *
     * @return string $this->display block rendered
     */
    public function show() 
    {
        $action = $this->getParam('action', NULL);
        switch ($action) {
            case "showsp":
                $id = $this->getParam('id', FALSE);
                if ($id) {
                    $objDbspecies = & $this->getObject('dbspecies', 'species');
                    $fullName = $objDbspecies->getFullName($id);
                    // Serialize item to Javascript
                    $arrayVars['fullName'] = str_replace(" ", "+", $fullName);
                    $objSerialize = $this->getObject('serializevars', 'utilities');
                    $objSerialize->varsToJs($arrayVars);
                    $this->appendArrayVar('headerParams',
                      $this->getJavaScriptFile('species.js',
                      'species'));
                }
                break;
            default:
                
                break;
        }
        return "<div id='wikioverviewcontents'></div>";
    }
}
?>
