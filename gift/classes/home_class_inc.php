<?php
class home extends object {

    public function init() {
        /*impoting classes*/
        $this->loadClass('htmlheading', 'htmlelements');
		$this->loadClass('link', 'htmlelements');
        $this->objLanguage = $this->getObject("language", "language");
    }
	
	public function displayForm($msg) {
	
        $addgift  = new link($this->uri(array("action"=>"add")));
        $editgift = new link($this->uri(array("action"=>"result")));
		
        $addgift->link  = $this->objLanguage->languageText('mod_home_addLink','gift');
        $editgift->link = $this->objLanguage->languageText('mod_home_editLink','gift');
		
        $add  = $addgift->show();
        $edit = $editgift->show();
		
        $home = "<ul>
                 <li>$add</li>
                 <li>$edit</li>
                 </ul>";
				 
		return '<center><font color="green">'.$msg.'</font></center>'.$home;
	}
}
?>