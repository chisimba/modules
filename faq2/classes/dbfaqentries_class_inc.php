<?php
/* ----------- data class extends dbTable for tbl_blog------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_faq_entries
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbFaqEntries extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_faq2_entries');
        //$this->USE_PREPARED_STATEMENTS=True;
    }
 
    /**
	* Insert a record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @param string $question The question
	* @param string $answer The answer
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
	function insertFaqEntry($license,$userid,$datelastupdated)
	{
	  $ins = $this->insert(array(
			'licenseid'=>$license, 
			'userid'=>$userid,
			'dateLastupdated' =>$datelastupdated
		));
	  $id=$this->getLastInsertId();
		return $id;  
		
		
<<<<<<< dbfaqentries_class_inc.php
		
	}
	
	/* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @param string $question The question
	* @param string $answer The answer
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
	function insertFaqCatEntry($categoryid,$entryid,$entryorder,$userid,$datelastupdated)
	{
	  $ins = $this->insert(array(
			'categoryid'=>$categoryid, 
			'entryid'=>$entryid, 
			'entryorder' => $entryorder,
			'userid' => $userid,
			'datelastupdated' => $datelastupdated
			
		),"tbl_faq2_categories_entries");
		
	return $ins;
	}
    function insertFaqEntryLang($entryid,$question,$answer,$language,$isdefaultlang,$userid,$datelastupdated)
	{
	  $ins = $this->insert(array(
			'entryid'=>$entryid, 
			'question'=>$question, 
			'answer' => $answer,
			'language' => $language,
			'isdefaultlang' => $isdefaultlang,
			'userid' => $userid,
			'datelastupdated' => $datelastupdated
			
		),"tbl_faq2_entries_lang");
		
		return $ins;
=======
		// Add to Search
        $objIndexData = $this->getObject('indexdata', 'search');
        
        // Prep Data
        $docId = 'faq_entry_'.$ins;
        $docDate = strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated);
        $url = $this->uri(array('action'=>'view', 'category'=>$categoryId), 'faq');
        $title = $categoryRow['categoryname'];
        $contents = $question.': '.$answer;
        $teaser = $question;
        $module = 'faq';
        $userId = $userId;
        
        // Add to Index
        $objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents, $teaser, $module, $userId);
		return $ins;	
>>>>>>> 1.10
	}
    
    /**
    * Get FAQ entries
    * @author Nonhlanhla Gangeni <noegang@gmail.com>
    */
    function getEntries($contextId, $categoryId)
    {
        $sql = "SELECT fc.categoryname as categoryname, fe.question as qn, fe.answer FROM tbl_faq_entries fe,tbl_faq_categories fc WHERE fe.contextid='" . $contextId . "' and fc.id= fe.categoryid";

        return $this->getArray($sql);
    }
    function getFaqEntriesCount($categoryid)
    {
        $sql = "SELECT * from tbl_faq2_categories_entries where categoryid='$categoryid'";

        return $this->getArray($sql);
    }

    /**
    * Return all records
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return array The FAQ entries
    */
	function listAll($categoryid)
	{
	    if(empty($categoryid))
            $categoryid=0;
            $sql="select distinct * from tbl_faq2_categories_entries ce,tbl_faq2_entries_lang el";
	    $sql.=" where ce.entryid=el.entryid and ce.categoryid='$categoryid' group by ce.entryid";
        return $this->getArray($sql);	
	}

	/**
	* Return a single record
	* @param string $id ID
	* @return array
	* @return array The FAQ entrry
	*/	
	function listSingle($id)
	{
		$sql="select * from tbl_faq2_entries_lang el";
		$sql.=" where el.id='$id'";
		
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}
        function getCatLangId($entryid)
	{
		$sql="select * from tbl_faq2_entries_lang el";
		$sql.=" where el.entryid='$entryid'";
		
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}
	function listCatEntry($entryid)
	{
		$sql="select * from tbl_faq2_categories_entries ce";
		$sql.=" where ce.entryid='$entryid'";
		
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}
        function getCatEntry($entryid,$lang)
	{
		$sql="select * from tbl_faq2_entries_lang el";
		$sql.=" where el.entryid='$entryid' and el.language<>'$lang'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}
        
        function getLicenseCode($id="")
	{
		$sql="select * from tbl_faq2_entries e";
                if(!empty($id))
		$sql.=" where e.id='$id'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}

	/** 
	* Get the next index
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return int The next index
	*/
	function getNextIndex($categoryid)
	{
		$array = $this->getArray("SELECT MAX(entryorder) AS _max FROM tbl_faq2_categories_entries WHERE categoryid='$categoryid'");
		return $array[0]['_max'] + 1;
	}
	
        function updateEntry($id,$license, $userid,$datelastupdated)
	{
		$this->update("id", $id, 
			array(
				'licenseid' => $license,
        		'userid' => $userid,
                        'datelastupdated' => $datelastupdated
				
			)
		);
        }
        function updateEntryLang($id,$question,$answer,$language,$isdefaultlang,$userid,$datelastupdated)
	{
		$this->update("id", $id, 
			array(
				'question' => $question,
                                'answer' => $answer,
                                'language' => $language,
                                'isdefaultlang' => $isdefaultlang,
                                'userid' => $userid,
                                'datelastupdated' => $datelastupdated
				
			),"tbl_faq2_entries_lang"
		);
        }
	/**
	* Update a record
	* @param string $id ID
	* @param string $question The question
	* @param string $answer The answer
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
	function updateSingle($id, $index, $question, $answer, $categoryId, $userId, $dateLastUpdated)
	{
		$this->update("id", $id, 
			array(
				'_index' => $index,
        		'question' => $question,
        		'answer' => $answer,
                'categoryid' => $categoryId,
				'userid' => $userId,
				'datelastupdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)
			)
		);
		
		$this->objDbFaqCategories =& $this->getObject('dbfaqcategories');
		$categoryRow = $this->objDbFaqCategories->getRow('id', $categoryId);
		
		// Add to Search
        $objIndexData = $this->getObject('indexdata', 'search');
        
        // Prep Data
        $docId = 'faq_entry_'.$id;
        $docDate = strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated);
        $url = $this->uri(array('action'=>'view', 'category'=>$categoryId), 'faq');
        $title = $categoryRow['categoryname'];
        $contents = $question.': '.$answer;
        $teaser = $question;
        $module = 'faq';
        $userId = $userId;
        
        // Add to Index
        $objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents, $teaser, $module, $userId);
	}
	
	/**
	* Delete a record
	* @param string $id ID
	*/
	function deleteSingle($col,$value,$table)
	{
<<<<<<< dbfaqentries_class_inc.php
		$this->delete($col,$value,$table);
		//$objIndexData = $this->getObject('indexdata', 'lucene');
                //$objIndexData->removeIndex('faq_entry_'.$id);
=======
		$this->delete("id", $id);
		$objIndexData = $this->getObject('indexdata', 'search');
        $objIndexData->removeIndex('faq_entry_'.$id);
>>>>>>> 1.10
	}//
}
?>
