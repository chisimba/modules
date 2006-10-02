<?

/***
* FrontPage for KLOR module
* @author James Scoble
*/
// Get html elements
$objHeading = &$this->getObject('htmlheading','htmlelements');


$objHeading->type=1;
$objHeading->align='center';
$objHeading->str=$this->objLanguage->languageText('mod_klorserver_desc','KNG Learning Object Repository');
print $objHeading->show();

$clientLink=$this->uri(array('action'=>'client'));
$localLink=$this->uri(array('action'=>'local'));

print "<a href='$clientLink' class='pseudobutton' >Remote Repository</a><br>\n";
print "<a href='$localLink' class='pseudobutton' >Local Content</a><br>\n";





?>