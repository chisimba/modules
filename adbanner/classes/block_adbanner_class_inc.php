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
class block_adbanner extends object {
	function init() {
		$this->objBanner=$this->getObject('adban');
	}

	public function show() {
		return $this->objBanner->displayBannerInBlock();	}}
?>
