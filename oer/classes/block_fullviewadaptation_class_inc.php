<?php
/**
 * This class contains util methods for displaying full original product details
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
 * @package    oer

 * @copyright  2011 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 * @author     pwando paulwando@gmail.com
 */

/**
 * this block is used for rendering an adaptation
 *
 * @author pwando
 */
class block_fullviewadaptation extends object {

    function init() {
        $this->title = "";
    }
    /**
     * Function returns form for viewing adaptation full data
     *
     * @return string
     */

    function show() {
        $id = $this->configData;
        $objFullViewAdaptation = $this->getObject("fullviewadaptation", "oer");
        //return $objFullViewAdaptation->buildAdaptationFullView($id);
        return $objFullViewAdaptation->buildFullView($id);
    }
}
?>