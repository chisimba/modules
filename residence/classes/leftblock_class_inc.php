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

	function show($id){
		if($id==null){
		$list = "";
		$list .='<h3>'.'Residence and Catering Service'.'</h3>';
		
		
		$linkintro = new link();
		$linkintro->href=$this->uri(array('action'=>''));
		$linkintro->link="Introduction";
		
		$list .=$linkintro->show();
		
		return $list;
	
}else{
		$list = "";
		$list .='<h3>'.'Residence and Catering Service'.'</h3>';
		
		
		$linkintro = new link();
		$linkintro->href=$this->uri(array('action'=>''));
		$linkintro->link="Introduction";

		$linkres = new link();
		print $link1;
		$linkres->href=$this->uri(array('action'=>'resapp','id'=>$id));
		$linkres->link="Add Student";
		
		$list .='<p>'.$linkintro->show().'</p>';
			
		$list .='<h3>'.'Residence Application'.'</h3>';
			

		$list .='<p>'.$linkres->show().'</p>';
		
		return $list;

}//else
}//fnction
}//end class

?>
