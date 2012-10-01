<?php
/**
 *
 * Access to encyclopedia of life for Species
 *
 * Access to encyclopedia of life in order to access species data via
 * the API and scrapings.
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
 * Access to encyclopedia of life for Species
 *
 * Access to encyclopedia of life in order to access species data via
 * the API and scrapings.
*
* @package   species
* @author    Derek Keats derek@localhost.local
*
*/
class eol extends object
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
     * Get a sound file for this species via the EOL API
     * 
     * @param string $latinName The latin name of the species to lookup
     * @return string The embedded sound file with player
     * @access public
     * 
     */
    public function getSound($latinName)
    {
        $obj = $this->jsonSearch($latinName);
        $id = $obj->results[0]->id;
        $uri = "http://eol.org/api/pages/1.0/$id.json?details=0&images=0&sounds=2&subjects=overview&text=0";
        $page = $this->getResults($uri);
        $obj = json_decode($page);
        /*echo "<pre>";
        print_r($obj);
        die("</pre>");*/
        if (isset($obj->dataObjects[0])) {
            $url = $obj->dataObjects[0]->mediaURL;
            return $this->embedAudio($url);
        } else {
            return $this->noAudioFound($latinName);
        }
    }
    
    /**
     * 
     * Send a warning message if no sound file is found on EOL
     * 
     * @param string $latinName The latin name we are looking up
     * @return string The warning message
     * @access private
     * 
     */
    private function noAudioFound($latinName)
    {
        $repArray = array('species'=>$latinName);
        $res = $this->objLanguage->code2txt(
                "mod_species_eolnosound", "species", $repArray,
                "EOL has no linked sound file"
          );
        $doc = new DOMDocument('UTF-8');
        $div = $doc->createElement('div');
        $div->setAttribute('class', 'species_stub');
        $div->appendChild($doc->createTextNode($res));
        $doc->appendChild($div);
        return $this->italicizeSpecies($doc->saveHTML(), $latinName);
    }
    
    /**
     * 
     * Embed the found audio file in a player, detecting Firefox which
     * won't play MP3 files as native HTML5 audio
     * 
     * @param type $url
     * @return type
     * @access private
     * 
     */
    private function embedAudio($url)
    {
        if ($this->isFirefox()) {
            // Do a Flash player
            $objSoundPlayerBuilder = $this->newObject('buildsoundplayer', 'files');
            $objSoundPlayerBuilder->setSoundFile($url);
            return $objSoundPlayerBuilder->show();
        } else {
            // Do an HTML5 player
            $doc = new DOMDocument('UTF-8');
            $snd = $doc->createElement('audio');
            $snd->setAttribute('controls', 'controls');
            $file = $doc->createElement('source');
            $file->setAttribute('src', $url);
            $file->setAttribute('type', "audio/mpeg");
            $snd->appendChild($file);
            $doc->appendChild($snd);
            return $doc->saveHTML();
        }
    }
    
    /**
     * 
     * Check if a browser is Firefox (because Firefox cannot embed MP3 via
     * the HTML5 AUDIO tag)
     * 
     * @return boolean TRUE|FALSE
     * @access private
     * 
     */
    private function isFirefox()
    {
        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $agent = $_SERVER['HTTP_USER_AGENT'];
        }
        if(strlen(strstr($agent,"Firefox")) > 0 ){ 
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * 
     * Get up to two images for the given species identified by its
     * scientific name. It returns the thumbnail, and sets the full size
     * image to $this->fullImage
     * 
     * @param string $scientificName The latin species name
     * @return string The URL for the thumbnail image
     * @access public
     * 
     */
    public function getImage($scientificName)
    {
        $obj = $this->jsonSearch($scientificName);
        $id = $obj->results[0]->id;
        $uri = "http://eol.org/api/pages/1.0/$id.json?"
          . "details=0&images=2&sounds=0&subjects=overview&text=0";
        $page = $this->getResults($uri);
        $obj = json_decode($page);
        /*echo "<pre>";
        print_r($obj);
        die("</pre>");*/
        if (isset($obj->dataObjects[0])) {
            $url = $obj->dataObjects[0]->eolThumbnailURL;
            $this->fullImage = $obj->dataObjects[0]->mediaURL;
            $this->eolImage = $obj->dataObjects[0]->eolMediaURL;
        } else {
            $url = NULL;
            $this->fullImage = NULL;
            $this->eolImage = NULL;
        }
        return $url;
    }

    /**
     * 
     * Carry out a search, returning JSON as a result
     * 
     * @param string $searchTerm The term to search
     * @return string A linked alphabetic list
     * @access public
     * 
     */
    public function jsonSearch($searchTerm)
    {
        $searchTerm = str_replace(' ', '%20', $searchTerm);
        $uri = 'http://eol.org/api/search/1.0/' . $searchTerm . '.json?exact=1';
        $page = $this->getResults($uri);
        return json_decode($page);
    }
    
    /**
     * 
     * Use curl to retrieve a api page
     * 
     * @param string $uri The URI to retrieve
     * @return string The contents of the rerturned page
     * @access private
     * 
     */
    private function getResults($uri)
    {
        $objCurl = $this->getObject('curlwrapper', 'utilities');
        return $objCurl->exec($uri);
    }
    
    /**
     * 
     * Italicise occurrences of the latin name in the text.
     * 
     * @param $string $txt The text to look in
     * @param string $latin The latin name
     * @return string The text with italics added
     * @access private
     * 
     */
    private function italicizeSpecies($txt, $latinName) {
        return str_replace(
          $latinName, 
          '<i class="species_latin">' 
          . $latinName . '</i>', $txt
        );
    }
    
    
}
?>