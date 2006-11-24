<?php
/*
$sqldata[]="CREATE TABLE tbl_internalmail(
    id VARCHAR(32) NOT NULL,
    senderId VARCHAR(32) NOT NULL,
    recipientList TEXT NOT NULL,
    subject VARCHAR(50) NULL,
    message TEXT NULL,
    dateSent DATETIME NULL,
    attachment TINYINT(1) NULL,
    updated TIMESTAMP(14) NOT NULL,
    PRIMARY KEY(id),
    KEY(senderId),
    CONSTRAINT `FK_tbl_internalmail_senderId` FOREIGN KEY (`senderId`) REFERENCES `tbl_users` (`userId`)
    ) TYPE=InnoDB COMMENT='Main email table'";
*/

//5ive definition
$tablename = 'tbl_email';

//Options line for comments, encoding and character set
$options = array('comment' => 'Main email table', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'sender_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'recipient_list' => array(
        'type' => 'clob'
        ),
    'subject' => array(
        'type' => 'text',
        'length' => 255,
        ),
    'message' => array(
        'type' => 'clob',
        ),
    'date_sent' => array(
        'type' => 'timestamp',
        ),
    'attachments' => array(
        'type' => 'integer',
        'length' => 2,
        ),
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
$name = 'email_index';

$indexes = array(
                'fields' => array(
                    'sender_id' => array(),
                ),
        );
?>