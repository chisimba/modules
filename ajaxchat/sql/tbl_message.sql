<?php
	$sqldata[] = "CREATE TABLE tbl_ajaxchat_message (
		  id varchar(50) NOT NULL,
		  chat_id varchar(50) NULL ,
		  user_id varchar(50) NULL ,
		  user_name VARCHAR(50) DEFAULT NULL,
		  message TEXT,
		  post_time DATETIME DEFAULT NULL,
		  PRIMARY KEY  (message_id)
		) ENGINE=INNODB DEFAULT CHARSET=latin1";
?>
