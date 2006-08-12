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
    
    /**
    * 
    * Method to get the XML schema for a table.
    * 
    * @param $tableName The table for which to look up the schema
    * 
    */
    public function getXmlSchema($table)
    {
        return $this->getTableSchema($table);
    }
    
    public function getFieldNamesAsArray($table)
    {
    	$ret=array();
        $schema = $this->getXmlSchema($table);
        $structure = $schema['fields'];
        unset($schema);
        foreach ($structure as $key=>$valueArray) {
			$ret[] = $key;
        }
        return $ret;
    }
}
?>