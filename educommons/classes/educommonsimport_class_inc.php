<?php

/**
 * educommons import
 * 
 * eduCommons Import class for Chisimba
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
 * @package   educommons
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright 2008 Charl van Niekerk
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
 * educommons import
 * 
 * eduCommons Import class for Chisimba
 * 
 * @category  Chisimba
 * @package   educommons
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright 2008 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 */

class educommonsimport extends object
{
    protected $objSpie;
    protected $objChapters;
    protected $objChapterContent;

    /**
     * Standard constructor to load the necessary resources
     * and populate the new object's instance variables.
     *
     * @access public
     */
    public function init()
    {
        // SimplePie feed reader object for importing form RSS.
        $this->objSpie = $this->getObject('spie', 'feed');

        // Contextcontent chapter objects for importing chapters.
        $this->objChapters = $this->getObject('db_contextcontent_chapters', 'contextcontent');
        $this->objChapterContent = $this->getObject('db_contextcontent_chaptercontent', 'contextcontent');
    }

    /**
     * Import chapters from RSS feed.
     *
     * @access public
     * @param string $uri The URI of the RSS feed.
     */
    public function doRssChapters($uri)
    {
        $this->objSpie->startPie($uri);
        $items = $this->objSpie->get_items();
        foreach ($items as $item) {
            $title = $item->get_title();
            $intro = $item->get_content();
            if (!$this->objChapterContent->checkChapterTitleExists($title, 'en')) {
                $this->objChapters->addChapter('', $title, $intro, 'en');
            }
        }
    }

    /**
     * Parse an IMS XML file and return its data as a PHP array.
     * TODO: Remove SimpleXMLElement and start using proper XML-DOM parsing.
     *
     * @access public
     * @return array The data
     */
    public function parseIms()
    {
        // Determine the path to the file.
        $file = $this->getResourcePath('sample/imsmanifest.xml', 'educommons');

        // Read the contents of the file.
        $contents = file_get_contents($file);

        // Replace xml:lang with lang in order to retrieve the language via SimpleXML.
        // TODO: This is a temporary hack and needs to be removed.
        $contents = str_replace('xml:lang', 'lang', $contents);

        // Parse the contents of the file as an XML document.
        // SimpleXML returns warnings. Temporarily supressing with @.
        $xml = @new SimpleXMLElement($contents);

        // Define the data array and its sub-arrays to be returned.
        $data = array();
        $data['courses'] = array();
        $data['documents'] = array();

        // Run through each IMS resource and parse individually.
        foreach ($xml->resources->resource as $resource) {
            // Define the resource data array and populate from the DOM nodes.
            $resourceData = array();
            $resourceData['title'] = $this->getLangStrings($resource->metadata->lom->general->title);
            if ($resource->metadata->lom->general->description) {
                $resourceData['description'] = $this->getLangStrings($resource->metadata->lom->general->description);
            }
            $resourceData['language'] = trim($resource->metadata->lom->general->language);
            $resourceData['href'] = trim($resource->file['href']);

            // Determine the type of the resource (course, document, image, etc).
            $objectType = trim($resource->metadata->eduCommons->objectType);

            // Depending on the resource type, add the resource data to the correct point in the data array.
            switch ($objectType) {
                case 'Course':
                    $data['courses'][] = $resourceData;
                    break;
                case 'Document':
                    $data['documents'][] = $resourceData;
                    break;
            }
        }

        return $data;
    }

    /**
     * Convert a set of IMS langstring elements to a PHP associative array.
     * The keys in the returned array are the languages and the values are the text contents.
     *
     * @access protected
     * @param object $element The SimpleXMLElement object to be converted.
     * @return array The associative array of languages and their values.
     */
    protected function getLangStrings($element)
    {
        $langStrings = array();
        foreach ($element->langstring as $langstring) {
            $lang = (string) $langstring['lang'];
            $string = trim($langstring);
            $langStrings[$lang] = $string;
        }
        return $langStrings;
    }
}

?>
