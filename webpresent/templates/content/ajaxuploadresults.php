<?php
$this->setVar('pageSuppressXML', TRUE);

$this->appendArrayVar('bodyOnLoad', '

var par = window.parent.document;
window.history.forward(1);

par.forms[\'uploadfile_'.$id.'\'].reset();
par.getElementById(\'form_upload_'.$id.'\').style.display=\'block\';
par.getElementById(\'uploadresults\').innerHTML += \''.addslashes(htmlentities($filename)).' has been uploaded<br />\';
par.getElementById(\'div_upload_'.$id.'\').style.display=\'none\';

parent.processConversions();

window.location = "'.str_replace('&amp;', '&', $this->uri(array('action'=>'tempiframe', 'id'=>$id))).'";

');


?>