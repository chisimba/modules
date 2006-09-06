<?php

class db_contextcontent_pages extends dbtable
{

    function init()
    {
        parent::init('tbl_contextcontent_pages');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    function addPage($titleId, $menutitle, $content, $language, $headerScript)
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
    
    function checkPageExists($titleId, $language)
    {
        $recordCount = $this->getRecordCount('WHERE titleid=\''.$titleId.'\' AND language=\''.$language.'\'');
        
        if ($recordCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function updatePage($id, $title, $content, $headerScripts)
    {
        return $this->update('id', $id, array('menutitle'=>$title, 'pagecontent'=>$content, 'headerscripts'=>$headerScripts));
    }

}


?>