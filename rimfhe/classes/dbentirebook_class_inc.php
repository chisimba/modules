<?php
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class dbentirebook extends dbtable
{	
	public $objUrl;
	public $mode;
	
	//method to define the table
	public function init()
	{
		parent::init('tbl_rimfhe_books');
		$this->objUrl = $this->getObject('url', 'strings');
	}//end init()
		
	public function addEntireBook()
	{			
		$surname = $this->getParam('surname');
		$initials= $this->getParam('initials');
		$firstname= $this->getParam('firstname');
		$droptitle= $this->getParam('title');
		$rank= $this->getParam('rank');
		$appointment= $this->getParam('appointment');
		$dept= $this->getParam('department');
		$faculty= $this->getParam('faculty');
		$staffNumber= $this->getParam('staffNumber');
		$email= $this->getParam('email');
		 // Else add to database
           $fields =array(
				'surname'=> $surname,
				'initials'=> $initials,
				'firstname' => $firstname,
				'tiltle' => $droptitle,
				'rank'=> $rank,
				'appointmenttype'=> $appointment,
				'department' => $dept,
				'staffnumber'=> $staffNumber,
				'faculty'=> $faculty,
				'email'=> $email,
				'dateresistered' => $this->now()
			);
		$this->insert($fields);
		
	}//end addStaffDetails	
	

}//end dbstaffmember
?>
