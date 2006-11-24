<?php
/*
$sqldata[] = "CREATE TABLE tbl_internalmail_addressbook_entries(
    id VARCHAR(32) NOT NULL,
    addressbookId VARCHAR(32) NOT NULL,
    recipientId VARCHAR(32) NOT NULL,
    updated TIMESTAMP(14) NOT NULL,
    PRIMARY KEY(id),
    KEY(addressbookId),
    KEY(recipientId),
    CONSTRAINT `FK_tbl_internalmail_addressbook_entries_addressbookId` FOREIGN KEY (`addressbookId`) REFERENCES `tbl_internalmail_addressbook` (`id`),
    CONSTRAINT `FK_tbl_internalmail_addressbook_entries_recipientId` FOREIGN KEY (`recipientId`) REFERENCES `tbl_users` (`userId`)
    ) type=InnoDB COMMENT='Table containing addressbook entries'";
*/

//5ive definition
$tablename = 'tbl_email_addressbook_entries';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing addressbook entries', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'addressbook_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'recipient_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
$name = 'email_addressbook_entries_index';

$indexes = array(
                'fields' => array(
                    'addressbook_id' => array(),
                    'recipient_id' => array(),
                ),
        );
?>