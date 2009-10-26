<?php
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class dbrimfhe_journal extends dbtable
{	
	//method to define the table
	public function init()
	{
		parent::init('tbl_rimfhe_journal');
		$this->objUser = &$this->getObject('user', 'security');
	}
    /**
     * Return all records
     * @param string $journcatid The journal category id
     * @return array The entries
     */
    function listAll($journcatid) 
    {
        return $this->getAll("WHERE journcatid='" . $journcatid . "'");
    }
    /**
     * Return a single record
     * @param string $id ID
     * @return array The values
     */
    function listSingle($id) 
    {
        return $this->getAll("WHERE id='" . $id . "'");
    }
    function getByItem($issn) 
    {
        $sql = "SELECT * FROM tbl_rimfhe_journal.sql WHERE issn = '" . $issn . "'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
    }
    function getJournals($journid, $journal, $start, $limit) 
    {
         if(!empty($start) && !empty($limit)){
		        if(!empty($jounid)){
		         return $this->getAll("WHERE journcatid = ".$journid." AND journal LIKE '%".$journal."%' LIMIT ".$start." , ".$limit);
		        }else{
		         return $this->getAll("WHERE journal LIKE '%".$journal."%' LIMIT ".$start." , ".$limit);
		        }
         }else{
		        if(!empty($jounid)){
		         return $this->getAll("WHERE journcatid = ".$journid." AND journal LIKE '%".$journal."%'");
		        }else{
		         return $this->getAll("WHERE journal LIKE '%".$journal."%'");
		        }         
         }
    }
 
     function jsongetJournals($journid,$journal, $start, $limit) 
    {
        $myJournals = $this->getJournals($journid,$journal, $start, $limit);
       	$journalCount = ( count ( $myJournals ) );
        $str = '{"journalcount":"'.$journalCount.'","searchedjournals":[';
        $searchArray = array();
        foreach($myJournals as $thisJournal){
          $infoArray = array();
          $infoArray['jid'] = $thisJournal['id'];
          $infoArray['jname'] = $thisJournal['journal'];
          $searchArray[] = $infoArray;
        }
        return json_encode(array('journalcount' => $journalCount, 'searchresults' =>  $searchArray));
    }
     function jsongetAllJournals($start=Null, $limit=Null) 
    {
        if(empty($start) && empty($limit)){
         $myJournals = $this->getAll();
       	}else{
         $myJournals = $this->getAll(" LIMIT ". $start.", ".$limit);
        }
        $journalCount = ( count ( $myJournals ) );
        $str = '{"journalcount":"'.$journalCount.'","searchedjournals":[';
        $searchArray = array();
        foreach($myJournals as $thisJournal){
          $infoArray = array();
          $infoArray['jid'] = $thisJournal['id'];
          $infoArray['jname'] = $thisJournal['journal'];
          $searchArray[] = $infoArray;
        }
        return json_encode(array('journalcount' => $journalCount, 'searchresults' =>  $searchArray));
    }

    /**
     * Insert a record
     * @param string $journal The journal
     * @param string $issn The issn
     * -- @param string $description The description
     */
    function insertSingle($id, $journcatid, $journal, $issn, $description) 
    {
        $userid = $this->objUser->userId();
        $id = $this->insert(array(
            'journcatid' => $journcatid,
            'journal' => $journal,
            'issn' => $issn,
            'description' => $description
        ));
        return $id;
    }
    /**
     * Update a record
     * @param string $journal The journal
     * @param string $issn The issn
     * -- @param string $description The description
     */
    function updateSingle($id, $journcatid, $journal, $issn, $description) 
    {
        $userid = $this->objUser->userId();
        $this->update("id", $id, array(
            'journcatid' => $journcatid,
            'journal' => $journal,
            'issn' => $issn,
            'description' => $description
        ));
    }
    /**
     * Delete a record
     * @param string $id ID
     */
    function deleteSingle($id) 
    {
        $this->delete("id", $id);
    }
}//end 
?>
