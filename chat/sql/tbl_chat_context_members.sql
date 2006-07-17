<?

//5ive definition
$tablename = 'tbl_chat_context_members';

//Options line for comments, encoding and character set
$options = array('comment' => 'Users in context', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'contextId' => array(
		'type' => 'text',
		'length' => 32
	),
	'userId' => array(
		'type' => 'text',
		'length' => 25
	),
	'updated' => array(
		'type' => 'timestamp'
	)
);

?>
