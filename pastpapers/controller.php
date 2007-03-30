<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* 
* @ Athor Nsabagwa Mary,Kaddu Ismael
**/



class pastpapers extends controller
{

/**
    * @var  object $objLanguage
    */
    public $objLanguage;
	
	public function init(){
	$this->objLanguage = $this->getObject('language','language');
	$this->_objDBContext =& $this->getObject('dbcontext','context');
	$this->objConfig = $this->getObject('altconfig','config');
	$this->objUser = $this->getObject('user','security');
	
	}//end of function	
	
	public function dispatch($action){
	$this->setLayoutTemplate('pastpaper_layout_tpl.php');
	 try{
	 switch ($action) { 
	 
	   case "add":	  
	    return "add_tpl.php";
	   break;
	   case "savepaper":
	     $topic = $this->getParam('topic',NULL); 
	     $examyear = $this->getParam('date',NULL);
	     $this->pastpaper =& $this->getObject('pastpaper');
		 $this->pastpaper->uploadfile();		
		
	  $file_name = $_FILES['filename']['name'];			 
	  $this->pastpaper->savepaper($file_name,$examyear,$topic );
	  return "main_tpl.php";   	 
		
	   break;
	   
	   case "otherpapers":
	   return "otherpapers_tpl.php"; 
	   break;
	   
	   case "addanswers":
	     $paperid = $this->getParam('paperid',NULL);
		 return "addanswers_tpl.php";
		 
	   break;
	   
	   
	   default : return "main_tpl.php";
	 
	 }//closing the switch 
	 
	 
	 }
	 catch(customException $e){
	   customException::cleanUp();
	 
	 }
	
	
	
	}

}

?>