<?php

//Template to view the different admin functions

$link = & $this->newObject('link', 'htmlelements');


//content link
$link->link = 'Content';
$link->href = $this->uri(array('action' => 'content'));
$str.= '<p>'.$link->show();
//sections link

$link->link = 'Sections';
$link->href = $this->uri(array('action' => 'sections'));
$str .= '<p>'.$link->show();

//categories link
$link->link = 'Categories';
$link->href = $this->uri(array('action' => 'categories'));
$str .= '<p>'.$link->show();

echo $str;

?>