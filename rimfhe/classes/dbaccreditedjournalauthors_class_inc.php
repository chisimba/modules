<?php
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class dbaccreditedjournalauthors extends dbtable
{	
	public $objUrl;
	public $mode;
	
	//method to define the table
	public function init()
	{
		parent::init('tbl_rimfhe_accr_journal_authors');
		$this->objUrl = $this->getObject('url', 'strings');
	}//end init()
		
	public function accreditedJournalAuthors()
	{	
		$authorname = array();
		$authoraffiliate = array();
		$fractionalweightedavg = array();
		
		$author1 =$this->getParam('author1');
		$author2 =$this->getParam('author2');
		$author3 =$this->getParam('author3');
		$author4 =$this->getParam('author4');	
		//$fractweightedavg= $this->getParam('category');
		$author1affiliate= $this->getParam('author1affiliate');
		$author2affiliate= $this->getParam('author2affiliate');
		$author3affiliate= $this->getParam('author3affiliate');
		$author4affiliate= $this->getParam('author4affiliate');
		$journalname = $this->getParam('articletitle');
		
		//check which author fieild is not empty
		if (!empty($author1)){
		$authorname[] =$author1;
			switch($author1affiliate){
				case 'UWC Staff Member':
				$authorname[] ='<b>'.$author1.'</b>';
				$fractionalweightedavg[]=0.67;
				break;
				case 'UWC Student':
				$authoraffiliate[] = '2';
				$fractionalweightedavg[]=NULL;
				break;
				case 'External Author':
				$authoraffiliate[] = '3';
				$fractionalweightedavg=NULL;
				$fractionalweightedavg[]=NULL;
				break;						
			}
		
		}
		if (!empty($author2)){
		$authorname[] =$author2;
			switch($author2affiliate){
				case 'UWC Staff Member':
				$authorname[] ='<b>'.$author1.'</b>';
				$fractionalweightedavg[]=0.67;
				break;
				case 'UWC Student':
				$authoraffiliate[] = '2';
				$fractionalweightedavg[]=NULL;
				break;
				case 'External Author':
				$authoraffiliate[] = '3';
				$fractionalweightedavg[]=NULL;
				break;						
			}
		}
		if (!empty($author3)){
		$authorname[] =$author3;
			switch($author3affiliate){
				case 'UWC Staff Member':
				$authorname[] ='<b>'.$author1.'</b>';
				$fractionalweightedavg[]=0.67;
				break;
				case 'UWC Student':
				$authoraffiliate[] = '2';
				$fractionalweightedavg[]=NULL;
				break;
				case 'External Author':
				$authoraffiliate[] = '3';
				$fractionalweightedavg[]=NULL;
				break;						
			}
		}
		if (!empty($author4)){
		$authorname[] =$author4;
			switch($author4affiliate){
				case 'UWC Staff Member':
				$authorname[] ='<b>'.$author1.'</b>';
				$fractionalweightedavg[]=0.67;
				break;
				case 'UWC Student':
				$authoraffiliate[] = '2';
				$fractionalweightedavg[]=NULL;
				break;
				case 'External Author':
				$authoraffiliate[] = '3';
				$fractionalweightedavg[]=NULL;
				break;						
			}
		}
		//count the number of authors
		$author = count($authorname);
		$countaffiliate=count($authoraffiliate);
		$countfractionalweightedavg=count($fractionalweightedavg);
		$fractionalweightedavgcount = 0;
		$affiliatecount = 0;
		$authorcount = 0;		
		do{
		$fields =array(
				'authorname'=> $authorname[$authorcount],
				'fractweightedavg' => $fractionalweightedavg[$fractionalweightedavgcount ],
				'authorsaffiliate' => $authoraffiliate[$affiliatecount],
				'articletitle'=> $journalname
				);
		$this->insert($fields);
		$fractionalweightedavgcount +=1;
		$affiliatecount+=1;
		$authorcount+=1;
		
		}
		while ($authorcount<$author);
		
	}//end accreditedJournalAuthors method
	
	public function getAllJournalAuthor($articletitle)
	{	
	$query="SELECT authorname, authorsaffiliate  FROM tbl_rimfhe_accr_journal_authors WHERE articletitle = '$articletitle'";
		$where = "WHERE articletitle='".$articletitle."'";				
		return $this->getArray($query);	
	}
		
}//end dbstaffmember
?>
