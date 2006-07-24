<?

//5ive definition
$tablename = 'tbl_workgroup_users';

//Options line for comments, encoding and character set
$options = array('comment' => 'Users in workgroups', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'workgroupid' => array(
		'type' => 'text',
		'length' => 32
		),
	'userid' => array(
		'type' => 'text',
		'length' => 25
		),
	'updated' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00 00:00:00'
		)
);

?>
