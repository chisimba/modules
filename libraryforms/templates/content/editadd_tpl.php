<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
// end security check

/**
 *
 * libraryforms
 *
 * libraryforms allows students or distant user to request books online
 *
 * @category  Chisimba
 * @package   libraryforms
 * @author    Brenda Mayinga brendamayinga@ymail.com
 */
$objEditForm = $this->getObject('editform', 'libraryforms');
$objBookThesis = $this->getObject('bookthesis', 'libraryforms');
$objFeedbk = $this->getObject('feedbk', 'libraryforms');
$objILLperiodical = $this->getObject('illperiodical', 'libraryforms');
$tab = $this->newObject('tabbedbox', 'htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');
$tabcontent = $this->newObject('tabcontent', 'htmlelements');
$objTable = $this->newObject('htmltable', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$objForm = new form('myform', $this->uri(array('action' => 'valform'), 'htmlelements'));
$this->objUser = $this->getObject('User', 'security');


$objTable->startRow();
$objTable->addCell($objEditForm->show(), '', '', '', '', 'colspan="4"');
//$table->addCell('&nbsp;', 3);
$objTable->endRow();

echo $display;
if (count($msg) > 0) {
    foreach ($msg as $txt) {
        echo $txt . '<br/>';
    }
}
echo '<div class="LibraryForms">' . $this->objLanguage->languageText("category_resource_seven", "libraryforms") . '</div>';

$header = new htmlheading();
$header->type = 2;
$header = $this->objLanguage->languageText("mod_libraryforms_commentsnamerequired", "libraryforms", "heading2");
if ($mode != 'fixerror') {
    echo '<div class="LibraryForms">' . '<br /><span style="color:red;font-size:12px;">' . $header . '</span>' . '</div>';
}

$category = 'user';
//$tab->tabbedbox();
//$tabcontent->addTab('Distance User Form','Book/ Thesis only Form','Periodical Request Form',$tab->show());
//$tab->addBoxContent($objEditForm->show());
//$tab->addTabLabel($this->objLanguage->languageText("category_resource_one", "libraryforms"));
//$tabcontent->addTab('Distance User Form', $tab->show());
//$category->addTab('Distance',$tab->show());



$tab->tabbedbox();
$tab->addTabLabel($this->objLanguage->languageText("category_resource_four", "libraryforms"));
$tab->addBoxContent($objFeedbk->show());

$tabcontent->addTab('FeedbackForm', $tab->show());

$tab->tabbedbox();
$tab->addTabLabel($this->objLanguage->languageText("category_resource_two", "libraryforms"));
$tab->addBoxContent($objBookThesis->show());

$tabcontent->addTab('Book / Thesis only Form', $tab->show());

$tab->tabbedbox();
$tab->addTabLabel($this->objLanguage->languageText("category_resource_three", "libraryforms"));
$tab->addBoxContent($objILLperiodical->show());

$tabcontent->addTab('Periodical Request Form', $tab->show());



//captcha
$table->startRow();
$objCaptcha = $this->getObject('captcha', 'utilities');
$captcha = new textinput('request_captcha');
$captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'libraryforms', 'Verify Request'), 'request_captcha');

$table->addCell($captchaLabel->show(), 150, NULL, 'left');
$content = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as shown below. If you are unable to view the code, click on "Redraw" for a new one.'));
$table->addCell($content, 150, NULL, 'left');
$table->endRow();

$table->startRow();
$table->addCell(NULL, 150, NULL, 'left');
$table->addCell('<div id="captchaDiv">' . $objCaptcha->show() . '</div>', 150, NULL, 'left');
$table->endRow();
$objForm->addRule('request_captcha', $this->objLanguage->languageText("mod_blogcomments_captchaval", 'blogcomments', 'You have not entered the right code'), 'required');
$table->startRow();
$table->addCell(NULL, 150, NULL, 'left');
$table->addCell($captcha->show() . '<a href="javascript:redraw();">' . $this->objLanguage->languageText('word_redraw', 'library forms', 'Redraw') . '</a>', 150, NULL, 'left');
$table->endRow();


/* Get the CSS layout to make two column layout
  $cssLayout = $this->newObject('csslayout', 'htmlelements');
  //Add some text to the left column
  $cssLayout->setLeftColumnContent("Place holder text");
  //get the editform object and instantiate it
  $objEditForm = $this->getObject('editform', 'libraryforms');
  //Add the form to the middle (right in two column layout) area
  $cssLayout->setMiddleColumnContent($objEditForm->show());
  echo $cssLayout->show(); */

//$objTable->addRow(array($objFeedbk->show());
//$tabcontent->addTab($this->objLanguage->languageText('mod_toolbar_'.$category,'toolbar'),$tab->show());
//$tabcontent->addTab($this->objLanguage->languageText("mod_libraryforms_commenttoolbar","libraryforms"),$tab->show());

$tabcontent->width = '90%';
echo '<br/><center>' . $tabcontent->show() . '</center>';
?>

