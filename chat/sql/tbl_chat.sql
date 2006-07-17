<?

//5ive definition
$tablename = 'tbl_chat';

//Options line for comments, encoding and character set
$options = array('comment' => 'Postings of chat', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'username' => array(
		'type' => 'text',
		'length' => 25
	),
	'content' => array(
		'type' => 'text',
		'length' => 32767
	),	
	'contextId' => array(
		'type' => 'text',
		'length' => 32
	),	
	'recipient' => array(
		'type' => 'text',
		'length' => 25
	),	
	'stamp' => array(
		'type' => 'integer'
	),
	'updated' => array(
		'type' => 'timestamp'
	)
);
	
?>
