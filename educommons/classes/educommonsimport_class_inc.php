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
     * and populate the new object's instance variables
     */
    public function init()
    {
        // SimplePie feed reader object for importing form RSS
        $this->objSpie = $this->getObject('spie', 'feed');

        // Contextcontent chapter objects for importing chapters
        $this->objChapters = $this->getObject('db_contextcontent_chapters', 'contextcontent');
        $this->objChapterContent = $this->getObject('db_contextcontent_chaptercontent', 'contextcontent');
    }

    /**
     * Import chapters from RSS feed
     *
     * @param string $uri The URI of the RSS feed
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
     * Import chapters from MySQL
     */
    public function doMysqlChapters()
    {
    }
}

?>
