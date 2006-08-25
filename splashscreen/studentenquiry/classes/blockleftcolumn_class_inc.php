<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table  
* Creates the menu on the left for Student Details
*/
class blockleftcolumn extends object
{
    /**
    * var object Property to hold language object
    */
    var $objLanguage;

	function show($studentid=null){
        // Get an Instance of the language object
        $this->objLanguage = &$this->getObject('language', 'language');

		if ($studentid || $this->getParam('id')) {
		
			$str = "<p>". $this->objLanguage->languageText('mod_studentenquiry_search', 'studentenquiry') ."</p>";
			$href = new href("index.php?module=studentenquiry&action=info&id=$studentid",
                $this->objLanguage->languageText('mod_studentenquiry_search', 'studentenquiry'));
			$str.="<p>".$href->show()."</p>";
			$href = new href("index.php?module=studentenquiry&action=more_info&id=$studentid","Detailed Info");
			$str.="<p>".$href->show()."</p>";
			
			return $str;
		
		}
		
	}

}
