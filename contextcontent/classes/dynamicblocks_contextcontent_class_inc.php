<?php
/**
* Class that contains the content of chapters in the contextcontent module
*
* Chapters can be multilingual, and this table contains the language version of a chapter
* 
* @author Tohir Solomons
*/
class dynamicblocks_contextcontent extends object
{

    /**
    * Constructor
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objContextChapters = $this->getObject('db_contextcontent_contextchapter');
        $this->objContentOrder = $this->getObject('db_contextcontent_order');
        $this->loadClass('link', 'htmlelements');
    }
    
    public function renderChapter($id)
    {
        $chapter = $this->objContextChapters->getRow('id', $id);
        
        if ($chapter == FALSE) {
            return '';
        } else {
            return $this->objContentOrder->getTree($chapter['contextcode'], $chapter['chapterid'], 'htmllist');
        }
    }
    
    public function listChapters($contextCode)
    {
        
        
        $chapters = $this->objContextChapters->getContextChapters($contextCode);
        
        if (count($chapters) == 0) {
            return '<div class="noRecordsMessage">'.$this->objLanguage->code2Txt('mod_contextcontent_contexthasnochaptersorcontent', 'contextcontent', NULL, 'This [-context-] does not have chapters or content').'</div>';
        } else {
            $str = '<ol>';
            foreach ($chapters as $chapter)
            {
                $link = new link ($this->uri(array('action'=>'viewchapter', 'id'=>$chapter['id']), 'contextcontent'));
                $link->link = $chapter['chaptertitle'];
                
                $str .= '<li>'.$link->show().'</li>';
            }
            
            $str .= '</ol>';
            
            return $str;
        }
    }
    
    public function listChaptersWide($contextCode)
    {
        return $this->listChapters($contextCode);
    }

}


?>