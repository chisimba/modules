<?php
$tablename = 'tb l_forum_email';

//Options line for comments, encoding and character set
$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
        'id'=>array(
                'type'=>'text',
                'length'=>'32'
),
        'post_parent'=>array(
                'type'=>'text',
                'length'=>'32'
),
        'post_title'=>array(
                'type'=>'text',
                'length'=>'100'
),
        'post_text'=>array(
                'type'=>'text',
                'length'=>'800'
),
        'forum_name'=>array(
                'type'=>'text',
                'length'=>'32'
),
        'user_id'=>array(
                'type'=>'text',
                'length'=>'32'
),
        'reply_uri'=>array(
                'type'=>'text',
                'length'=>'32'
),
        'sent'=>array(
                'type'=>'boolean'
)
);
?>
