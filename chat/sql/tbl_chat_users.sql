<?

//5ive definition
$tablename = 'tbl_chat_users';

//Options line for comments, encoding and character set
$options = array('comment' => 'Users in chat rooms', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'username' => array(
		'type' => 'text',
		'length' => 25
	),
	'contextId' => array(
		'type' => 'text',
		'length' => 32
	),
	'start' => array(
		'type' => 'integer'
	),
	'lastActive' => array(
		'type' => 'integer'
	),
	'updated' => array(
		'type' => 'timestamp'
	)
);

?>
