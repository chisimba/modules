<?php

/**
 * @Author Emmanuel Natalis
 * @University Computing Center
 * @University of Dar es salaam
 */
$this->objAltconfig = $this->getObject('altconfig', 'config');
echo "<center><img alt=\"\" src=\"" . $this->objAltconfig->getsiteRoot() . "/skins/_common/icons/happybirthday.gif\"></center><br>";
$this->objDbbeswishes = $this->getObject('dbbestwishes', 'bestwishes');
echo $this->objDbbeswishes->viewBirthdayUsers();
echo "<center><h4><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes'>go to main menu</a></h4></center>";
?>
