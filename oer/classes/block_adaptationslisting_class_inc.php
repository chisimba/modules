<?php
/**
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
 * @author     pwando paulwando@gmail.com
 * @copyright  2011 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 */
$this->loadClass('link', 'htmlelements');

/**
 * This class lists the adaptations
 *
 * @author pwando
 */
class block_adaptationslisting extends object {

    public function init() {
        $this->loadClass("link", "htmlelements");
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = "";
    }
    /**
     * Function renders a list of paginated adaptations
     *
     * @return form
     */

    public function show() {
         $modeRaw = $this->configData;
        $modeParts = explode("__", $modeRaw);
        $mode = $modeParts[0];
        $filter = "";
        $filterOptions = "";
        if (count($modeParts) == 3) {
            $filter = $modeParts[1];
            $filterOptions = $modeParts[2];
        }
        $objProductManager = $this->getObject("adaptationmanager", "oer");
        return $objProductManager->getAdaptationListingPaginated($mode, $filterOptions, $filter);
    }

}

?>
