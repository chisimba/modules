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
$this->loadClass('link', 'htmlelements');

$content= $this->objViewerUtils->getTopic($storyid);
echo $content;
?>
