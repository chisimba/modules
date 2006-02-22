<?
$sqldata[]="CREATE TABLE if not exists tbl_menu_category (
    id varchar(32), 
    category varchar(120),
    module varchar(60),
    adminOnly TINYINT NOT NULL default 0,
    lecturerOnly TINYINT NOT NULL default 0,
    dependsContext TINYINT NOT NULL default 0
    ) Type=InnoDB ";

?>
