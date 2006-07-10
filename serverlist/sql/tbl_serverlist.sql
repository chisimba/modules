<?php
$sqldata[] = "CREATE TABLE `tbl_serverlist` (
`id` VARCHAR( 32 ) NOT NULL ,
`servername` VARCHAR( 32 ) NOT NULL ,
`location` TEXT NOT NULL ,
`updated` TIMESTAMP NOT NULL ,
PRIMARY KEY ( `id` )
) TYPE = MYISAM ;";
?>