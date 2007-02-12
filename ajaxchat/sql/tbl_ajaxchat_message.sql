<?php
	$sqldata[] = "CREATE TABLE tbl_ajaxchat_message ( 
    id          	int(11) NOT NULL AUTO_INCREMENT,
    chat_id     	varchar(50) NULL,
    user_id     	varchar(50) NULL,
    user_name   	varchar(50) NULL,
    message     	text NULL,
    post_time   	datetime NULL,
    post_seconds	varchar(100) NULL,
    isRead      	smallint(6) NULL DEFAULT '0',
    PRIMARY KEY(id)
) ENGINE=INNODB DEFAULT CHARSET=latin1";
?>
