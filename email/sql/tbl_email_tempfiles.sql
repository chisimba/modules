<?
$sqldata[]=" CREATE TABLE tbl_email_tempfiles ("
    ."id varchar(32) PRIMARY KEY NOT NULL,"
    ."email_id varchar(32),"
    ."userId varchar(32),"
    ."fileId varchar(100),"
    ."filename varchar(120),"
    ."filetype varchar(32),"
    ."size int,"
    ."uploadtime int,"
    ."updated timestamp(14)"
.") type=InnoDB COMMENT='This table stores metadata for temporary file attachments'";
?>
