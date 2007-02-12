<?php
	$sqldata[] = "CREATE TABLE tbl_ajaxchat_rooms (
		   id VARCHAR(50) NOT NULL,
		   name VARCHAR(64) DEFAULT NULL,
		   description text,
		   date_created DATE ,
		   created_by varchar(50),
		   chat_id         text,
		  PRIMARY KEY  (id)
		) ENGINE=INNODB DEFAULT CHARSET=latin1";
		$sqldata[] = "INSERT INTO tbl_ajaxchat_rooms(id, name, description, date_created,created_by)
		VALUES('101', 'Lobby', 'General Talk and Discussion', now(),'1')";
?>
