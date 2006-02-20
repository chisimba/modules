<?
$sqldata[]=" CREATE TABLE tbl_email_blob ("
    ."id varchar(32) PRIMARY KEY NOT NULL,"
    ."fileId varchar(100),"
    ."segment int,"
    ."filedata blob,"
     ."updated timestamp(14)"
.") type=InnoDB COMMENT='This table stores file attachments in BLOB form'";
?>
