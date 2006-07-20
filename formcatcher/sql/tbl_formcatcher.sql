<?php
$sqldata[] = "CREATE TABLE tbl_formcatcher (
   `id` varchar(32) NOT NULL,
   `creatorId` varchar(25) NOT NULL,
   `dateCreated` datetime NOT NULL,
   `modifierId` varchar(25) NULL,
   `dateModified` datetime NULL,
   `usefullpage` char(1) NOT NULL DEFAULT 0,
   `title` varchar(250) NULL,
   `email` varchar(250) NULL,
   `link` varchar(250) NULL,
   `filename` varchar(250) NULL,
   `description` varchar(250) NULL,
   `modified` timestamp(14) NOT NULL,
   `context` varchar(50) NOT NULL,
 PRIMARY KEY (id)) TYPE=INNODB ";
?>