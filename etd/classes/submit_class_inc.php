<?php
/**
* submit class extends object
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Class for students submissions to the archive
* Functions allow for new submissions, pending submissions, copyright acceptance, document uploading
*
* @author Megan Watson
* @copyright (c) 2006 University of the Western Cape
* @version 0.1
*/

class submit extends object
{
    /**
    * Constructor for the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        $this->dbSubmissions =& $this->getObject('dbsubmissions', 'etd');
        $this->dbSubmissions->setDocType('thesis');
        $this->dbSubmissions->setSubmitType('etd');

        $this->dbThesis =& $this->getObject('dbthesis', 'etd');
        $this->dbThesis->setSubmitType('etd');

        $this->etdTools =& $this->getObject('etdtools', 'etd');
        $this->dbCopyright =& $this->getObject('dbcopyright', 'etd');
        $this->dbDublinCore =& $this->getObject('dbdublincore', 'etd');
        $this->xmlMetadata =& $this->getObject('xmlmetadata', 'etd');
        $this->etdFiles =& $this->getObject('etdfiles', 'etd');
        $this->dbFiles =& $this->getObject('dbfiles', 'etd');

        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objDate =& $this->getObject('datetime', 'utilities');

        $this->objHead =& $this->newObject('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->objEditor = $this->newObject('htmlarea', 'htmlelements');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');

        $this->loadClass('tabbedbox', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->userId = $this->objUser->userId();
        $this->fullName = $this->objUser->fullName();
        $this->firstName = $this->objUser->getFirstname($this->userId);
        $this->surname = $this->objUser->getSurname($this->userId);
    }

    /**
    * Method to display a list of recent submissions requiring approval. And a link for submitting a new ETD.
    *
    * @access private
    * @param array $data The submissions list
    * @return string html
    */
    private function showSubmissions($data)
    {
        $head = $this->objLanguage->languageText('phrase_newsubmissions');
        $lnSubmit = $this->objLanguage->languageText('mod_etd_submitnewresource', 'etd');
        $lbNone = $this->objLanguage->languageText('mod_etd_nonewresources', 'etd');

        $this->objHead->str = $head;
        $this->objHead->type = 1;
        $str = $this->objHead->show();

        // table containing submissions requiring approval / metadata
        $objTable = new htmltable();
        $objTable->cellpadding = '5';
        $objTable->cellspacing = '2';

        if(!empty($data)){
        }else{
            $objTable->addRow(array($lbNone), 'noRecordsMessage');
        }

        $str .= $objTable->show();

        // link to add a new submission
        $objLink = new link($this->uri(array('action' => 'submit', 'mode' => 'addsubmission')));
        $objLink->link = $lnSubmit;
        $str .= '<p>'.$objLink->show().'</p>';

        return $str;
    }

    /**
    * Method to display a resource for editing.
    *
    * @access private
    * @param array $data The resource data
    * @return string html
    */
    private function editResource($data)
    {
        if(!empty($data)){
            $head = $this->objLanguage->languageText('phrase_editresource');
        }else{
            $head = $this->objLanguage->languageText('phrase_newresource');
        }
        $lbMetaData = $this->objLanguage->languageText('word_metadata');
        $lbTitle = $this->objLanguage->languageText('phrase_documenttitle');
        $lbAuthorFirst = $this->objLanguage->languageText('phrase_authorfirstname');
        $lbAuthorSurname = $this->objLanguage->languageText('phrase_authorsurname');
        $lbNumber = $this->objLanguage->languageText('phrase_studentnumber');
        $lbCitation = $this->objLanguage->languageText('word_citation');
        $lbSummary = $this->objLanguage->languageText('word_abstract');
        $lbCountry = $this->objLanguage->languageText('word_country');
        $lbDepartment = $this->objLanguage->languageText('word_department');
        $lbDegree = $this->objLanguage->languageText('phrase_degreeobtained');
        $lbDocType = $this->objLanguage->languageText('phrase_documenttype');
        $lbSource = $this->objLanguage->languageText('word_source');
        $lbDateAdded = $this->objLanguage->languageText('phrase_dateadded');
        $lbContributor = $this->objLanguage->languageText('word_contributor');
        $lbPublisher = $this->objLanguage->languageText('word_publisher');
        $lbDate = $this->objLanguage->languageText('word_year');
        $lbFormat = $this->objLanguage->languageText('word_format');
        $lbRights = $this->objLanguage->languageText('word_rights');
        $lbRelationship = $this->objLanguage->languageText('word_relationship');
        $lbLanguage = $this->objLanguage->languageText('word_language');
        $lbAudience = $this->objLanguage->languageText('word_audience');
        $lbKeywords = $this->objLanguage->languageText('word_keywords');
        $btnSave = $this->objLanguage->languageText('word_save');
        $btnCancel = $this->objLanguage->languageText('word_cancel');

        $this->objHead->str = $head;
        $this->objHead->type = 1;
        $str = $this->objHead->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '5';
        $objTable->cellspacing = '5';

        $author = $this->surname.', '.$this->firstName;
        if(!empty($data['dc_creator'])){
            $author = $data['dc_creator'];
        }
        $objInput = new textinput('author', $author, 'hidden');


        $objTable->addRow(array($lbAuthorFirst.': ', $this->firstName));
        $objTable->addRow(array($lbAuthorSurname.': ', $this->surname.$objInput->show()));
        $objTable->addRow(array($lbNumber.': ', $this->userId));

        $department = '';
        if(!empty($data['thesis_degree_discipline'])){
            $department = $data['thesis_degree_discipline'];
        }
        $objLabel = new label($lbDepartment.': ', 'input_department');
        $objInput = new textinput('department', $department, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $degree = '';
        if(!empty($data['thesis_degree_name'])){
            $degree = $data['thesis_degree_name'];
        }
        $objLabel = new label($lbDegree.': ', 'input_degree');
        $objInput = new textinput('degree', $degree, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $title = '';
        if(!empty($data['dc_title'])){
            $title = $data['dc_title'];
        }
        $objLabel = new label($lbTitle.': ', 'input_title');
        $objInput = new textinput('title', $title, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));


        $date = '';
        if(!empty($data['dc_date'])){
            $date = $data['dc_date'];
        }
        $objLabel = new label($lbDate.': ', 'input_date');
        $year = $this->etdTools->getYearSelect('date', $date);

        $objTable->addRow(array($objLabel->show(), $year));

        $country = '';
        if(!empty($data['dc_coverage'])){
            $country = $data['dc_coverage'];
        }
        $objLabel = new label($lbCountry.': ', 'input_country');
        $objTable->addRow(array($objLabel->show(), $this->etdTools->getCountriesDropdown($country)));

        $keywords = '';
        if(!empty($data['dc_subject'])){
            $keywords = $data['dc_subject'];
        }
        $objLabel = new label($lbKeywords.': ', 'input_keywords');
        $objText = new textarea('keywords', $keywords, 3, 58);

        $objTable->addRow(array($objLabel->show(), $objText->show()));

        $type = '';
        if(!empty($data['dc_type'])){
            $type = $data['dc_type'];
        }
        $objLabel = new label($lbDocType.': ', 'input_type');
        $objInput = new textinput('type', $type, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $contributor = '';
        if(!empty($data['dc_contributor'])){
            $contributor = $data['dc_contributor'];
        }
        $objLabel = new label($lbContributor.': ', 'input_contributor');
        $objInput = new textinput('contributor', $contributor, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $relationship = '';
        if(!empty($data['dc_relationship'])){
            $relationship = $data['dc_relationship'];
        }
        $objLabel = new label($lbRelationship.': ', 'input_relationship');
        $objInput = new textinput('relationship', $relationship, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $publisher = '';
        if(!empty($data['dc_publisher'])){
            $publisher = $data['dc_publisher'];
        }
        $objLabel = new label($lbPublisher.': ', 'input_publisher');
        $objInput = new textinput('publisher', $publisher, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $rights = '';
        if(!empty($data['dc_rights'])){
            $rights = $data['dc_rights'];
        }
        $objLabel = new label($lbRights.': ', 'input_rights');
        $objInput = new textinput('rights', $rights, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $format = '';
        if(!empty($data['dc_format'])){
            $format = $data['dc_format'];
        }
        $objLabel = new label($lbFormat.': ', 'input_format');
        $objInput = new textinput('format', $format, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $source = '';
        if(!empty($data['dc_source'])){
            $source = $data['dc_source'];
        }
        $objLabel = new label($lbSource.': ', 'input_source');
        $objInput = new textinput('source', $source, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $language = '';
        if(!empty($data['dc_language'])){
            $language = $data['dc_language'];
        }
        $objLabel = new label($lbLanguage.': ', 'input_language');
        $objInput = new textinput('language', $language, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $audience = '';
        if(!empty($data['dc_audience'])){
            $audience = $data['dc_audience'];
        }
        $objLabel = new label($lbAudience.': ', 'input_audience');
        $objInput = new textinput('audience', $audience, '', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $abstract = '';
        if(!empty($data['dc_description'])){
            $abstract = $data['dc_description'];
        }
        $this->objEditor->init('abstract', $abstract, '400px', '700px');
        $this->objEditor->setBasicToolBar();


        // Display the metadata in tabbed boxes
        $objTab = new tabbedbox();
//        $objTab->extra = 'style="background-color: #FFFDF5"';
        $objTab->cssClass = 'wrapperLightBkg';
        $objTab->addTabLabel($lbMetaData);
        $objTab->addBoxContent('<div style="padding: 10px;">'.$objTable->show().'</div>');
        $formStr = $objTab->show();

        $objTab = new tabbedbox();
//        $objTab->extra = 'style="background-color: #FFFDF5"';
        $objTab->cssClass = 'wrapperLightBkg';
        $objTab->addTabLabel($lbSummary);
        $objTab->addBoxContent('<div style="padding: 10px;">'.$this->objEditor->showFCKEditor().'</div>');
        $formStr .= $objTab->show();

        $objButton = new button('save', $btnSave);
        $objButton->setToSubmit();
        $formStr .= '<p>'.$objButton->show().'&nbsp;&nbsp;&nbsp;&nbsp;';

        $objButton = new button('cancel', $btnCancel);
        $objButton->setToSubmit();
        $formStr .= $objButton->show().'</p>';

        // hidden id fields
        $hidden = '';
        if(!empty($data['dcid'])){
            $objInput = new textinput('dcMetaId', $data['dcid'], 'hidden');
            $hidden .= $objInput->show();
        }
        if(!empty($data['metaid'])){
            $objInput = new textinput('thesisId', $data['metaid'], 'hidden');
            $hidden .= $objInput->show();
        }

        // Add to a form
        $objForm = new form('editresource', $this->uri(array('action' => 'savesubmit', 'mode' => 'saveresource', 'nextmode' => 'showresource')));
        $objForm->addToForm($formStr);
        $objForm->addToForm($hidden);
        $str .= $objForm->show();

        return $str;
    }

    /**
    * Method to save the new / updated metadata
    *
    * @access private
    * @param string $submitId The submission id
    * @return
    */
    private function saveResource($submitId = NULL)
    {
        // Update the submissions table to show who modified it and when
        $submitId = $this->dbSubmissions->editSubmission($this->userId, $submitId);

        // Save the dublincore metadata
        $dublin = array();
        $dublin['dc_title'] = $this->getParam('title');
        $dublin['dc_creator'] = $this->getParam('author');
        $dublin['dc_date'] = $this->getParam('date');
        $dublin['dc_type'] = $this->getParam('type');
        $dublin['dc_coverage'] = $this->getParam('country');
        $dublin['dc_source'] = $this->getParam('source');
        $dublin['dc_contributor'] = $this->getParam('contributor');
        $dublin['dc_publisher'] = $this->getParam('publisher');
        $dublin['dc_format'] = $this->getParam('format');
        $dublin['dc_relationship'] = $this->getParam('relationship');
        $dublin['dc_language'] = $this->getParam('language');
        $dublin['dc_audience'] = $this->getParam('audience');
        $dublin['dc_rights'] = $this->getParam('rights');
        $dublin['dc_subject'] = $this->getParam('keywords');
        $dublin['dc_description'] = $this->getParam('abstract');

        // Save the extended thesis metadata
        $thesis = array();
        $thesis['thesis_degree_name'] = $this->getParam('degree');
        $thesis['thesis_degree_level'] = $this->getParam('thesis_degree_level');
        $thesis['thesis_degree_discipline'] = $this->getParam('department');
        $thesis['thesis_degree_grantor'] = $this->getParam('thesis_degree_grantor');

        $extra = array();
        $extra['submitid'] = $submitId;

        $data['metadata']['dublincore'] = $dublin;
        $data['metadata']['thesis'] = $thesis;
        $data['metadata']['extra'] = $extra;

        $file = 'etd_'.$submitId;
        $this->xmlMetadata->saveToXml($data, $file);
        return $submitId;
    }

    /**
    * Method to delete a resource
    *
    * @access private
    * @param string $submitId The submissions table id
    * @return
    */
    private function deleteResource($submitId)
    {
        // delete metadata in xml file
        $this->xmlMetadata->deleteXML('etd_'.$submitId);

        // delete submission
        $this->dbSubmissions->deleteSubmission($submitId);

        return TRUE;
    }

    /**
    * Method to display a resource.
    *
    * @access private
    * @param array $data The resource data
    * @return string html
    */
    private function showResource($data)
    {
        $submitId = $this->getSession('submitId');

        $head = $this->objLanguage->languageText('word_resource');
        $lbMetaData = $this->objLanguage->languageText('word_metadata');
        $lbTitle = $this->objLanguage->languageText('phrase_documenttitle');
        $lbDocument = $this->objLanguage->languageText('word_document');
        $lbAuthor = $this->objLanguage->languageText('word_authors');
        $lbCitation = $this->objLanguage->languageText('word_citation');
        $lbSummary = $this->objLanguage->languageText('word_abstract');
        $lbCountry = $this->objLanguage->languageText('word_country');
        $lbDocType = $this->objLanguage->languageText('phrase_documenttype');
        $lbSource = $this->objLanguage->languageText('word_source');
        $lbDateAdded = $this->objLanguage->languageText('phrase_dateadded');
        $lbContributor = $this->objLanguage->languageText('word_contributor');
        $lbPublisher = $this->objLanguage->languageText('word_publisher');
        $lbDate = $this->objLanguage->languageText('word_year');
        $lbFormat = $this->objLanguage->languageText('word_format');
        $lbRights = $this->objLanguage->languageText('word_rights');
        $lbRelationship = $this->objLanguage->languageText('word_relationship');
        $lbLanguage = $this->objLanguage->languageText('word_language');
        $lbAudience = $this->objLanguage->languageText('word_audience');
        $lbKeywords = $this->objLanguage->languageText('word_keywords');
        $confirmDel = $this->objLanguage->languageText('mod_etd_confirmdeleteresource', 'etd');
        $lbEmbargo = $this->objLanguage->languageText('phrase_requestembargo');
        $lbSubmit = $this->objLanguage->languageText('word_submit');

        $icons = '&nbsp;&nbsp;';
        $icons .= $this->objIcon->getEditIcon($this->uri(array('action' => 'submit', 'mode' => 'editresource')));
        $icons .= $this->objIcon->getDeleteIconWithConfirm('', array('action' => 'savesubmit', 'mode' => 'deleteresource', 'nextmode' => '', 'save' => 'true'),  'etd', $confirmDel);

        $this->objHead->str = $head.$icons;
        $this->objHead->type = 1;
        $str = $this->objHead->show();

        if(!empty($data)){
            $objTable = new htmltable();
            $objTable->cellpadding = 2;
            $objTable->cellspacing = 2;

            $objTable->addRow(array($lbTitle.': ', $data['dc_title']));
            $objTable->addRow(array($lbAuthor.': ', $data['dc_creator']));
            $objTable->addRow(array($lbDate.': ', $data['dc_date']));
            $objTable->addRow(array($lbDocType.': ', $data['dc_type']));
            $objTable->addRow(array($lbCountry.': ', $data['dc_coverage']));
            $objTable->startRow();
            $objTable->addCell($lbKeywords.': ', '20%');
            $objTable->addCell($data['dc_subject']);
            $objTable->endRow();
            $objTable->addRow(array($lbContributor.': ', $data['dc_contributor']));
            $objTable->addRow(array($lbRelationship.': ', $data['dc_relationship']));
            $objTable->addRow(array($lbPublisher.': ', $data['dc_publisher']));
            $objTable->addRow(array($lbRights.': ', $data['dc_rights']));
            $objTable->addRow(array($lbFormat.': ', $data['dc_format']));
            $objTable->addRow(array($lbSource.': ', $data['dc_source']));
            $objTable->addRow(array($lbLanguage.': ', $data['dc_language']));
            $objTable->addRow(array($lbAudience.': ', $data['dc_audience']));

            // Display the metadata in tabbed boxes
            $objTab = new tabbedbox();
//            $objTab->extra = 'style="background-color: #FFFDF5; padding:5px;"';
            $objTab->cssClass = 'wrapperLightBkg';
            $objTab->addTabLabel($lbMetaData);
            $objTab->addBoxContent($objTable->show());
            $str .= $objTab->show();

            $objTab = new tabbedbox();
//            $objTab->extra = 'style="background-color: #FFFDF5; padding:5px;"';
            $objTab->cssClass = 'wrapperLightBkg';
            $objTab->addTabLabel($lbSummary);
            $objTab->addBoxContent($data['dc_description']);
            $str .= $objTab->show();
        }

        // Display the attached document for download or replacement
        $docStr = $this->showDocument();
        $objTab = new tabbedbox();
//        $objTab->extra = 'style="background-color: #FFFDF5; padding:5px;"';
        $objTab->cssClass = 'wrapperLightBkg';
        $objTab->addTabLabel($lbDocument);
        $objTab->addBoxContent($docStr);
        $str .= $objTab->show();

        // Display the embargo request
        $embargoStr = $this->showEmbargo();
        $objTab = new tabbedbox();
//        $objTab->extra = 'style="background-color: #FFFDF5; padding:5px;"';
        $objTab->cssClass = 'wrapperLightBkg';
        $objTab->addTabLabel($lbEmbargo);
        $objTab->addBoxContent($embargoStr);
        $str .= $objTab->show();

        // Display the form for submitting to supervisor for approval
        $submitStr = $this->showSubmit();
        $objTab = new tabbedbox();
//        $objTab->extra = 'style="background-color: #FFFDF5; padding:5px;"';
        $objTab->cssClass = 'wrapperLightBkg';
        $objTab->addTabLabel($lbSubmit);
        $objTab->addBoxContent($submitStr);
        $str .= $objTab->show();

        return $str.'<br />';
    }

    /**
    * Method to get the attached document for viewing and updating.
    *
    * @access private
    * @return string html
    */
    private function showDocument()
    {
        $submitId = $this->getSession('submitId');
        $data = array();//$this->dbFiles->getFiles($submitId);
        $hidden = '';

        $lbFileSize = $this->objLanguage->languageText('phrase_filesize');
        $lbFileName = $this->objLanguage->languageText('phrase_documentname');
        $lbDate = $this->objLanguage->languageText('phrase_datelastmodified');
        $lbType = $this->objLanguage->languageText('phrase_documenttype');
        $lbDownload = $this->objLanguage->languageText('phrase_downloaddocument');
        $lbUpload = $this->objLanguage->languageText('phrase_selectdocument');
        $btnUpload = $this->objLanguage->languageText('phrase_uploaddocument');
        $btnReplace = $this->objLanguage->languageText('phrase_replacedocument');
        $lbDocHidden = $this->objLanguage->languageText('mod_etd_documentavailonrequestonly', 'etd');
        $btnSet = $this->objLanguage->languageText('mod_etd_setasavailable', 'etd');
        $lbDocAvail = $this->objLanguage->languageText('mod_etd_documentavailtoall', 'etd');
        $btnUnSet = $this->objLanguage->languageText('mod_etd_setashidden', 'etd');

        $lbKb = $this->objLanguage->languageText('word_kb');
        $lbBytes = $this->objLanguage->languageText('word_bytes');
        $lbMb = $this->objLanguage->languageText('word_mb');
        $typePDF = $this->objLanguage->languageText('word_pdf');
        $typeWord = $this->objLanguage->languageText('word_msword');
        $typeExcel = $this->objLanguage->languageText('word_excel');
        $typeText = $this->objLanguage->languageText('phrase_plaintext');

        $objTable = new htmltable();

        if(!empty($data)){
            $btnUpload = $btnReplace;

            $objTable->startRow();
            $objTable->addCell($lbFileName.': ', '20%');
            $objTable->addCell($data[0]['fileName']);
            $objTable->endRow();

            // format size
            $size = $data[0]['size'];
            if($size < 1000){
                $formSize = $size.'&nbsp;'.$lbBytes; // bytes
            }else if($size > 1000000){
                $formSize = round($size/1000000,2).'&nbsp;'.$lbMb; // megabytes
            }else{
                $formSize = round($size/1000).'&nbsp;'.$lbKb; // kilobytes
            }
            $objTable->addRow(array($lbFileSize.': ', $formSize));

            // format type
            $format = $data[0]['filetype'];
            if(strpos($format, 'pdf')){
                $format = $typePDF;
            }else if(strpos($format, 'msword')){
                $format = $typeWord;
            }else if(strpos($format, 'excel')){
                $format = $typeExcel;
            }else if(!(strpos($format, 'text/plain')===FALSE)){
                $format = $typeText;
            }
            $objTable->addRow(array($lbType.': ', $format));

            // date
            if(!empty($data[0]['dateModified'])){
                $date = $this->objDate->formatDate($data[0]['dateModified']);
            }else{
                $date = $this->objDate->formatDate($data[0]['dateCreated']);
            }
            $objTable->addRow(array($lbDate.': ', $date));

            // download
            $url = $this->uri(array('action' => 'downloadfile', 'fileid' => $data[0]['fileId']));
            $this->objIcon->setIcon('fulltext');
            $this->objIcon->title = $lbDownload;

            $objLink = new link($url);
            $objLink->link = $this->objIcon->show();
            $objTable->addRow(array($lbDownload.': ', $objLink->show()));

            $objTable->addRow(array('&nbsp;'));

            // hidden fields
            $objInput = new textinput('id', $data[0]['id'], 'hidden');
            $hidden = $objInput->show();

            $objInput = new textinput('fileId', $data[0]['fileId'], 'hidden');
            $hidden .= $objInput->show();
        }
        $objInput = new textinput('submitId', $submitId, 'hidden');
        $hidden .= $objInput->show();

        // Section to upload a new / replace an existing document
        $objLabel = new label($lbUpload.': ', 'input_document');
        $objInput = new textinput('document', '', 'file', 60);

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $objButton = new button('save', $btnUpload);
        $objButton->setToSubmit();
        $objTable->addRow(array('', $objButton->show()));

        $objForm = new form('upload', $this->uri(array('action' => 'savesubmit', 'mode' => 'uploaddoc', 'nextmode' => 'showresource')));
        $objForm->extra = "enctype='multipart/form-data'";
        $objForm->addToForm($objTable->show());
        $objForm->addToForm($hidden);

        $str = $objForm->show();

//        if($accessLevel == 'protected'){
//            $inputValue = 'public';
//        }else{
//            $btnSet = $btnUnSet;
//            $lbDocHidden = $lbDocAvail;
//            $inputValue = 'protected';
//        }
//        $objInput = new textinput('access', $inputValue, 'hidden');
//        $hidden = $objInput->show();
//        $objInput = new textinput('save', 'save', 'hidden');
//        $hidden .= $objInput->show();
//
//        $objButton = new button('set', $btnSet);
//        $objButton->setToSubmit();
//        $formStr = $lbDocHidden.':&nbsp;&nbsp;'.$objButton->show();

//        $objForm = new form('sethidden', $this->uri(array('action' => 'savesubmit', 'mode' => 'setdoc', 'nextmode' => 'showresource')));
//        $objForm->addToForm($formStr);
//        $objForm->addToForm($hidden);
//
//        $str .= '<p style="padding-top:5px;">'.$objForm->show().'</p>';

        return $str;
    }

    /**
    * Method to display the form for requesting an embargo / display the request
    *
    * @access private
    * @return string html
    */
    private function showEmbargo()
    {
        $lbEmbargo = $this->objLanguage->languageText('mod_etd_requestembargoforperiod', 'etd');
        $lbReason = $this->objLanguage->languageText('word_reason');
        $lbPeriod = $this->objLanguage->languageText('word_period');
        $months = $this->objLanguage->languageText('word_months');
        $btnRequest = $this->objLanguage->languageText('word_request');

        $str = '<p>'.$lbEmbargo.'</p>';

        $objLabel = new label($lbReason.': ', 'input_reason');
        $objText = new textarea('reason', '', '4', '100');
        $formStr = $objLabel->show().'<br />'.$objText->show();

        $objLabel = new label($lbPeriod.': ', 'input_reason');
        $objDrop = new dropdown('period');
        //for($i = $config['shortPeriod']; $i <= $config['longPeriod']; $i += $config['incPeriod']){
        for($i = 0; $i <= 12; $i += 3){
            $objDrop->addoption($i, $i.$months);
        }
        $formStr .= '<p>'.$objLabel->show().'&nbsp;&nbsp;'.$objDrop->show().'</p>';


        $objButton = new button('save', $btnRequest);
        $objButton->setToSubmit();
        $formStr .= '<p>'.$objButton->show().'</p>';

        $objForm = new form('request', $this->uri(array('action' => 'savesubmit', 'mode' => 'embargo')));
        $objForm->addToForm($formStr);
        $str .= '<p>'.$objForm->show().'</p>';

        return $str;
    }

    /**
    * Method to display the form for submitting to supervisor for approval / examination
    *
    * @access private
    * @return string html
    */
    private function showSubmit()
    {
        $lbApproval = $this->objLanguage->languageText('mod_etd_submitforexamination', 'etd');
        $btnSubmit = $this->objLanguage->languageText('word_submit');

        $str = '<p>'.$lbApproval.'</p>';

        $objButton = new button('save', $btnSubmit);
        $objButton->setToSubmit();

        $objForm = new form('submit', $this->uri(array('action' => 'submit', 'mode' => 'copyright')));
        $objForm->addToForm($objButton->show());
        $str .= '<p>'.$objForm->show().'</p>';

        return $str;
    }

    /**
    * Method to display the copyright for acceptance
    *
    * @access private
    * @return string html
    */
    private function showCopyright()
    {
        $btnAccept = $this->objLanguage->languageText('mod_etd_acceptconditions', 'etd');

        $copy = $this->dbCopyright->getCopyright('');

        $str = $copy['copyright'];

        $objButton = new button('save', $btnAccept);
        $objButton->setToSubmit();

        $objForm = new form('accept', $this->uri(array('action' => 'savesubmit', 'mode' => 'accept', 'nextmode' => 'showresource')));
        $objForm->addToForm($objButton->show());
        $str .= $objForm->show();

        return $str;
    }

    /**
    * Entry portal into class
    *
    * @access public
    * @param string $mode The mode / action to perform within the class
    * @return string html
    */
    public function show($mode)
    {
        switch($mode){
            case 'addsubmission':
                $this->unsetSession('submitId');
                return $this->editResource('');

            case 'editresource':
                $submitId = $this->getSession('submitId');
                $xml = $this->xmlMetadata->openXML('etd_'.$submitId);
                $dublin = $xml['metadata']['dublincore'];
                $data = array_merge($dublin, $xml['metadata']['thesis']);
                return $this->editResource($data);
                break;

            case 'deleteresource':
                $submitId = $this->getSession('submitId');
                return $this->deleteResource($submitId);
                break;

            case 'saveresource':
                $submitId = $this->getSession('submitId');
                $submitId = $this->saveResource($submitId);
                $this->setSession('submitId', $submitId);
                return $submitId;
                break;

            case 'showresource':
                $submitId = $this->getSession('submitId');
                if(!isset($submitId) || empty($submitId)){
                    $submitId = $this->getParam('submitId');
                    $this->setSession('submitId', $submitId);
                }
                $xml = $this->xmlMetadata->openXML('etd_'.$submitId);
                $dublin = $xml['metadata']['dublincore'];
                $data = array_merge($dublin, $xml['metadata']['thesis']);
                return $this->showResource($data);

            case 'uploaddoc':
                $results = $this->etdFiles->doUpload();
                foreach($results as $result){
                    if($result['success']){
                        $submitId = $this->getSession('submitId');
                        $this-> dbFiles->addFile($submitId, $this->userId, '', $result['fileid']);
                    }
                }
                break;

            case 'embargo':
                break;

            case 'copyright':
                return $this->showCopyright();
                break;

            default:
                $this->unsetSession('submitId');
                $data = array(); // get the latest submissions requiring approval
                return $this->showSubmissions($data);
        }
    }
}
?>