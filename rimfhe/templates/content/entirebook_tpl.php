<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

if(!isset($mode)){
$mode = '';
}
$this->loadClass('htmlheading', 'htmlelements');
/*
 *The Tilte of The Page
 */
$pageHeading = new htmlheading();
$pageHeading->type = 2;

if($mode =='edit'){
$editMode= 'update';
$pageHeading->str = $this->objLanguage->languageText('mod_rimfhe_pgheadingeditentirebook', 'rimfhe');
}
else{
$pageHeading->str = $this->objLanguage->languageText(' mod_rimfhe_pgheadingentirebook', 'rimfhe');
$editMode= '';
}

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
 echo '<br />'.$formheader->show();
 echo '<br /><span style="color:red;font-size:12px;">'.$header2.'<br /></span>';
}

//load the required form elements
$this->formElements->sendElements();

//Instantiate a new form object
$entireBook = new form ('entirebook', $this->uri(array('action'=>'entirebook', 'editmode' => $editMode), 'rimfhe'));
//add instruction to form
$entireBook->addToForm('<br />');

/* ---------------------- Form Elements--------*/
//assign laguage objects to variables		
$bookName = $this->objLanguage->languageText('mod_rimfhe_booktitle', 'rimfhe');
$isbnNumber= $this->objLanguage->languageText('mod_rimfhe_isbn', 'rimfhe');
$publisher= $this->objLanguage->languageText('mod_rimfhe_publisher', 'rimfhe');
$peerReview= $this->objLanguage->languageText('mod_rimfhe_peer', 'rimfhe');
$author1= $this->objLanguage->languageText('mod_rimfhe_author1', 'rimfhe');
$author2= $this->objLanguage->languageText('mod_rimfhe_author2', 'rimfhe');
$author3= $this->objLanguage->languageText('mod_rimfhe_author3', 'rimfhe');
$author4= $this->objLanguage->languageText('mod_rimfhe_author4', 'rimfhe');

$firstChaprtPgNo= $this->objLanguage->languageText('mod_rimfhe_firstchapterpageno', 'rimfhe');

$author1label= $this->objLanguage->languageText('mod_rimfhe_author1affiliation', 'rimfhe');
$author2label= $this->objLanguage->languageText('mod_rimfhe_author2affiliation', 'rimfhe');
$author3label= $this->objLanguage->languageText('mod_rimfhe_author3affiliation', 'rimfhe');
$author4label= $this->objLanguage->languageText('mod_rimfhe_author4affiliation', 'rimfhe');

$lastChaprtPgNo= $this->objLanguage->languageText('mod_rimfhe_lastchapterpageno', 'rimfhe');

//create table
$table =new htmltable('entirebook');
$table->width ='80%';
$table->startRow();

//hidden id field to be used for update purposes
if($mode == 'edit'){
$txtId = new textinput("bookid", $arrEdit['id'], 'hidden');
$table->startRow();
$table->addCell(NULL, 150, NULL, 'left');
$table->addCell($txtId->show(), 150, NULL, 'left');
$table->endRow();
}

//Input and label for Book Name
$objbookName = new textinput('bookname');
$bookNameLabel = new label($bookName,'bookname');
	if($mode == 'fixerror'){
		$objbookName->value =$this->getParam('bookname');
	}
	if($mode == 'edit'){
		$objbookName->value =$arrEdit['booktitle'];
	}
$table->addCell($bookNameLabel->show(), 150, NULL, 'left');		
$table->addCell($objbookName->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for isbn Number
$table->startRow();
$objIsbnNumber = new textinput('isbnnumber');
$isbnNumberLabel = new label($isbnNumber.'&nbsp;', 'isbnnumber');
	if($mode == 'fixerror'){
		$objIsbnNumber->value =$this->getParam('isbnnumber');
	}
	if($mode == 'edit'){
		$objIsbnNumber->value =$arrEdit['isbn'];
	}
$table->addCell($isbnNumberLabel->show(), 150, NULL, 'left');
$table->addCell($objIsbnNumber->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Publishing House
$table->startRow();
$objPublishingHouse = new textinput('publishinghouse');
$publishingHouseLabel = new label($publisher,'publishinghouse');
	if($mode == 'fixerror'){
		$objPublishingHouse->value =$this->getParam('publishinghouse');
	}
	if($mode == 'edit'){
		$objPublishingHouse->value =$arrEdit['publishinghouse'];
	}	
$table->addCell($publishingHouseLabel->show(), 150, NULL, 'left');
$table->addCell($objPublishingHouse->show(), 150, NULL, 'left');
$table->endRow();

/*
 * the Authors names are stored with HTML tgas in the Database
 * based on the Authors Affiliation.
 * Carry out some PHP operations to reverse tags and 
 * distinguish affiliations
 */

// split Authors
if($mode == 'edit'){
	list($editAuthor1, $editAuthor2, $editAuthor3,$editAuthor4) = explode("<br />", $arrEdit['authorname']);

	// Match HTML tags (<b>)
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

	//Third Author
	if (preg_match("/<b>/",$editAuthor3)) {
		$editAuthor3 = strip_tags($editAuthor3);
		$authorAffiliate3 = 'UWC Staff Member';
	}
	elseif (preg_match("/<span>/",$editAuthor3)) {
		$editAuthor3 = strip_tags($editAuthor3);
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
	}
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
$objAuthor3 = new textinput ('author3');
$Author3lLabel = new label($author3.'&nbsp;', 'author3');	
	if($mode == 'fixerror'){
		$objAuthor3->value =$this->getParam('author3');
	}
	if($mode == 'edit'){
		$objAuthor3->value =$editAuthor3;
	}	
$table->addCell($Author3lLabel->show(), 150, NULL, 'left');
$table->addCell($objAuthor3->show(), 150, NULL, 'left');
$table->endRow();

// Author 3 affiliate
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
$table->endRow();

//Input and label for First Chapter Page Number
$table->startRow();
$objFirstPage= new textinput ('firstpage');
$firstPageLabel = new label($firstChaprtPgNo.'&nbsp;', 'firstpage');
	if($mode == 'fixerror'){
		$objFirstPage->value =$this->getParam('firstpage');
	}
	if($mode == 'edit'){
		$objFirstPage->value =$arrEdit['firstchapterpageno'];
	}			
$table->addCell($firstPageLabel->show(), 150, NULL, 'left');
$table->addCell($objFirstPage ->show(), 150, NULL, 'left');
$table->endRow();		

//Input and label for Last Chapter Page Number
$table->startRow();
$objLastPage = new textinput ('lastpage');
$lastPageLabel = new label($lastChaprtPgNo.'&nbsp;', 'lastpage');
	if($mode == 'fixerror'){
		$objLastPage->value =$this->getParam('lastpage');
	}
	if($mode == 'edit'){
		$objLastPage->value =$arrEdit['lastchapterpageno'];
	}			
$table->addCell($lastPageLabel->show(), 150, NULL, 'left');
$table->addCell($objLastPage ->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Peer Review
$table->startRow();
$objPeerReview = new dropdown('peerreview');
$peerRevLabel = new label($peerReview.'&nbsp;', 'peerreview');
$answers=array("YES", "NO");
foreach ($answers as $answer)
{   
    $objPeerReview->addOption($answer,$answer);
	if($mode == 'fixerror'){
		$objPeerReview->setSelected($this->getParam('peerreview'));
	}
	if($mode == 'edit'){
		$objPeerReview->setSelected($arrEdit['peerreviewed']);
	}
}
$table->addCell($peerRevLabel->show(), 150, NULL, 'left');
$table->addCell($objPeerReview->show(), 150, NULL, 'left');
$table->endRow();

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
$table->addCell($captcha->show().'  <a href="javascript:redraw();">'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>', 150, NULL, 'left');
$table->endRow();

//submit button
$table->startRow();
$table->addCell(NULL, 150, NULL, 'left');	
$button = new button ('submitform', 'Submit Details');
$button->setToSubmit();
$table->addCell($button->show(), 150, NULL, 'left');
$table->endRow();
		
//display table
$entireBook->addToForm($table->show());

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
echo $entireBook->show();
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
