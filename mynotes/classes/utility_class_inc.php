<?php

/**
 *
 * A utility class with helper methods
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
 * @version
 * @package    mynotes
 * @author     Nguni Phakela info@nguni52.co.za
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
 * A utility class with helper methods
 * 
 * @category  Chisimba
 * @author    Nguni Phakela
 * @version
 * @copyright 2010 AVOIR
 *
 */
class utility extends object {

    private $module;
    
    public function init(){
        $this->module = "mynotes";
    }

    /*
     * Function 
     * 
     */
    public function wordlimit($string, $length = 50, $ellipsis = " ...") {
        $words = explode(' ', $string);
        if (count($words) > $length) {
            return implode(' ', array_slice($words, 0, $length)) . $ellipsis;
        } else {
            return $string . $ellipsis;
        }
    }
    
    /*
     * Method use to process the tags that are used by the tag cloud utility.
     * 
     * @return array that is used by tag cloud containing tags and weights, with 
     * tag url.
     */
    public function processTags($tagCloud) {
        $tags = NULL;
        foreach ($tagCloud as $arrs) {
            if (!empty($arrs['name'])) {
                $tags .= $arrs['name'] . ",";
            }
        }
        $tagsArr [] = explode(',', $tags);

        if (empty($tagsArr)) {
            return NULL;
        }

        foreach ($tagsArr as $tagger) {
            foreach ($tagger as $tagged) {
                $tags .= $tagged . ",";
            }
        }
        $tags = str_replace(',,', ',', $tags);
        $tagarray = explode(',', $tags);
        $basetags = array_unique($tagarray);

        foreach ($basetags as $q) {
            $numbers = array_count_values($tagarray);
            $weight = $numbers[$q];
            $entry [] = array('name' => $q,
                              'url' => $this->uri(array(
                                                        'action' => 'search', 
                                                        'srchstr' => $q, 
                                                        'srchtype' => 'tags'),
                                                  $this->module),
                              'weight' => $weight * 1000, 
                              'time' => time());
        }

        return $entry;
    }

}