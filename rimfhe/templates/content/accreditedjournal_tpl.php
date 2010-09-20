<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

if(!isset($mode)){
    $mode = '';
}
$this->loadClass('htmlheading', 'htmlelements');
$objSysConfig  = $this->getObject('altconfig','config');
$this->appendArrayVar('headerParams', '
	<script type="text/javascript">
		var uri = "'.str_replace('&amp;','&',$this->uri(array('module' => 'rimfhe', 'action' => 'jsongetjournals'))).'"; 
		var baseuri = "'.$objSysConfig->getsiteRoot().'index.php";
 </script>');

//Ext stuff
$ext = '<link rel="stylesheet" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css', 'htmlelements').'" type="text/css" />';
$ext .=$this->getJavaScriptFile('ext-3.0-rc2/adapter/ext/ext-base.js', 'htmlelements');
$ext .=$this->getJavaScriptFile('ext-3.0-rc2/ext-all.js', 'htmlelements');
//$ext .=$this->getJavaScriptFile('forum-searches.js', 'rimfhe');
$ext .=$this->getJavaScriptFile('extjsgetjournal.js', 'rimfhe');
//$ext .=$this->getJavaScriptFile('forum-search.js', 'rimfhe');
$ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('combos.css', 'rimfhe').'"type="text/css" />';
$ext .=$this->getJavaScriptFile('ext-3.0-rc2/examples/shared/examples.js', 'htmlelements');
$this->appendArrayVar('headerParams', $ext);

$bodypar = $this->getJavaScriptFile('ext-3.0-rc2/examples/shared/examples.js', 'htmlelements');


/*
*The Tilte of The Page
*/

$pageHeading = new htmlheading();
$pageHeading->type = 2;

/*
* New Changes
*/
if($mode =='edit'){
    $editMode= 'update';
    $pageHeading->str = $this->objLanguage->languageText('mod_rimfhe_pgheadingeditjournalarticles', 'rimfhe');
}
else{
    $pageHeading->str = $this->objLanguage->languageText('mod_rimfhe_pgheadingjournalarticles', 'rimfhe');
    $editMode= '';
}

/*
* End New Changes
*/

echo '<br />'.$pageHeading->show();

/*
*The heading of the Form
*/

$formheader = new htmlheading();
$formheader->type = 3;
$formheader->str = $this->objLanguage->languageText('mod_rimfhe_forminstruction', 'rimfhe');

//All fields are Required
$header2 = $this->objLanguage->languageText('mod_rimfhe_requiredauthor', 'rimfhe');

// Show if no Error
if($mode!='fixerror'){
    echo $formheader->show();
    echo '<span style="color:red;font-size:12px;">'.$header2.'<br /><br /></span>';
}

//load the required form elements
$this->formElements->sendElements();

//Instantiate a new form object
$accreditedJournal = new form ('accreditedjournal', $this->uri(array('action'=>'accreditedjournal', 'editmode' => $editMode), 'rimfhe'));
//assign laguage objects to variables
$journalCategory= $this->objLanguage->languageText('mod_rimfhe_categorey', 'rimfhe');
$journalName = $this->objLanguage->languageText('mod_rimfhe_journalname', 'rimfhe');
$articleTitle= $this->objLanguage->languageText('mod_rimfhe_atitcletitle', 'rimfhe');
$publicationYear= $this->objLanguage->languageText('mod_rimfhe_year', 'rimfhe');
$volume= $this->objLanguage->languageText('mod_rimfhe_volume', 'rimfhe');
$firstPageNo= $this->objLanguage->languageText('mod_rimfhe_firstpage', 'rimfhe');
$lastPageNo= $this->objLanguage->languageText('mod_rimfhe_lastpage', 'rimfhe');
$author1= $this->objLanguage->languageText('mod_rimfhe_author1', 'rimfhe');
$author2= $this->objLanguage->languageText('mod_rimfhe_author2', 'rimfhe');
$others= $this->objLanguage->languageText('mod_rimfhe_others', 'rimfhe');
//$author4= $this->objLanguage->languageText('mod_rimfhe_author4', 'rimfhe');
$author1label= $this->objLanguage->languageText('mod_rimfhe_author1affiliation', 'rimfhe');
$author2label= $this->objLanguage->languageText('mod_rimfhe_author2affiliation', 'rimfhe');
$author3label= $this->objLanguage->languageText('mod_rimfhe_author3affiliation', 'rimfhe');
//$author4label= $this->objLanguage->languageText('mod_rimfhe_author4affiliation', 'rimfhe');
//$fractionWeight=$this->objLanguage->languageText('mod_rimfhe_fraction', 'rimfhe'));
//create table
$table =new htmltable('accreditedjournal');
$table->width ='80%';
$table->startRow();

//hidden id field to be used for update purposes
if($mode == 'edit'){
    $txtId = new textinput("journalid", $arrEdit['id'], 'hidden');
    $table->startRow();
    $table->addCell(NULL, 150, NULL, 'left');
    $table->addCell($txtId->show(), 150, NULL, 'left');
    $table->endRow();
}



//Input and label for Journal Category
$table->startRow();
$objCategory = new dropdown('category');
$categoryLabel = new label($journalCategory.'&nbsp;', 'category');
$categories=array("ISI", "IBSS", "Approved SA");
foreach ($categories as $category)
{
    $objCategory->addOption($category,$category);
    if($mode == 'fixerror'){
        $objCategory->setSelected($this->getParam('category'));
    }
    if($mode == 'edit'){
        $objCategory->setSelected($arrEdit['journalcategory']);
    }
}
$table->addCell($categoryLabel->show(), 150, NULL, 'left');
$table->addCell($objCategory->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Journal Name
$objjournalName1 = new textinput('journalname1');
$objjournalName1->size = 71;
$objjournalName1->extra = 'disabled = "true"';
$objjournalName = new textinput('journalname',null, 'hidden', '10');
$objjournalName2 = new textinput('journalname2');
$objjournalName2->size = 70;
$journalNameLabel = new label($journalName,'journalname1');
$table->addCell($journalNameLabel->show(), 150, 'top', 'left');
if($mode == 'fixerror'){
    $objjournalName->value =$this->getParam('journalname');
}
if($mode == 'edit'){
    $journalname = $this->objDBJournal->listSingle($arrEdit['journalname']);
    $objjournalName1->value =$journalname[0]['journal'];
    $objjournalName->value =$arrEdit['journalname'];
}

$table->addCell($objjournalName2->show()." ".$objjournalName1->show()." ".$objjournalName->show(), 150, NULL, 'left');
$table->addCell(NULL, 110, NULL, 'left');
$table->endRow();

//Input and label for Title of Article
$table->startRow();
$objarticleTitle = new textinput('articletitle');

$articleTiltleLabel = new label($articleTitle,'articletitle');
if($mode == 'fixerror'){
    $objarticleTitle->value =$this->getParam('articletitle');
}
if($mode == 'edit'){
    $objarticleTitle->value =$arrEdit['articletitle'];
}
$table->addCell($articleTiltleLabel->show(), 150, NULL, 'left');
$table->addCell($objarticleTitle->show(), 150, NULL, 'left');
$table->endRow();


//Input for Year of Publication
$table->startRow();
$objPublicationYr = new textinput ('publicationyear');
$pubYearsLabel = new label($publicationYear.'&nbsp;', 'publicationyear');
if($mode == 'fixerror'){
    $objPublicationYr->value =$this->getParam('publicationyear');
}
if($mode == 'edit'){
    $objPublicationYr->value =$arrEdit['publicationyear'];
}
$table->addCell($pubYearsLabel->show(), 150, NULL, 'left');
$table->addCell($objPublicationYr ->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Journal Volume
$table->startRow();
$objVolume = new textinput ('volume');
$volumeLabel = new label($volume.'&nbsp;', 'volume');
if($mode == 'fixerror'){
    $objVolume->value =$this->getParam('volume');
}
if($mode == 'edit'){
    $objVolume->value =$arrEdit['volume'];
}
$table->addCell($volumeLabel->show(), 150, NULL, 'left');
$table->addCell($objVolume ->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Article Fisrt Page Numbers
$table->startRow();
$objFirstPage = new textinput ('firstpage');
$firstPageLabel = new label($firstPageNo.'&nbsp;', 'firstpage');
if($mode == 'fixerror'){
    $objFirstPage->value =$this->getParam('firstpage');
}
if($mode == 'edit'){
    $objFirstPage->value =$arrEdit['firstpageno'];
}
$table->addCell($firstPageLabel->show(), 150, NULL, 'left');
$table->addCell($objFirstPage ->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Article Last Page Numbers
$table->startRow();
$objLastPage = new textinput ('lastpage');
$lastPageLabel = new label($lastPageNo.'&nbsp;', 'lastpage');
if($mode == 'fixerror'){
    $objLastPage->value =$this->getParam('lastpage');
}
if($mode == 'edit'){
    $objLastPage->value =$arrEdit['lastpageno'];
}
$table->addCell($lastPageLabel->show(), 150, NULL, 'left');
$table->addCell($objLastPage ->show(), 150, NULL, 'left');
$table->endRow();

/*
* the Authors names are stored with HTML tgas in the Database
* based on the Authors Affiliation.
* Carry out some PHP operations to reverse tags and
* distinguish affiliations
*/

// split Authors
if($mode == 'edit'){
    list($editAuthor1, $editAuthor2, $editOthers, $editAuthor4) = explode("<br />", $arrEdit['authorname']);

    // Match HTML tags (<b>) and ()
    //First Author
    if (preg_match("/<b>/",$editAuthor1)) {
        $editAuthor1 = strip_tags($editAuthor1);
        $authorAffiliate1 = 'UWC Staff Member';
    }
    elseif (preg_match("/<span>/",$editAuthor1)) {
        $editAuthor1 = strip_tags($editAuthor1);
        $authorAffiliate1 = 'UWC Student';
    }
    else {
        $authorAffiliate1 = 'External Author';
    }

    //Second Author
    if (preg_match("/<b>/",$editAuthor2)) {
        $editAuthor2 = strip_tags($editAuthor2);
        $authorAffiliate2 = 'UWC Staff Member';
    }
    elseif (preg_match("/<span>/",$editAuthor2)) {
        $editAuthor2 = strip_tags($editAuthor2);
        $authorAffiliate2 = 'UWC Student';
    }
    else {
        $authorAffiliate2 = 'External Author';
    }

   /*//Third Author
    if (preg_match("/<b>/",$editOthers)) {
        $editOthers = strip_tags($editOthers);
        $authorAffiliate3 = 'UWC Staff Member';
    }
    elseif (preg_match("/<span>/",$editOthers)) {
        $editOthers = strip_tags($editOthers);
        $authorAffiliate3 = 'UWC Student';
    }
    else {
        $authorAffiliate3 = 'External Author';
    }

    //Fourth Author
    if (preg_match("/<b>/",$editAuthor4)) {
        $editAuthor4= strip_tags($editAuthor4);
        $authorAffiliate4 = 'UWC Staff Member';
    }
    elseif (preg_match("/<span>/",$editAuthor4)) {
        $editAuthor4 = strip_tags($editAuthor4);
        $authorAffiliate4 = 'UWC Student';
    }
    else {
        $authorAffiliate4 = 'External Author';
    }*/
}

//Input and label for Author 1
$table->startRow();
$objAuthor1 = new textinput ('author1');
$Author1lLabel = new label($author1.'&nbsp;', 'author1');
if($mode == 'fixerror'){
    $objAuthor1->value =$this->getParam('author1');
}
if($mode == 'edit'){
    $objAuthor1->value =$editAuthor1;
}
$table->addCell($Author1lLabel->show(), 150, NULL, 'left');
$table->addCell($objAuthor1->show(), 150, NULL, 'left');
$table->endRow();

// Author 1 affiliate
$table->startRow();
$objAffiliate1 = new dropdown('author1affiliate');
$affiliate1Label = new label($author1label, 'author1affiliate');
$categories=array("UWC Staff Member", "UWC Student", "External Author");
foreach ($categories as $category)
{

    $objAffiliate1->addOption($category,$category);
    if($mode == 'fixerror'){
        $objAffiliate1->setSelected($this->getParam('author1affiliate'));
    }
    if($mode == 'edit'){
        $objAffiliate1->setSelected($authorAffiliate1);
    }
}
$table->addCell($affiliate1Label->show(), 150, NULL, 'left');
$table->addCell($objAffiliate1->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Author 2
$table->startRow();
$objAuthor2 = new textinput ('author2');
$Author2lLabel = new label($author2.'&nbsp;', 'author2');
if($mode == 'fixerror'){
    $objAuthor2->value =$this->getParam('author2');
}
if($mode == 'edit'){
    $objAuthor2->value =$editAuthor2;
}
$table->addCell($Author2lLabel->show(), 150, NULL, 'left');
$table->addCell($objAuthor2->show(), 150, NULL, 'left');
$table->endRow();

// Author 2 affiliate
$table->startRow();
$objAffiliate2 = new dropdown('author2affiliate');
$affiliate2Label = new label($author2label, 'author2affiliate');
$categories=array("UWC Staff Member", "UWC Student", "External Author");
foreach ($categories as $category)
{

    $objAffiliate2->addOption($category,$category);
    if($mode == 'fixerror'){
        $objAffiliate2->setSelected($this->getParam('author2affiliate'));
    }
    if($mode == 'edit'){
        $objAffiliate2->setSelected($authorAffiliate2);
    }
}

$table->addCell($affiliate2Label->show(), 150, NULL, 'left');
$table->addCell($objAffiliate2->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Author 3
$table->startRow();
$objOthers = new textinput ('others');
$OtherslLabel = new label($others.'&nbsp;', 'others');
if($mode == 'fixerror'){
    $objOthers->value =$this->getParam('others');
}
if($mode == 'edit'){
    $objOthers->value =$editOthers;
}
$table->addCell($OtherslLabel->show(), 150, NULL, 'left');
$table->addCell($objOthers->show(), 150, NULL, 'left');
$table->endRow();

/*// Author 3 affiliate
$table->startRow();
$objAffiliate3 = new dropdown('author3affiliate');
$affiliate3Label = new label($author3label, 'author3affiliate');
$categories=array("UWC Staff Member", "UWC Student", "External Author");
foreach ($categories as $category)
{
    $objAffiliate3->addOption($category,$category);
    if($mode == 'fixerror'){
        $objAffiliate3->setSelected($this->getParam('author3affiliate'));
    }
    if($mode == 'edit'){
        $objAffiliate3->setSelected($authorAffiliate3);
    }
}
$table->addCell($affiliate3Label->show(), 150, NULL, 'left');
$table->addCell($objAffiliate3->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Author 4
$table->startRow();
$objAuthor4 = new textinput ('author4');
$Author4lLabel = new label($author4.'&nbsp;', 'author4');
if($mode == 'fixerror'){
    $objAuthor4->value =$this->getParam('author4');
}
if($mode == 'edit'){
    $objAuthor4->value =$editAuthor4;
}
$table->addCell($Author4lLabel->show(), 150, NULL, 'left');
$table->addCell($objAuthor4->show(), 150, NULL, 'left');
$table->endRow();

// Author 4 affiliate
$table->startRow();
$objAffiliate4 = new dropdown('author4affiliate');
$affiliate4Label = new label($author4label, 'author4affiliate');
$categories=array("UWC Staff Member", "UWC Student", "External Author");
foreach ($categories as $category)
{

    $objAffiliate4->addOption($category,$category);
    if($mode == 'fixerror'){
        $objAffiliate4->setSelected($this->getParam('author4affiliate'));
    }
    if($mode == 'edit'){
        $objAffiliate4->setSelected($authorAffiliate4);
    }
}

$table->addCell($affiliate4Label->show(), 150, NULL, 'left');
$table->addCell($objAffiliate4->show(), 150, NULL, 'left');
$table->endRow();*/


//captcha
$table->startRow();
$objCaptcha = $this->getObject('captcha', 'utilities');
$captcha = new textinput('request_captcha');
$captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'request_captcha');

$table->addCell($captchaLabel ->show(), 150, NULL, 'left');
$content = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as shown below. If you are unable to view the code, click on "Redraw" for a new one.'));
$table->addCell($content, 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell(NULL, 150, NULL, 'left');
$table->addCell('<div id="captchaDiv">'.$objCaptcha->show().'</div>', 150, NULL, 'left');
$table->endRow();

$table->startRow();
$table->addCell(NULL, 150, NULL, 'left');
$table->addCell($captcha->show().'<a href="javascript:redraw();">'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>', 150, NULL, 'left');
$table->endRow();


//submit button
$table->startRow();
$table->addCell(NULL, 150, NULL, 'left');
$button = new button ('submitform', 'Submit Details');
$button->setToSubmit();
$table->addCell($button->show(), 150, NULL, 'left');
$table->endRow();

//display table
$accreditedJournal->addToForm($table->show());//$objjournalName->show()
//$accreditedJournal->addToForm($objCategory->show().$objjournalName2->show().$objjournalName->show());
//Code to display errors
$messages=array();
if ($mode == 'fixerror') {
    foreach ($problems as $problem)
    {
        $messages[] = $this->explainProblemsInfo($problem);
    }
}
if ($mode == 'fixerror' && count($messages) > 0) {

    echo '<ul><li><span class="error">'.$this->objLanguage->languageText('mod_userdetails_infonotsavedduetoerrors', 'userdetails').'</span>';

    echo '<ul>';
    foreach ($messages as $message)
    {
        if ($message != '') {
            echo '<li class="error">'.$message.'</li>';
        }
    }

    echo '</ul></li></ul>';
}
//display form
echo $accreditedJournal->show();
?>
<script type="text/javascript">
//<![CDATA[
function init () {
    $('input_redraw').onclick = function () {
        redraw();
    }
}
function redraw () {
    var url = 'index.php';
    var pars = 'module=security&action=generatenewcaptcha';
    var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showResponse} );
}
function showLoad () {
    $('load').style.display = 'block';
}
function showResponse (originalRequest) {
    var newData = originalRequest.responseText;
    $('captchaDiv').innerHTML = newData;
}
//]]>
</script>
