<?

//5ive definition
$tablename = 'tbl_contextcontent_involvement';

//Options line for comments, encoding and character set
$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => TRUE
        ),
    'titleid' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => TRUE
        ),
    'userid' => array(
        'type' => 'text',
        'length' => 25,
        'notnull' => TRUE
        )
    );
    
//create other indexes here...

$name = 'tbl_contextcontent_involvement_idx';

$indexes = array(
                'fields' => array(
                    'titleid' => array(),
                    'userid' => array()
                )
        );
        
?>