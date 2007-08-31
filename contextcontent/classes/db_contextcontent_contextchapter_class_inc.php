<?php

/**
* Class to Control which Chapters should be available in a context
*
* This allows for a single chapter to be reused in multiple contexts
*
* @author Tohir Solomons
*/
class db_contextcontent_contextchapter extends dbtable
{

    /**
    * Constructor
    */
    public function init()
    {
        parent::init('tbl_contextcontent_chaptercontext');
        $this->objUser =& $this->getObject('user', 'security');
        
    }
    
    /**
    * Method to get the number of pages in a context
    * @param string $contextCode Code of Context to get num pages
    */
    public function getNumContextChapters($contextCode)
    {
        return $this->getRecordCount('WHERE contextcode=\''.$contextCode.'\'');
    }
    
    /**
    * Method to get the list of chapters in a particular context
    * @param string $context Context Code
    * @return array List of Chapters
    */
    public function getContextChapters($context)
    {
        
        return $this->query($this->getContextChaptersSQL($context));
    }
    
    /**
    * Method to get the SQL statement to get the list of chapters in a context
    * @param string $context Context Code
    * @return string SQL statement
    */
    public function getContextChaptersSQL($context)
    {
        $sql = 'SELECT tbl_contextcontent_chaptercontext.visibility, tbl_contextcontent_chaptercontent. *, tbl_contextcontent_chaptercontext.id as contextchapterid, (Select count(id) FROM  tbl_contextcontent_order WHERE tbl_contextcontent_chaptercontent.chapterid = tbl_contextcontent_order.chapterid) as pagecount 
FROM tbl_contextcontent_chaptercontext, tbl_contextcontent_chaptercontent
WHERE (tbl_contextcontent_chaptercontent.chapterid = tbl_contextcontent_chaptercontext.chapterid) AND tbl_contextcontent_chaptercontext.contextcode=\''.$context.'\' ORDER BY tbl_contextcontent_chaptercontext.chapterorder';
        
        return $sql;
    }
    
    /**
    * Method to get the title of a chapter by providing the record id of the chapter
    * @param string $chapterId
    * @return string Title of Chapter : FALSE
    */
    function getContextChapterTitle($chapterId)
    {
        $sql = 'SELECT tbl_contextcontent_chaptercontent.chaptertitle FROM tbl_contextcontent_chapters, tbl_contextcontent_chaptercontent WHERE (tbl_contextcontent_chaptercontent.chapterid = tbl_contextcontent_chapters.id) AND tbl_contextcontent_chapters.id=\''.$chapterId.'\' LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0]['chaptertitle'];
        }
    }
    
    /**
    * Method to get the details of a chapter
    * @param string $chapterid Record Id of the Chapter
    * @return array Details of the chapter
    */
    public function getChapter($chapterid)
    {
        $sql = 'SELECT tbl_contextcontent_chaptercontext.visibility, tbl_contextcontent_chaptercontent. *, tbl_contextcontent_chaptercontext.id as contextchapterid 
FROM tbl_contextcontent_chaptercontext, tbl_contextcontent_chaptercontent, tbl_contextcontent_chapters
WHERE (tbl_contextcontent_chaptercontent.chapterid = tbl_contextcontent_chaptercontext.chapterid AND tbl_contextcontent_chaptercontext.chapterid = tbl_contextcontent_chapters.id) AND tbl_contextcontent_chapters.id=\''.$chapterid.'\' LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    
    
    /**
    * Method to add a chapter to a context
    * @param string $chapterId Record Id of the CHapter
    * @param string $context Context Code
    * @param string $visibility Visibility status of chapter within a context
    */
    public function addChapterToContext($chapterId, $context, $visibility)
    {
        $order = $this->getLastOrder($context)+1;
        
        return $this->insertTitle($context, $chapterId, $order, $visibility);
    }
    
    
    /**
    * Method to add a chapter to a context - Saves Record to Database
    * @param string $chapterId Record Id of the CHapter
    * @param string $context Context Code
    * @param int $order Order of the Item
    * @param string $visibility Visibility status of chapter within a context
    */
    private function insertTitle($context, $chapterId, $order, $visibility='Y')
    {
        return $this->insert(array(
                'contextcode' => $context,
                'chapterid' => $chapterId,
                'chapterorder' => $order,
                'visibility' => $visibility,
                'creatorid' => $this->objUser->userId(),
                'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
            ));
    }
    
    /**
    * Method to get the order of the last chapter in a context
    * @param string $context Context Code
    * @return integer
    */
    private function getLastOrder($context)
    {
        $sql = 'WHERE contextcode =\''.$context.'\' ORDER BY chapterorder DESC LIMIT 1';
        $result = $this->getAll($sql);
        
        if (count($result) == 0) {
            return 0;
        } else {
            return $result[0]['chapterorder'];
        }
    }
    
    
    
    /**
     * Method to remove a chapter from a context
     *
     * @param string $chapterId Record Id of the Chapter
     * @param string $context Context Code
     * @return boolean Result of deletion
     */
    function removeChapterFromContext($chapterId, $context)
    {
        $results = $this->getAll('WHERE contextcode =\''.$context.'\' AND chapterid=\''.$chapterId.'\' ');
        if (count($results) > 0) {
            foreach ($results as $item)
            {
                $this->delete('id', $item['id']);
            }
        }
    }
    
    /**
    * Method to move a chapter up
    * @param string $id Record Id of the Chapter
    * @return boolean Result of Chapter Move
    */
    function moveChapterUp($id)
    {
        $chapter = $this->getRow('id', $id);
        
        if ($chapter == FALSE) {
            return FALSE;
        }
        
        $prevChapterSQL = ' WHERE contextcode=\''.$chapter['contextcode'].'\' AND chapterorder < '.$chapter['chapterorder'].' ORDER BY chapterorder DESC';
        $prevChapter = $this->getAll($prevChapterSQL);
        
        if (count($prevChapter) == 0) {
            return FALSE;
        } else {
            $prevChapter = $prevChapter[0];
            
            $this->update('id', $chapter['id'], array('chapterorder'=>$prevChapter['chapterorder']));
            $this->update('id', $prevChapter['id'], array('chapterorder'=>$chapter['chapterorder']));
            
            return TRUE;
        }
        
        
    }
    
    /**
    * Method to move a Chapter down
    * @param string $id Record Id of the Chapter
    * @return boolean Result of Chapter Move
    */
    function moveChapterDown($id)
    {
        $chapter = $this->getRow('id', $id);
        
        if ($chapter == FALSE) {
            return FALSE;
        }
        
        $nextChapterSQL = ' WHERE contextcode=\''.$chapter['contextcode'].'\' AND chapterorder > '.$chapter['chapterorder'].' ORDER BY chapterorder';
        $nextChapter = $this->getAll($nextChapterSQL);
        
        if (count($nextChapter) == 0) {
            return FALSE;
        } else {
            $nextChapter = $nextChapter[0];
            
            $this->update('id', $chapter['id'], array('chapterorder'=>$nextChapter['chapterorder']));
            $this->update('id', $nextChapter['id'], array('chapterorder'=>$chapter['chapterorder']));
            
            return TRUE;
        }
        
        
    }
    
    /**
    * Method to update the visibility status of a chapter
    * @param string $id Record Id
    * @param string $visibility Visibility Status
    * @return boolean
    */
    function updateChapterVisibility($id, $visibility)
    {
        return $this->update('id', $id, array('visibility'=>$visibility));
    }
    
    /**
    * Method to check whether a chapter exists in a context or not
    * @param string $contextCode Context Code
    * @param string $chapterId Chapter Id
    * @return boolean
    */
    public function isContextChapter($contextCode, $chapterId)
    {
        $result = $this->getRecordCount('WHERE contextcode=\''.$contextCode.'\' AND chapterid=\''.$chapterId.'\' ');
        
        return ($result == 0) ? FALSE : TRUE;
    }

    /**
    * Method to check how many chapters are using a particular chapter
    * @param string $chapterId Chapter Id
    * @return boolean
    */
    public function getNumContextWithChapter($chapterId)
    {
        return $this->getRecordCount('WHERE chapterid=\''.$chapterId.'\' ');
    }
    



}


?>