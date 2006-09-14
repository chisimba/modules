<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_homepages
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbHomePagesLog extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_homepages_log');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
	* @param string $homepageId The homepage ID
	* @param integer $dow The day of the week
	* @return array The count of accesses to a homepage for a given d.o.w.
    */
	function listAll($homepageId,$dow)
	{
		$sql = "SELECT count(id) FROM tbl_homepages_log
		WHERE (homepageId='".$homepageId."')
		AND (dow='".$dow."')";
		return $this->getArray($sql);
	}

	/**
	* Insert a record
	* @param string $homepageId The homepage ID
	* @param integer $dow The day of the week
	* @param integer $ip The IP address
        * @param datetime $timestamp The timestamp
	*/
	function insertSingle($homepageId, $dow, $ip, $timestamp)
	{
		$this->insert(array(
			'homepageId' => $homepageId,
			'dow' => $dow,
			'ip' => $ip,
                        'timestamp' => strftime('%Y-%m-%d %H:%M:%S',$timestamp)
		));
		return;	
	}
}
?>
