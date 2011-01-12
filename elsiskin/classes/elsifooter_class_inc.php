<?php
/*
 *
 * A module to display the footer of the elsi skin. It retrieves the url actions from the
 * news module category footer. The title of the newstory would be inside the <a> tags,
 * and the href of the tag would be the storytext from the news module query.
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
 * @package   elsiskin
 * @author    Nguni Phakela nonkululeko.phakela@wits.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: elsifooter_class_inc.php,v 1.1 2011-01-12 09:13:27 nguni52 Exp $
 * @link      http://avoir.uwc.ac.za
 *
 *
 *
 */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class elsifooter extends object {

    // news stories object
    private $objNews;
    // stories for rotating banners at the top
    private $stories;

    /**
     * Constructor
     */
    public function init() {
        $this->objCategory = $this->getObject('dbnewscategories', 'news');
        $this->objNews = $this->getObject('dbnewsstories', 'news');
    }

    /*
     * Method to display the footer of elsiskin
     *
     */
    public function show() {
        $category = 'footer';
        $exists = $this->objCategory->categoryExists($category);
        if ($exists) {
            $categoryid = $this->objCategory->getCategoryById($category);
            $this->stories = $this->objNews->getCategoryStories($categoryid);
            ;
        }

        $retstr = '
			<!-- Start: Footer -->
			<div id="Footer">
                <div class="grid_4">&nbsp;</div>
                <!-- end .grid_4 -->
                <div class="clear">&nbsp;</div>
                <div class="grid_4">';

        $count = 1;
        $totalstories = count($this->stories);
        foreach ($this->stories as $row) {
            $action = trim(str_replace("index.html", "", strip_tags($row['storytext'])));
            $action = str_replace("/", "", $action);
            if (strlen($action) != 0) {
                $myArray = array('action' => $action);
            } else {
                $myArray = "";
            }
            $retstr .= '<a href="' . $this->uri($myArray) . '">' . strip_tags($row['storytitle']) . '</a>';
            if ($count < $totalstories) {
                $retstr .= " | ";
            }
        }

        $retstr .= '</div><!-- End: Footer --><div class="clear">&nbsp;</div>';

        return $retstr;
    }

}