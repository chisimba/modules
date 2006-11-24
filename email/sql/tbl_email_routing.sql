<?php
/*
$sqldata[] = "CREATE TABLE tbl_internalmail_routing(
    id VARCHAR(32) NOT NULL,
    emailId VARCHAR(32) NOT NULL,
    senderId VARCHAR(32) NOT NULL,
    recipientId VARCHAR(32) NOT NULL,
    folderId VARCHAR(32) NOT NULL,
    sentEmail TINYINT(1) NOT NULL DEFAULT 0,
    emailRead TINYINT(1) NOT NULL DEFAULT 0,
    dateRead DATETIME NULL,
    updated TIMESTAMP(14) NOT NULL,
    PRIMARY KEY(id),
    KEY(emailId),
    KEY(senderId),
    KEY(recipientId),
    KEY(folderId),
    CONSTRAINT `FK_tbl_internalmail_routing_emailId` FOREIGN KEY (`emailId`) REFERENCES `tbl_internalmail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_tbl_internalmail_routing_senderId` FOREIGN KEY (`senderId`) REFERENCES `tbl_users` (`userId`),
    CONSTRAINT `FK_tbl_internalmail_routing_recipientId` FOREIGN KEY (`recipientId`) REFERENCES `tbl_users` (`userId`),
    CONSTRAINT `FK_tbl_internalmail_routing_folderId` FOREIGN KEY (`folderId`) REFERENCES `tbl_internalmail_folders` (`id`)
    ) type=InnoDB COMMENT='Table containing email routing.'";
*/

//5ive definition
$tablename = 'tbl_email_routing';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing email routing.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'email_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'sender_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'recipient_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'folder_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'sent_email' => array(
        'type' => 'integer',
        'length' => 1,
        ),
    'read_email' => array(
        'type' => 'integer',
        'length' => 1,
        ),
    'date_read' => array(
        'type' => 'timestamp',
        ),
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
$name = 'email_routing_index';

$indexes = array(
                'fields' => array(
                    'email_id' => array(),
                    'sender_id' => array(),
                    'recipient_id' => array(),
                    'folder_id' => array(),
                ),
        );
?>