<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check


$leftContent = 'Left Column';

$middleContent = 'Management interface to controll automated Data Source additions based on AI API pattern matching techniques will go here :)';

$this->setVar('leftContent', $leftContent);
$this->setVar('middleContent', $middleContent);

?>
