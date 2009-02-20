<?php
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class dbaccreditedjournal extends dbtable
{	
	public $objUrl;
	public $mode;
	
	//method to define the table
	public function init()
	{
		parent::init('tbl_rimfhe_doe_accr_journal');
		$this->objUrl = $this->getObject('url', 'strings');
	}//end init()
		
	public function accreditedJournal()
	{			
		$journalname = $this->getParam('journalname');
		$journalcategorys= $this->getParam('category');
		$articletitle= $this->getParam('articletitle');
		$yrofpubication= $this->getParam('publicationyear');
		$volume= $this->getParam('volume');
		$fisrtpgno= $this->getParam('firstpage');
		$lastpgno= $this->getParam('lastpage');
		$pagetotal= ($lastpgno - $fisrtpgno) + 1;
		 // Else add to database

           $fields =array(
				'journalname'=> $journalname,
				'journalcategory'=> $journalcategorys,
				'articletitle' => $articletitle,
				'publicationyear' => $yrofpubication,
				'volume'=> $volume,
				'firstpageno'=> $fisrtpgno,
				'lastpageno' => $lastpgno,
				'pagetotal'=> $pagetotal,
				);
		$this->insert($fields);
		
	}//end addStaffDetails	

public function getAllJournalAuthor()
	{						
		return $this->getAll();	
	}
	
	
}//end dbstaffmember
?>
