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
<?php
$this->loadClass('htmlheading', 'htmlelements');
/*
 *The Tilte of The Page
 */
 $pageHeading = new htmlheading();
 $pageHeading->type = 2;
 $pageHeading->str = $this->objLanguage->languageText('mod_rimfhe_pageheading', 'rimfhe', 'Chapter In a Book');
 echo '<br />'.$pageHeading->show();

/*
 *The heading of the Form
 */
 $formheader = new htmlheading();
 $formheader->type = 3;
 $formheader->str = $this->objLanguage->languageText('mod_staffregistration_forminstruction', 'rimfhe');

//All fields are Required
$header2 = $this->objLanguage->languageText('mod_staffregistration_required', 'rimfhe', '*All fields are Required except Authors. Atleast one Author must be entered');
// Show if no Error
if($mode!='fixerror'){
 echo '<br />'.$formheader->show();
 echo '<br /><span style="color:red;font-size:12px;">'.$header2.'<br /></span>';
}
//load the required form elements
$this->formElements->sendElements();

//Instantiate a new form object
$chapterinbook = new form ('entirebook', $this->uri(array('action'=>'chapterinbook'), 'rimfhe'));
///add instruction to form
$chapterinbook->addToForm('<br />');
		/* ---------------------- Form Elements--------*/
//assign laguage objects to variables		
$bookTitle = $this->objLanguage->languageText('mod__chaptersinbook_title', 'rimfhe', 'Title of the Book');
$isbnNumber= $this->objLanguage->languageText('mod_entirebook_isbn', 'rimfhe');
$editors = $this->objLanguage->languageText('mod_chaptersin book_editors', 'rimfhe', 'Editors');
$publisher= $this->objLanguage->languageText('mod_entirebook_publisher', 'rimfhe');
$chapterTitle = $this->objLanguage->languageText('mod_entirebook_chapter', 'rimfhe', 'Title of The Chapter');
$authorsofthechapter= $this->objLanguage->languageText('mod_chaptersinbook_authors', 'rimfhe', 'Authors of the Chapter:');
$author1= $this->objLanguage->languageText('mod_entirebook_author1', 'rimfhe');
$author2= $this->objLanguage->languageText('mod_entirebook_author2', 'rimfhe');
$author3= $this->objLanguage->languageText('mod_entirebook_author3', 'rimfhe');
$author4= $this->objLanguage->languageText('mod_entirebook_author4', 'rimfhe');
$firstChaprtPgNo= $this->objLanguage->languageText('mod_chaptersinbook_chapterfirstpageno', 'rimfhe', 'Numnber of First Page of the Chapter');
$lastChaprtPgNo= $this->objLanguage->languageText('mod_chaptersinbook_chapterlastpageno', 'rimfhe', 'Numnber of Last Page of the Chapter');
$firstChaprtPgNo= $this->objLanguage->languageText('mod_entirebook_firschapterpageno', 'rimfhe');
$author1label= $this->objLanguage->languageText('mod_accreditedjournal_author1label', 'rimfhe', "1st Author's Affiliation");
$author2label= $this->objLanguage->languageText('mod_accreditedjournal_author2label', 'rimfhe',"2nd Author's Affiliation");
$author3label= $this->objLanguage->languageText('mod_accreditedjournal_author3label', 'rimfhe', "3rd Author's Affiliation");
$author4label= $this->objLanguage->languageText('mod_accreditedjournal_author4label', 'rimfhe', "4th Author's Affiliation");
$lastChaprtPgNo= $this->objLanguage->languageText('mod_entirebook_lastchapterpageno', 'rimfhe');

//create table
$table =new htmltable('entirebook');
$table->width ='80%';
$table->startRow();

//Input and label for Book Title
$objbookTitle = new textinput('bookname');
$bookTitleLabel = new label($bookTitle,'bookname');
$table->addCell($bookTitleLabel->show(), 150, NULL, 'left');		
$table->addCell($objbookTitle->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for isbn Number
$table->startRow();
$objIsbnNumber = new textinput('isbnnumber');
$isbnNumberLabel = new label($isbnNumber.'&nbsp;', 'isbnnumber');
$table->addCell($isbnNumberLabel->show(), 150, NULL, 'left');
$table->addCell($objIsbnNumber->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Editors
$table->startRow();
$objEditors = new textinput('editors');
$editorsLabel = new label($editors,'editors');
$table->addCell($editorsLabel->show(), 150, NULL, 'left');
$table->addCell($objEditors->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Publishing House
$table->startRow();
$objPublishingHouse = new textinput('publishinghouse');
$publishingHouseLabel = new label($publisher,'publishinghouse');
$table->addCell($publishingHouseLabel->show(), 150, NULL, 'left');
$table->addCell($objPublishingHouse->show(), 150, NULL, 'left');
$table->endRow();

		
//Input and label for Title of the Chapter
$table->startRow();
$objChapterTile = new textinput ('chaptertile');
$chapterTilelLabel = new label($chapterTitle.'&nbsp;', 'chaptertile');		
$table->addCell($chapterTilelLabel->show(), 150, NULL, 'left');
$table->addCell($objChapterTile->show(), 150, NULL, 'left');
$table->endRow();
		
//Input and label for Author 1
$table->startRow();
$objAuthor1 = new textinput ('author1');
$Author1lLabel = new label($author1.'&nbsp;', 'author1');		
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
}

$table->addCell($affiliate1Label->show(), 150, NULL, 'left');
$table->addCell($objAffiliate1->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Author 2
$table->startRow();
$objAuthor2 = new textinput ('author2');
$Author2lLabel = new label($author2.'&nbsp;', 'author2');		
$table->addCell($Author2lLabel->show(), 150, NULL, 'author2');
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
}

$table->addCell($affiliate2Label->show(), 150, NULL, 'left');
$table->addCell($objAffiliate2->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Author 3
$table->startRow();
$objAuthor3 = new textinput ('author3');
$Author3lLabel = new label($author3.'&nbsp;', 'author3');		
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
}

$table->addCell($affiliate3Label->show(), 150, NULL, 'left');
$table->addCell($objAffiliate3->show(), 150, NULL, 'left');
$table->endRow();


//Input and label for Author 4
$table->startRow();
$objAuthor4 = new textinput ('author4');
$Author4lLabel = new label($author4.'&nbsp;', 'author4');		
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
}

$table->addCell($affiliate4Label->show(), 150, NULL, 'left');
$table->addCell($objAffiliate4->show(), 150, NULL, 'left');
$table->endRow();
//Input and label for First Chapter Page Number
$table->startRow();
$objFirstPagey = new textinput ('firstpage');
$firstPageLabel = new label($firstChaprtPgNo.'&nbsp;', 'firstpage');		
$table->addCell($firstPageLabel->show(), 150, NULL, 'left');
$table->addCell($objFirstPagey ->show(), 150, NULL, 'left');
$table->endRow();		

//Input and label for Last Chapter Page Number
$table->startRow();
$objLastPage = new textinput ('lastpage');
$lastPageLabel = new label($lastChaprtPgNo.'&nbsp;', 'lastpage');		
$table->addCell($lastPageLabel->show(), 150, NULL, 'left');
$table->addCell($objLastPage ->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Peer Review
$table->startRow();
$objPeerReview = new dropdown('category');
$peerRevLabel = new label($peerReview.'&nbsp;', 'category');
$answers=array("YES", "NO");
foreach ($answers as $answer)
{
   
    $objPeerReview->addOption($answer,$answer);
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
$chapterinbook->addToForm($table->show());

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
echo $chapterinbook->show();
?>
