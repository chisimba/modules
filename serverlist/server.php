<?php
/**
 * This file gets a sername then creates
 * the xml that the client will consume
 * in REST format
 */
$GLOBALS['kewl_entry_point_run'] = true;
require_once 'C:/Program Files/xampp/htdocs/5ive/app/lib/logging.php';
require_once 'C:/Program Files/xampp/htdocs/5ive/app/classes/core/dbtable_class_inc.php';
require_once 'C:/Program Files/xampp/htdocs/5ive/app/modules/serverlist/classes/dbserverlist_class_inc.php' ;

$objServerList = new dbserverlist();
$xml = $objServerList->generateXML();
echo $xml;
?>