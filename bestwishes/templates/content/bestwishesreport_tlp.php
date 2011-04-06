<?php

/**
 * @ Author Emmanuel Natalis
 * @ University of dar es salaam
 * @ 2008
 */
$this->objAltconfig = $this->getObject('altconfig', 'config');
if (isset($report)) {
    $this->message = $report;
}
echo "<center><h3>" . $this->message . "</h3></center>";

echo "<center><h4><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes'>go to main menu</a></h4></center>";
?>
