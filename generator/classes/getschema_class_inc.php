<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}

/**
* 
* Class to get a database schema from the database and prepare
* it for use in code generation
* 
* @author Derek Keats
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class getschema extends dbTableManager
{
    /**
    * Constructor method to define the table
    */
    public function init()
    {
        parent::init();
    }
    
    public function getXmlSchema()
    {
        //return $this->getDefFromDb('tbl_calendar');
        $def = $this->getDefFromDb();
        echo ">>>>" . $def;
		return $def;
    }
}
?>