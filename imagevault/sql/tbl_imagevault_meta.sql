<?php
// Table Name
$tablename = 'tbl_imagevault_meta';

//Options line for comments, encoding and character set
$options = array('comment' => 'Imagevault metadata', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'imageid' => array(
		'type' => 'text',
		'length' => 32
		),	
    'userid' => array(
		'type' => 'text',
		'length' => 50,
		),
    'filesize' => array(
        'type' => 'text',
        'length' => 100,
        ),
    'filename' => array(
        'type' => 'text',
        'length' => 100,
        ),
    'cam_man' => array(
        'type' => 'text', 
        'length' => 255,
        ),
	'cam_model' => array(
		'type' => 'text',
		'length' => 255,
		),
	'exp_time' => array(
		'type' => 'text',
		'length' => 50,
		),
	'fnumber' => array(
		'type' => 'text',
		'length' => 50,
		),
	'usercomment' => array(
		'type' => 'text',
		'length' => 255,
		),
	'iso' => array(
		'type' => 'text',
		'length' => 50,
		),
    'picdatetime' => array(
        'type' => 'text',
		'length' => 50,
       ),
    'focallength' => array(
		'type' => 'text',
		'length' => 50,
		),
	'orientation' => array(
		'type' => 'text',
		'length' => 50,
		),
	'xres' => array(
		'type' => 'text',
		'length' => 50,
		),
    'yres' => array(
		'type' => 'text',
		'length' => 50,
		),
	'software' => array(
		'type' => 'text',
		'length' => 50,
		),
	'modificationdatetime' => array(
		'type' => 'text',
		'length' => 50,
		),
	'ycpos' => array(
		'type' => 'text',
		'length' => 50,
		),
	'exifver' => array(
		'type' => 'text',
		'length' => 50,
		),
	'digidatetime' => array(
		'type' => 'text',
		'length' => 50,
		),
	'shutterspeed' => array(
		'type' => 'text',
		'length' => 50,
		),
	'aperture' => array(
		'type' => 'text',
		'length' => 50,
		),
	'ev' => array(
		'type' => 'text',
		'length' => 50,
		),
	'maxlandaperture' => array(
		'type' => 'text',
		'length' => 50,
		),
	'meteringmode' => array(
		'type' => 'text',
		'length' => 50,
		),
	'flash' => array(
		'type' => 'text',
		'length' => 100,
		),
	'colourspace' => array(
		'type' => 'text',
		'length' => 50,
		),
	'sensingmethod' => array(
		'type' => 'text',
		'length' => 50,
		),
	);
//create other indexes here...

$name = 'userid';

$indexes = array(
                'fields' => array(
                	'userid' => array(),
                )
        );
?>
