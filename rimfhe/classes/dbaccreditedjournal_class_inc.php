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
		$id = $this->getParam('journalid');//get record id when in edit mode		
		$journalname = $this->getParam('journalname');
		$journalcategorys= $this->getParam('category');
		$articletitle= $this->getParam('articletitle');
		$yrofpubication= $this->getParam('publicationyear');
		$volume= $this->getParam('volume');
		$fisrtpgno= $this->getParam('firstpage');
		$lastpgno= $this->getParam('lastpage');
		$pagetotal= ($lastpgno - $fisrtpgno) + 1;
		$author1 =$this->getParam('author1');
		$author2 =$this->getParam('author2');
		$author3 =$this->getParam('author3');
		$author4 =$this->getParam('author4');	
		$author1affiliate= $this->getParam('author1affiliate');
		$author2affiliate= $this->getParam('author2affiliate');
		$author3affiliate= $this->getParam('author3affiliate');
		$author4affiliate= $this->getParam('author4affiliate');
		$fractionalweightedavg->getParam('fraction');
		//check which author fieild is not empty
		//Differiate authors affiliated to UWC with bold text
		if (!empty($author1)){
		
			switch($author1affiliate){
				case 'UWC Staff Member':
				$author1 ='<b>'.$author1.'</b><br />';
				$fractionalweightedavg=0.67;
				break;
				case 'UWC Student':
				$author1 = '<span>'.$author1.'</span><br />';
				$fractionalweightedavg=NULL;
				break;
				case 'External Author':
				$author1 = $author1.'<br />';
				$fractionalweightedavg=NULL;
				break;						
			}
		
		}
		if (!empty($author2)){
		
			switch($author2affiliate){
				case 'UWC Staff Member':
				$author2 ='<b>'.$author2.'</b><br />';
				$fractionalweightedavg=0.67;
				break;
				case 'UWC Student':
				$author2 = '<span>'.$author2.'</span><br />';
				$fractionalweightedavg=NULL;
				break;
				case 'External Author':
				$author2 = $author2.'<br />';
				$fractionalweightedavg=NULL;
				break;						
			}
		}
		if (!empty($author3)){
		
		switch($author3affiliate){
				case 'UWC Staff Member':
				$author3 ='<b>'.$author3.'</b><br />';
				$fractionalweightedavg=0.67;
				break;
				case 'UWC Student':
				$author3 = '<span>'.$author3.'</span><br />';
				$fractionalweightedavg=NULL;
				break;
				case 'External Author':
				$author3 = $author3.'<br />';
				$fractionalweightedavg=NULL;
				break;						
			}
		}
		if (!empty($author4)){
		
			switch($author4affiliate){
				case 'UWC Staff Member':
				$author4 ='<b>'.$author4.'</b><br />';
				$fractionalweightedavg=0.67;
				break;
				case 'UWC Student':
				$author4 = '<span>'.$author4.'</span><br />';				
				$fractionalweightedavg=NULL;
				break;
				case 'External Author':
				$author4 = $author4.'<br />';
				$fractionalweightedavg=NULL;
				break;						
			}
		}
		$author = $author1.$author2.$author3.$author3;
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
				'authorname' => $author,
			
				);
		//if not edite mode, add record tp database
		if (empty($id)){
			//Cheeck if book with same title is already in the database
			$where = "WHERE articletitle='".$articletitle."'";
			$checkRecord = $this->getAll($where);
			if(count($checkRecord) > 0){
				return FALSE;
			}
			else{
				return $this->insert($fields);
			}
		}
		else{
		//update record
		return $this->update('id', $id, $fields);
		}
		
	}//end 

	public function getAllJournalAuthor()
	{						
		return $this->getAll();	
	}
	
	//This public method counts the totall number of Journal Artilce 
	public function totalJournalArticle()
	{
		$query ="SELECT COUNT(*) AS totalarticles FROM tbl_rimfhe_doe_accr_journal";
		$result = $this->query($query);
    		$return = $result[0]['totalarticles'];
		return $return; 
	}
	
	
}//end 
?>
