<?php

/**
 * eduCommons controller class
 * 
 * Class to control the eduCommons module
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
 * @category  chisimba
 * @package   educommons
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright 2008 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class educommons extends controller
{
	protected $objSpie;
	protected $objChapters;
	protected $objChapterContent;

	/**
	 * Standard constructor to load the necessary resources
	 * and populate the new object's instance variables
	 * @access public
	 */
	public function init()
	{
		$this->objSpie = $this->getObject('spie', 'feed');
		$this->objChapters = $this->getObject('db_contextcontent_chapters', 'contextcontent');
		$this->objChapterContent = $this->getObject('db_contextcontent_chaptercontent', 'contextcontent');
	}

	/**
	 * Standard dispatch method to handle the various possible actions
	 * @access public
	 */
	public function dispatch()
	{
		$action = $this->getParam('action');
		switch ($action) {
			default:
				$this->objSpie->startPie('http://free.uwc.ac.za/freecourseware/biodiversity-conservation-biology/conservation-biology/RSS');
				$items = $this->objSpie->get_items();
				foreach ($items as $item) {
					$title = $item->get_title();
					$intro = $item->get_content();
					if (!$this->objChapterContent->checkChapterTitleExists($title, 'en')) {
						$this->objChapters->addChapter('', $title, $intro, 'en');
					}
				}
		}
	}
}

?>
