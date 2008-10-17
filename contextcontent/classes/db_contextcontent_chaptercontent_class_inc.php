<?php
 /**
 * Class that contains the content of chapters in the contextcontent module
 *
 * Chapters can be multilingual, and this table contains the language version of a chapter
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
 * Class that contains the content of chapters in the contextcontent module
 *
 * Chapters can be multilingual, and this table contains the language version of a chapter
 *
 * @author Tohir Solomons
 *
 */
class db_contextcontent_chaptercontent extends dbtable
{

    /**
    * Constructor
    */
    public function init()
    {
        parent::init('tbl_contextcontent_chaptercontent');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    /**
     * Method to get the context of a chapter
     *
     * @param string $chapterId Record Id of the Chapter
     * @return array
     */
    public function getChapterContent($chapterId)
    {
        $sql = 'WHERE chapterid=\''.$chapterId.'\' AND original=\'Y\' LIMIT 1';
        
        $results = $this->getAll($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    
    
    /**
    * Method to add a Chapter
    *
    * @param string $chapterId Chapter Id of the Chapter
    * @param string $title Title of the Chapter
    * @param string $intro Intro to Chapter
    * @param string $language Language of the Chapter
    * @return boolean Result of Insert
    */
    public function addChapter($chapterId, $title, $intro, $language)
    {
        if (!$this->checkChapterExists($chapterId, $language)) {
            return $this->insert(array(
                    'chapterid' => $chapterId,
                    'chaptertitle' => $title,
                    'introduction' => $intro,
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
    * Method to Check whether a Chapter exists for a title
    *
    * @param string $chapterId Record Id of the Chapter
    * @param string $language Requested language
    * @return boolean
    */
    public function checkChapterExists($chapterId, $language)
    {
        $recordCount = $this->getRecordCount('WHERE chapterid=\''.$chapterId.'\' AND language=\''.$language.'\'');
        
        if ($recordCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Method to check whether a chapter title exists
     *
     * @param string $chapterTitle The chapter title to check
     * @param string $language The language of the title
     * @return boolean
     */
    public function checkChapterTitleExists($chapterTitle, $language) {
        return (boolean) $this->getRecordCount('WHERE chaptertitle=\''.$chapterTitle.'\' AND language=\''.$language.'\'');
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
    public function updateChapter($id, $title, $intro)
    {
        //echo $id;
        
        $result = $this->update('id', $id, array(
                'chaptertitle'=>(stripslashes($title)), 
                'introduction'=>(stripslashes($intro)), 
                'modifierid' => $this->objUser->userId(),
                'datemodified' => strftime('%Y-%m-%d %H:%M:%S', mktime())
            ));
        
        if ($result) {
            
            $chapter = $this->getRow('id', $id);
            
            $objChapterContext = $this->getObject('db_contextcontent_contextchapter');
            $contexts = $objChapterContext->getContextsWithChapter($chapter['chapterid']);
            
            if (count($contexts) > 0) {
                foreach ($contexts as $context)
                {
                    $objChapterContext->indexChapter($context, $chapter);
                }
            }
        }
        
        return $result;
    }
    
    /**
    * Method to delete a chapter
    * @param string $id Chapter Id
    * @return boolean
    */
    public function deleteChapterTitle($id)
    {
        return $this->delete('chapterid', $id);
    }
    

}


?>
