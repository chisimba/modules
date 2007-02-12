<?php
	$sqldata[] = "CREATE TABLE tbl_ajaxchat_users ( 
    id          	int(11) NOT NULL AUTO_INCREMENT,
    user_id     	varchar(50) NULL,
    user_name     	varchar(50) NULL,
    enter_time   	date NULL,
	chat_id   	    text,
    PRIMARY KEY(id)
) ENGINE=INNODB DEFAULT CHARSET=latin1";
?>
