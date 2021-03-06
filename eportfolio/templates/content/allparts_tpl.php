<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('mouseoverpopup', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->_objUser = $this->getObject('user', 'security');
$objIcon = $this->newObject('geticon', 'htmlelements');
$objinfoTitles = &$this->getObject('htmlheading', 'htmlelements');
$objactivityTitles = &$this->getObject('htmlheading', 'htmlelements');
$objaddressTitles = &$this->getObject('htmlheading', 'htmlelements');
$objcontactTitles = &$this->getObject('htmlheading', 'htmlelements');
$emailobjHeading = &$this->getObject('htmlheading', 'htmlelements');
$demographicsobjHeading = &$this->getObject('htmlheading', 'htmlelements');
$objactivityTitles = &$this->getObject('htmlheading', 'htmlelements');
$objaddressTitles = &$this->getObject('htmlheading', 'htmlelements');
$objaffiliationTitles = &$this->getObject('htmlheading', 'htmlelements');
$objtranscriptTitles = &$this->getObject('htmlheading', 'htmlelements');
$objqclTitles = &$this->getObject('htmlheading', 'htmlelements');
$objgoalsTitles = &$this->getObject('htmlheading', 'htmlelements');
$objcompetencyTitles = &$this->getObject('htmlheading', 'htmlelements');
$objinterestTitles = &$this->getObject('htmlheading', 'htmlelements');
$objreflectionTitles = &$this->getObject('htmlheading', 'htmlelements');
$objassertionsTitles = &$this->getObject('htmlheading', 'htmlelements');
$objcategoryTitles = &$this->getObject('htmlheading', 'htmlelements');
$tabBox = $this->newObject('tabpane', 'htmlelements');
$childtabBox = $this->newObject('tabpane', 'htmlelements');
$contacttabBox = $this->newObject('tabpane', 'htmlelements');
$emailtabBox = $this->newObject('tabpane', 'htmlelements');
$demographicstabBox = $this->newObject('tabpane', 'htmlelements');
$featureBox = $this->newObject('featurebox', 'navigation');
$addressfeatureBox = $this->newObject('featurebox', 'navigation');
$contactfeatureBox = $this->newObject('featurebox', 'navigation');
$demographicsfeatureBox = $this->newObject('featurebox', 'navigation');
$page = '';
$demographicspage = '';
$emailpage = '';
$contactpage = '';
$addresspage = '';
$activitypage = '';
$affiliationpage = '';
$transcriptpage = '';
$qclpage = '';
$goalspage = '';
$competencypage = '';
$interestpage = '';
$reflectionpage = '';
$assertionspage = '';
$categorypage = '';
$categorytypepage = '';
//Get Group Name
$groupname = $this->_objGroupAdmin->getName($groupId);
//Get the subgroups which represent the various parts of the eportfolio ie a goal item, an activity item
$isSubGroup = $this->_objGroupAdmin->getSubgroups($groupId);
$gname = explode('^', $groupname);
$gname = $gname[1];
$objHeading = &$this->getObject('htmlheading', 'htmlelements');
$objHeading->align = 'center';
$objHeading->type = 2;
$objHeading->str = '<font color="#FF8800">' . $objLanguage->languageText("mod_eportfolio_wordManage", 'eportfolio') . ' ' . $gname . ' '  . '</font>';
echo $objHeading->show();
$mygroupId = new hiddeninput("mygroupId", $groupId);
echo "</br>";
//Link to epms home
$iconSelect = $this->getObject('geticon', 'htmlelements');
$iconSelect->setIcon('home');
$iconSelect->alt = $objLanguage->languageText("mod_eportfolio_eportfoliohome", 'eportfolio');
$mnglink = new link($this->uri(array(
                    'module' => 'eportfolio'
                )));
$mnglink->link = $iconSelect->show();
$linkManage = $mnglink->show();
echo '<div align="center">' . $linkManage . '</div>';
echo "</br>";
$form = new form("add", $this->uri(array(
                    'module' => 'eportfolio',
                    'action' => 'addparts'
                )));
//Save button
$button = new button("submit", $objLanguage->languageText("word_save")); //word_save
$button->setToSubmit();
$objHeading->align = 'left';
$objinfoTitles->type = 1;
$objaddressTitles->type = 1;
$objcontactTitles->type = 1;
//$hasAccess = $this->objEngine->_objUser->isContextLecturer();
//$hasAccess|= $this->objEngine->_objUser->isAdmin();
$hasAccess = $this->_objUser->isContextLecturer();
$hasAccess|= $this->_objUser->isAdmin();
$this->setVar('pageSuppressXML', true);
$link = new link($this->uri(array(
                    'module' => 'eportfolio',
                    'action' => 'view_contact'
                )));
$link->link = 'View Identification Details';
//echo '<br clear="left" />'.$link->show();
//Create Owner and Guest group for user
$eportfoliogrpList = $this->_objGroupAdmin->getId($this->objUser->PKId($this->objUser->userId()), $pkField = 'name');
if (empty($eportfoliogrpList)) {
    //Add User to context groups
    $title = $this->objUser->PKId($this->objUser->userId()) . ' ' . $objUser->getSurname();
    $this->createGroups($this->objUser->PKId($this->objUser->userId()), $title);
}
//End Create
//Start Address View
$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
// Show the heading
$addressList = $this->objDbAddressList->getByItem($userId);
// Create a table object
$addressTable = &$this->newObject("htmltable", "htmlelements");
$addressTable->border = 0;
$addressTable->cellspacing = '12';
$addressTable->width = "100%";
// Add the table heading.
$addressTable->startRow();
$addressTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$addressTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
$addressTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_streetno", 'eportfolio') . "</b>");
$addressTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_streetname", 'eportfolio') . "</b>");
$addressTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_locality", 'eportfolio') . "</b>");
$addressTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_city", 'eportfolio') . "</b>");
$addressTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_postcode", 'eportfolio') . "</b>");
$addressTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_postaddress", 'eportfolio') . "</b>");
$addressTable->endRow();
// Step through the list of addresses.
if (!empty($addressList)) {
    foreach ($addressList as $addressItem) {
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $addCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($addressItem['id'] == $subgrp['group_define_name']) {
                    $addCheck = 1;
                }
            }
            if ($addCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        // Display each field for addresses
        $addressTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $addressItem['id'];
        $objCheck->value = $addressItem['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $addressTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $cattype = $this->objDbCategorytypeList->listSingle($addressItem['type']);
        $addressTable->addCell($cattype[0]['type'], "", NULL, NULL, NULL, '');
        $addressTable->addCell($addressItem['street_no'], "", NULL, NULL, NULL, '');
        $addressTable->addCell($addressItem['street_name'], "", NULL, NULL, NULL, '');
        $addressTable->addCell($addressItem['locality'], "", NULL, NULL, NULL, '');
        $addressTable->addCell($addressItem['city'], "", NULL, NULL, NULL, '');
        $addressTable->addCell($addressItem['postcode'], "", NULL, NULL, NULL, '');
        $addressTable->addCell($addressItem['postal_address'], "", NULL, NULL, NULL, '');
        $addressTable->endRow();
    }
    unset($addressItem);
} else {
    $addressTable->startRow();
    $addressTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="8"');
    $addressTable->endRow();
}
$addressTable->startRow();
$addressTable->addCell('', '', '', '', '', 'noRecordsMessage', 'colspan="8"');
$addressTable->endRow();
//echo '<br clear="left" />'.$mainlink->show();
//End Address View
//Start Contacts View
// Show the heading
$objcontactTitles->type = 3;
$objcontactTitles->str = $objLanguage->languageText("mod_eportfolio_contact", 'eportfolio');
//echo $objHeading->show();
$contactList = $this->objDbContactList->getByItem($userId);
$emailList = $this->objDbEmailList->getByItem($userId);
// Create a table object
$contactTable = &$this->newObject("htmltable", "htmlelements");
$contactTable->border = 0;
$contactTable->cellspacing = '3';
$contactTable->width = "100%";
// Add the table heading.
$contactTable->startRow();
$contactTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$contactTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
$contactTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contacttype", 'eportfolio') . "</b>");
$contactTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_countrycode", 'eportfolio') . "</b>");
$contactTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_areacode", 'eportfolio') . "</b>");
$contactTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contactnumber", 'eportfolio') . "</b>");
$contactTable->endRow();
// Step through the list of addresses.
if (!empty($contactList)) {
    foreach ($contactList as $contactItem) {
        // Display each field
        $cattype = $this->objDbCategorytypeList->listSingle($contactItem['type']);
        $modetype = $this->objDbCategorytypeList->listSingle($contactItem['contact_type']);
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $contCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($contactItem['id'] == $subgrp['group_define_name']) {
                    $contCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($contCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        $contactTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $contactItem['id'];
        $objCheck->value = $contactItem['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $contactTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $contactTable->addCell($cattype[0]['type'], "", NULL, NULL, NULL, '');
        $contactTable->addCell($modetype[0]['type'], "", NULL, NULL, NULL, '');
        $contactTable->addCell($contactItem['country_code'], "", NULL, NULL, NULL, '');
        $contactTable->addCell($contactItem['area_code'], "", NULL, NULL, NULL, '');
        $contactTable->addCell($contactItem['id_number'], "", NULL, NULL, NULL, '');
        $contactTable->endRow();
    }
    unset($contactItem);
} else {
    $contactTable->startRow();
    $contactTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="6"');
    $contactTable->endRow();
}
$contactTable->startRow();
$contactTable->addCell('', '', '', '', 'noRecordsMessage', 'colspan="6"');
$contactTable->endRow();
//End Contact View
//Start Email View
$iconAdd = $this->getObject('geticon', 'htmlelements');
$iconAdd->setIcon('add');
$iconAdd->alt = $objLanguage->languageText("mod_eportfolio_add", 'eportfolio');
// Create a table object for emails
$emailTable = &$this->newObject("htmltable", "htmlelements");
$emailTable->border = 0;
$emailTable->cellspacing = '3';
$emailTable->width = "50%";
// Add the table heading.
$emailTable->startRow();
$emailTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$emailTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
$emailTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_email", 'eportfolio') . "</b>");
$emailTable->endRow();
// Step through the list of addresses.
$class = 'even';
if (!empty($emailList)) {
    foreach ($emailList as $emailItem) {
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $emailCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($emailItem['id'] == $subgrp['group_define_name']) {
                    $emailCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($emailCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        // Display each field for addresses
        $cattype = $this->objDbCategorytypeList->listSingle($emailItem['type']);
        $emailTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $emailItem['id'];
        $objCheck->value = $emailItem['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $emailTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $emailTable->addCell($cattype[0]['type'], "", NULL, NULL, NULL, '');
        $emailTable->addCell($emailItem['email'], "", NULL, NULL, NULL, '');
        $emailTable->endRow();
    }
    unset($emailItem);
} else {
    $emailTable->startRow();
    $emailTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="3"');
    $emailTable->endRow();
}
$emailTable->startRow();
$emailTable->addCell('', '', '', '', 'noRecordsMessage', 'colspan="3"');
$emailTable->endRow();
//End Email View
//Demographics view
$demographicsList = $this->objDbDemographicsList->getByItem($userId);
// Create a table object
$demographicsTable = &$this->newObject("htmltable", "htmlelements");
$demographicsTable->border = 0;
$demographicsTable->cellspacing = '3';
$demographicsTable->width = "50%";
// Add the table heading.
$demographicsTable->startRow();
$demographicsTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$demographicsTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
$demographicsTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_birth", 'eportfolio') . "</b>");
$demographicsTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_nationality", 'eportfolio') . "</b>");
$demographicsTable->endRow();
// Step through the list of addresses.
if (!empty($demographicsList)) {
    foreach ($demographicsList as $demographicsItem) {
        // Display each field for Demographics
        $cattype = $this->objDbCategorytypeList->listSingle($demographicsItem['type']);
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $demoCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($demographicsItem['id'] == $subgrp['group_define_name']) {
                    $demoCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($demoCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        $demographicsTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $demographicsItem['id'];
        $objCheck->value = $demographicsItem['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $demographicsTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $demographicsTable->addCell($cattype[0]['type'], "", NULL, NULL, NULL, '');
        $demographicsTable->addCell($this->objDate->formatDate($demographicsItem['birth']), "", NULL, NULL, NULL, '');
        $demographicsTable->addCell($demographicsItem['nationality'], "", NULL, NULL, NULL, '');
        $demographicsTable->endRow();
    }
    unset($demographicsItem);
} else {
    $demographicsTable->startRow();
    $demographicsTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="4"');
    $demographicsTable->endRow();
}
//echo $demographicsTable->show();
//End Demographics view
//view name
// Show the heading
$objHeading->type = 3;
$objHeading->str = $objLanguage->languageText("mod_eportfolio_title", 'eportfolio');
//echo $objHeading->show();
// Create a table object
$userTable = &$this->newObject("htmltable", "htmlelements");
$userTable->border = 0;
$userTable->cellspacing = '12';
$userTable->width = "40%";
// Add the table heading.
$userTable->startRow();
$userTable->addCell("<b>" . $objLanguage->languageText('word_title', 'system') . "</b>");
$userTable->addCell("<b>" . $objLanguage->languageText('word_surname', 'system') . "</b>");
$userTable->addCell("<b>" . $objLanguage->languageText('phrase_othernames', 'eportfolio') . "</b>");
$userTable->endRow();
// Step through the list of addresses.
if (!empty($user)) {
    // Display each field for addresses
    $userTable->startRow();
    $userTable->addCell($user['title'], "", NULL, NULL, NULL, '');
    $userTable->addCell($user['surname'], "", NULL, NULL, NULL, '');
    $userTable->addCell($user['firstname'], "", NULL, NULL, NULL, '');
    $userTable->endRow();
    $userTable->startRow();
    $userTable->addCell('', '', '', '', '', 'colspan="3"');
    $userTable->endRow();
} else {
    $userTable->startRow();
    $userTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="3"');
    $userTable->endRow();
}
//	echo $userTable->show();
//end view name
//View Activity
//Language Items
$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
// Show the heading
$activitylist = $this->objDbActivityList->getByItem($userId);
// Create a table object
$activityTable = &$this->newObject("htmltable", "htmlelements");
$activityTable->border = 0;
$activityTable->cellspacing = '3';
$activityTable->width = "100%";
// Add the table heading.
$activityTable->startRow();
$activityTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$activityTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contexttitle", 'eportfolio') . "</b>");
$activityTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_activitytype", 'eportfolio') . "</b>");
$activityTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_activitystart", 'eportfolio') . "</b>");
$activityTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_activityfinish", 'eportfolio') . "</b>");
$activityTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
$activityTable->endRow();
// Step through the list of addresses.
$class = NULL;
if (!empty($activitylist)) {
    $i = 0;
    foreach ($activitylist as $item) {
        //Get context title
        $objDbContext = &$this->getObject('dbcontext', 'context');
        $mycontextRecord = $objDbContext->getContextDetails($item['contextid']);
        if (!empty($mycontextRecord)) {
            $mycontextTitle = $mycontextRecord['title'];
        } else {
            $mycontextTitle = $item['contextid'];
        }
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $actvCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($item['id'] == $subgrp['group_define_name']) {
                    $actvCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($actvCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        // Display each field for activities
        $cattype = $this->objDbCategorytypeList->listSingle($item['type']);
        $activityTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $item['id'];
        $objCheck->value = $item['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $activityTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $activityTable->addCell($mycontextTitle, "", NULL, NULL, $class, '');
        $activityTable->addCell($cattype[0]['type'], "", NULL, NULL, $class, '');
        $activityTable->addCell($this->objDate->formatDate($item['start']), "", NULL, NULL, $class, '');
        $activityTable->addCell($this->objDate->formatDate($item['finish']), "", NULL, NULL, $class, '');
        $activityTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
        $activityTable->endRow();
    }
    unset($item);
} else {
    $activityTable->startRow();
    $activityTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="6"');
    $activityTable->endRow();
}
//End View Activity
//View Affiliation
//Language Items
$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
// Show the heading
$affiliationList = $this->objDbAffiliationList->getByItem($userId);
// Create a table object
$affiliationTable = &$this->newObject("htmltable", "htmlelements");
$affiliationTable->border = 0;
$affiliationTable->cellspacing = '12';
$affiliationTable->width = "100%";
// Add the table heading.
$affiliationTable->startRow();
$affiliationTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$affiliationTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
$affiliationTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_classificationView", 'eportfolio') . "</b>");
$affiliationTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_roleView", 'eportfolio') . "</b>");
$affiliationTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_organisation", 'eportfolio') . "</b>");
$affiliationTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_activitystart", 'eportfolio') . "</b>");
$affiliationTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_activityfinish", 'eportfolio') . "</b>");
$affiliationTable->endRow();
// Step through the list of addresses.
$class = NULL;
if (!empty($affiliationList)) {
    $i = 0;
    foreach ($affiliationList as $affiliationItem) {
        // Display each field for addresses
        $cattype = $this->objDbCategorytypeList->listSingle($affiliationItem['type']);
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $affiliationCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($affiliationItem['id'] == $subgrp['group_define_name']) {
                    $affiliationCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($affiliationCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        $affiliationTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $affiliationItem['id'];
        $objCheck->value = $affiliationItem['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $affiliationTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $affiliationTable->addCell($cattype[0]['type'], "", NULL, NULL, $class, '');
        $affiliationTable->addCell($affiliationItem['classification'], "", NULL, NULL, $class, '');
        $affiliationTable->addCell($affiliationItem['role'], "", NULL, NULL, $class, '');
        $affiliationTable->addCell($affiliationItem['organisation'], "", NULL, NULL, $class, '');
        $affiliationTable->addCell($this->objDate->formatDate($affiliationItem['start']), "", NULL, NULL, $class, '');
        $affiliationTable->addCell($this->objDate->formatDate($affiliationItem['finish']), "", NULL, NULL, $class, '');
        $affiliationTable->endRow();
    }
    unset($affiliationItem);
} else {
    $affiliationTable->startRow();
    $affiliationTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="8"');
    $affiliationTable->endRow();
}
//View Affiliation
//View Transcript
//Language Items
$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
// Show the heading
$transcriptlist = $this->objDbTranscriptList->getByItem($userId);
// Create a table object
$transcriptTable = &$this->newObject("htmltable", "htmlelements");
$transcriptTable->border = 0;
$transcriptTable->cellspacing = '12';
$transcriptTable->width = "50%";
// Add the table heading.
$transcriptTable->startRow();
$transcriptTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$transcriptTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
$transcriptTable->endRow();
// Step through the list of addresses.
$class = NULL;
if (!empty($transcriptlist)) {
    foreach ($transcriptlist as $item) {
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $transCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($item['id'] == $subgrp['group_define_name']) {
                    $transCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($transCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        // Display each field for activities
        $transcriptTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $item['id'];
        $objCheck->value = $item['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $transcriptTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $transcriptTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
        $transcriptTable->endRow();
    }
    unset($item);
} else {
    $transcriptTable->startRow();
    $transcriptTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="2"');
    $transcriptTable->endRow();
}
//View Transcript
//View Qcl
//Language Items
$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
// Show the heading
$qclList = $this->objDbQclList->getByItem($userId);
// Create a table object
$qclTable = &$this->newObject("htmltable", "htmlelements");
$qclTable->border = 0;
$qclTable->cellspacing = '3';
$qclTable->width = "100%";
// Add the table heading.
$qclTable->startRow();
$qclTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$qclTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
$qclTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordtitle", 'eportfolio') . "</b>");
$qclTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_organisation", 'eportfolio') . "</b>");
$qclTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_level", 'eportfolio') . "</b>");
$qclTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_qclawarddate", 'eportfolio') . "</b>");
//$qclTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_shortdescription",'eportfolio')."</b>");
$qclTable->endRow();
// Step through the list of addresses.
$class = NULL;
if (!empty($qclList)) {
    foreach ($qclList as $qclItem) {
        // Display each field for addresses
        $cattype = $this->objDbCategorytypeList->listSingle($qclItem['qcl_type']);
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $qclCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($qclItem['id'] == $subgrp['group_define_name']) {
                    $qclCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($qclCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        $qclTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $qclItem['id'];
        $objCheck->value = $qclItem['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $qclTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $qclTable->addCell($cattype[0]['type'], "", NULL, NULL, $class, '');
        $qclTable->addCell($qclItem['qcl_title'], "", NULL, NULL, $class, '');
        $qclTable->addCell($qclItem['organisation'], "", NULL, NULL, $class, '');
        $qclTable->addCell($qclItem['qcl_level'], "", NULL, NULL, $class, '');
        $qclTable->addCell($this->objDate->formatDate($qclItem['award_date']), "", NULL, NULL, $class, '');
        $qclTable->endRow();
    }
    unset($qclItem);
} else {
    $qclTable->startRow();
    $qclTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="6"');
    $qclTable->endRow();
}
//End View Qcl
//View Goals
//Language Items
$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
// Show the heading
$goalsList = $this->objDbGoalsList->getByItem($userId);
// Create a table object
$goalsTable = &$this->newObject("htmltable", "htmlelements");
$goalsTable->border = 0;
$goalsTable->cellspacing = '12';
$goalsTable->width = "60%";
// Add the table heading.
$goalsTable->startRow();
$goalsTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$goalsTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_goals", 'eportfolio', "Goals") . "</b>");
$goalsTable->endRow();
// Step through the list of addresses.
$class = NULL;
if (!empty($goalsList)) {
    $i = 0;
    foreach ($goalsList as $item) {
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $goalsCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($item['id'] == $subgrp['group_define_name']) {
                    $goalsCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($goalsCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        // Display each field for activities
        $goalsTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $item['id'];
        $objCheck->value = $item['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $goalsTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $goalsTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
        $goalsTable->endRow();
    }
    unset($item);
} else {
    $goalsTable->startRow();
    $goalsTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="2"');
    $goalsTable->endRow();
}
//End View Goals
//View Competency
//Language Items
$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
// Show the heading
$competencyList = $this->objDbCompetencyList->getByItem($userId);
// Create a table object
$competencyTable = &$this->newObject("htmltable", "htmlelements");
$competencyTable->border = 0;
$competencyTable->cellspacing = '12';
$competencyTable->width = "100%";
// Add the table heading.
$competencyTable->startRow();
$competencyTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$competencyTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
$competencyTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_qclawarddate", 'eportfolio') . "</b>");
$competencyTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
$competencyTable->endRow();
// Step through the list of addresses.
$class = NULL;
if (!empty($competencyList)) {
    foreach ($competencyList as $item) {
        // Display each field for activities
        $cattype = $this->objDbCategorytypeList->listSingle($item['type']);
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $ctyCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($item['id'] == $subgrp['group_define_name']) {
                    $ctyCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($ctyCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        $competencyTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $item['id'];
        $objCheck->value = $item['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $competencyTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $competencyTable->addCell($cattype[0]['type'], "", NULL, NULL, $class, '');
        $competencyTable->addCell($this->objDate->formatDate($item['award_date']), "", NULL, NULL, $class, '');
        $competencyTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
        $competencyTable->endRow();
    }
    unset($item);
} else {
    $competencyTable->startRow();
    $competencyTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="4"');
    $competencyTable->endRow();
}
//End View Competency
//View Interest
//Language Items
$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
// Show the heading
$interestList = $this->objDbInterestList->getByItem($userId);
// Create a table object
$interestTable = &$this->newObject("htmltable", "htmlelements");
$interestTable->border = 0;
$interestTable->cellspacing = '12';
$interestTable->width = "100%";
// Add the table heading.
$interestTable->startRow();
$interestTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$interestTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
$interestTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_creationDate", 'eportfolio') . "</b>");
$interestTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
$interestTable->endRow();
// Step through the list of addresses.
$class = NULL;
if (!empty($interestList)) {
    foreach ($interestList as $item) {
        // Display each field for activities
        $cattype = $this->objDbCategorytypeList->listSingle($item['type']);
        //Check if this item has been checked already
        if (!empty($isSubGroup)) {
            $intrstCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($item['id'] == $subgrp['group_define_name']) {
                    $intrstCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($intrstCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        $interestTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $item['id'];
        $objCheck->value = $item['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $interestTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $interestTable->addCell($cattype[0]['type'], "", NULL, NULL, $class, '');
        $interestTable->addCell($this->objDate->formatDate($item['creation_date']), "", NULL, NULL, $class, '');
        $interestTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
        $interestTable->endRow();
    }
    unset($item);
} else {
    $interestTable->startRow();
    $interestTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="4"');
    $interestTable->endRow();
}
//End View Interest
//View reflection
//Language Items
$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
// Show the heading
$reflectionList = $this->objDbReflectionList->getByItem($userId);
// Create a table object
$reflectionTable = &$this->newObject("htmltable", "htmlelements");
$reflectionTable->border = 0;
$reflectionTable->cellspacing = '3';
$reflectionTable->width = "100%";
// Add the table heading.
$reflectionTable->startRow();
$reflectionTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
$reflectionTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_rationaleTitle", 'eportfolio') . "</b>");
$reflectionTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_creationDate", 'eportfolio') . "</b>");
$reflectionTable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
$reflectionTable->endRow();
// Step through the list of addresses.
$class = NULL;
if (!empty($reflectionList)) {
    foreach ($reflectionList as $item) {
        //Check if this item has been checked already

        if (!empty($isSubGroup)) {
            $rfctnCheck = 0;
            foreach ($isSubGroup[0] as $subgrp) {
                if ($item['id'] == $subgrp['group_define_name']) {
                    $rfctnCheck = 1;
                }
            }
            //Do justice on the checkbox
            if ($rfctnCheck == 1) {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
        } else {
            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
        }
        // Display each field for activities
        $reflectionTable->startRow();
        // Show the manage item check box
        $objCheck->cssId = 'checkbox_' . $item['id'];
        $objCheck->value = $item['id'];
        $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
        $reflectionTable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
        $reflectionTable->addCell($item['rationale'], "", NULL, NULL, $class, '');
        $reflectionTable->addCell($this->objDate->formatDate($item['creation_date']), "", NULL, NULL, $class, '');
        $reflectionTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
        $reflectionTable->endRow();
    }
    unset($item);
} else {
    $reflectionTable->startRow();
    $reflectionTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="4"');
    $reflectionTable->endRow();
}
//End View Reflection
//View assertions
$hasAccess = $this->_objUser->isContextLecturer();
$hasAccess|= $this->_objUser->isAdmin();
//$this->setVar('pageSuppressXML',true);
if (!$hasAccess) {
    //Language Items
    $notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
    // Show the heading
    $Id = $this->_objGroupAdmin->getUserGroups($userPid);
    // Create a table object
    $assertionstable = &$this->newObject("htmltable", "htmlelements");
    $assertionstable->border = 0;
    $assertionstable->cellspacing = '3';
    $assertionstable->width = "100%";
    // Add the table heading.
    $assertionstable->startRow();
    $assertionstable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
    $assertionstable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_lecturer", 'eportfolio') . "</b>");
    $assertionstable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_rationaleTitle", 'eportfolio') . "</b>");
    $assertionstable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_creationDate", 'eportfolio') . "</b>");
    $assertionstable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
    $assertionstable->endRow();
    // Step through the list of addresses.
    $class = NULL;
    if (!empty($Id)) {
        foreach ($Id as $groupId) {
            //Get the group parent_id
            $parentId = $this->_objGroupAdmin->getParent($groupId);
            foreach ($parentId as $myparentId) {
                //Get the name from group table
                $assertionId = $this->_objGroupAdmin->getName($myparentId['parent_id']);
                $assertionslist = $this->objDbAssertionList->listSingle($assertionId);
                if (!empty($assertionslist)) {
                    //Check if this item has been checked already
                    if (!empty($isSubGroup)) {
                        $asserCheck = 0;
                        foreach ($isSubGroup[0] as $subgrp) {
                            if ($assertionslist[0]['id'] == $subgrp['group_define_name']) {
                                $asserCheck = 1;
                            }
                        }
                        //Do justice on the checkbox
                        if ($asserCheck == 1) {
                            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
                        } else {
                            $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
                        }
                    } else {
                        $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
                    }
                    // Display each field for activities
                    $assertionstable->startRow();
                    // Show the manage item check box
                    $objCheck->cssId = 'checkbox_' . $assertionslist[0]['id'];
                    $objCheck->value = $assertionslist[0]['id'];
                    $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
                    $assertionstable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
                    $assertionstable->addCell($objUser->fullName($assertionslist[0]['userid']), "", NULL, NULL, $class, '');
                    $assertionstable->addCell($assertionslist[0]['rationale'], "", NULL, NULL, $class, '');
                    $assertionstable->addCell($this->objDate->formatDate($assertionslist[0]['creation_date']), "", NULL, NULL, $class, '');
                    $assertionstable->addCell($assertionslist[0]['shortdescription'], "", NULL, NULL, $class, '');
                    $assertionstable->endRow();
                }
                unset($myparentId);
            }
            unset($groupId);
        }
    } else {
        $assertionstable->startRow();
        $assertionstable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="5"');
        $assertionstable->endRow();
    }
    //echo $assertionstable->show();
} else {
    //Language Items
    $notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
    // Show the heading
    $assertionslist = $this->objDbAssertionList->getByItem($userId);
    // Create a table object
    $assertionstable = &$this->newObject("htmltable", "htmlelements");
    $assertionstable->border = 0;
    $assertionstable->cellspacing = '3';
    $assertionstable->width = "100%";
    // Add the table heading.
    $assertionstable->startRow();
    $assertionstable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_wordSelect", 'eportfolio') . "<b>");
    $assertionstable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_rationaleTitle", 'eportfolio') . "</b>");
    $assertionstable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_creationDate", 'eportfolio') . "</b>");
    $assertionstable->addCell("<b>" . $objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
    $assertionstable->endRow();
    // Step through the list of addresses.
    $class = NULL;
    //    $arrayLists = array();
    if (!empty($assertionslist)) {
        foreach ($assertionslist as $item) {
            //Check if this item has been checked already
            if (!empty($isSubGroup)) {
                $assertCheck = 0;
                foreach ($isSubGroup[0] as $subgrp) {
                    if ($item['id'] == $subgrp['group_define_name']) {
                        $assertCheck = 1;
                    }
                }
                //Do justice on the checkbox
                if ($assertCheck == 1) {
                    $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = true);
                } else {
                    $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
                }
            } else {
                $objCheck = new checkbox('arrayList[]', $label = NULL, $ischecked = false);
            }
            // Display each field for activities
            $assertionstable->startRow();
            // Show the manage item check box
            $objCheck->cssId = 'checkbox_' . $item['id'];
            $objCheck->value = $item['id'];
            $objCheck->extra = 'onclick="javascript:toggleChecked(this);"';
            $assertionstable->addCell($objCheck->show(), "", NULL, NULL, NULL, '');
            $assertionstable->addCell($item['rationale'], "", NULL, NULL, $class, '');
            $assertionstable->addCell($this->objDate->formatDate($item['creation_date']), "", NULL, NULL, $class, '');
            $assertionstable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
            $assertionstable->endRow();
        }
        unset($item);
    } else {
        $assertionstable->startRow();
        $assertionstable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="5"');
        $assertionstable->endRow();
    }
    //Store the GroupId
    //$groupId = new hiddeninput("groupId", $groupId);
    //$row = array( $groupId->show() );
    //$assertionstable->addRow($row, NULL);
} //end else hasAccess
//End View Assertions
//Information Title
$objinfoTitles->str = $objUser->getSurname($userId) . $objLanguage->languageText("phrase_eportfolio_userinformation", 'eportfolio');
$this->objmainTab = $this->newObject('tabber', 'htmlelements');
$this->objTab = $this->newObject('tabber', 'htmlelements');
$this->objTab->init();
$this->objTab->tabId = TRUE;

//For each tab, check if made visible and order as per the eportfolio blocks
//Get Visible MAIN blocks
$mainBlocks = $this->objEPBlocks->getVisibleBlocks('main');
//Step through each Block
foreach ($mainBlocks as $mainBlock) {
    if ($mainBlock["title"] == 'Identification') {
        //Names tab (Visible By Default)
        $this->objTab->tabId = "minitab001";
        $this->objTab->addTab(array(
            'name' => $this->objLanguage->languageText("word_name"),
            'content' => $userTable->show()
        ));
        //Get Visible IDENTIFICATION blocks
        $identityBlocks = $this->objEPBlocks->getVisibleBlocks('identity');
        //Step through each Block
        foreach ($identityBlocks as $identityBlock) {
            if ($identityBlock["title"] == 'Address') {
                //Address Tab
                $this->objTab->tabId = "identitytab001";
                $this->objTab->addTab(array(
                    'name' => $this->objLanguage->languageText("mod_eportfolio_wordAddress", 'eportfolio'),
                    'content' => $addressTable->show()
                ));
            } elseif ($identityBlock["title"] == 'Contact') {
                //Contact Tab
                $this->objTab->tabId = "identitytab002";
                $this->objTab->addTab(array(
                    'name' => $this->objLanguage->languageText("mod_eportfolio_wordContact", 'eportfolio'),
                    'content' => $contactTable->show()
                ));
            } elseif ($identityBlock["title"] == 'Email') {
                //Email Tab
                $this->objTab->tabId = "identitytab003";
                $this->objTab->addTab(array(
                    'name' => $this->objLanguage->languageText("mod_eportfolio_wordEmail", 'eportfolio'),
                    'content' => $emailTable->show()
                ));
            } elseif ($identityBlock["title"] == 'Demographics') {
                //Demographics Tab
                $this->objTab->tabId = "identitytab004";
                $this->objTab->addTab(array(
                    'name' => $this->objLanguage->languageText("mod_eportfolio_wordDemographics", 'eportfolio'),
                    'content' => $demographicsTable->show()
                ));
            }
        }
        $infotabs = $this->objTab->show();
        //Identification Tab
        $this->objmainTab->tabId = "maintab001";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordInformation", 'eportfolio'),
            'content' => $infotabs
        ));
    } elseif ($mainBlock["title"] == 'Activities') {
        //Activity Title
        $this->objmainTab->tabId = "maintab002";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordActivity", 'eportfolio'),
            'content' => $activityTable->show()
        ));
    } elseif ($mainBlock["title"] == 'Affiliation') {
        //Affiliation Title
        $this->objmainTab->tabId = "maintab003";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordAffiliation", 'eportfolio'),
            'content' => $affiliationTable->show()
        ));
    } elseif ($mainBlock["title"] == 'Transcripts') {
        //Transcripts Title
        $this->objmainTab->tabId = "maintab004";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordTranscripts", 'eportfolio'),
            'content' => $transcriptTable->show()
        ));
    } elseif ($mainBlock["title"] == 'Qualifications') {
        //Qualifications Title
        $this->objmainTab->tabId = "maintab005";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordQualification", 'eportfolio'),
            'content' => $qclTable->show()
        ));
    } elseif ($mainBlock["title"] == 'Goals') {
        //Goals Title
        $this->objmainTab->tabId = "maintab006";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordGoals", 'eportfolio'),
            'content' => $goalsTable->show()
        ));
    } elseif ($mainBlock["title"] == 'Competencies') {
        //Competencies Title
        $this->objmainTab->tabId = "maintab007";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordCompetency", 'eportfolio'),
            'content' => $competencyTable->show()
        ));
    } elseif ($mainBlock["title"] == 'Interests') {
        //Interests Title
        $this->objmainTab->tabId = "maintab008";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordInterests", 'eportfolio'),
            'content' => $interestTable->show()
        ));
    } elseif ($mainBlock["title"] == 'Reflections') {
        //Reflections Title
        $this->objmainTab->tabId = "maintab009";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordReflections", 'eportfolio'),
            'content' => $reflectionTable->show()
        ));
    } elseif ($mainBlock["title"] == 'Assertions') {
        //Assertions Title
        $this->objmainTab->tabId = "maintab010";
        $this->objmainTab->addTab(array(
            'name' => $this->objLanguage->languageText("mod_eportfolio_wordAssertion", 'eportfolio'),
            'content' => $assertionstable->show()
        ));
    }
}

$myeportfolioTab = $this->objmainTab->show();
$tabBox->addTab(array(
    'name' => $this->objLanguage->languageText("phrase_myePortfolio", 'eportfolio'),
    'content' => $myeportfolioTab
        ), 'winclassic-tab-style-sheet');
//echo $tabBox->show();
$form->addToForm($myeportfolioTab . '<div align="center" >' . $button->show() . $mygroupId->show() . '</div>');
echo $form->show();
?>
