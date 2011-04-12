<?php

$tablename = 'tbl_payments';

$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'payment_id' => array(
        'type' => 'integer',
        'length' => 32,
        'notnull' => FALSE
        ),
    'amount_paid' => array(
        'type' => 'text',
        'length' => 255,
        'notnull' => TRUE
        ),
    'receipt_no' => array(
        'type' => 'text',
        'length' => 45,
        'notnull' => TRUE
        ),
    'bank_name' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => TRUE
        ),

     'bank_branch' => array(
          'type' => 'text',
          'length' => 45,
          'notnull' => TRUE
    ),

      'date_paid' => array(
            'type' => 'timestamp',
            'notnull' => TRUE
    ),

        'installments' => array(
            'type' => 'text',
            'length' => 32,
            'notnull' => TRUE
     ),




;

$name = 'tbl_context_has_tbl_context_parentnodes_FKIndex1';

$indexes = array(
                'fields' => array(
                    'tbl_context_id' => array(),
                    'tbl_context_contextCode' => array()
                )
        );
?>