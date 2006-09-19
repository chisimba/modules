<?
$this->objDbStudentInfo =& $this->getObject('dbstudentinfo','studentenquiry');

$details = "<center><h2>".$objLanguage->languagetext('mod_financialaid_addstudent','financialaid')."</h2></center>";


$link = new link();
$link->href=$this->uri(array('action'=>'addapplication'));
$link->link = $objLanguage->languagetext('mod_financialaid_addanotherstudent','financialaid');
$content = $details."<center><div class='error'>".$objLanguage->languagetext('mod_financialaid_applicationexists','financialaid')."</div><br />";
$content .= $link->show()."</center>";

echo $content;


?>
