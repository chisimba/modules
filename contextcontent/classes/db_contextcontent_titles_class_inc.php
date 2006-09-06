<?php

class db_contextcontent_titles extends dbtable
{

    public function init()
    {
        parent::init('tbl_contextcontent_titles');
        $this->objContentPages =& $this->getObject('db_contextcontent_pages');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    public function addTitle($titleId='', $menutitle, $content, $language, $headerScript)
    {
        if ($titleId == '') {
            $titleId = $this->autoCreateTitle();
            
            $pageId = $this->objContentPages->addPage($titleId, $menutitle, $content, $language, $headerScript);
        }
        
        return $titleId;
    }
    
    private function autoCreateTitle()
    {
        return $this->insert(array(
                'creatorid' => $this->objUser->userId(),
                'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
            ));
    }
    
    public function deleteTitle($id)
    {
        $this->delete('id', $id);
        $this->objContentPages->delete('titleid', $id);
        
        return;
    }

}


?>