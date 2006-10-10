<?

//5ive definition
$tablename = 'tbl_chat_contexts';

//Options line for comments, encoding and character set
$options = array('comment' => 'Chat contexts (rooms)', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'context' => array(
		'type' => 'text',
		'length' => 255
	),
	'username' => array(
		'type' => 'text',
		'length' => 25
	),
	'type' => array(
		'type' => 'text',
		'length' => 32
	),
	'updated' => array(
		'type' => 'timestamp'
	)
);

?>
