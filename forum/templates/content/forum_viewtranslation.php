<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$header = new htmlheading();
$header->type=1;

$languageCodes = & $this->newObject('languagecode','language');

$link = new link($this->uri(array('action'=>'forum', 'id'=>$post['forum_id'])));
$link->link = $forum['forum_name'];
$headerString = $link->show().' &gt; '.stripslashes($post['post_title']);
$headerString .= ' - <em>'.$languageCodes->getLanguage($post['language']).'</em>';

$header->str=$headerString;

echo $header->show();

if ($this->getParam('message') == 'translationsaved') {
    $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
    $timeoutMessage->setMessage($this->objLanguage->languageText('mod_forum_translationsaved'));
    $timeoutMessage->setTimeout(10000);
    echo ('<p>'.$timeoutMessage->show().'</p>');
}

echo $postDisplay;

?>