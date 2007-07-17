<?

$tablename = 'tbl_contextdesigner_links';

$options = array('comment' => 'Context Designer Links holder context links that biulds that context content', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'contextcode' => array(
		'type' => 'text',
		'length' => 255,
	        'notnull' => TRUE
		),
	'title' => array(
		'type' => 'text',
		'length' => 255,
        	'notnull' => TRUE
		),
    	'menutext' => array(
		'type' => 'text',
		'length' => 255
		),
	'params' => array(
		'type' => 'text',
		'length' => 255
		),
   	'moduleid' => array(
		'type' => 'text',
		'length' => 255,
        	'notnull' => TRUE
		),
    	'linkid' => array(
		'type' => 'text',
		'length' => 255,
        	'notnull' => TRUE
		),
    'datecreated' => array(
		'type' => 'date'
		),    
    'status' => array(
        	'type' => 'text',
		'length' => 32,
        	'notnull' => TRUE
        	),
    'access' => array(
        	'type' => 'text',
	'length' => 32,
        	'notnull' => TRUE
        	),
    'linkorder' => array(
        	'type' => 'integer',		
        	)
    
    );

$name = 'id';

$indexes = array(
                'fields' => array(
                	'id' => array()
                )
        );
?>