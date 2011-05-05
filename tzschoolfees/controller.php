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
 * Controller class for academic module
 *
 * @category  Chisimba
 * @package   SMIS Fees
 * @author    Fees Team
 */
class tzschoolfees extends controller {

    public $lang;
    private $user;

    public function init() {

    }

    public function dispatch($action) {

        }
    

}

?>
