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

		$list = array('ok'=>'Search');
		$links = "";
		foreach($list as $key=>$value){
			$link = new link();
			$link->href = $this->uri(array('action'=>$key));
			$link->link = $value;
			$links .= $link->show()."<br><br>";
		}
		
		$studentid = $this->getParam('id');
		if ($studentid) {
			
			$links .= "<p><strong>Student Details</strong></p>";
			$href = new href("index.php?module=studentenquiry&action=info&id=$studentid","Basic Info");
			$links.="<p>&nbsp;&nbsp;&nbsp;".$href->show()."</p>";
			$href = new href("index.php?module=studentenquiry&action=results&id=$studentid","Course Info");
			$links.="<p>&nbsp;&nbsp;&nbsp;".$href->show()."</p>";
			$href = new href("index.php?module=studentenquiry&action=enquiry&id=$studentid","Enquiry");
			$links.="<p>&nbsp;&nbsp;&nbsp;".$href->show()."</p>";

		}
		
		return $links;
	}
	
	

}

?>
