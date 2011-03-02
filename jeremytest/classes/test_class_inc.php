<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
* Model class for the table tbl_jeremytest_test1
* @author Jeremy O'Connor
* @copyright 2011 University of the Western Cape
*/
class test extends dbtable
{
    /**
    * Constructor method to define the table
    */
    public function init()
    {
        parent::init('tbl_jeremytest_test1');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Insert records
	* @return void
    */
	public function doTest()
	{
        $this->insert(array('content'=>'test'));
	}
}
?>