<?php
/*
$sqldata[] = "CREATE TABLE tbl_internalmail_addressbook(
    id VARCHAR(32) NOT NULL,
    userId VARCHAR(32) NOT NULL,
    bookName VARCHAR(50) NOT NULL,
    updated TIMESTAMP(14) NOT NULL,
    PRIMARY KEY(id),
    KEY(userId),
    CONSTRAINT `FK_tbl_internalmail_addressbook_userId` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`)
    ) type=InnoDB COMMENT='Table containing addressbooks for a user'";
*/

//5ive definition
$tablename = 'tbl_email_addressbooks';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing addressbooks for a user', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'user_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'book_name' => array(
        'type' => 'text',
        'length' => 50,
        ),
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
$name = 'email_addressbooks_index';

$indexes = array(
                'fields' => array(
                    'user_id' => array(),
                ),
        );
?>