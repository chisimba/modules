<?php
/* ----------- getall_Eportfolio class extends object------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for getting eportfolio content
 * @author Paul Mungai
 * @copyright 2008 University of the Western Cape
 */
class getall_Eportfolio extends object
{
    /**
     *
     * Intialiser for the getall_Eportfolio controller
     * @access public
     *
     */
    public function init() 
    {
        // Get the DB object.
        $this->objDbAddressList = &$this->getObject('dbeportfolio_address', 'eportfolio');
        $this->objDbCategorytypeList = &$this->getObject('dbeportfolio_categorytypes', 'eportfolio');
        $this->objDbContactList = &$this->getObject('dbeportfolio_contact', 'eportfolio');
        $this->objDbEmailList = &$this->getObject('dbeportfolio_email', 'eportfolio');
        $this->objDbDemographicsList = &$this->getObject('dbeportfolio_demographics', 'eportfolio');
        $this->objDbActivityList = &$this->getObject('dbeportfolio_activity', 'eportfolio');
        $this->objDbAffiliationList = &$this->getObject('dbeportfolio_affiliation', 'eportfolio');
        $this->objDbTranscriptList = &$this->getObject('dbeportfolio_transcript', 'eportfolio');
        $this->objDbQclList = &$this->getObject('dbeportfolio_qcl', 'eportfolio');
        $this->objDbGoalsList = &$this->getObject('dbeportfolio_goals', 'eportfolio');
        $this->objDbCompetencyList = &$this->getObject('dbeportfolio_competency', 'eportfolio');
        $this->objDbInterestList = &$this->getObject('dbeportfolio_interest', 'eportfolio');
        $this->objDbReflectionList = &$this->getObject('dbeportfolio_reflection', 'eportfolio');
        $this->objDbAssertionList = &$this->getObject('dbeportfolio_assertion', 'eportfolio');
        $this->_objGroupAdmin = &$this->newObject('groupadminmodel', 'groupadmin');
        $this->objUser = &$this->getObject('user', 'security');
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->objDate = &$this->newObject('dateandtime', 'utilities');
        //$objLanguage =& $this->getObject('language','language');
        $this->objLanguage = &$this->getObject('language', 'language');
    }
    public function getAddress($userId) 
    {
        $objaddressTitles = &$this->getObject('htmlheading', 'htmlelements');
        $objaddressTitles->type = 2;
        //$objLanguage =& $this->getObject('language','language');
        $addressList = $this->objDbAddressList->getByItem($userId);
        if (!empty($addressList)) {
            // Create a table object
            $addressTable = &$this->newObject("htmltable", "htmlelements");
            $addressTable->border = 0;
            $addressTable->cellspacing = '2';
            $addressTable->width = "60%";
            $objaddressTitles->str = $this->objLanguage->languageText("mod_eportfolio_wordAddress", 'eportfolio');
            // Add the table heading.
            $addressTable->startRow();
            $addressTable->addCell($objaddressTitles->show() , '', '', '', '', 'colspan="7"');
            $addressTable->endRow();
            $addressTable->startRow();
            $addressTable->addCell("<b>&nbsp;&nbsp;&nbsp;" . $this->objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
            $addressTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_streetno", 'eportfolio') . "</b>");
            $addressTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_streetname", 'eportfolio') . "</b>");
            $addressTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_locality", 'eportfolio') . "</b>");
            $addressTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_city", 'eportfolio') . "</b>");
            $addressTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_postcode", 'eportfolio') . "</b>");
            $addressTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_postaddress", 'eportfolio') . "</b>");
            $addressTable->endRow();
            // Step through the list of addresses.
            if (!empty($addressList)) {
                $addressNo = 1;
                foreach($addressList as $addressItem) {
                    // Display each field for addresses
                    $addressTable->startRow();
                    $cattype = $this->objDbCategorytypeList->listSingle($addressItem['type']);
                    //$addressTable->startRow();
                    $addressTable->addCell($addressNo . ")&nbsp;&nbsp;&nbsp;" . $cattype[0]['type'], "", NULL, NULL, NULL, '');
                    $addressTable->addCell($addressItem['street_no'], "", NULL, NULL, NULL, '');
                    $addressTable->addCell($addressItem['street_name'], "", NULL, NULL, NULL, '');
                    $addressTable->addCell($addressItem['locality'], "", NULL, NULL, NULL, '');
                    $addressTable->addCell($addressItem['city'], "", NULL, NULL, NULL, '');
                    $addressTable->addCell($addressItem['postcode'], "", NULL, NULL, NULL, '');
                    $addressTable->addCell($addressItem['postal_address'], "", NULL, NULL, NULL, '');
                    $addressTable->endRow();
                    $addressNo = $addressNo+1;
                }
                unset($addressItem);
            } else {
                $addressTable->startRow();
                $addressTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="7"');
                $addressTable->endRow();
            }
            $addressLabel = '<br></br>' . $addressTable->show() . '<br></br>';
            return $addressLabel;
        } //end if
        
    } //end function
    public function getContacts($userId) 
    {
        //$objLanguage =& $this->getObject('language','language');
        // Show the heading
        $objcontactTitles = &$this->getObject('htmlheading', 'htmlelements');
        $objcontactTitles->type = 2;
        $objcontactTitles->str = $this->objLanguage->languageText("mod_eportfolio_wordContact", 'eportfolio');
        $contactList = $this->objDbContactList->getByItem($userId);
        if (!empty($contactList)) {
            //$emailList = $this->objDbEmailList->getByItem($userId);
            // Create a table object
            $contactTable = &$this->newObject("htmltable", "htmlelements");
            $contactTable->border = 0;
            $contactTable->cellspacing = '3';
            $contactTable->width = "100%";
            // Add the table heading.
            $contactTable->startRow();
            $contactTable->addCell($objcontactTitles->show() , '', '', '', '', 'colspan="5"');
            $contactTable->endRow();
            $contactTable->startRow();
            $contactTable->addCell("<b>&nbsp;&nbsp;&nbsp;" . $this->objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
            $contactTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_contacttype", 'eportfolio') . "</b>");
            $contactTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_countrycode", 'eportfolio') . "</b>");
            $contactTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_areacode", 'eportfolio') . "</b>");
            $contactTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_contactnumber", 'eportfolio') . "</b>");
            $contactTable->endRow();
            // Step through the list of contacts
            if (!empty($contactList)) {
                $contactNo = 1;
                foreach($contactList as $contactItem) {
                    // Display each field for contacts
                    $cattype = $this->objDbCategorytypeList->listSingle($contactItem['type']);
                    $modetype = $this->objDbCategorytypeList->listSingle($contactItem['contact_type']);
                    $contactTable->startRow();
                    $contactTable->addCell($contactNo . ')&nbsp;&nbsp;&nbsp;' . $cattype[0]['type'], "", NULL, NULL, NULL, '');
                    $contactTable->addCell($modetype[0]['type'], "", NULL, NULL, NULL, '');
                    $contactTable->addCell($contactItem['country_code'], "", NULL, NULL, NULL, '');
                    $contactTable->addCell($contactItem['area_code'], "", NULL, NULL, NULL, '');
                    $contactTable->addCell($contactItem['id_number'], "", NULL, NULL, NULL, '');
                    $contactTable->endRow();
                    $contactNo = $contactNo+1;
                }
                unset($contactItem);
            } else {
                $contactTable->startRow();
                $contactTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="5"');
                $contactTable->endRow();
            }
            //echo $contactTable->show();
            $contactTable->startRow();
            $contactTable->addCell('', '', '', '', 'noRecordsMessage', 'colspan="5"');
            $contactTable->endRow();
            $contacts = $contactTable->show() . '<br></br>';
            return $contacts;
        } //end if
        
    } //end function
    public function getEmail($userId) 
    {
        // Create a heading for emails
        //$objLanguage =& $this->getObject('language','language');
        $emailList = $this->objDbEmailList->getByItem($userId);
        if (!empty($emailList)) {
            $emailobjHeading = &$this->getObject('htmlheading', 'htmlelements');
            $emailobjHeading->type = 2;
            $emailobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordEmail", 'eportfolio');
            //echo $emailobjHeading->show();
            // Create a table object for emails
            $emailTable = &$this->newObject("htmltable", "htmlelements");
            $emailTable->border = 0;
            $emailTable->cellspacing = '3';
            $emailTable->width = "25%";
            // Add the table heading.
            $emailTable->startRow();
            $emailTable->addCell($emailobjHeading->show() , '', '', '', '', 'colspan="2"');
            $emailTable->endRow();
            $emailTable->startRow();
            $emailTable->addCell("<b>&nbsp;&nbsp;&nbsp;" . $this->objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
            $emailTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_email", 'eportfolio') . "</b>");
            $emailTable->endRow();
            // Step through the list of emails.
            $class = 'even';
            if (!empty($emailList)) {
                $emailNo = 1;
                foreach($emailList as $emailItem) {
                    // Display each field for emails
                    $cattype = $this->objDbCategorytypeList->listSingle($emailItem['type']);
                    $emailTable->startRow();
                    $emailTable->addCell($emailNo . ')&nbsp;&nbsp;&nbsp;' . $cattype[0]['type'], "", NULL, NULL, NULL, '');
                    $emailTable->addCell($emailItem['email'], "", NULL, NULL, NULL, '');
                    $emailTable->endRow();
                    $emailNo = $emailNo+1;
                }
                unset($emailItem);
            } else {
                $emailTable->startRow();
                $emailTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="2"');
                $emailTable->endRow();
            }
            $emailTable->startRow();
            $emailTable->addCell('', '', '', '', 'noRecordsMessage', 'colspan="2"');
            $emailTable->endRow();
            $emailtbl = $emailTable->show() . '<br></br>';
            return $emailtbl;
        } //end if
        
    } //end function
    public function getDemographics($userId) 
    {
        //Demographics view
        //$objLanguage =& $this->getObject('language','language');
        $demographicsobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $demographicsobjHeading->type = 2;
        $demographicsList = $this->objDbDemographicsList->getByItem($userId);
        if (!empty($demographicsList)) {
            // Create a table object
            $demographicsTable = &$this->newObject("htmltable", "htmlelements");
            $demographicsTable->border = 0;
            $demographicsTable->cellspacing = '3';
            $demographicsTable->width = "50%";
            // Show the heading
            $demographicsobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_demographics", 'eportfolio');
            $demographicsTable->startRow();
            $demographicsTable->addCell($demographicsobjHeading->show() , '', '', '', '', 'colspan="3"');
            $demographicsTable->endRow();
            $demographicsTable->startRow();
            $demographicsTable->addCell("<b>&nbsp;&nbsp;&nbsp;" . $this->objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
            $demographicsTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_birth", 'eportfolio') . "</b>");
            $demographicsTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_nationality", 'eportfolio') . "</b>");
            $demographicsTable->endRow();
            // Step through the list of Demographics.
            if (!empty($demographicsList)) {
                $demNo = 1;
                foreach($demographicsList as $demographicsItem) {
                    // Display each field for Demographics
                    $cattype = $this->objDbCategorytypeList->listSingle($demographicsItem['type']);
                    $demographicsTable->startRow();
                    $demographicsTable->addCell($demNo . ')&nbsp;&nbsp;&nbsp;' . $cattype[0]['type'], "", NULL, NULL, NULL, '');
                    $demographicsTable->addCell($this->objDate->formatDate($demographicsItem['birth']) , "", NULL, NULL, NULL, '');
                    $demographicsTable->addCell($demographicsItem['nationality'], "", NULL, NULL, NULL, '');
                    $demographicsTable->endRow();
                    $demNo = $demNo+1;
                }
                unset($demographicsItem);
            } else {
                $demographicsTable->startRow();
                $demographicsTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="3"');
                $demographicsTable->endRow();
            }
            $demographicstbl = $demographicsTable->show();
            return $demographicstbl;
        } //end if
        
    } //end function
    public function getActivity($userId) 
    {
        //Language Items
        $notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
        // Show the heading
        $activityobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $activityobjHeading->type = 2;
        $activityobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordActivity", 'eportfolio');
        $activitylist = $this->objDbActivityList->getByItem($userId);
        if (!empty($activitylist)) {
            // Create a table object
            $activityTable = &$this->newObject("htmltable", "htmlelements");
            $activityTable->border = 0;
            $activityTable->cellspacing = '3';
            $activityTable->width = "100%";
            // Add the table heading.
            $activityTable->startRow();
            $activityTable->addCell($activityobjHeading->show() , '', '', '', '', Null);
            $activityTable->endRow();
            // Step through the list of activities
            $class = NULL;
            if (!empty($activitylist)) {
                $i = 0;
                $actyNo = 1;
                foreach($activitylist as $item) {
                    //Get context title
                    $objDbContext = &$this->getObject('dbcontext', 'context');
                    $mycontextRecord = $objDbContext->getContextDetails($item['contextid']);
                    if (!empty($mycontextRecord)) {
                        $mycontextTitle = $mycontextRecord['title'];
                    } else {
                        $mycontextTitle = $item['contextid'];
                    }
                    // Display each field for activities
                    $cattype = $this->objDbCategorytypeList->listSingle($item['type']);
                    $activityTable->startRow();
                    $activityTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_wordActivity", 'eportfolio') . '&nbsp;&nbsp;' . $actyNo . "</b>");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("&nbsp;");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_contexttitle", 'eportfolio') . "</b>");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell($mycontextTitle, "", NULL, NULL, $class, '');
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("&nbsp;");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_activitytype", 'eportfolio') . "</b>");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell($cattype[0]['type'], "", NULL, NULL, $class, '');
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("&nbsp;");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_activitystart", 'eportfolio') . "</b>");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell($this->objDate->formatDate($item['start']) , "", NULL, NULL, $class, '');
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("&nbsp;");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_activityfinish", 'eportfolio') . "</b>");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell($this->objDate->formatDate($item['finish']) , "", NULL, NULL, $class, '');
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("&nbsp;");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("&nbsp;");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_longdescription", 'eportfolio') . "</b>");
                    $activityTable->endRow();
                    $activityTable->startRow();
                    $activityTable->addCell($item['longdescription'], "", NULL, NULL, $class, '');
                    $activityTable->endRow();
                    $actyNo = $actyNo+1;
                }
                unset($item);
            } else {
                $activityTable->startRow();
                $activityTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', Null);
                $activityTable->endRow();
            }
            $activitytbl = $activityTable->show();
            return $activitytbl;
        } //end if
        
    } //end function
    public function getAffiliation($userId) 
    {
        //Language Items
        $notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
        // Show the heading
        $affiliationobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $affiliationobjHeading->type = 2;
        $affiliationobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordAffiliation", 'eportfolio');
        $affiliationList = $this->objDbAffiliationList->getByItem($userId);
        if (!empty($affiliationList)) {
            // Create a table object
            $affiliationTable = &$this->newObject("htmltable", "htmlelements");
            $affiliationTable->border = 0;
            $affiliationTable->cellspacing = '3';
            $affiliationTable->width = "30%";
            // Add the table heading.
            $affiliationTable->startRow();
            //addCell($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
            $affiliationTable->addCell($affiliationobjHeading->show() , '', '', '', '', 'colspan="6"', Null);
            $affiliationTable->endRow();
            $affiliationTable->startRow();
            $affiliationTable->addCell("<b>&nbsp;&nbsp;&nbsp;" . $this->objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
            $affiliationTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_classificationView", 'eportfolio') . "</b>");
            $affiliationTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_roleView", 'eportfolio') . "</b>");
            $affiliationTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_organisation", 'eportfolio') . "</b>");
            $affiliationTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_activitystart", 'eportfolio') . "</b>");
            $affiliationTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_activityfinish", 'eportfolio') . "</b>");
            $affiliationTable->endRow();
            // Step through the list of affiliations
            $class = NULL;
            if (!empty($affiliationList)) {
                $i = 0;
                $affNo = 1;
                foreach($affiliationList as $affiliationItem) {
                    // Display each field for affiliations
                    $cattype = $this->objDbCategorytypeList->listSingle($affiliationItem['type']);
                    $affiliationTable->startRow();
                    $affiliationTable->addCell($affNo . ')&nbsp;&nbsp;&nbsp;' . $cattype[0]['type'], "", NULL, NULL, Null, '');
                    $affiliationTable->addCell($affiliationItem['classification'], "", NULL, NULL, NULL, '');
                    $affiliationTable->addCell($affiliationItem['role'], "", NULL, NULL, NULL, '');
                    $affiliationTable->addCell($affiliationItem['organisation'], "", NULL, NULL, NULL, '');
                    $affiliationTable->addCell($this->objDate->formatDate($affiliationItem['start']) , "", NULL, NULL, NULL, '');
                    $affiliationTable->addCell($this->objDate->formatDate($affiliationItem['finish']) , "", NULL, NULL, NULL, '');
                    $affiliationTable->endRow();
                    $affNo = $affNo+1;
                }
                unset($affiliationItem);
            } else {
                $affiliationTable->startRow();
                $affiliationTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', Null);
                $affiliationTable->endRow();
            }
            $affiliationtbl = $affiliationTable->show();
            return $affiliationtbl;
        } //end if
        
    } //end function
    public function getTranscripts($userId) 
    {
        //Language Items
        $notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
        // Show the heading
        $transcriptobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $transcriptobjHeading->type = 2;
        $transcriptobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordTranscripts", 'eportfolio');
        // echo $transcriptobjHeading->show();
        $transcriptlist = $this->objDbTranscriptList->getByItem($userId);
        if (!empty($transcriptlist)) {
            // Create a table object
            $transcriptTable = &$this->newObject("htmltable", "htmlelements");
            $transcriptTable->border = 0;
            $transcriptTable->cellspacing = '4';
            $transcriptTable->width = "100%";
            // Add the table heading.
            $transcriptTable->startRow();
            $transcriptTable->addCell($transcriptobjHeading->show() , '', '', '', '', Null);
            $transcriptTable->endRow();
            // Step through the list of transcripts.
            $class = NULL;
            if (!empty($transcriptlist)) {
                $transNo = 1;
                foreach($transcriptlist as $item) {
                    // Display each field for transcripts
                    $transcriptTable->startRow();
                    $transcriptTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_wordtranscript", 'eportfolio') . '&nbsp;&nbsp;' . $transNo . "</b>");
                    $transcriptTable->endRow();
                    $transcriptTable->startRow();
                    $transcriptTable->addCell("&nbsp;");
                    $transcriptTable->endRow();
                    $transcriptTable->startRow();
                    $transcriptTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
                    $transcriptTable->endRow();
                    $transcriptTable->startRow();
                    $transcriptTable->addCell("&nbsp;");
                    $transcriptTable->endRow();
                    $transcriptTable->startRow();
                    $transcriptTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
                    $transcriptTable->endRow();
                    $transcriptTable->startRow();
                    $transcriptTable->addCell("&nbsp;");
                    $transcriptTable->endRow();
                    $transcriptTable->startRow();
                    $transcriptTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_longdescription", 'eportfolio') . "</b>");
                    $transcriptTable->endRow();
                    $transcriptTable->startRow();
                    $transcriptTable->addCell("&nbsp;");
                    $transcriptTable->endRow();
                    $transcriptTable->startRow();
                    $transcriptTable->addCell($item['longdescription'], "", NULL, NULL, $class, '');
                    $transcriptTable->endRow();
                    $transNo = $transNo+1;
                }
                unset($item);
            } else {
                $transcriptTable->startRow();
                $transcriptTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', Null);
                $transcriptTable->endRow();
            }
            $transcripttbl = $transcriptTable->show();
            return $transcripttbl;
        } //end if
        
    } //end function
    public function getQualification($userId) 
    {
        //Language Items
        $notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
        // Show the heading
        $qclobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $qclobjHeading->type = 2;
        $qclobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordQualification", 'eportfolio');
        //echo $qclobjHeading->show();
        $qclList = $this->objDbQclList->getByItem($userId);
        if (!empty($qclList)) {
            // Create a table object
            $qclTable = &$this->newObject("htmltable", "htmlelements");
            $qclTable->border = 0;
            $qclTable->cellspacing = '3';
            $qclTable->width = "100%";
            // Add the table heading.
            $qclTable->startRow();
            $qclTable->addCell($qclobjHeading->show() , '', '', '', '', Null);
            $qclTable->endRow();
            // Step through the list of qcl.
            $class = NULL;
            if (!empty($qclList)) {
                $qclNo = 1;
                foreach($qclList as $qclItem) {
                    // Display each field for qcl
                    $cattype = $this->objDbCategorytypeList->listSingle($qclItem['qcl_type']);
                    $qclTable->startRow();
                    $qclTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_wordQualification", 'eportfolio') . "&nbsp;&nbsp;" . $qclNo . "</b>");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell($cattype[0]['type'], "", NULL, NULL, $class, '');
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_wordtitle", 'eportfolio') . "</b>");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell($qclItem['qcl_title'], "", NULL, NULL, $class, '');
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_organisation", 'eportfolio') . "</b>");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell($qclItem['organisation'], "", NULL, NULL, $class, '');
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_level", 'eportfolio') . "</b>");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell($qclItem['qcl_level'], "", NULL, NULL, $class, '');
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_qclawarddate", 'eportfolio') . "</b>");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell($this->objDate->formatDate($qclItem['award_date']) , "", NULL, NULL, $class, '');
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("&nbsp;");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell($qclItem['shortdescription'], "", NULL, NULL, $class, '');
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_longdescription", 'eportfolio') . "</b>");
                    $qclTable->endRow();
                    $qclTable->startRow();
                    $qclTable->addCell($qclItem['longdescription'], "", NULL, NULL, $class, '');
                    $qclTable->endRow();
                    $qclNo = $qclNo+1;
                }
                unset($qclItem);
            } else {
                $qclTable->startRow();
                $qclTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', Null);
                $qclTable->endRow();
            }
            $qcltbl = $qclTable->show();
            return $qcltbl;
        } //end if
        
    } //end function
    public function getGoals($userId) 
    {
        //Language Items
        $notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
        // Show the heading
        $goalsobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $goalsobjHeading->type = 2;
        $goalsobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordGoals", 'eportfolio');
        $goalsList = $this->objDbGoalsList->getByItem($userId);
        if (!empty($goalsList)) {
            // Create a table object
            $goalsTable = &$this->newObject("htmltable", "htmlelements");
            $goalsTable->border = 0;
            $goalsTable->cellspacing = '12';
            $goalsTable->width = "60%";
            // Add the table heading.
            $goalsTable->startRow();
            $goalsTable->addCell($goalsobjHeading->show() , '', '', '', '', Null);
            $goalsTable->endRow();
            // Step through the list of goals.
            $class = NULL;
            if (!empty($goalsList)) {
                $i = 0;
                $goalNo = 1;
                foreach($goalsList as $item) {
                    // Display each field for goals
                    $goalsTable->startRow();
                    $goalsTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_wordGoal", 'eportfolio') . "&nbsp;" . $goalNo . "</b>");
                    $goalsTable->endRow();
                    $goalsTable->startRow();
                    $goalsTable->addCell("&nbsp;");
                    $goalsTable->endRow();
                    $goalsTable->startRow();
                    $goalsTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
                    $goalsTable->endRow();
                    $goalsTable->startRow();
                    $goalsTable->addCell("&nbsp;");
                    $goalsTable->endRow();
                    $goalsTable->startRow();
                    $goalsTable->addCell("<li>" . $item['shortdescription'] . "</li>", "", NULL, NULL, $class, '');
                    $goalsTable->endRow();
                    $goalsTable->startRow();
                    $goalsTable->addCell("&nbsp;");
                    $goalsTable->endRow();
                    $goalsTable->startRow();
                    $goalsTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_longdescription", 'eportfolio') . "</b>");
                    $goalsTable->endRow();
                    $goalsTable->startRow();
                    $goalsTable->addCell("&nbsp;");
                    $goalsTable->endRow();
                    $goalsTable->startRow();
                    $goalsTable->addCell("<li>" . $item['longdescription'] . "</li>", "", NULL, NULL, $class, '');
                    $goalsTable->endRow();
                    $goalNo = $goalNo+1;
                }
                unset($item);
            } else {
                $goalsTable->startRow();
                $goalsTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', Null);
                $goalsTable->endRow();
            }
            $goalstbl = $goalsTable->show();
            return $goalstbl;
        } //end if
        
    } //end function
    public function getCompetency($userId) 
    {
        // Show the heading
        $competencyobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $competencyobjHeading->type = 2;
        $competencyobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordCompetency", 'eportfolio');
        $competencyList = $this->objDbCompetencyList->getByItem($userId);
        if (!empty($competencyList)) {
            // Create a table object
            $competencyTable = &$this->newObject("htmltable", "htmlelements");
            $competencyTable->border = 0;
            $competencyTable->cellspacing = '12';
            $competencyTable->width = "100%";
            // Add the table heading.
            $competencyTable->startRow();
            $competencyTable->addCell($competencyobjHeading->show() , '', '', '', '', NULL);
            $competencyTable->endRow();
            // Step through the list of competencies.
            $class = NULL;
            if (!empty($competencyList)) {
                $compNo = 1;
                foreach($competencyList as $item) {
                    // Display each field for competencies
                    $cattype = $this->objDbCategorytypeList->listSingle($item['type']);
                    $competencyTable->startRow();
                    $competencyTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_wordCompetency", 'eportfolio') . "&nbsp;&nbsp;" . $compNo . "</b>");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("&nbsp;&nbsp;");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("&nbsp;&nbsp;");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell($cattype[0]['type'], "", NULL, NULL, $class, '');
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("&nbsp;&nbsp;");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_qclawarddate", 'eportfolio') . "</b>");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("&nbsp;&nbsp;");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell($this->objDate->formatDate($item['award_date']) , "", NULL, NULL, $class, '');
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("&nbsp;&nbsp;");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("&nbsp;&nbsp;");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("&nbsp;&nbsp;");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_longdescription", 'eportfolio') . "</b>");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell("&nbsp;&nbsp;");
                    $competencyTable->endRow();
                    $competencyTable->startRow();
                    $competencyTable->addCell($item['longdescription'], "", NULL, NULL, $class, '');
                    $competencyTable->endRow();
                    $compNo = $compNo+1;
                }
                unset($item);
                $competencytbl = $competencyTable->show();
                return $competencytbl;
            }
        } //end if
        
    } //end function
    public function getInterests($userId) 
    {
        // Show the heading
        $interestobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $interestobjHeading->type = 2;
        $interestobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordInterests", 'eportfolio');
        //echo $interestobjHeading->show();
        $interestList = $this->objDbInterestList->getByItem($userId);
        if (!empty($interestList)) {
            // Create a table object
            $interestTable = &$this->newObject("htmltable", "htmlelements");
            $interestTable->border = 0;
            $interestTable->cellspacing = '12';
            $interestTable->width = "100%";
            // Add the table heading.
            $interestTable->startRow();
            $interestTable->addCell($interestobjHeading->show() , '', '', '', '', Null);
            $interestTable->endRow();
            // Step through the list of interests.
            $class = NULL;
            if (!empty($interestList)) {
                $intNo = 1;
                foreach($interestList as $item) {
                    // Display each field for interests
                    $cattype = $this->objDbCategorytypeList->listSingle($item['type']);
                    $interestTable->startRow();
                    $interestTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_wordInterest", 'eportfolio') . "&nbsp;&nbsp;" . $intNo . "</b>");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("&nbsp;");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_contypes", 'eportfolio') . "</b>");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("&nbsp;");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell($cattype[0]['type'], "", NULL, NULL, $class, '');
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("&nbsp;");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_creationDate", 'eportfolio') . "</b>");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("&nbsp;");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell($this->objDate->formatDate($item['creation_date']) , "", NULL, NULL, $class, '');
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("&nbsp;");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("&nbsp;");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("&nbsp;");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("&nbsp;");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_longdescription", 'eportfolio') . "</b>");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell("&nbsp;");
                    $interestTable->endRow();
                    $interestTable->startRow();
                    $interestTable->addCell($item['longdescription'], "", NULL, NULL, $class, '');
                    $interestTable->endRow();
                    $intNo = $intNo+1;
                }
                unset($item);
                $interesttbl = $interestTable->show();
                return $interesttbl;
            }
        } //end if
        
    } //end function
    public function getReflections($userId) 
    {
        // Show the heading
        $reflectionobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $reflectionobjHeading->type = 2;
        $reflectionobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordReflections", 'eportfolio');
        //echo $reflectionobjHeading->show();
        $reflectionList = $this->objDbReflectionList->getByItem($userId);
        if (!empty($reflectionList)) {
            // Create a table object
            $reflectionTable = &$this->newObject("htmltable", "htmlelements");
            $reflectionTable->border = 0;
            $reflectionTable->cellspacing = '3';
            $reflectionTable->width = "100%";
            // Add the table heading.
            $reflectionTable->startRow();
            $reflectionTable->addCell($reflectionobjHeading->show() , '', '', '', '', Null);
            $reflectionTable->endRow();
            // Step through the list of reflections.
            $class = NULL;
            if (!empty($reflectionList)) {
                $refNo = 1;
                foreach($reflectionList as $item) {
                    // Display each field for reflections
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_wordReflection", 'eportfolio') . "&nbsp;&nbsp;" . $refNo . "</b>");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("&nbsp;");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_rationaleTitle", 'eportfolio') . "</b>");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("&nbsp;");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell($item['rationale'], "", NULL, NULL, $class, '');
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("&nbsp;");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_creationDate", 'eportfolio') . "</b>");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("&nbsp;");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell($this->objDate->formatDate($item['creation_date']) , "", NULL, NULL, $class, '');
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("&nbsp;");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . "</b>");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("&nbsp;");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("&nbsp;");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_longdescription", 'eportfolio') . "</b>");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell("&nbsp;");
                    $reflectionTable->endRow();
                    $reflectionTable->startRow();
                    $reflectionTable->addCell($item['longdescription'], "", NULL, NULL, $class, '');
                    $reflectionTable->endRow();
                    $refNo = $refNo+1;
                }
                unset($item);
            }
            $reflectiontbl = $reflectionTable->show();
            return $reflectiontbl;
        } //end if
        
    } //end function
    public function getAssertions($userPid) 
    {
        // Show the heading
        $assertionsobjHeading = &$this->getObject('htmlheading', 'htmlelements');
        $assertionsobjHeading->type = 2;
        $assertionsobjHeading->str = $this->objLanguage->languageText("mod_eportfolio_wordAssertion", 'eportfolio');
        $Id = $this->_objGroupAdmin->getUserGroups($userPid);
        if (!empty($Id)) {
            // Create a table object
            $assertionstable = &$this->newObject("htmltable", "htmlelements");
            $assertionstable->border = 0;
            $assertionstable->cellspacing = '3';
            $assertionstable->width = "100%";
            // Add the table heading.
            $assertionstable->startRow();
            $assertionstable->addCell($assertionsobjHeading->show() , '', '', '', '', Null);
            $assertionstable->endRow();
            // Step through the list of assertions.
            $class = NULL;
            if (!empty($Id)) {
                //$assertNo = 1;
                foreach($Id as $groupId) {
                    //Get the group parent_id
                    $parentId = $this->_objGroupAdmin->getParent($groupId);
                    $newParentId = array_unique($parentId);
                    foreach($newParentId as $myparentId) {
                        //Get the name from group table
                        $assertionId = $this->_objGroupAdmin->getName($myparentId['parent_id']);
                        $assertionslist = $this->objDbAssertionList->listSingle($assertionId);
                        if (!empty($assertionslist)) {
                            // Display each field for assertions
                            $assertionstable->startRow();
                            $assertionstable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_assertion", 'eportfolio') . "&nbsp;&nbsp;" . "</b>");
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("&nbsp;", "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_lecturer", 'eportfolio') . ":</b>");
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell($this->objUser->fullName($assertionslist[0]['userid']) , "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("&nbsp;", "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_rationaleTitle", 'eportfolio') . ":</b>");
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell($assertionslist[0]['rationale'], "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("&nbsp;", "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_creationDate", 'eportfolio') . ":</b>");
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell($this->objDate->formatDate($assertionslist[0]['creation_date']) , "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("&nbsp;", "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . ":</b>");
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell($assertionslist[0]['shortdescription'], "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("&nbsp;", "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell("<b>" . $this->objLanguage->languageText("mod_eportfolio_longdescription", 'eportfolio') . ":</b>");
                            $assertionstable->endRow();
                            $assertionstable->startRow();
                            $assertionstable->addCell($assertionslist[0]['longdescription'], "", NULL, NULL, $class, '');
                            $assertionstable->endRow();
                        }
                        unset($myparentId);
                    }
                    //$assertNo = $assertNo + 1;
                    unset($groupId);
                }
            }
            $assertionstbl = $assertionstable->show();
            return $assertionstbl;
        } //end if
        
    } //end function
    
}
?>
