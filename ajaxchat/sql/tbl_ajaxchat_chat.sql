<?php
	$sqldata[] = "CREATE TABLE tbl_ajaxchat_chat (
		   id VARCHAR(50) NOT NULL,
		   chat_name VARCHAR(64) DEFAULT NULL,
		   start_time DATETIME DEFAULT NULL,
		  PRIMARY KEY  (id)
		) ENGINE=INNODB DEFAULT CHARSET=latin1;";
?>
