<?php
/*
$sqldata[] = "CREATE TABLE tbl_internalmail_rules(
    id VARCHAR(32) NOT NULL,
    userId VARCHAR(32) NOT NULL,
    mailAction TINYINT(1) NULL,
    mailField TINYINT(1) NULL,
    criteria VARCHAR(50) NULL,
    ruleAction TINYINT(1) NULL,
    destFolderId VARCHAR(32) NULL,
    updated TIMESTAMP(14) NOT NULL,
    PRIMARY KEY(id),
    KEY(userId),
    CONSTRAINT `FK_tbl_internalmail_rules_userId` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`)
    ) type=InnoDB COMMENT='Table containing email folder rules.'";
*/

//5ive definition
$tablename = 'tbl_email_rules';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing email folder rules.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'user_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'mail_action' => array(
        'type' => 'integer',
        'length' => 1,
        ),
    'mail_field' => array(
        'type' => 'integer',
        'length' => 1,
        ),
    'criteria' => array(
        'type' => 'text',
        'length' => 50,
        ),
    'rule_action' => array(
        'type' => 'integer',
        'length' => 1,
        ),
    'dest_folder_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
$name = 'email_rules_index';

$indexes = array(
                'fields' => array(
                    'user_id' => array(),
                ),
        );
?>