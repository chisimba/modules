<?php

/**
 * class that contains the content of pages in the contextcontent module
 *
 * This class controls the content of pages, including multilingualised versions
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
 * @version    $Id$
 * @package    contextcontent
 * @author     Tohir Solomons <tsolomons@uwc.ac.za>
 * @copyright  2006-2007 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://avoir.uwc.ac.za
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
 * class that contains the content of pages in the contextcontent module
 *
 * This class controls the content of pages, including multilingualised versions
 *
 * @author Tohir Solomons
 *
 */

class db_contextcontent_pages extends dbtable
{

    /**
     * Constructor
     */
    public function init()
    {
        parent::init('tbl_contextcontent_pages');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    /**
     * Method to add a Page
     *
     * @param string $titleId Record Id of the Title
     * @param string $menutitle Title of the Page
     * @param string $content Content of the Page
     * @param string $language Language of the Page
     * @param string $headerScript Header JS of the Page
     * @return boolean Result of Insert
     */
    public function addPage($titleId, $menutitle, $content, $language, $headerScript=null)
    {
        if (!$this->checkPageExists($titleId, $language)) {
            return $this->insert(array(
                    'titleid' => $titleId,
                    'menutitle' => $menutitle,
                    'pagecontent' => $content,
                    'headerscripts' => $headerScript,
                    'language' => $language,
                    'original' => 'Y',
                    'creatorid' => $this->objUser->userId(),
                    'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
                ));
        } else {
            return FALSE;
        }
    }
    
    /**
     * Method to Check whether a Page exists for a title
     *
     * @param string $titleId Record Id of the Title
     * @param string $language Requested language
     * @return boolean
     */
    public function checkPageExists($titleId, $language)
    {
        $recordCount = $this->getRecordCount('WHERE titleid=\''.$titleId.'\' AND language=\''.$language.'\'');
        
        if ($recordCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    /**
     * Method to Update the Content of a Page
     *
     * @param string id Record Id of the Page
     * @param string $menutitle Title of the Page
     * @param string $content Content of the Page
     * @param string $headerScript Header JS of the Page
     * @return boolean
     */
    public function updatePage($id, $title=false, $content=false, $headerScripts=false)
    {
        $row = array();
        $row['creatorid'] = $this->objUser->userId();
        $row['datecreated'] = strftime('%Y-%m-%d %H:%M:%S', mktime());

        if ($title !== false) {
            $row['menutitle'] = stripslashes($title);
        }
        if ($content !== false) {
            $row['pagecontent'] = stripslashes($content);
        }
        if ($headerScripts !== false) {
            $row['headerscripts'] = stripslashes($headerScripts);
        }

        return $this->update('id', $id, $row);
    }

}

?>
