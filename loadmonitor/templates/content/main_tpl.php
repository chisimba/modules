<?php

/**
 *
 *
 * @version $Id: dump_tpl.php 21037 2011-03-29 13:20:11Z joconnor $
 * @copyright (C) 2009, 2011 AVOIR
 */

$objSiteLoad = $this->newObject('siteload');
$count = $objSiteLoad->Count();
if ($count == 1) {
    echo 'There is currently '.$count.' user logged in.';
} else {
    echo 'There are currently '.$count.' users logged in.';
}

?>