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
    
    public function addRecord($recarr)
    {
    	return $this->insert($recarr, 'tbl_remotepopularity');
    }
}
?>