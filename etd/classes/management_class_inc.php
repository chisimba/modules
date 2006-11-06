<?php
/**
* management class extends object
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Class for managing the individual resources - editing and deleting archived resources and adding new resources
* @author Megan Watson
* @copyright (c) 2006 University of the Western Cape
* @version 0.1
*/

class management extends object
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
        
        /*
        $this->etdBridge =& $this->getObject('dbcollectsubmit', 'etd');
        $this->etdCollections =& $this->getObject('dbcollection', 'etd');
        
        $this->dbQualified =& $this->getObject('dbqualified', 'etd');
       
        $this->dbFiles =& $this->getObject('dbfiles', 'etd');
                
        $this->objComment =& $this->getObject('commentinterface', 'comment');
        $this->objComment->useApproval(TRUE);
        $this->objComment->set('moduleCode', 'etd');
        $this->objComment->set('tableName', 'tbl_etd_submissions');
        */
        
        $this->etdTools =& $this->getObject('etdtools', 'etd');
        $this->dbDublinCore =& $this->getObject('dbdublincore', 'etd');
        
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
        $objLink = new link($this->uri(array('action' => 'managesubmissions', 'mode' => 'addsubmission')));
        $objLink->link = $lnSubmit;
        $str .= '<p>'.$objLink->show().'</p>';
        
        return $str;
    }
            
    /**
    * Method to display a form for searching the archive for documents that need editing or deleting.
    *
    * @access private
    * @return string html
    */
    private function showManage()
    {
        $head = $this->objLanguage->languageText('phrase_managerepository');
        $lbSearch = $this->objLanguage->languageText('mod_etd_searcharchivedresources', 'etd');
        $lbAuthor = $this->objLanguage->languageText('word_author');
        $lbTitle = $this->objLanguage->languageText('word_title');
        $lbKeywords = $this->objLanguage->languageText('word_keywords');
        $btnSearch = $this->objLanguage->languageText('word_search');
        
        $this->objHead->str = $head;
        $this->objHead->type = 1;
        $str = $this->objHead->show();
        
        $objTable = new htmltable();
        $objTable->cellspacing = 2;
        $objTable->cellpadding = 5;
        
        // Author
        $objLabel = new label($lbAuthor.': ', 'input_author');
        $objInput = new textinput('author', '', '', 60);
        
        $objTable->addRow(array($objLabel->show(), $objInput->show()));
        
        // Title
        $objLabel = new label($lbTitle.': ', 'input_ title');
        $objInput = new textinput('title', '', '', 60);
        
        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        // Keywords
        $objLabel = new label($lbKeywords.': ', 'input_keywords');
        $objInput = new textinput('keywords', '', '', 60);
        
        $objTable->startRow();
        $objTable->addCell($objLabel->show(), '20%');
        $objTable->addCell($objInput->show());
        $objTable->endRow();
        
        $objButton = new button('search', $btnSearch);
        $objButton->setToSubmit();
        
        $objTable->addRow(array('', $objButton->show()));
        
        $objForm = new form('searcharchive', $this->uri(array('action' => 'managesubmissions', 'mode' => 'search')));
        $objForm->addToForm('<p style="padding:5px;">'.$objTable->show().'</p>');
        
        $objTab = new tabbedbox();
        $objTab->extra = 'style="background-color: #FCFAF2"';
        $objTab->addTabLabel($lbSearch);
        $objTab->addBoxContent($objForm->show());
        $str .= $objTab->show();
        
        return $str;
    }
    
    /**
    * Method to display the results from a search
    *
    * @access private
    * @param array $data The search results
    * @param int $count The number of results
    * @return string html
    */
    private function showResults($data, $count = 0)
    {
        $head = $this->objLanguage->languageText('phrase_requestedresources');
        $lbNone = $this->objLanguage->languageText('mod_etd_noresourcesmatchedsearchcriteria', 'etd');
        $lbAuthor = $this->objLanguage->languageText('word_author');
        $lbTitle = $this->objLanguage->languageText('word_title');
        $lbDate = $this->objLanguage->languageText('word_date');
        
        
        $this->objHead->str = $head.' ('.$count.')';
        $this->objHead->type = 3;
        $str = $this->objHead->show();
        
        if(!empty($data)){
            $hdArr = array();
            $hdArr[] = $lbAuthor;
            $hdArr[] = $lbTitle;
            $hdArr[] = $lbDate;
            
            $objTable = new htmltable();
            $objTable->cellspacing = 2;
            $objTable->cellpadding = 5;
            
            $objTable->addHeader($hdArr);
            
            $class = 'even';
            foreach($data as $item){
                $class = ($class == 'odd') ? 'even':'odd';
                
                $author = $item['dc_creator'];
                $title = $item['dc_title'];
                                
                $url = $this->uri(array('action' => 'managesubmissions', 'mode' => 'showresource', 'submitId' => $item['submitid']));
                $objLink = new link($url);
                
                if(empty($author)){
                    $objLink->link = $title;
                    $title = $objLink->show();
                }else{
                    $objLink->link = $author;
                    $author = $objLink->show();
                }
                
                $row = array();
                $row[] = $author;
                $row[] = $title;
                $row[] = $item['dc_date'];
                
                $objTable->addRow($row, $class);
            }
            $str .= $objTable->show();
        }else{
            $str .= '<p class="noRecordsMessage">'.$lbNone.'</p>';
        }
                
        return $str;
    }

    /**
    * Method to get resources using the entered search criteria
    *
    * @access private
    * @return array $data
    */
    private function getResults()
    {
        $author = $this->getParam('author');
        $title = $this->getParam('title');
        $keywords = $this->getParam('keywords');

        $criteria = array();
        if(!empty($author)){
            $arrAuthor = explode(' ', $author);
            foreach($arrAuthor as $strAuthor){
                $criteria[] = array('field' => 'dc_creator', 'compare' => 'LIKE', 'value' => '%'.$strAuthor.'%');
            }
        }
        if(!empty($title)){
            $criteria[] = array('field' => 'dc_title', 'compare' => 'LIKE', 'value' => '%'.$title.'%');
        }
        if(!empty($keywords)){
            $arrKeywords = explode(', ', $keywords);
            foreach($arrKeywords as $strKeywords){
                $criteria[] = array('field' => 'dc_subject', 'compare' => 'LIKE', 'value' => '%'.$strKeywords.'%');
            }
        }
        
        $data = $this->dbSubmissions->fetchResources($criteria);
        
        return $data;
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
        $btnSave = $this->objLanguage->languageText('word_save');
        $btnCancel = $this->objLanguage->languageText('word_cancel');
        
        $this->objHead->str = $head;
        $this->objHead->type = 1;
        $str = $this->objHead->show();
        
        $objTable = new htmltable();
        $objTable->cellpadding = '5';
        $objTable->cellspacing = '5';
            
        $title = '';
        if(!empty($data['dc_title'])){
            $title = $data['dc_title'];
        }
        $objLabel = new label($lbTitle.': ', 'input_title');
        $objInput = new textinput('title', $title, '', 60);
            
        $objTable->addRow(array($objLabel->show(), $objInput->show()));
            
        $author = '';
        if(!empty($data['dc_creator'])){
            $author = $data['dc_creator'];
        }
        $objLabel = new label($lbAuthor.': ', 'input_author');
        $objInput = new textinput('author', $author, '', 60);
            
        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $date = '';
        if(!empty($data['dc_date'])){
            $date = $data['dc_date'];
        }
        $objLabel = new label($lbDate.': ', 'input_date');
        $year = $this->etdTools->getYearSelect('date', $date);
//        $objInput = new textinput('date', $date, '', 60);
            
        $objTable->addRow(array($objLabel->show(), $year));
           
        $type = '';
        if(!empty($data['dc_type'])){
            $type = $data['dc_type'];
        }
        $objLabel = new label($lbDocType.': ', 'input_type');
        $objInput = new textinput('type', $type, '', 60);
           
        $objTable->addRow(array($objLabel->show(), $objInput->show()));
           
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
        $objTab->addTabLabel($lbMetaData);
        $objTab->addBoxContent('<div style="padding: 10px;">'.$objTable->show().'</div>');
        $formStr = $objTab->show();
            
        $objTab = new tabbedbox();
        $objTab->addTabLabel($lbSummary);
        $objTab->addBoxContent('<div style="padding: 10px;">'.$this->objEditor->showFCKEditor().'</dive>');
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
        $objForm = new form('editresource', $this->uri(array('action' => 'savesubmissions', 'mode' => 'saveresource', 'nextmode' => 'showresource')));
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
        $metaId = $this->getParam('dcMetaId');
        $fields = array();
        $fields['dc_title'] = $this->getParam('title');
        $fields['dc_creator'] = $this->getParam('author');
        $fields['dc_date'] = $this->getParam('date');
        $fields['dc_type'] = $this->getParam('type');
        $fields['dc_coverage'] = $this->getParam('country');
        $fields['dc_source'] = $this->getParam('source');
        $fields['dc_contributor'] = $this->getParam('contributor');
        $fields['dc_publisher'] = $this->getParam('publisher');
        $fields['dc_format'] = $this->getParam('format');
        $fields['dc_relationship'] = $this->getParam('relationship');
        $fields['dc_language'] = $this->getParam('language');
        $fields['dc_audience'] = $this->getParam('audience');
        $fields['dc_rights'] = $this->getParam('rights');
        $fields['dc_subject'] = $this->getParam('keywords');
        $fields['dc_description'] = $this->getParam('abstract');
        $metaId = $this->dbDublinCore->updateElement($fields, $metaId);

        // Save the extended thesis metadata
        $fields = array();
        $thesisId = $this->getParam('thesisId');
        $fields['submitid'] = $submitId;
        $fields['dcmetaid'] = $metaId;
        $fields['thesis_degree_name'] = $this->getParam('thesis_degree_name');
        $fields['thesis_degree_level'] = $this->getParam('thesis_degree_level');
        $fields['thesis_degree_discipline'] = $this->getParam('thesis_degree_discipline');
        $fields['thesis_degree_grantor'] = $this->getParam('thesis_degree_grantor');
        $this->dbThesis->insertMetadata($fields, $thesisId);
        
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
        // delete document
//        $this->dbFiles->deleteAllFiles($submitId);
        
        // delete metadata
        $metaId = $this->getParam('dcMetaId');
        $this->dbDublinCore->deleteMetadata($metaId);
        
        // delete extended metadata
        $thesisId = $this->getParam('thesisId');
        $this->dbThesis->deleteMetadata($thesisId);
                
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
        
        $icons = '&nbsp;&nbsp;';
        $icons .= $this->objIcon->getEditIcon($this->uri(array('action' => 'managesubmissions', 'mode' => 'editresource')));
        $icons .= $this->objIcon->getDeleteIconWithConfirm('', array('action' => 'savesubmissions', 'mode' => 'deleteresource', 'nextmode' => 'resources', 'save' => 'true', 'dcMetaId' => $data['dcid'], 'thesisId' => $data['metaid']),  'etd', $confirmDel);
        
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
            $objTab->extra = 'style="background-color: #FFFDF5; padding:5px;"';
            $objTab->addTabLabel($lbMetaData);
            $objTab->addBoxContent($objTable->show());
            $str .= $objTab->show();
            
            $objTab = new tabbedbox();
            $objTab->extra = 'style="background-color: #FFFDF5; padding:5px;"';
            $objTab->addTabLabel($lbSummary);
            $objTab->addBoxContent($data['dc_description']);
            $str .= $objTab->show();                      
        }        
        
        // Display the attached document for download or replacement
        $docStr = $this->showDocument();        
        $objTab = new tabbedbox();
        $objTab->extra = 'style="background-color: #FFFDF5; padding:5px;"';
        $objTab->addTabLabel($lbDocument);
        $objTab->addBoxContent($docStr);
        $str .= $objTab->show();
        
        // Display the references for the resource
//        $refStr = $this->showReferences();        
//        $objTab = new tabbedbox();
//        $objTab->addTabLabel($lbReferences);
//        $objTab->addBoxContent($refStr);
//        $str .= $objTab->show();

        // Display the authors declaration on the document
//        $decStr = $this->showDeclaration();        
//        $objTab = new tabbedbox();
//        $objTab->addTabLabel($lbDeclaration);
//        $objTab->addBoxContent($decStr);
//        $str .= $objTab->show();
        
        // Display the authors declaration on the document
//        $this->objComment->set('sourceId', $submitId);
//        $comStr = $this->objComment->showAll();        
//        $objTab = new tabbedbox();
//        $objTab->addTabLabel($lbComments);
//        $objTab->addBoxContent($comStr);
//        $str .= $objTab->show();

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
//        $accessLevel = $this->getSession('accessLevel');
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
        
        $objForm = new form('upload', $this->uri(array('action' => 'savemanage', 'mode' => 'uploaddoc', 'nextmode' => 'showresource')));
        $objForm->extra = "ENCTYPE='multipart/form-data'";
        $objForm->addToForm($objTable->show());
        $objForm->addToForm($hidden);
        
        $str = $objForm->show();
        
        if($accessLevel == 'protected'){
            $inputValue = 'public';
        }else{
            $btnSet = $btnUnSet;
            $lbDocHidden = $lbDocAvail;
            $inputValue = 'protected';
        }
        $objInput = new textinput('access', $inputValue, 'hidden');
        $hidden = $objInput->show();
        $objInput = new textinput('save', 'save', 'hidden');
        $hidden .= $objInput->show();

        $objButton = new button('set', $btnSet);
        $objButton->setToSubmit();
        $formStr = $lbDocHidden.':&nbsp;&nbsp;'.$objButton->show();
        
        $objForm = new form('sethidden', $this->uri(array('action' => 'savemanage', 'mode' => 'setdoc', 'nextmode' => 'showresource')));
        $objForm->addToForm($formStr);
        $objForm->addToForm($hidden);
        
        $str .= '<p style="padding-top:5px;">'.$objForm->show().'</p>';
        
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
                return $this->editResource('');
                
            case 'editresource':
                $submitId = $this->getSession('submitId');
                $data = $this->dbSubmissions->getSubmission($submitId);
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
                $data = $this->dbSubmissions->getSubmission($submitId);
                return $this->showResource($data);
    
            case 'resources':
                $this->unsetSession('submitId');
                return $this->showManage();
                
            case 'search':
                $this->unsetSession('submitId');
                $results = $this->getResults();
                $str = $this->showManage().'<br />';
                if(!empty($results)){
                    $str .= $this->showResults($results[0], $results[1]);
                }else{
                    $str .= $this->showResults('');
                }
                return $str;
                
            
            default:
                $this->unsetSession('submitId');
                $data = array(); // get the latest submissions requiring approval
                return $this->showSubmissions($data);
        }
    }


/* ** Methods below have not been ported and still belong to the cshe module ** */
        
    /**
    * Method to display the references on the resource
    *
    * @access private
    * @return string html
    */
    function showReferences()
    {
        return $this->dbRef->show('');
    }
    
    /**
    * Method to display the authors declaration
    *
    * @access private
    * @return string html
    */    
    function showDeclaration()
    {
        $submitId = $this->getSession('submitId');
        $data = $this->dbSign->getSignature($submitId);
        
        $lbSigned = $this->objLanguage->languageText('phrase_signedby');
        $lbDate = $this->objLanguage->languageText('word_date');
        
        if(!empty($data)){
            $name = $this->objUser->fullName($data['creatorId']);
            $date = $this->objDate->formatDate($data['dateCreated']);
            $str = '<p>'.$lbSigned.':&nbsp;'.$name;
            $str .= '<br>'.$lbDate.':&nbsp;'.$date.'</p>';
        }
        return $str;
    }
            
    /**
    * Method to generate the link for adding pre-defined keywords.
    *
    * @access private
    * @param string $form The name of the form containing the field.
    * @param string $field The name of the field to add the keywords to.
    */
    function getKeywordLink($form, $field)
    {
        $lbAddKeywords = $this->objLanguage->languageText('mod_etd_addkeywords');
        $array = array('action' => 'addkeywords', 'letter' => 'a', 'formname' => $form, 'fieldname' => $field);
        $url = $this->uri($array, $this->module);
        $objLink = new link('#');
        $objLink->link = $lbAddKeywords;
        $objLink->extra = "onclick=\"javascript:window.open('$url', 'keywords', 'height = 500, width = 650, left = 100, top = 100, scrollbars=1, toolbar=no, resizable=yes')\"";
        return $objLink->show();
    }
        
    /**
    * Entry portal into class
    *
    * @access public
    * @param string $mode The mode / action to perform within the class
    * @return string html
    *
    function show($mode)
    {
        switch($mode){
            case 'search':
                $results = $this->getResults();
                $str = $this->showManage();
                if(!empty($results)){
                    $str .= $this->showResults($results[0], $results[1]);
                }else{
                    $str .= $this->showResults();
                }
                return $str;
                
            case 'showresource':
                $submitId = $this->getParam('submitId');
                if(!isset($submitId) || empty($submitId)){
                    $submitId = $this->getSession('submitId');
                }
                $this->setSession('submitId', $submitId);
                $data = $this->dbSubmissions->getETD($submitId);
                $collection = $this->etdBridge->getCollectionData($submitId);
                $data['country'] = $collection['name'];
                $this->setSession('country', $collection['name']);
                $this->setSession('countryId', $collection['id']);
                $this->setSession('accessLevel', $data['accessLevel']);
                return $this->showResource($data);
                break;
                
            case 'editresource':
                $submitId = $this->getParam('submitId');
                $data = $this->dbSubmissions->getETD($submitId);
                $data['country'] = $this->getSession('country');
                return $this->editResource($data);
                break;                
            
            case 'saveresource':
                return $this->saveResource();
                break;
                
            case 'deleteresource':
                return $this->deleteResource();
                break;
            
            case 'uploaddoc':
                $id = $this->getParam('id');
                $submitId = $this->getSession('submitId');
                $this->dbFiles->uploadFile($this->userId, 'document', $id);
                break;
                
            case 'setdoc':
                $submitId = $this->getSession('submitId');
                $access = $this->getParam('access');
                return $this->dbSubmissions->changeAccess($submitId, $this->userId, $access);
                break;
            
            default:
                $this->unsetSession('submitId');
                return $this->showManage();
        }
    }*/
}
?>