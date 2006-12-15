<?
/**
* @package mcqtests
*/

/**
* Template for adding a new test or editing an existing one.
* @param array $data The details of the test to be edited.
* @param string $mode Add or edit
*/

$this->setLayoutTemplate('mcqtests_layout_tpl.php');

// set up html elements
$objTable=&$this->newObject('htmltable','htmlelements');
$objLink=&$this->newObject('link','htmlelements');
$objText=&$this->newObject('textinput','htmlelements');
$objButton=&$this->newObject('button','htmlelements');
$objForm=&$this->newObject('form','htmlelements');

// set up language items
$addHeading = $this->objLanguage->languageText('mod_mcqtests_addlab', 'mcqtests');
$submitLabel = $this->objLanguage->languageText('word_submit');
$requiredLabel = $this->objLanguage->languageText('mod_mcqtests_labrequired', 'mcqtests');
$helpLabel = $this->objLanguage->languageText('mod_mcqtests_labfile', 'mcqtests');
$errorLabel = $this->objLanguage->languageText('mod_mcqtests_laberror', 'mcqtests');
$backLabel=$this->objLanguage->languageText('word_back');

// set up heading
$this->setVarByRef('heading',$addHeading);

$objText=new textinput('comLab','','file',50);
$labText=$objText->show();

$objTable=new htmltable();
$objTable->cellspacing=2;
$objTable->cellpadding=2;

$objTable->startRow();
$objTable->addCell($helpLabel,'','','','','');
$objTable->endRow();
if($error){
    $objTable->startRow();
    $objTable->addCell("<b>".$errorLabel."</b>",'','','','error','');
    $objTable->endRow();
}
$objTable->startRow();
$objTable->addCell($labText,'','','','','');
$objTable->endRow();
$labTable=$objTable->show();

$objButton=new button('submitbutton',$submitLabel);
$objButton->setToSubmit();
$submitButton=$objButton->show();

$objForm=new form('addlab',$this->uri(array('action'=>'applyaddlab','id'=>$id,'mode'=>$mode)));
$objForm->addToForm($labTable."<br />".$submitButton);
$objForm->addRule('comLab',$requiredLabel,'required');
$objForm->extra="enctype='multipart/form-data'";
$labForm=$objForm->show();

echo $labForm;

// set up rerurn link
$objLink=new link("javascript:history.back()");
$objLink->link=$backLabel;
$backLink=$objLink->show();
echo "<br />".$backLink;

?>