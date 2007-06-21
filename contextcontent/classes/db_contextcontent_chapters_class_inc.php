<?php

class db_contextcontent_chapters extends dbtable
{

    public function init()
    {
        parent::init('tbl_contextcontent_chapters');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objChapterContent =& $this->getObject('db_contextcontent_chaptercontent');
    }
    
    public function addChapter($chapterId='', $title, $intro, $language='en')
    {
        if ($chapterId == '') {
            $chapterId = $this->autoCreateChapter();
            
            $pageId = $this->objChapterContent->addChapter($chapterId, $title, $intro, $language);
        }
        
        return $chapterId;
    }
    
    private function autoCreateChapter()
    {
        return $this->insert(array(
                'creatorid' => $this->objUser->userId(),
                'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
            ));
    }
    

}


?>