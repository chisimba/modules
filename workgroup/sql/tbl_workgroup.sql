<?

//5ive definition
$tablename = 'tbl_workgroup';

//Options line for comments, encoding and character set
$options = array('comment' => 'Workgroups', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'contextcode' => array(
		'type' => 'text',
		'length' => 255
		),
	'description' => array(
		'type' => 'text',
		'length' => 100
		),
	'creator_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'updated' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00 00:00:00'
		)
);

?>
