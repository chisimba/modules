<?php
    $tablename = "tbl_documentusers";
    $options = array('comment' => 'Table for users that have been sent a document', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array(
                'id' => array('type' => 'text','length' => 32,'notnull' => TRUE),
                'lname' => array('type' => 'text', 'length' => 32,'notnull' => TRUE),
                'fname' => array('type' => 'text', 'length' => 32,'notnull' => TRUE),
                'email' => array('type' => 'text', 'length' => 32,'notnull' => TRUE),
                'phone' => array('type' => 'text', 'length' => 32,'notnull' => TRUE),
                'courseid' => array('type' => 'text', 'length' => 32,'notnull' => TRUE)
              );
?>