<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//$objListallForm = $this->getObject('view_all_messages', 'hosportal');
$objNavigationLinks = $this->getObject('side_other_links', 'hosportal');
////$objListMessageOptions = $this->getObject('set_original_message_options', 'hosportal');
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
//Add some text to the left column
$cssLayout->setLeftColumnContent("Place holder text".$objNavigationLinks->showBuiltSwitchMenu());
$cssLayout->setMiddleColumnContent($theData);
//echo $theData;
//get the editform object and instantiate it
//$objEditForm = $this->getObject('editmessage', 'hosportal');
//include_once 'text.txt';
//$myFile = "text.txt";
//$fh = fopen($myFile, 'r');
//$ourFileName = "apple.txt";
//$ourFileHandle = fopen($ourFileName, 'w');

//$myFile = "insert.txt";
//$fh = fopen($myFile, 'r');
//"c:\\data\\info.txt", "r"
///var/www/chisimba/packages/hosportal/text files/testing.txt
//$myFile = "/var/www/chisimba/packages/hosportal/text files/testing.txt";
//$fh = fopen($myFile, 'r');
//$theData = fread($fh, filesize($myFile));
//fclose($fh);
//echo $theData;
//fclose($ourFileHandle);
//Add the form to the middle (right in two column layout) area

//$objListallForm->setNoOfDesiredMessagesPerPage($noOfMessages);

//$objListallForm->sortMessages($sortOptions);
//$objListallForm->setPageNumber($pageNumber);
//$cssLayout->setMiddleColumnContent($objListallForm->show());
//$switchmenu = $this->newObject('switchmenu', 'htmlelements');
//$option1 = "option ot";
//$option2 = "option otsdfsdf";
 // $switchmenu->addBlock('Title 1', $option1.' <br />' .$option2.'Block Text 1 <br /> Block Text 1');
 // $switchmenu->addBlock('Title 2', 'Block Text 2 <br /> Block Text 2 <br /> Block Text 2', 'confirm'); // Adds
 // $cssLayout->setLeftColumnContent("Place holder text");
 // $cssLayout->setRightColumnContent($objListMessageOptions->showBuiltSwitchMenu());

echo $cssLayout->show();
?>
