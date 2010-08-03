<?php

/**
 * @JCSE
 * 
 */
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


$short_courses = $this->newObject('short_courses_main', 'cfe');

echo $short_courses->show();

?>
