<?PHP

/*
 * This file was generated by the Web Parts Module Builder
 */

// Security Check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
		
$tablename = 'tbl_libraryexams_faculty';

$options = array('collate' => 'latin1_swedish_ci','comment' => '');

$fields = array(


    'id' => array(
        'type' => 'text',
        'length' => 32
        ),
    'f_code' => array(
        'type' => 'text',
        'length' => '6',
        'notnull' => 'FALSE',
        'default' => '',
    ),
    'f_name' => array(
        'type' => 'text',
        'length' => '30',
        'notnull' => 'FALSE',
        'default' => '',
    )
);


//Generating Indexes

$name = 'idx_70c9ae1444ecb760c33c244f4611934e';
//Generating Indexes

$indexes = array(
             'fields' => array(
                'f_code' => array()
             )
);