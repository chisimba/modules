<?

$sqldata[]="CREATE TABLE tbl_email (
    id varchar(32) PRIMARY KEY not NULL, 
    email_id varchar(32), 
    sender_id varchar(32), 
    user_id varchar(32),
    subject varchar(50),
    message text,
    date_sent varchar(20),
    date_read char(20),
    email_attach varchar(32),
    folder varchar(32),
    updated timestamp(14),
    KEY (email_id),
    KEY (user_id)
    ) Type=InnoDB COMMENT='This is the primary table for the internal email system'";

?>
