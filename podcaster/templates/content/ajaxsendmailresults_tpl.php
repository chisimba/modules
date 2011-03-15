<?php
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$buttonLabel = $this->objLanguage->languageText('word_next', 'system', 'System') . " " . $this->objLanguage->languageText('mod_podcaster_wordstep', 'podcaster', 'Step');
$buttonNote = $this->objLanguage->languageText('mod_podcaster_clickthreefromemail', 'podcaster', 'Click on the "Next step" button to send the emails and proceed to upload podcast');

$button = new button('submit', $buttonLabel);

$button->cssId = 'savebutton';

$nextActionButton = $button->show();

$descProdLink = new link($this->uri(array(
    'module' => 'podcaster',
    'action' => 'upload',
    'path' => $path,
    'createcheck' => $createcheck,
    'folderid' => $folderid
    )));
$descProdLink->link = $nextActionButton;
$linkDescribe = $descProdLink->show()." ".$buttonNote;

$this->setVar('pageSuppressXML', TRUE);

$this->appendArrayVar('bodyOnLoad', '

var par = window.parent.document;
window.history.forward(1);

par.forms[\'sendmailconfirm_'.$id.'\'].reset();
par.getElementById(\'sendmailconfirm_'.$id.'\').style.display=\'block\';
par.getElementById(\'sendmailresults\').style.display=\'block\';
par.getElementById(\'sendmailresults\').innerHTML = \'<span class="confirm">An email was successfully sent to these addresses: '.$useremail.'</span><br /><br /> '.$linkDescribe.' \';
par.getElementById(\'div_email_'.$id.'\').style.display=\'none\';

parent.loadAjaxForm(\''.$fileid.'\');
parent.processConversions();
window.location = "'.str_replace('&amp;', '&', $this->uri(array('action'=>'tempiframe', 'id'=>$id))).'";
');

?>