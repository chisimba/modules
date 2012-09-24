<?php
/**
 *
 * Database access for Species
 *
 * Database access for Species. This is a sample database model class
 * that you will need to edit in order for it to work.
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
* Database access for Species
*
* Database access for Species. This is a sample database model class
* that you will need to edit in order for it to work.
*
* @package   species
* @author    Derek Keats derek@localhost.local
*
*/
class speciesops extends object
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
    * Intialiser for the species operations class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // Get an instance of the languate object
        $this->objLanguage = $this->getObject('language', 'language');
    }

    /**
     * 
     * Get the whole alphabet linked to a URL that will return species beginning
     * with the letter clicked.
     * 
     * @return string A linked alphabetic list
     * @access public
     * 
     */
    public function alphaLinked()
    {
        $alpha = range('a', 'z');
        $ret = "| ";
        foreach ($alpha as $letter) {
            $url = $this->uri(array(
                'action' => 'byletter',
                'letter' => $letter
            ), 'species');
            $url = str_replace("&amp;", "&", $url);
            // Use the DOM to make a link
            $doc = new DOMDocument('UTF-8');
            $a = $doc->createElement('a');
            $a->setAttribute('href', $url);
            $a->appendChild($doc->createTextNode($letter));
            $doc->appendChild($a);
            $ret .= $doc->saveHTML()  . " | ";
        }
        return $ret;
    }
    
    /**
     * 
     * List species once a letter has been clicked, list all species whose
     * alphabetical name begins with the particular letter.
     * 
     * @param string $letter The letter of the alphabet chosen
     * @param string $field The database field to search, normally alphabeticalname
     * @return string A formatted table of species
     * @access public
     * 
     */
    public function listSpeciesByLetter($letter, $field='alphabeticalname') {
        $objDbspecies = & $this->getObject('dbspecies', 'species');
        $spArr = $objDbspecies->getListByLetter($letter, $field);
        $ret = "";
        $tab = new DOMDocument('UTF-8');
        $table = $tab->createElement('table');
        $table->setAttribute('class', "species_list_table");
        $class="odd";
        foreach ($spArr as $species) {
            // Create a table row
            $tr = $tab->createElement('tr');
            
            // The linked alphabetical name
            $td = $tab->createElement('td');
            $td->setAttribute('class', $class);
            $id = $species['id'];
            $alphabeticalName = $species['alphabeticalname'];
            $fullName = $species['fullname'];
            $scientificName = $species['scientificname'];
            $url = $this->uri(array(
                'action' => 'showsp',
                'id' => $id
            ), 'species');
            $url = str_replace("&amp;", "&", $url);
            // Use the DOM to make a link
            $aLink = $tab->createElement('a');
            $aLink->setAttribute('href', $url);
            $aLink->appendChild($tab->createTextNode($alphabeticalName));
            $td->appendChild($aLink);
            $tr->appendChild($td);
            
            // The linked scientific name
            $td = $tab->createElement('td');
            $td->setAttribute('class', $class);
            // Use the DOM to make a link
            $aLink = $tab->createElement('a');
            $aLink->setAttribute('href', $url);
            $aLink->appendChild($tab->createTextNode($scientificName));
            $td->appendChild($aLink);
            $tr->appendChild($td);
            
            
             // Add the row to the table
            $table->appendChild($tr);
            
            // Convoluted odd/even
            if ($class == "odd") { 
                $class = "even";
            } else {
                $class = "odd";
            }
        }
        $tab->appendChild($table);
        return $tab->saveHTML();
    }
    
    /**
     * 
     * Show the info for a particular species by the record id
     * 
     * @param string $id The record id for the species in the database
     * @return The formatter species record from Wikipedia
     * @access public
     */
    public function showSpecies($id)
    {
        // Create an instance of the database class
        $objDbspecies = & $this->getObject('dbspecies', 'species');
        $record = $objDbspecies->getRecord($id);
        $latin = $record['scientificname'];
        $common = $record['fullname'];
        $wikiname = str_replace('  ', ' ', $common);
        $wikiname = str_replace(' ', '_', $wikiname);
        $uri = 'http://en.wikipedia.org/wiki/' . $wikiname;
        $page = $this->getWikipediaPage($uri);
        $isStub = $this->checkStub($page);
        $wikiTxt = $this->getContent($page);
        $wikiTxt = $this->italicizeSpecies($wikiTxt, $latin);
        $commonLinked = "<a href='$uri' target='_blank'>$common</a>";
        $ret = '<div class="species_speciesrecord">'
          . '<div class="species_titletop>"'
          . '<span class="speciesrecord_common">' . $commonLinked . '</span><br />'
          . '<span class="speciesrecord_latin">' . $latin . '</span>'
          . $this->getWikipediaIcon() . '</div>'
          . '<div class="species_txt">'. $wikiTxt . '</div>'
          . '</div>';
        if ($isStub) {
            $doc = new DOMDocument('UTF-8');
            $div = $doc->createElement('div');
            $div->setAttribute('class', 'species_stub');
            $doc->appendChild($div);
            $stub = $this->objLanguage->languageText(
                "mod_species_stub", "species",
                "This article is a stub in Wikipedia");
            $div->appendChild($doc->createTextNode($stub));
            $ret .= $doc->saveHTML();
        }
        return $ret;
    }
    
    /**
     * 
     * Use curl to retrieve a wikipedia page
     * 
     * @param string $uri The wikipedia URI to retrieve
     * @return string The contents of the page
     * @access private
     * 
     */
    private function getWikipediaPage($uri)
    {
        $objCurl = $this->getObject('curlwrapper', 'utilities');
        $page = $objCurl->exec($uri);
        $this->appendArrayVar('headerParams', $this->getWikipediaCss());
        return $page;
    }
   
    /**
     * 
     * Get the page content from the DIV with id=bodyContent by using the 
     * dom document
     * 
     * @param string $page The retrieved Wikipedia page
     * @return string The extracted content
     * @access private
     * 
     */
    private function getContent($page)
    {
        $tree = new DOMDocument();
        @$tree->loadHTML($page);
        $count = 1;
        $output = "";
        foreach($tree->getElementsByTagName('div') as $div) {
            if($div->getAttribute('id') == "bodyContent") {
                foreach($div->getElementsByTagName('p') as $p) {
                    $output .= "<p>".$p->nodeValue."</p>";
                }
                // Remove the [##] links
                $output = preg_replace('/\[[^\]]*\]/', '', $output);
                return $output;
            }
        }
        return NULL;
    }
    
    /**
     * 
     * Get an array of thumbnails from the Wikipedia page
     * 
     * @param string $page The wikipedia page contents
     * @return string Array An array of HTML links to thumb images
     * @access private
     * 
     */
    private function getImageThumbs($page)
    {
        $tree = new DOMDocument();
        @$tree->loadHTML($page);
        $arImgs = array();
        foreach($tree->getElementsByTagName('div') as $div) {
            if($div->getAttribute('id') == "bodyContent") {
                foreach($div->getElementsByTagName('img') as $img) {
                    if($div->getAttribute('class') == "thumbimage") {
                        $arImgs[] = $img->getAttribute('src');
                    }
                }
            }
        }
        return $arImgs;
    }
    
    
    //@@@DEPRECATED@@@@
    private function getInfoBox($page) {
        $tree = new DOMDocument();
        @$tree->loadHTML($page);
        $count = 1;
        $output = "NO TABLE FOUND";
        foreach($tree->getElementsByTagName('table') as $table) {
            if($table->getAttribute('class') == "infobox biota") {
                foreach($table->getElementsByTagName('img') as $img) {
                    die($img->nodeValue);
                }
                //die(dom_dump($table));
                //return $output;
            }
        }
        return $output;
    }

    
    /**
     * 
     * Get the wikipedia CSS from the filters module
     * 
     * @return string The formatted link for the page header
     * @access private
     * 
     */
    private function getWikipediaCss()
    {
        return "<link rel=\"stylesheet\" type=\"text/css\" "
          . "href=\"" . $this->getResourceUri("css/wikipedia.css", "filters") . "\" />";
    }
    
    /**
     * 
     * Italicise occurrences of the latin name in the text.
     * 
     * @param $string $wikiTxt The text to look in
     * @param string $latin The latin name
     * @return string The text with italics added
     * @access private
     * 
     */
    private function italicizeSpecies($wikiTxt, $latin) {
        return str_replace($latin, '<i class="species_latin">' . $latin . '</i>', $wikiTxt);
    }
    
    /**
     * 
     * Get the wikipedia icon
     * 
     * @return string The IMG tag for the icon
     * @access private
     * 
     */
    private function getWikipediaIcon()
    {
        $icon = $this->getResourceURI('icons/wikipedia64.png');
        return "<img src='$icon' class='speciesrecord_wikipicon'>";
    }

    /**
     * 
     * Check if an article returned is a stub
     * 
     * @param string $wikiTxt The text of the page
     * @return boolean TRUE|FALSE
     * @access private
     * 
     */
    private function checkStub($wikiTxt)
    {
        if (strpos($wikiTxt, 'Wikipedia:Stub')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
?>