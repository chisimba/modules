<?php
/**
 * 
 */
class dbpopularity extends dbTable 
{
    
    /**
     * Constructor
     *
     */
    public function init()
    {
        parent::init('tbl_remotepopularity');
    }
    
    /**
     * Public method to insert a record to the popularity contest table as a log.
     * 
     * This method takes the IP and module_name and inserts the record. 
     *
     * @param unknown_type $recarr
     * @return unknown
     */
    public function addRecord($recarr)
    {
    	$times = $this->now();
    	$recarr['downloadedon'] = $times;
    	return $this->insert($recarr, 'tbl_remotepopularity');
    }
}
?>