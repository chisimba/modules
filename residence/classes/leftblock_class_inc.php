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
		
		$list .='<p>'.$linkintro->show().'</p>';
		
		$list .='<h3>'.'Residence Application'.'</h3>';
		$linkapp = new link();
		
		$linkapp->href=$this->uri(array('action'=>'viewallres','id'=>$id));
		$linkapp->link="View All Applicants";
		
		$list .='<p>'.$linkapp->show().'</p>';
			
		$linkres = new link();
		
		$linkres->href=$this->uri(array('action'=>'viewallres','id'=>$id));
		$linkres->link="View All Residence";
		
		$list .='<p>'.$linkres->show().'</p>';
			

		$link_add = new link();
		$link_add->href=$this->uri(array('action'=>'resapp','id'=>$id));
		$link_add->link="Add Student";
		$list .='<p>'.$link_add->show().'</p>';

		return $list;
	
}else{
		$list = "";
		$list .='<h3>'.'Residence and Catering Service'.'</h3>';
		
		
		$linkintro = new link();
		$linkintro->href=$this->uri(array('action'=>''));
		$linkintro->link="Introduction";

		
		
		return $list;

}//else
}//fnction
}//end class

?>
