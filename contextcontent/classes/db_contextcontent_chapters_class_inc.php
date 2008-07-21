<?php
/**
 * Class that contains the chapters in the contextcontent module
 *
 * At this stage, chapters are not yet assigned to contexts. It provides a list of contexts
 * that can be reused.
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
 * @version    CVS: $Id$
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
 * Class that contains the chapters in the contextcontent module
 *
 * At this stage, chapters are not yet assigned to contexts. It provides a list of contexts
 * that can be reused.
 *
 * @author Tohir Solomons
 *
 */
class db_contextcontent_chapters extends dbtable
{

    /**
    * Constructor
    */
    public function init()
    {
        parent::init('tbl_contextcontent_chapters');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objChapterContent =& $this->getObject('db_contextcontent_chaptercontent');
    }
    
    /**
    * Method to add a chapter
    * @param string $chapterId Record Id if it exists - used for multilingual versions
    * @param string $title Title of Chapter
    * @param string $intro Introduction to the Chapter
    * @param string $language Language Version of the Chapter
    * @return string Record Id of the Chapter
    */
    public function addChapter($chapterId='', $title, $intro, $language='en')
    {
        if ($chapterId == '') {
            $chapterId = $this->autoCreateChapter();
            
            $pageId = $this->objChapterContent->addChapter($chapterId, $title, $intro, $language);
        }
        
        return $chapterId;
    }
    
    /**
    * Method to autogenerated an id for a chapter
    * This is used when a chapter is being created for the version time
    * and passed for language version of the chapter
    * @return string Record Id of Chapter
    */
    private function autoCreateChapter()
    {
        return $this->insert(array(
                'creatorid' => $this->objUser->userId(),
                'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
            ));
    }
    
    /**
    * Method to delete a chapter
    * @param string $id Record Id of Chapter
    */
    function deleteChapter($id)
    {
        // Delete the Chapter
        $this->delete('id', $id);
        
        // Delete Chapter Content
        $this->objChapterContent->deleteChapterTitle($id);
        
        // Also delete from context
        $objChapterContext = $this->getObject('db_contextcontent_contextchapter');
        $contexts = $objChapterContext->getContextsWithChapter($id);
        
        if (count($contexts) > 0 && is_array($contexts)) {
            
            foreach ($contexts as $context)
            {
                $objChapterContext->objContextChapters->removeChapterFromContext($id, $context);
            }
        }
    }
    

}


?>