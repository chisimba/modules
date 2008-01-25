<?php
/**
* Class that contains the content of chapters in the contextcontent module
*
* Chapters can be multilingual, and this table contains the language version of a chapter
* 
* @author Tohir Solomons
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