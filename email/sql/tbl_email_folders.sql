<?php
/*
$sqldata[] = "CREATE TABLE tbl_internalmail_folders(
    id VARCHAR(32) NOT NULL,
    userId VARCHAR(32) NOT NULL,
    folderName VARCHAR(50) NOT NULL,
    updated TIMESTAMP(14) NOT NULL,
    PRIMARY KEY(id)
    ) type=InnoDB COMMENT='Table containing the users email folders.'";

$sqldata[] = "INSERT INTO tbl_internalmail_folders(id, userId, folderName)
    values('PKVALUE', 'system', 'Inbox')";
$sqldata[] = "INSERT INTO tbl_internalmail_folders(id, userId, folderName)
    values('PKVALUE', 'system', 'Drafts')";
$sqldata[] = "INSERT INTO tbl_internalmail_folders(id, userId, folderName)
    values('PKVALUE', 'system', 'Sent items')";
$sqldata[] = "INSERT INTO tbl_internalmail_folders(id, userId, folderName)
    values('PKVALUE', 'system', 'Trash')";
*/

//5ive definition
$tablename = 'tbl_email_folders';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing the users email folders.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'user_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'folder_name' => array(
        'type' => 'text',
        'length' => 50,
        ),
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
?>