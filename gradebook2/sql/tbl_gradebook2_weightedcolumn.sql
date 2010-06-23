<?php
/**
 *
 * Table for storing weighted columns
 *
 */

/* Set the table name */
 
$tablename = 'tbl_gradebook2_weightedcolumn';

/* Options line for comments, encoding and character set */

   $options = array(
    'comment' => 'Table for tbl_gradebook2_weightedcolumn',
    'collate' => 'utf8_general_ci',
    'character_set' => 'utf8');
/* Create the table fields */
   $fields = array(
    'id' => array(
       'type' => 'text',
       'length' => 32,
       'notnull' => true
       ),
   'userid' => array(
       'type' => 'text',
       'length' => 32,
       'notnull' => true
       ),
   'column_name' => array(
       'type' => 'text',
       'length' => 64,
       'notnull' => true
       ),
   'display_name' => array(
       'type' => 'text',
       'length' => 64,
       'notnull' => true
       ),
   'secondary_name' => array(
       'type' => 'text',
       'length' => 64,
       'notnull' => true
       ),
   'grading_period' => array(
       'type' => 'date',
       'notnull' => true
       ),
   'creation_date' => array(
       'type' => 'datetime',
       'notnull' => true
       ),
   'include_weighted_grade' => array(
       'type' => 'boolean',
       'notnull' => true
       ),
   'running_total' => array(
       'type' => 'decimal'
       ),
   'show_grade_center_calc' => array(
       'type' => 'boolean',
       'notnull' => true
       ),
   'show_my_grades' => array(
       'type' => 'boolean',
       'notnull' => true
       ),
   'show_statistics' => array(
       'type' => 'boolean',
       'notnull' => true
       )
);
?>
