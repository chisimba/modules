<?php

/**
 * Class the controls the list of pages available.
 *
 * It doesn't contain the content of pages, just the index to track which pages
 * are translations of each other.
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
 * @package   contextcontent
 * @author    Tohir Solomons <tsolomons@uwc.ac.za>
 * @copyright 2006-2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       core
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
 * Class the controls the list of pages available.
 *
 * It doesn't contain the content of pages, just the index to track which pages
 * are translations of each other.
 *
 * @category  Chisimba
 * @package   contextcontent
 * @author    Tohir Solomons <tsolomons@uwc.ac.za>
 * @copyright 2006-2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 * @see       core
 */

class db_contextcontent_titles extends dbtable
{

    /**
     * Constructor
     */
    public function init()
    {
        parent::init('tbl_contextcontent_titles');
        $this->objContentPages =& $this->getObject('db_contextcontent_pages');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    /**
     * Method to add a title
     * @param string $titleId Record controlling translation group index
     * @param string $menutitle Menu title of the page
     * @param string $content Content of the Page
     * @param string $language Language of the Page
     * @param string $headerScript Any Script to go in the header
     * @return Record Id
     */
    public function addTitle($titleId='', $menutitle, $content, $language, $headerScript)
    {
        if ($titleId == '') {
            $titleId = $this->autoCreateTitle();
            
            $pageId = $this->objContentPages->addPage($titleId, $menutitle, $content, $language, $headerScript);
        }
        
        return $titleId;
    }
    
    /**
     * Method to auto create a translation group index
     * @return record Id
     */
    private function autoCreateTitle()
    {
        return $this->insert(array(
                'creatorid' => $this->objUser->userId(),
                'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
            ));
    }
    
    /**
     * Method to delete a title
     *
     * It also deletes all translations of the page
     *
     * @param $id Title Id
     */
    public function deleteTitle($id)
    {
        $this->delete('id', $id);
        $this->objContentPages->delete('titleid', $id);
        
        
        $objContextOrder = $this->getObject('db_contextcontent_order');
        $contexts = $objContextOrder->getContextWithPages($id);
        
        if (is_array($contexts) && count($contexts) > 0) {
            foreach ($contexts as $context)
            {
                $objContextOrder->deletePage($context['id']);
            }
        }
        return;
    }

}


?>
