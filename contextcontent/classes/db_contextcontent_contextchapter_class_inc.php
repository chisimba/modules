<?php

/**
* Class to Arrange the order of pages
*
*
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
    
    public function getContextChapters($context)
    {
        
        return $this->query($this->getContextChaptersSQL($context));
    }
    
    public function getContextChaptersSQL($context)
    {
        $sql = 'SELECT tbl_contextcontent_chaptercontext.visibility, tbl_contextcontent_chaptercontent. *, tbl_contextcontent_chaptercontext.id as contextchapterid 
FROM tbl_contextcontent_chaptercontext, tbl_contextcontent_chaptercontent
WHERE (tbl_contextcontent_chaptercontent.chapterid = tbl_contextcontent_chaptercontext.chapterid) AND tbl_contextcontent_chaptercontext.contextcode=\''.$context.'\' ORDER BY tbl_contextcontent_chaptercontext.chapterorder';
        
        return $sql;
    }
    
    
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
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @return array Details of the Page, FALSE if does not exist
    * @access private
    */
    public function addChapterToContext($chapterId, $context, $visibility)
    {
        $order = $this->getLastOrder($context)+1;
        
        return $this->insertTitle($context, $chapterId, $order, $visibility);
    }
    
    
    
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
     * Method to delete a page
     *
     * @param string $id Record Id of the Page
     * @return boolean Result of deletion
     */
    function deletePage($id)
    {
        return $this->delete('id', $id);
    }
    
    /**
    * Method to move a page up
    * @param string $id Record Id of the Page
    * @return boolean Result of Page Move
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
    * Method to move a page down
    * @param string $id Record Id of the Page
    * @return boolean Result of Page Move
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
    
    function updateChapterVisibility($id, $visibility)
    {
        //echo '<pre>';
        
        //print_r($this->update('id', $id, array('visibility'=>$visibility)));
        return $this->update('id', $id, array('visibility'=>$visibility));
    }
    
    /**
    * Function to check whether a chapter exists in a context or not
    *
    */
    function isContextChapter($contextCode, $chapterId)
    {
        $result = $this->getRecordCount('WHERE contextcode=\''.$contextCode.'\' AND chapterid=\''.$chapterId.'\' ');
        
        return ($result == 0) ? FALSE : TRUE;
    }
    



}


?>