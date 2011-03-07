<?php
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$buttonNote = $this->objLanguage->languageText('mod_podcaster_clicknexttwo', 'podcaster', 'Click on the "Next step" button to describe the podcast');

$buttonLabel = $this->objLanguage->languageText('word_next', 'system', 'System')." ".$this->objLanguage->languageText('mod_podcaster_wordstep', 'podcaster', 'Step');

$button = new button('create', $buttonLabel);

$button->cssId = 'savebutton';

$nextActionButton = $button->show();

$descProdLink = new link($this->uri(array(
    'module' => 'podcaster',
    'action' => 'describepodcast',
    'fileid' => $fileid,
    )));
$descProdLink->link = $nextActionButton;
$linkDescribe = $descProdLink->show()." ".$buttonNote;

$this->setVar('pageSuppressXML', TRUE);

$this->appendArrayVar('bodyOnLoad', '

var par = window.parent.document;
window.history.forward(1);

par.forms[\'uploadfile_'.$id.'\'].reset();
par.getElementById(\'form_upload_'.$id.'\').style.display=\'block\';
par.getElementById(\'uploadresults\').style.display=\'block\';
par.getElementById(\'uploadresults\').innerHTML = \'<span class="confirm">'.addslashes(htmlentities($filename)).' has been uploaded</span><br /><br /> '.$linkDescribe.' \';
par.getElementById(\'div_upload_'.$id.'\').style.display=\'none\';

parent.loadAjaxForm(\''.$fileid.'\');
parent.processConversions();

window.location = "'.str_replace('&amp;', '&', $this->uri(array('action'=>'tempiframe', 'id'=>$id))).'";

');


?>