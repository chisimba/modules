<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table  
* Creates the right-Quick Search box.
*/
class tabbox extends object
{
    public $tabs;
    public $tabCount;
    public $tabName;
    
    public function init(){
        $this->tabs = array();
        $this->tabCount = 0;
    }
    
    public function addTab($name, $caption, $content){
        $this->tabs[$this->tabCount]['name'] = $name;
        $this->tabs[$this->tabCount]['caption'] = $caption;
        $this->tabs[$this->tabCount]['content'] = $content;
        $this->tabCount++;
    }
    
	public function show($includeHeader = TRUE){
        if ($this->tabCount > 0){
            if($includeHeader == TRUE){
                $this->appendArrayVar('headerParams','<link rel="stylesheet" type="text/css" href="modules/financialaid/resources/tabcontent.css" />');
                $this->appendArrayVar('headerParams', $this->getJavascriptFile('tabcontent.js', 'financialaid'));

            }
            $content .='<ol id="'.$this->tabName.'" class="shadetabs">';
            $content .='<li class="selected"><a href="#" rel="'.$this->tabs[0]['name'].'">'.$this->tabs[0]['caption'].'</a></li>';
            for ($i = 1; $i < $this->tabCount; $i++){
                $content .='<li><a href="#" rel="'.$this->tabs[$i]['name'].'">'.$this->tabs[$i]['caption'].'</a></li>';
            }
            $content .='</ol>';

            $content .='<div class="tabcontentstyle"> ';

            for ($i = 0; $i < $this->tabCount; $i++){
                $content .='<div id="'.$this->tabs[$i]['name'].'" class="tabcontent">';
                $content .=$this->tabs[$i]['content'];
                $content .='</div>';
            }
            $content .='</div>';

            $content .='<script type="text/javascript">';
            $content .='initializetabcontent("'.$this->tabName.'")';
            $content .='</script>';
        }else{
            $content = 'No tabs';
        }
		return $content;
	}

}
