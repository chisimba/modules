<?
  $sqldata[]="CREATE TABLE tbl_freemind (
  id varchar(32) NOT NULL,
  contextCode varchar(255) NOT NULL,
  title varchar(255) default NULL,
  map TEXT default NULL,
  dateCreated datetime default NULL,
  PRIMARY KEY  (id)
  
) 
TYPE=InnoDB
";
?>