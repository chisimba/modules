<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table  
*/
class leftblock extends object
{

	function show(){
		$list = "";
		if($this->getParam('module') === "financialaid"){
			$list = array('sponsorlist'=>'Sponsor List','ok'=>'Student List');
		}
	
		if($this->getParam('module') === "residence"){
			$list = array('ok'=>'Student List');
		}
		$links = "";

		foreach($list as $key=>$value){
			$link = new link();
			$link->href = $this->uri(array('action'=>$key));
			$link->link = $value;
			$links .= "<p>".$link->show()."</p>";
		}
		return '<p>'.$links.'</p>';
		
	}

}

?>
