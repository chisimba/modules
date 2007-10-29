<?php
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Data access (db model) Class for the adm module
 *
 * This is a database model class for the adm module. All database transaactions will go through
 * this class. This class is derived from the top level dbTable superclass in the framework core.
 *
 * @author     Paul Scott
 * @filesource
 * @copyright  AVOIR
 * @package    adm
 * @category   chisimba
 * @access     public
 */
class dbadm extends dbTable
{
    /**
     * Standard init function - Class Constructor
     *
     * @access public
     * @param  void  
     * @return void  
     */
    public function init() 
    {
        $this->objLanguage = $this->getObject("language", "language");
        parent::init('tbl_users');
    }
    
    public function insertSqldata($sqldata)
    {
    	return $this->_execute($sqldata);
    }
    
}
?>