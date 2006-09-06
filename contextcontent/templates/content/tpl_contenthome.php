<?php

$this->loadClass('link', 'htmlelements');

echo '<h1>Content Pages for '.$this->objContext->getTitle().'</h1>';


    echo '<div class="noRecordsMessage">No Content Pages Found for this Course. Please add a page</div>';


$addLink = new link($this->uri(array('action'=>'addpage', 'context'=>$this->contextCode)));
$addLink->link = 'Add a New Page';

echo '<p>'.$addLink->show().'</p>';
?>