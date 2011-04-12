 <?php
 /**
    *
    * Table for registering students
    *
    */
    /*
     Here we are setting the table name. The name of the table should resemble as the name of this file       (without .sql)
    */
    $tablename = 'tbl_students';
    /*
    Options line for comments, encoding and character set
    */
    $options = array(
       'comment' => 'Table for tbl_students - registers student particulars',
       'collate' => 'utf8_general_ci',
       'character_set' => 'utf8');
    /*
    Create the table fields
    */
    $fields = array(
       'id' => array(
          'type' => 'text',
          'length' => 32,
          'notnull' => TRUE
          
       ),
   'fname' => array(
       'type' => 'text',
       'length' => 25,
       'notnull' => TRUE
       ),
   'lname' => array(
       'type' => 'text',
       'length' => 25,
       'notnull' => TRUE
       ),
    'current_form' => array(
       'type' => 'integer',
       'length' => 5,
       'notnull' => TRUE
       ),
    'place_of_birth' => array(
       'type' => 'text',
       'length' => 50,
       'notnull' => TRUE
       ),
    'age' => array(
       'type' => 'integer',
       'length' => 3,
       'notnull' => TRUE
       ),
    'sex' => array(
       'type' => 'text',
       'length' => 10,
       'notnull' => TRUE
       ),
   'mailing_address' => array(
       'type' => 'text',
       'length' => 80,
       'notnull' => TRUE
       ),
   'birth_date' => array(
       'type' => 'date',
       'notnull' => TRUE
       )
);
?>
