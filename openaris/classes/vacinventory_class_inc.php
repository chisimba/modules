<?php

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check


class vacinventory extends dbtable {
	
  
	public function init() {
		try {
			parent::init('tbl_ahis_vacinventory');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}
	}
}