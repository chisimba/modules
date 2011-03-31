<?php
$content = "The system needs to be upgraded with the new group manager for this to work";
if (class_exists('groupops', false)) {
    $content = $this->objEventUtils->getUserGroups();
}
echo $content;
?>