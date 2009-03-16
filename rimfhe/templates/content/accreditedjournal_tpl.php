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
 $pageHeading->str = $this->objLanguage->languageText('mod_rimfhe_pageheading', 'rimfhe', 'DOE Accredited Journal Articles');
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
$accreditedJournal = new form ('accreditedjournal', $this->uri(array('action'=>'accreditedjournal'), 'rimfhe'));
//assign laguage objects to variables		
$journalName = $this->objLanguage->languageText('mod_rimfhe_journalname', 'rimfhe');
$journalCategory= $this->objLanguage->languageText('mod_rimfhe_categorey', 'rimfhe');
$articleTitle= $this->objLanguage->languageText('mod_rimfhe_atitcletitle', 'rimfhe');
$publicationYear= $this->objLanguage->languageText('mod_rimfhe_year', 'rimfhe');
$volume= $this->objLanguage->languageText('mod_rimfhe_volume', 'rimfhe');
$firstPabeNo= $this->objLanguage->languageText('mod_rimfhe_firstpage', 'rimfhe');
$lastPageNo= $this->objLanguage->languageText('mod_rimfhe_lastpage', 'rimfhe');
$author1= $this->objLanguage->languageText('mod_rimfhe_author1', 'rimfhe');
$author2= $this->objLanguage->languageText('mod_rimfhe_author2', 'rimfhe');
$author3= $this->objLanguage->languageText('mod_rimfhe_author3', 'rimfhe');
$author4= $this->objLanguage->languageText('mod_rimfhe_author4', 'rimfhe');
$author1label= $this->objLanguage->languageText('mod_rimfhe_author1affiliation', 'rimfhe');
$author2label= $this->objLanguage->languageText('mod_rimfhe_author2affiliation', 'rimfhe');
$author3label= $this->objLanguage->languageText('mod_rimfhe_author3affiliation', 'rimfhe');
$author4label= $this->objLanguage->languageText('mod_rimfhe_author4affiliation', 'rimfhe');
	
//create table
$table =new htmltable('accreditedjournal');
$table->width ='80%';
$table->startRow();
//Input and label for Journal Name
$objjournalName = new textinput('journalname');
$journalNameLabel = new label($journalName,'journalname');
$table->addCell($journalNameLabel->show(), 150, NULL, 'left');
	if($mode == 'fixerror'){
		$objjournalName->value =$this->getParam('journalname');
	}
$table->addCell($objjournalName->show(), 150, NULL, 'left');
$table->addCell(NULL, 150, NULL, 'left');
$table->endRow();

//Input and label for Journal Category
$table->startRow();
$objCategory = new dropdown('category');
$categoryLabel = new label($journalCategory.'&nbsp;', 'category');
$categories=array("ISI Listing", "IBSS Listing", "Approved SA Listing");
foreach ($categories as $category)
{   	$objCategory->addOption($category,$category);
	if($mode == 'fixerror'){
		$objCategory->setSelected($this->getParam('category'));
	}
}
$table->addCell($categoryLabel->show(), 150, NULL, 'left');
$table->addCell($objCategory->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Title of Article
$table->startRow();
$objarticleTitle = new textinput('articletitle');
$articleTiltleLabel = new label($articleTitle,'articletitle');
	if($mode == 'fixerror'){
		$objarticleTitle->value =$this->getParam('articletitle');
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
$table->addCell($volumeLabel->show(), 150, NULL, 'left');
$table->addCell($objVolume ->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Article Fisrt Page Numbers
$table->startRow();
$objFirstPage = new textinput ('firstpage');
	$firstPageLabel = new label($firstPabeNo.'&nbsp;', 'firstpage');
	if($mode == 'fixerror'){
		$objFirstPage->value =$this->getParam('firstpage');
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
$table->addCell($lastPageLabel->show(), 150, NULL, 'left');
$table->addCell($objLastPage ->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Author 1
$table->startRow();
$objAuthor1 = new textinput ('author1');
$Author1lLabel = new label($author1.'&nbsp;', 'author1');
	if($mode == 'fixerror'){
		$objAuthor1->value =$this->getParam('author1');
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
}

$table->addCell($affiliate4Label->show(), 150, NULL, 'left');
$table->addCell($objAffiliate4->show(), 150, NULL, 'left');
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
$accreditedJournal->addToForm($table->show());

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
