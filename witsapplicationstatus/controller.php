<?php 
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

//class has same name as module class
class witsapplicationstatus extends controller
{
	
public $objWSresult;

public function init()
{
     //parent controller contains this function getOblect     $this->objWSresult = $this->getObject("wsgetapplicationstatus");
}
  
public function dispatch($action)
{
	switch($action){
		case "login" :	
			return "loginform_tpl.php";	
			
		case 'querystatus' :  
			return "applicationqueryform_tpl.php";
			
		case 'getstatus' :
			return 'displayapplicationstatus_tpl.php';

			case 'getexample' :
			return 'displayapplicationstatus_tpl.php';				

			default : 	
		   return 'applicationqueryform_tpl.php';	
	}
}

function requiresLogin(){
	return false;
}
}
?>