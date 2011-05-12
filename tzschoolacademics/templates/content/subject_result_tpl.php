<?php

// security check-must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check


/*
 * template for displaying data regarding various subject result
 *
 *  @author charles mhoja
 *  @email charlesmhoja@gmail.com
 */

//loading thereport display class
$displayObj = $this->newObject('reportdisplay', 'tzschoolacademics');
if (strcmp($option, 'sub_result') == 0) {
    echo $displayObj->generate_subject_result($subject_id, $exam_id, $term_id, $year_id, $class_id);
}
else {
    echo $displayObj->create_subject_result_form();
}
?>
