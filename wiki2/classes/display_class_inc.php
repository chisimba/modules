<?php
/* ----------- templates class extends object ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Templates class for wiki version 2 module
* @author Kevin Cyster
*/

class display extends object
{
    /**
    * @var object $objLanguage: The language class of the language module
    * @access private
    */
    private $objLanguage;
     
    /**
    * @var object $objDbwiki: The dbwiki class in the wiki version 2 module
    * @access public
    */
    public $objDbwiki;

    /**
    * @var object $objIcon: The geticon class in the htmlelements module
    * @access public
    */
    public $objIcon;

    /**
    * @var object $objFeature: The featurebox class in the navigation module
    * @access public
    */
    public $objFeature;

    /**
    * @var object $objPopup: The windowpop class in the htmlelements module
    * @access public
    */
    public $objPopup;

    /**
    * @var object $objDate: The dateandtime class in the utilities module
    * @access public
    */
    public $objDate;

    /**
    * @var object $objUser: The user class in the securities module
    * @access public
    */
    public $objUser;

    /**
    * @var string $userId: The user id of the current logged in user
    * @access public
    */
    public $userId;

    /**
    * @var object $objTab: The tabpane class in the htmlelements module
    * @access public
    */
    public $objTab;

    /**
    * @var object $objBizCard: The userbizcard class in the useradmin module
    * @access public
    */
    public $objBizCard;

    /**
    * @var object $objUserAdmin: The useradmin_model2 class in the security module
    * @access public
    */
    public $objUserAdmin;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {   
        // load html element classes
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('layer', 'htmlelements');

        // system classes
        $this->objLanguage = $this->newObject('language','language');
        $this->objDbwiki = $this->newObject('dbwiki', 'wiki2');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->objFeature = $this->newObject('featurebox', 'navigation');
        $this->objPopup = $this->newObject('windowpop', 'htmlelements');
        $this->objWiki = $this->newObject('wikitextparser', 'wiki2');
        $this->objDate = $this->newObject('dateandtime', 'utilities');
        $this->objUser = $this->newObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->objTab = $this->newObject('tabpane', 'htmlelements');
        $this->objBizCard = $this->getObject('userbizcard', 'useradmin');
        $this->objUserAdmin = $this->getObject('useradmin_model2','security');
    }

    /**
    * Method to create left column content of the wiki 
    *
    * @access public
    * @return string $str: The output string
    **/
    public function showWikiToolbar()
    {
        // text elements
        $nameLabel = $this->objLanguage->languageText('mod_wiki2_name', 'wiki2');
        $mainLabel = $this->objLanguage->languageText('mod_wiki2_main', 'wiki2');
        $viewLabel = $this->objLanguage->languageText('mod_wiki2_view', 'wiki2');
        $addLabel = $this->objLanguage->languageText('mod_wiki2_add', 'wiki2');
        $formatLabel = $this->objLanguage->languageText('mod_wiki2_format', 'wiki2');
        $mainTitleLabel = $this->objLanguage->languageText('mod_wiki2_maintitle', 'wiki2');
        $viewTitleLabel = $this->objLanguage->languageText('mod_wiki2_viewtitle', 'wiki2');
        $addTitleLabel = $this->objLanguage->languageText('mod_wiki2_addtitle', 'wiki2');
        $formatTitleLabel = $this->objLanguage->languageText('mod_wiki2_formattitle', 'wiki2');
        $searchLabel = $this->objLanguage->languageText('word_search');
        $searchWikiLabel = $this->objLanguage->languageText('mod_wiki2_searchwiki', 'wiki2');
        $addedLabel = $this->objLanguage->languageText('mod_wiki2_added', 'wiki2');
        $updatedLabel = $this->objLanguage->languageText('mod_wiki2_updated', 'wiki2');
        $errorLabel = $this->objLanguage->languageText('mod_wiki2_searcherror', 'wiki2');
        $noPageLabel = $this->objLanguage->languageText('mod_wiki2_nopages', 'wiki2');
        $pageTitleLabel = $this->objLanguage->languageText('mod_wiki2_pagetitle', 'wiki2');
        $titleLabel = $this->objLanguage->languageText('mod_wiki2_byname', 'wiki2');
        $contentLabel = $this->objLanguage->languageText('mod_wiki2_bycontent', 'wiki2');
        $bothLabel = $this->objLanguage->languageText('mod_wiki2_both', 'wiki2');
        $authorLabel = $this->objLanguage->languageText('mod_wiki2_authors', 'wiki2');
        $authorsTitleLabel = $this->objLanguage->languageText('mod_wiki2_authorstitle', 'wiki2');
        
        // links
        $string = '<ul>';
        // main page link
        $objLink = new link($this->uri(array(
            'action' => 'view_page',
        ), 'wiki2'));
        $objLink->link = $mainLabel;
        $objLink->title = $mainTitleLabel;
        $mainLink = $objLink->show();
        $string .= '<li>'.$mainLink.'</li>';
        
        // add page link
        $objLink = new link($this->uri(array(
            'action' => 'view_page',
            'mode' => 'add',
        ), 'wiki2'));
        $objLink->link = $addLabel;
        $objLink->title = $addTitleLabel;
        $addLink = $objLink->show();
        $string .= '<li>'.$addLink.'</li>';
        
        // view all page link
        $objLink = new link($this->uri(array(
            'action' => 'view_all',
        ), 'wiki2'));
        $objLink->link = $viewLabel;
        $objLink->title = $viewTitleLabel;
        $viewLink = $objLink->show();
        $string .= '<li>'.$viewLink.'</li>';

        // view all authors link
        $objLink = new link($this->uri(array(
            'action' => 'view_authors',
        ), 'wiki2'));
        $objLink->link = $authorLabel;
        $objLink->title = $authorsTitleLabel;
        $viewLink = $objLink->show();
        $string .= '<li>'.$viewLink.'</li>';

        // popup link for formatting rules
        $objPopup = new windowpop();
        $objPopup->title = $formatTitleLabel;
        $objPopup->set('location', $this->uri(array(
            'action' => 'view_rules',
        ), 'wiki2'));
        $objPopup->set('linktext', $formatLabel);
        $objPopup->set('width', '550');
        $objPopup->set('height', '500');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'yes');
        $objPopup->putJs(); // you only need to do this once per page
        $formatPopup = $objPopup->show();
        $string .= '<li>'.$formatPopup.'</li>';
        $string .= '</ul>';
        
        $str = $this->objFeature->show($nameLabel, $string);
        
        // seacrh box
        $objDrop = new dropdown('field');
        $objDrop->addOption(1, $bothLabel);
        $objDrop->addOption(2, $titleLabel);
        $objDrop->addOption(3, $contentLabel);
        $objDrop->setSelected(1);
        $fieldDrop = $objDrop->show();
        
        $objInput = new textinput('value', '', '', '');
        $valueInput = $objInput->show();
        
        $objButton = new button('search', $searchLabel);
        $objButton->setToSubmit();
        $searchButton = $objButton->show();
        
        $objForm = new form('search', $this->uri(array(
            'action' => 'search_wiki',
        )));
        $objForm->addToForm($fieldDrop.'<p />');
        $objForm->addToForm($valueInput);
        $objForm->addToForm($searchButton);
        $objForm->addRule('value', $errorLabel, 'required');
        $searchForm = $objForm->show();
        
        $str .= $this->objFeature->show($searchWikiLabel, $searchForm);
        
        // recently added pages
        $data = $this->objDbwiki->getRecentlyAdded();
        $string = '';
        if(!empty($data)){
            $string = '<ul>';
            foreach($data as $line){
                $name = $line['page_name'];
                $pageTitle = $this->objWiki->renderTitle($name);
                $objLink = new link($this->uri(array(
                    'action' => 'view_page',
                    'name' => $name,
                ), 'wiki2'));
                $objLink->link = $pageTitle;
                $objLink->title = $pageTitleLabel;
                $pageLink = $objLink->show();
                $string .= '<li>'.$pageLink .'</li>';
            }
            $string .= '</ul>';
        }else{
            $string = '<ul><li>'.$noPageLabel.'</li></ul>';
        }
        $str .= $this->objFeature->show($addedLabel, $string);

        // recently updated pages
        $data = $this->objDbwiki->getRecentlyUpdated();
        $string = '';
        if(!empty($data)){
            $string = '<ul>';
            foreach($data as $line){
                $name = $line['page_name'];
                $pageTitle = $this->objWiki->renderTitle($name);
                $objLink = new link($this->uri(array(
                    'action' => 'view_page',
                    'name' => $name,
                ), 'wiki2'));
                $objLink->link = $pageTitle;
                $objLink->title = $pageTitleLabel;
                $pageLink = $objLink->show();
                $string .= '<li>'.$pageLink .'</li>';
            }
            $string .= '</ul>';
        }else{
            $string = '<ul><li>'.$noPageLabel.'</li></ul>';
        }
        $str .= $this->objFeature->show($updatedLabel, $string);

        return $str.'<br />';
    }

    /**
    * Method to display the main wiki content area
    *
    * @access public
    * @param string $pageName: The name of the page to show
    * @param integer $version: The version of the page to show
    * @param string $mode: The mode of the page to show
    * @param integer $tab: The tab to set to default
    * @return string $str: The output string
    **/
    public function showMain($pageName = NULL, $version = NULL, $mode = NULL, $tab = 0)
    {
        // add  javascript
        $headerParams = $this->getJavascriptFile('wiki.js', 'wiki2');
        $this->appendArrayVar('headerParams', $headerParams);

        // add javascript to sort table
        $headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);

        // get data
        if(empty($mode) || $mode == 'edit'){
            if(empty($pageName)){
                $data = $this->objDbwiki->getMainPage();
            }else{
                $data = $this->objDbwiki->getPage($pageName, $version);
            }
            $pageId = $data['id'];
            $pageName = $data['page_name'];
            $pageTitle = $this->objWiki->renderTitle($pageName);    
            $wikiText = $this->objWiki->transform($data['page_content']);
            $array = array(
                'date' => $this->objDate->formatDate($data['date_created']),
            );
            $modifiedLabel = $this->objLanguage->code2Txt('mod_wiki2_modified', 'wiki2', $array);
        }
        
        // text elements
        $articleLabel = $this->objLanguage->languageText('word_article');
        $previewLabel = $this->objLanguage->languageText('word_preview');
        $noPreviewLabel = $this->objLanguage->languageText('mod_wiki2_nopreview', 'wiki2');
        $refreshLabel = $this->objLanguage->languageText('word_refresh');
        $refreshTitleLabel = $this->objLanguage->languageText('mod_wiki2_refreshtitle', 'wiki2');
        $addLabel = $this->objLanguage->languageText('mod_wiki2_addarticle', 'wiki2');
        $listLabel = $this->objLanguage->languageText('mod_wiki2_listarticles', 'wiki2');
        $summaryLabel = $this->objLanguage->languageText('mod_wiki2_listsummaries', 'wiki2');
        $historyLabel = $this->objLanguage->languageText('word_history');
        $editLabel = $this->objLanguage->languageText('word_edit');
        $versionLabel = $this->objLanguage->languageText('mod_wiki2_version', 'wiki2');
        
        if(empty($mode) || $mode == 'edit'){
            // wiki page
            if(empty($version)){
                $versionTitle = $pageTitle;
            }else{
                $versionTitle = $pageTitle.':&#160;'.$versionLabel.'&#160;'.$version;
            }
            $objHeader = new htmlheading();
            $objHeader->str = $versionTitle;
            $objHeader->type = 1;
            $heading = $objHeader->show();
            $contents = $heading;
        
            $contents .= $wikiText;
            $contents .= '<hr />'.$modifiedLabel;
        
            $objLayer = new layer();
            $objLayer->cssClass = 'featurebox';
            $objLayer->addToStr($contents);
            $contentLayer = $objLayer->show();
            
            $string = $contentLayer;
        
            // wiki page tab
            $tabArray = array(
                'name' => $articleLabel,
                'content' => $string,
            );
            $this->objTab->addTab($tabArray);
            
            // edit page
            if(empty($version)){ // can only edit latest version
                $edit = $this->_showEditPage($pageId);
        
                $objLayer = new layer();
                $objLayer->cssClass = 'featurebox';
                $objLayer->addToStr($edit);
                $contentLayer = $objLayer->show();
        
                $string = $contentLayer;
        
                // edit page tab
                $tabArray = array(
                    'name' => $editLabel,
                    'content' => $string,
                );
                $this->objTab->addTab($tabArray);
            }
        
            // page history
            $history = $this->_showPageHistory($pageName);
        
            $objLayer = new layer();
            $objLayer->cssClass = 'featurebox';
            $objLayer->addToStr($history);
            $contentLayer = $objLayer->show();
            
            $string = $contentLayer;
        
            // page history tab
            $tabArray = array(
                'name' => $historyLabel,
                'content' => $string,
            );
            $this->objTab->addTab($tabArray);
        }elseif($mode == 'add'){
            $add = $this->_showAddPage();
            
            $objLayer = new layer();
            $objLayer->cssClass = 'featurebox';
            $objLayer->addToStr($add);
            $contentLayer = $objLayer->show();
        
            $string = $contentLayer;
        
            // add page tab
            $tabArray = array(
                'name' => $addLabel,
                'content' => $string,
            );
            $this->objTab->addTab($tabArray);
            
            // refresh link
            $objLink = new link('#');
            $objLink->link = $refreshLabel;
            $objLink->title = $refreshTitleLabel;
            $objLink->extra = 'onclick="javascript:refreshPreview()"';
            $refreshLink = $objLink->show();
            
            $objLayer = new layer();
            $objLayer->id = 'previewDiv';
            $objLayer->addToStr('<ul><li>'.$noPreviewLabel.'</li></ul>');
            $previewLayer = $objLayer->show();
            
            $objLayer = new layer();
            $objLayer->id = 'refreshDiv';
            $objLayer->cssClass = 'featurebox';
            $objLayer->addToStr($refreshLink.'<br />'.$previewLayer);
            $refreshLayer = $objLayer->show();
            $string = $refreshLayer;
            
            // add page tab
            $tabArray = array(
                'name' => $previewLabel,
                'content' => $string,
            );
            $this->objTab->addTab($tabArray);
        }else{
            // list all pages
            $list = $this->_showAllPages();
            
            $objLayer = new layer();
            $objLayer->cssClass = 'featurebox';
            $objLayer->addToStr($list);
            $contentLayer = $objLayer->show();
        
            $string = $contentLayer;
        
            // add page tab
            $tabArray = array(
                'name' => $listLabel,
                'content' => $string,
            );
            $this->objTab->addTab($tabArray);
            
            // list summaries
            $list = $this->_showSummaries();
            
            $objLayer = new layer();
            $objLayer->cssClass = 'featurebox';
            $objLayer->addToStr($list);
            $contentLayer = $objLayer->show();
        
            $string = $contentLayer;
        
            // add page tab
            $tabArray = array(
                'name' => $summaryLabel,
                'content' => $string,
            );
            $this->objTab->addTab($tabArray);
        }
        
        //display tabs        
        $this->objTab->useCookie = 'false';
        $this->objTab->setSelected = $tab;
        $str = $this->objTab->show();
        
        return $str.'<br />';
    }
    
    /**
    * Method to show the page history
    * 
    * @access private
    * @param string $pageName: The name of the page to show the history for
    * @return string $str: The table with the page history
    */
    private function _showPageHistory($pageName)
    {
        // get data
        $data = $this->objDbwiki->getPagesByName($pageName);
        $pageTitle = $this->objWiki->renderTitle($data[0]['page_name']);
        
        // text elements
        $versionLabel = $this->objLanguage->languageText('word_version');
        $authorLabel = $this->objLanguage->languageText('word_author');
        $dateLabel = $this->objLanguage->languageText('mod_wiki2_datecreated', 'wiki2');
        $noRecordsLabel = $this->objLanguage->languageText('mod_wiki2_norecords', 'wiki2');
        $pageTitleLabel = $this->objLanguage->languageText('mod_wiki2_pagetitle', 'wiki2');
        $authorTitleLabel = $this->objLanguage->languageText('mod_wiki2_authortitle', 'wiki2');
        $originalLabel = $this->objLanguage->languageText('word_original');
        $restoreLabel = $this->objLanguage->languageText('word_restore');
        $restoreTitleLabel = $this->objLanguage->languageText('mod_wiki2_restoretitle', 'wiki2');
        $confirmLabel = $this->objLanguage->languageText('mod_wiki2_restoreconfirm', 'wiki2');
        $restoredLabel = $this->objLanguage->languageText('word_restored');
        $overwrittenLabel = $this->objLanguage->languageText('word_overwritten');
        $commentLabel = $this->objLanguage->languageText('mod_wiki2_comment', 'wiki2');
        
        // page heading
        $objHeader = new htmlheading();
        $objHeader->str = $pageTitle;
        $objHeader->type = 1;
        $heading = $objHeader->show();
        $str = $heading;
        
        // create display table
        $objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->border = '1';
        $objTable->row_attributes = ' name="row_'.$objTable->id.'"';
        $objTable->startRow();
        $objTable->addCell('<b>'.$versionLabel.'</b>', '', '', '', 'heading', '');
        $objTable->addCell('<b>'.$authorLabel.'</b>', '', '', '', 'heading', '');
        $objTable->addCell('<b>'.$dateLabel.'</b>', '', '', 'center', 'heading', '');
        $objTable->addCell('&#160;', '', '', 'center', 'heading', 'rowspan="2"');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>'.$commentLabel.'</b>', '', '', '', 'heading', 'colspan="3"');
        $objTable->endRow();
        
        if(empty($data)){
            // no records
            $objTable->startRow();
            $objTable->addCell($noRecordsLabel, '', '', '', 'noRecordsMessage', 'colspan="4"');
            $objTable->endRow();
        }else{
            // loop through data and display each record in the table
            $i = 0;
            foreach($data as $line){
                $class = (($i++%2) == 0)?'even':'odd';
                $pageId = $line['id'];
                $name = $line['page_name'];
                $pageVersion = $line['page_version'];
                $pageStatus = $line['page_status'];
                $versionComment = $line['version_comment'];
                
                if($pageVersion == 1){
                    $version = $pageVersion.'&#160;-&#160;'.$originalLabel;
                }elseif($pageStatus == 3){
                    $version = $pageVersion.'&#160;-&#160;'.$overwrittenLabel;
                }elseif($pageStatus == 2){
                    $version = $pageVersion.'&#160;-&#160;'.$restoredLabel;
                }else{
                    $version = $line['page_version'];
                }
                $authorId = $line['page_author_id'];
                $author = $this->objUser->fullname($authorId);
                $date = $this->objDate->formatDate($line['date_created']);

                // page name link
                if(count($data) == $pageVersion){
                    $action = $this->uri(array(
                        'action' => 'view_page',
                        'name' => $name,
                    ), 'wiki2');
                }else{
                    $action = $this->uri(array(
                        'action' => 'view_page',
                        'name' => $name,
                        'version' => $pageVersion,
                    ), 'wiki2');
                }
                $objLink = new link($action);
                $objLink->link = $version;
                $objLink->title = $pageTitleLabel;
                $pageLink = $objLink->show();
                
                // author link
                $objLink = new link($this->uri(array(
                    'action' => 'view_authors',
                    'author' => $authorId,
                ), 'wiki2'));
                $objLink->link = $author;
                $objLink->title = $authorTitleLabel;
                $authorLink = $objLink->show();
                
                // restore link
                if($pageStatus < 4 && count($data) != $pageVersion){
                    $objLink = new link($this->uri(array(
                        'action' => 'restore_page',
                        'name' => $name,
                        'version' => $pageVersion,
                    ), 'wiki2'));
                    $objLink->link = $restoreLabel;
                    $objLink->title = $restoreTitleLabel;
                $objLink->extra = 'onclick="javascript:if(!confirm(\''.$confirmLabel.'\')){return false};"';
                    $restoreLink = $objLink->show();                
                }else{
                    $restoreLink = '&#160;';
                }
                
                // data display
                $objTable->startRow();
                $objTable->addCell($pageLink, '', '', '', $class, '');
                $objTable->addCell($authorLink, '30%', '', '', $class, '');
                $objTable->addCell($date, '20%', '', 'center', $class, '');
                $objTable->addCell($restoreLink, '', '', 'center', $class, 'rowspan="2"');
                $objTable->endRow();
                $objTable->startRow();
                $objTable->addCell($versionComment, '', '', '', $class, 'colspan="3"');
                $objTable->endRow();
            }
        }
        $pageTable = $objTable->show();
        $str .= $pageTable;
        
        return $str;
    }

    /**
    * Method to edit wiki pages
    * 
    * @access private
    * @param string $id: The id of the page to be edited
    * @return string $str: The output string
    */
    private function _showEditPage($id)
    {
        // get data
        $data = $this->objDbwiki->getPageById($id);
        $pageTitle = $this->objWiki->renderTitle($data['page_name']);
        
        // text elements
        $contentLabel = $this->objLanguage->languageText('mod_wiki2_pagecontent', 'wiki2');
        $contentErrorLabel = $this->objLanguage->languageText('mod_wiki2_contenterror', 'wiki2');
        $commentLabel = $this->objLanguage->languageText('mod_wiki2_comment', 'wiki2');
        $commentErrorLabel = $this->objLanguage->languageText('mod_wiki2_commenterror', 'wiki2');
        $updateLabel = $this->objLanguage->languageText('mod_wiki2_update', 'wiki2');
        $cancelLabel = $this->objLanguage->languageText('word_cancel');
        $summaryLabel = $this->objLanguage->languageText('mod_wiki2_pagesummary', 'wiki2');
        $summaryErrorLabel = $this->objLanguage->languageText('mod_wiki2_summaryerror', 'wiki2');
        
        // heading
        $objHeader = new htmlheading();
        $objHeader->str = $pageTitle;
        $objHeader->type = 1;
        $heading = $objHeader->show();
        $str = $heading;
        
        // summary
        $objHeader = new htmlheading();
        $objHeader->str = $summaryLabel;
        $objHeader->type = 4;
        $heading = $objHeader->show();
        
        // summary textarea
        $objText = new textarea('summary', $data['page_summary'], '4', '70');
        $summaryText = $objText->show();
               
        $objInput = new textinput('choice', 'no', 'hidden', '');
        $hiddenInput = $objInput->show();
               
        // summary layer
        $objLayer = new layer();
        $objLayer->addToStr($heading.$summaryText.$hiddenInput);        
        $summaryLayer = $objLayer->show();
                

        // content
        $objHeader = new htmlheading();
        $objHeader->str = $contentLabel;
        $objHeader->type = 4;
        $heading = $objHeader->show();
        
        // page name textinput
        $objInput = new textinput('name', $data['page_name'], 'hidden', '');
        $nameInput = $objInput->show();
               
        // main page textinput
        $objInput = new textinput('main', $data['main_page'], 'hidden', '');
        $mainInput = $objInput->show();
               
        // content textarea
        $objText = new textarea('content', $data['page_content'], '25', '70');
        $contentText = $objText->show();
        
        // content layer
        $objLayer = new layer();
        $objLayer->addToStr($heading.$nameInput.$mainInput.$contentText);        
        $contentLayer = $objLayer->show();
        
        // comment
        $objHeader = new htmlheading();
        $objHeader->str = $commentLabel;
        $objHeader->type = 4;
        $heading = $objHeader->show();
        
        // comment textarea
        $objText = new textarea('comment', '', '4', '70');
        $commentText = $objText->show();
               
        // comment layer
        $objLayer = new layer();
        $objLayer->addToStr($heading.$commentText);        
        $commentLayer = $objLayer->show();

        // create button
        $objButton = new button('update', $updateLabel);
        $objButton->extra = 'onclick="javascript: validateUpdate(\''.$summaryErrorLabel.'\', \''.$contentErrorLabel.'\', \''.$commentErrorLabel.'\');"';
        $updateButton = $objButton->show();
        
        // create button
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setToSubmit();
        $cancelButton = $objButton->show();
        
        // button layer
        $objLayer = new layer();
        $objLayer->addToStr($updateButton.'&#160;'.$cancelButton);        
        $buttonLayer = $objLayer->show();

        // form
        $objForm = new form('update', $this->uri(array(
            'action' => 'update_page',
        ), 'wiki2'));
        $objForm->addToForm($summaryLayer);
        $objForm->addToForm($contentLayer);
        $objForm->addToForm($commentLayer);
        $objForm->addToForm($buttonLayer);
        $createForm = $objForm->show();
        $str .= $createForm;
        
        return $str;       
    }

    /**
    * Method to add wiki pages
    * 
    * @access private
    * @return string $str: The output string
    */
    private function _showAddPage()
    {
        // text elements
        $titleLabel = $this->objLanguage->languageText('mod_wiki2_add', 'wiki2');
        $pageLabel = $this->objLanguage->languageText('mod_wiki2_pagename', 'wiki2');
        $pageErrorLabel = $this->objLanguage->languageText('mod_wiki2_pageerror', 'wiki2');
        $nameErrorLabel = $this->objLanguage->languageText('mod_wiki2_nameerror', 'wiki2');
        $summaryLabel = $this->objLanguage->languageText('mod_wiki2_pagesummary', 'wiki2');
        $summaryErrorLabel = $this->objLanguage->languageText('mod_wiki2_summaryerror', 'wiki2');
        $contentLabel = $this->objLanguage->languageText('mod_wiki2_pagecontent', 'wiki2');
        $contentErrorLabel = $this->objLanguage->languageText('mod_wiki2_contenterror', 'wiki2');
        $createLabel = $this->objLanguage->languageText('mod_wiki2_create', 'wiki2');
        $cancelLabel = $this->objLanguage->languageText('word_cancel');
        
        // page name
        $objHeader = new htmlheading();
        $objHeader->str = $pageLabel;
        $objHeader->type = 4;
        $heading = $objHeader->show();
        
        // page name textinput
        $objInput = new textinput('name', '', '', '96');
        $objInput->extra = 'onblur="javascript:validateName(this);"';
        $nameInput = $objInput->show();
               
        // page name error layer
        $objLayer = new layer();
        $objLayer->id = 'errorDiv';
        $errorLayer = $objLayer->show();
                
        // page name layer
        $objLayer = new layer();
        $objLayer->addToStr($heading.$nameInput.$errorLayer);        
        $pageLayer = $objLayer->show();
                
        // summary
        $objHeader = new htmlheading();
        $objHeader->str = $summaryLabel;
        $objHeader->type = 4;
        $heading = $objHeader->show();
        
        // summary textarea
        $objText = new textarea('summary', '', '4', '70');
        $summaryText = $objText->show();
               
        $objInput = new textinput('choice', 'no', 'hidden', '');
        $hiddenInput = $objInput->show();
               
        // summary layer
        $objLayer = new layer();
        $objLayer->addToStr($heading.$summaryText.$hiddenInput);        
        $summaryLayer = $objLayer->show();
                
        // content
        $objHeader = new htmlheading();
        $objHeader->str = $contentLabel;
        $objHeader->type = 4;
        $heading = $objHeader->show();
        
        // content textarea
        $objText = new textarea('content', '', '25', '70');
        $contentText = $objText->show();
               
        // content layer
        $objLayer = new layer();
        $objLayer->addToStr($heading.$contentText);        
        $contentLayer = $objLayer->show();
                
        // create button
        $objButton = new button('create', $createLabel);
        $objButton->extra = 'onclick="javascript: validateCreate(\''.$pageErrorLabel.'\', \''.$summaryErrorLabel.'\', \''.$contentErrorLabel.'\');"';
        $createButton = $objButton->show();
        
        // create button
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setToSubmit();
        $cancelButton = $objButton->show();
        
        // button layer
        $objLayer = new layer();
        $objLayer->addToStr($createButton.'&#160;'.$cancelButton);        
        $buttonLayer = $objLayer->show();

        // form
        $objForm = new form('create', $this->uri(array(
            'action' => 'create_page',
        ), 'wiki2'));
        $objForm->addToForm($pageLayer);
        $objForm->addToForm($summaryLayer);
        $objForm->addToForm($contentLayer);
        $objForm->addToForm($buttonLayer);
        $createForm = $objForm->show();
        $str = $createForm;
        
        return $str;       
    }

    /**
    * Method to display the preview content area
    *
    * @access public
    * @param string $name: The name of the page
    * @param string $content: The page content
    * @return string $str: The output string
    **/
    public function showPreview($name, $content)
    {
        // text eleements
        $pageLabel = $this->objLanguage->languageText('mod_wiki2_pagename', 'wiki2');
        $noPreviewLabel = $this->objLanguage->languageText('mod_wiki2_nopreview', 'wiki2');
        
        if(!empty($name) || !empty($content)){
            if(!empty($name)){
                $pageName = $this->objWiki->renderTitle($name);
            }else{
                $pageName = $pageLabel;
            }
            $objHeader = new htmlheading();
            $objHeader->str = $pageName;
            $objHeader->type = 1;
            $heading = $objHeader->show();
            $str = $heading;
        
            $str .= $this->objWiki->transform($content).'<br />';
        }else{
            $str = '<ul><li>'.$noPreviewLabel.'</li></ul>';
        }
        
         echo $str;
    }
    
    /**
    * Method create a list all wiki pages
    *
    * @access private
    * @return string $str: The output string
    **/
    private function _showSummaries()
    {
        // text elements
        $pageLabel = $this->objLanguage->languageText('mod_wiki2_pagename', 'wiki2');
        $summaryLabel = $this->objLanguage->languageText('word_summary');
        $noRecordsLabel = $this->objLanguage->languageText('mod_wiki2_norecords', 'wiki2');
        $pageTitleLabel = $this->objLanguage->languageText('mod_wiki2_pagetitle', 'wiki2');
        
        // get data
        $data = $this->objDbwiki->getAllCurrentPages();

        // create display table
        $objTable = new htmltable();
        $objTable->id = 'summaryList';
        $objTable->css_class = 'sorttable';
        $objTable->cellpadding = '2';
        $objTable->border = '1';
        $objTable->row_attributes = ' name="row_'.$objTable->id.'"';
        $objTable->startRow();
        $objTable->addCell($pageLabel, '25%', '', '', 'heading', '');
        $objTable->addCell($summaryLabel, '', '', '', 'heading', '');
        $objTable->endRow();
        
        if(empty($data)){
            // no records
            $objTable->startRow();
            $objTable->addCell($noRecordsLabel, '', '', '', 'noRecordsMessage', 'colspan="4"');
            $objTable->endRow();
        }else{
            // loop through data and display each record in the table
            foreach($data as $line){
                $name = $line['page_name'];
                $pageTitle = $this->objWiki->renderTitle($name);
                $summary = $this->objWiki->transform($line['page_summary']);

                // page name link
                $objLink = new link($this->uri(array(
                    'action' => 'view_page',
                    'name' => $name,
                ), 'wiki2'));
                $objLink->link = $pageTitle;
                $objLink->title = $pageTitleLabel;
                $pageLink = $objLink->show();
                                
                // data display
                $objTable->startRow();
                $objTable->addCell($pageLink, '', '', '', '', '');
                $objTable->addCell($summary, '', '', '', '', '');
                $objTable->endRow();
            }
        }
        $pageTable = $objTable->show();
        $str = $pageTable;
        
        return $str;
    }

    /**
    * Method create a list all wiki pages
    *
    * @access private
    * @return string $str: The output string
    **/
    private function _showAllPages()
    {
        // text elements
        $titleLabel = $this->objLanguage->languageText('mod_wiki2_view', 'wiki2');
        $pageLabel = $this->objLanguage->languageText('mod_wiki2_pagename', 'wiki2');
        $authorLabel = $this->objLanguage->languageText('word_author');
        $dateLabel = $this->objLanguage->languageText('mod_wiki2_datecreated', 'wiki2');
        $noRecordsLabel = $this->objLanguage->languageText('mod_wiki2_norecords', 'wiki2');
        $editLabel = $this->objLanguage->languageText('word_edit');
        $editTitleLabel = $this->objLanguage->languageText('mod_wiki2_edittitle', 'wiki2');
        $deleteLabel = $this->objLanguage->languageText('word_delete');
        $deleteTitleLabel = $this->objLanguage->languageText('mod_wiki2_deletetitle', 'wiki2');
        $delConfirmLabel = $this->objLanguage->languageText('mod_wiki2_deleteconfirm', 'wiki2');
        $pageTitleLabel = $this->objLanguage->languageText('mod_wiki2_pagetitle', 'wiki2');
        $authorTitleLabel = $this->objLanguage->languageText('mod_wiki2_authortitle', 'wiki2');
        
        // get data
        $data = $this->objDbwiki->getAllCurrentPages();

        // create display table
        $objTable = new htmltable();
        $objTable->id = 'pageList';
        $objTable->css_class = 'sorttable';
        $objTable->cellpadding = '2';
        $objTable->border = '1';
        $objTable->row_attributes = ' name="row_'.$objTable->id.'"';
        $objTable->startRow();
        $objTable->addCell($pageLabel, '', '', '', 'heading', '');
        $objTable->addCell($authorLabel, '', '', '', 'heading', '');
        $objTable->addCell($dateLabel, '', '', 'center', 'heading', '');
        $objTable->addCell('&#160;', '', '', '', 'heading', '');
        $objTable->endRow();
        
        if(empty($data)){
            // no records
            $objTable->startRow();
            $objTable->addCell($noRecordsLabel, '', '', '', 'noRecordsMessage', 'colspan="4"');
            $objTable->endRow();
        }else{
            // loop through data and display each record in the table
            foreach($data as $line){
                $name = $line['page_name'];
                $pageTitle = $this->objWiki->renderTitle($name);
                $authorId = $line['page_author_id'];
                $author = $this->objUser->fullname($authorId);
                $date = $this->objDate->formatDate($line['date_created']);
                $type = $line['main_page'];
                $locked = $line['page_lock'];
                $lockerId = $line['page_locker_id'];
                
                // page name link
                $objLink = new link($this->uri(array(
                    'action' => 'view_page',
                    'name' => $name,
                ), 'wiki2'));
                $objLink->link = $pageTitle;
                $objLink->title = $pageTitleLabel;
                $pageLink = $objLink->show();
                
                // author link
                $objLink = new link($this->uri(array(
                    'action' => 'view_authors',
                    'author' => $authorId,
                ), 'wiki2'));
                $objLink->link = $author;
                $objLink->title = $authorTitleLabel;
                $authorLink = $objLink->show();
                
                // edit link
                $objLink = new link($this->uri(array(
                    'action' => 'view_page',
                    'name' => $name,
                    'mode' => 'edit',
                    'tab' => 1,
                ), 'wiki2'));
                $objLink->link = $editLabel;
                $objLink->title = $editTitleLabel;
                $editLink = $objLink->show();
                
                // delete link
                $objLink = new link($this->uri(array(
                    'action' => 'delete_page',
                    'name' => $name,
                ), 'wiki2'));
                $objLink->link = $deleteLabel;
                $objLink->title = $deleteTitleLabel;
                $objLink->extra = 'onclick="javascript:if(!confirm(\''.$delConfirmLabel.'\')){return false};"';
                $deleteLink = $objLink->show();
                
                if($type == 1){
                    $links = $editLink;
                }else{
                    $links = $editLink.'&#160;|&#160;'.$deleteLink;
                }
                
                // data display
                $objTable->startRow();
                $objTable->addCell($pageLink, '', '', '', '', '');
                $objTable->addCell($authorLink, '30%', '', '', '', '');
                $objTable->addCell($date, '20%', '', 'center', '', '');
                $objTable->addCell($links, '15%', '', 'center', '', '');
                $objTable->endRow();
            }
        }
        $pageTable = $objTable->show();
        $str = $pageTable;
        
        return $str;
    }

    /**
    * Method to create the formatting rules popup
    *
    * @access public
    * @return string $str: The output string
    **/
    public function showFormattingRules()
    {
         // text elements
         $formatLabel = $this->objLanguage->languageText('mod_wiki2_format', 'wiki2');
         
         // formatting rule string
         $formattingRules = '[[toc]]
----
+++ General Notes
The markup described on this page is for the default {{Text_Wiki}} rules; 
it is a combination of the [http://tavi.sourceforge.net WikkTikkiTavi] 
and [http://develnet.org/ coWiki] markup styles.

All text is entered as plain text, and will be converted to HTML entities as 
necessary.  This means that {{<}}, {{>}}, {{&}}, and so on are converted for 
you (except in special situations where the characters are Wiki markup; 
Text_Wiki is generally smart enough to know when to convert and when not to).

Just hit return twice to make a paragraph break.  If you want 
to keep the same logical line but have to split it across 
two physical lines (such as when your editor only shows a certain number 
of characters per line), end the line with a backslash {{\}} and hit 
return once:

<code>
This will cause the two lines to be joined on display, and the \ 
backslash will not show.
</code>

This will cause the two lines to be joined on display, and the \
backslash will not show.

(If you end a line with a backslash and a tab or space,
it will not be joined with the next line, and the backslash \ 
will be printed.)
----
+++ Inline Formatting
|| {{``//emphasis text//``}}                 || //emphasis text// ||
|| {{``**strong text**``}}                   || **strong text** ||
|| {{``//**emphasis and strong**//``}}       || //**emphasis and strong**// ||
|| {{``{{teletype text}}``}}                    || {{teletype text}} ||
|| {{``@@--- delete text +++ insert text @@``}} || @@--- delete text +++ insert text @@ ||
|| {{``@@--- delete only @@``}}                 || @@--- delete only @@ ||
|| {{``@@+++ insert only @@``}}                 || @@+++ insert only @@ ||
----
+++ Literal Text
If you dont want Text_Wiki to parse some text, enclose it in two backticks (not single-quotes).
<code>
This //text// gets **parsed**.
``This //text// does not get **parsed**.``
</code>
This //text// gets **parsed**.
``This //text// does not get **parsed**.``
----
+++ Headings
You can make various levels of heading by putting 
plus-signs before the text (all on its own line):
<code>
+++ Level 3 Heading
++++ Level 4 Heading
+++++ Level 5 Heading
++++++ Level 6 Heading
</code>
+++ Level 3 Heading
++++ Level 4 Heading
+++++ Level 5 Heading
++++++ Level 6 Heading
----
+++ Table of Contents
To create a list of every heading, with a link to that heading, put a table of contents tag on its own line.
<code>
[[toc]]
</code>
----
+++ Horizontal Rules
Use four dashes ({{``----``}}) to create a horizontal rule.
----
+++ Lists
++++ Bullet Lists
You can create bullet lists by starting a paragraph with one or more asterisks.
<code>
* Bullet one
 * Sub-bullet
</code>
* Bullet one
 * Sub-bullet
++++ Numbered Lists
Similarly, you can create numbered lists by starting a paragraph 
with one or more hashes.
<code>
# Numero uno
# Number two
 # Sub-item
</code>
# Numero uno
# Number two
 # Sub-item
++++ Mixing Bullet and Number List Items
You can mix and match bullet and number lists:
<code>
# Number one
 * Bullet
 * Bullet
# Number two
 * Bullet
 * Bullet
   * Sub-bullet
 # Sub-sub-number
 # Sub-sub-number
# Number three
 * Bullet
 * Bullet
</code>
# Number one
 * Bullet
 * Bullet
# Number two
 * Bullet
 * Bullet
  * Sub-bullet
 # Sub-sub-number
 # Sub-sub-number
# Number three
 * Bullet
 * Bullet
++++ Definition Lists
You can create a definition (description) list with the following syntax:
<code>
: Item 1 : Something
: Item 2 : Something else
</code>
: Item 1 : Something
: Item 2 : Something else
----
+++ Block Quotes
You can mark a blockquote by starting a line with one or more > 
characters, followed by a space and the text to be quoted.
<code>
This is normal text here.
> Indent me! The quick brown fox jumps over the lazy dog. 
Now this the time for all good men to come to the aid of 
their country. Notice how we can continue the block-quote 
in the same paragraph by using a backslash at the end of the line.
>
> Another block, leading to...
>> Second level of indenting.
Back to normal text.
</code>

This is normal text here.
> Indent me! The quick brown fox jumps over the lazy dog. Now this the time for all good men to come to the aid of their country. Notice how we can continue the block-quote \
in the same paragraph by using a backslash at the end of the line.
>
> Another block, leading to...
>> Second level of indenting.  This second is indented even more than \
the previous one.
Back to normal text.
----
+++ Links and Images
++++ Wiki Links
SmashWordsTogether to create a page link.
You can force a WikiPage name not to be clickable by putting 
an exclamation mark in front of it.
<code>
WikiPage !WikiPage
</code>
WikiPage !WikiPage

You can create a described or labeled link to a wiki page by putting the page name in brackets, followed by some text.
<code>
[WikiPage Descriptive text for the link.]
</code>
[WikiPage Descriptive text for the link.]
> **Note:** existing wiki pages must be in the [RuleWikilink wikilink] {{pages}} configuration,  and the [RuleWikilink wikilink] {{view_url}} configuration value must be set for the linking to work.
++++ Interwiki Links
Interwiki links are links to pages on other Wiki sites. 
Type the {{``SiteName:PageName``}} like this:
* MeatBall:RecentChanges
* Advogato:proj/WikkiTikkiTavi
* Wiki:WorseIsBetter
> **Note:** the interwiki site must be in the [RuleInterwiki interwiki] {{sites}} configuration array.
++++ URLs
Create a remote link simply by typing its URL: http://ciaweb.net.
If you like, enclose it in brackets to create a numbered reference and avoid cluttering the page; {{``[http://ciaweb.net/free/]``}} becomes [http://ciaweb.net/free/].
Or you can have a described-reference instead of a numbered reference:
<code>
[http://pear.php.net PEAR]
</code>
[http://pear.php.net PEAR]
++++ Images
You can put a picture in a page by typing the URL to the picture 
(it must end in gif, jpg, or png).
<code>
http://c2.com/sig/wiki.gif
</code>
http://c2.com/sig/wiki.gif
You can use the described-reference URL markup to give the image an ALT tag:
<code>
[http://phpsavant.com/etc/fester.jpg Fester]
</code>
[http://phpsavant.com/etc/fester.jpg Fester]
----
+++ Code Blocks
Create code blocks by using {{<code>...</code>}} tags (each on its own line).
<code>
This is an example code block!
</code>
To create PHP blocks that get automatically colorized when you use PHP tags, simply surround the code with {{<code type=php>...</code>}} tags (the tags themselves should each be on their own lines, and no need for the {{<?php ... ?>}} tags).
<code>
 <code type="php">
 // Set up the wiki options
 $options = array();
 $options[view_url] = index.php?page=;
 // load the text for the requested page
 $text = implode(\, file($page . .wiki.txt));
 // create a Wiki objext with the loaded options
 $wiki = new Text_Wiki($options);
 // transform the wiki text.
 echo $wiki->transform($text);
 </code>
</code>
<code type="php">
// Set up the wiki options
$options = array();
$options[view_url] = index.php?page=;
// load the text for the requested page
$text = implode( file($page . .wiki.txt));
// create a Wiki object with the loaded options
$wiki = new Text_Wiki($options);
// transform the wiki text.
echo $wiki->transform($text);
</code>
----
+++ Tables
You can create tables using pairs of vertical bars:
<code>
|| cell one || cell two ||
|||| big long line ||
|| cell four || cell five ||
|| cell six || heres a very long cell ||
</code>
|| cell one || cell two ||
|||| big long line ||
|| cell four || cell five ||
|| cell six || heres a very long cell ||
<code>
|| lines must start and end || with double vertical bars || nothing ||
|| cells are separated by || double vertical bars || nothing ||
|||| you can span multiple columns by || starting each cell ||
|| with extra cell |||| separators ||
|||||| but perhaps an example is the easiest way to see ||
</code>
|| lines must start and end || with double vertical bars || nothing ||
|| cells are separated by || double vertical bars || nothing ||
|||| you can span multiple columns by || starting each cell ||
|| with extra cell |||| separators ||
|||||| but perhaps an example is the easiest way to see ||';

        // parse formatting string
        $string = $this->objWiki->transform($formattingRules);
        
        // create feature box
        $formatFeature = $this->objFeature->show($formatLabel, $string);
        
        // main display layer
        $objLayer = new layer();
        $objLayer->addToStr($formatFeature);
        $objLayer->padding = '10px';
        $str = $objLayer->show();
        return $str;  
    }
    
    /**
    * Method to display the search results content area
    *
    * @access public
    * @param string $data: The seach data that was returned
    * @return string $str: The output string
    **/
    public function showSearch($data)
    {
        // text elements
        $listLabel = $this->objLanguage->languageText('mod_wiki2_searchlist', 'wiki2');
        $pageLabel = $this->objLanguage->languageText('mod_wiki2_pagename', 'wiki2');
        $authorLabel = $this->objLanguage->languageText('mod_wiki2_author', 'wiki2');
        $dateLabel = $this->objLanguage->languageText('mod_wiki2_datecreated', 'wiki2');
        $noRecordsLabel = $this->objLanguage->languageText('mod_wiki2_norecords', 'wiki2');
        $pageTitleLabel = $this->objLanguage->languageText('mod_wiki2_pagetitle', 'wiki2');
        $authorTitleLabel = $this->objLanguage->languageText('mod_wiki2_authortitle', 'wiki2');
        
        // create display table
        $objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->border = '1';
        $objTable->row_attributes = ' name="row_'.$objTable->id.'"';
        $objTable->startRow();
        $objTable->addCell('<b>'.$pageLabel.'</b>', '', '', '', 'heading', '');
        $objTable->addCell('<b>'.$authorLabel.'</b>', '', '', '', 'heading', '');
        $objTable->addCell('<b>'.$dateLabel.'</b>', '', '', 'center', 'heading', '');
        $objTable->endRow();

        if(empty($data)){
            // no records
            $objTable->startRow();
            $objTable->addCell($noRecordsLabel, '', '', '', 'noRecordsMessage', 'colspan="3"');
            $objTable->endRow();
        }else{
            // loop through data and display each record in the table
            $i = 0;
            foreach($data as $line){
                $class = (($i++ % 2) == 0)?'even':'odd';
                $name = $line['page_name'];
                $pageTitle = $this->objWiki->renderTitle($name);
                $authorId = $line['page_author_id'];
                $author = $this->objUser->fullname($authorId);
                $date = $this->objDate->formatDate($line['date_created']);
                $type = $line['main_page'];
                $locked = $line['page_lock'];
                $lockerId = $line['page_locker_id'];
                
                // page name link
                $objLink = new link($this->uri(array(
                    'action' => 'view_page',
                    'name' => $name,
                ), 'wiki2'));
                $objLink->link = $pageTitle;
                $objLink->title = $pageTitleLabel;
                $pageLink = $objLink->show();
                
                // author link
                $objLink = new link($this->uri(array(
                    'action' => 'view_authors',
                    'author' => $authorId,
                ), 'wiki2'));
                $objLink->link = $author;
                $objLink->title = $authorTitleLabel;
                $authorLink = $objLink->show();
                
                // data display
                $objTable->startRow();
                $objTable->addCell($pageLink, '', '', '', $class, '');
                $objTable->addCell($authorLink, '30%', '', '', $class, '');
                $objTable->addCell($date, '20%', '', 'center', $class, '');
                $objTable->endRow();
            }
        }
        $pageTable = $objTable->show();
            
        $objLayer = new layer();
        $objLayer->cssClass = 'featurebox';
        $objLayer->addToStr($pageTable);
        $contentLayer = $objLayer->show();
    
        $string = $contentLayer;
        
        // add page tab
        $tabArray = array(
            'name' => $listLabel,
            'content' => $string,
        );
       
        //display tabs        
        $this->objTab->addTab($tabArray);
        $this->objTab->useCookie = 'false';
        $str = $this->objTab->show();
        
        return $str;
    }

    /**
    * Method to display the author area
    *
    * @access public
    * @param string $author: The id of the author to show
    * @return string $str: The output string
    **/
    public function showAuthors($author = NULL)
    {
        // add javascript to sort table
        $headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);

        // text elements
        $listLabel = $this->objLanguage->languageText('mod_wiki2_authorlist', 'wiki2');
        $detailsLabel = $this->objLanguage->languageText('mod_wiki2_author', 'wiki2');
        $authorsLabel = $this->objLanguage->languageText('word_authors');
        $authorTitleLabel = $this->objLanguage->languageText('mod_wiki2_authortitle', 'wiki2');
        $numberLabel = $this->objLanguage->languageText('word_number');
        $noRecordsLabel = $this->objLanguage->languageText('mod_wiki2_norecords', 'wiki2');
        $articleLabel = $this->objLanguage->languageText('word_article');
        $pageTitleLabel = $this->objLanguage->languageText('mod_wiki2_pagetitle', 'wiki2');        
        
        // get data
        if(empty($author)){
            $data = $this->objDbwiki->getAuthors();
            // create display table
            $objTable = new htmltable();
            $objTable->id = 'authorList';
            $objTable->css_class = 'sorttable';
            $objTable->cellpadding = '2';
            $objTable->border = '1';
            $objTable->row_attributes = ' name="row_'.$objTable->id.'"';
            $objTable->startRow();
            $objTable->addCell($authorsLabel, '', '', '', 'heading', '');
            $objTable->addCell($numberLabel, '10%', '', 'center', 'heading', '');
            $objTable->endRow();

            if(empty($data)){
                // no records
                $objTable->startRow();
                $objTable->addCell($noRecordsLabel, '', '', '', 'noRecordsMessage', 'colspan="3"'   );
                $objTable->endRow();
            }else{
                // loop through data and display each record in the table
                foreach($data as $line){
                    $authorId = $line['page_author_id'];
                    $author = $this->objUser->fullname($authorId);
                    $number = $line['cnt'];
    
                    // author link
                    $objLink = new link($this->uri(array(
                        'action' => 'view_authors',
                        'author' => $authorId,
                    ), 'wiki2'));
                    $objLink->link = $author;
                    $objLink->title = $authorTitleLabel;
                    $authorLink = $objLink->show();
                            
                    // data display
                    $objTable->startRow();
                    $objTable->addCell($authorLink, '', '', '', '', '');
                    $objTable->addCell($number, '', '', 'center', '', '');
                    $objTable->endRow();
                }
            }
            $pageTable = $objTable->show();
            
            $objLayer = new layer();
            $objLayer->cssClass = 'featurebox';
            $objLayer->addToStr($pageTable);
            $contentLayer = $objLayer->show();
        
            $string = $contentLayer;
            
            // add page tab
            $tabArray = array(
                'name' => $listLabel,
                'content' => $string,
            );       
        }else{
            $data = $this->objDbwiki->getAuthorArticles($author);
            $user = $this->objUserAdmin->getUserDetails($this->objUser->PKId($author));
            $this->objBizCard->setUserArray($user);
            $bizCard = $this->objBizCard->show();

            $objLayer = new layer();
            $objLayer->cssClass = 'featurebox';
            $objLayer->addToStr($bizCard);
            $contentLayer = $objLayer->show();
        
            $string = $contentLayer.'<br />';
            

            // create display table
            $objTable = new htmltable();
            $objTable->id = 'articleList';
            $objTable->css_class = 'sorttable';
            $objTable->cellpadding = '2';
            $objTable->border = '1';
            $objTable->row_attributes = ' name="row_'.$objTable->id.'"';
            $objTable->startRow();
            $objTable->addCell($articleLabel, '', '', '', 'heading', '');
            $objTable->endRow();

            if(empty($data)){
                // no records
                $objTable->startRow();
                $objTable->addCell($noRecordsLabel, '', '', '', 'noRecordsMessage', 'colspan="3"'   );
                $objTable->endRow();
            }else{
                // loop through data and display each record in the table
                foreach($data as $line){
                    $name = $line['page_name'];
                    $pageTitle = $this->objWiki->renderTitle($name);

                    // page name link
                    $objLink = new link($this->uri(array(
                    'action' => 'view_page',
                        'name' => $name,
                        'tab' => 2,
                    ), 'wiki2'));
                    $objLink->link = $pageTitle;
                    $objLink->title = $pageTitleLabel;
                    $pageLink = $objLink->show();
                
                    // data display
                    $objTable->startRow();
                    $objTable->addCell($pageLink, '', '', '', '', '');
                    $objTable->endRow();
                }
            }
            $pageTable = $objTable->show();
            
            $objLayer = new layer();
            $objLayer->cssClass = 'featurebox';
            $objLayer->addToStr($pageTable);
            $contentLayer = $objLayer->show();
        
            $string .= $contentLayer;
            
            // add page tab
            $tabArray = array(
                'name' => $detailsLabel,
                'content' => $string,
            );       
        }
        //display tabs        
        $this->objTab->addTab($tabArray);
        $this->objTab->useCookie = 'false';
        $str = $this->objTab->show();
        
        return $str.'<br />';
    }
    
    /** 
    * Method to create the page name error message
    *
    * @access public
    * @param string $name: The name of the page to validate
    * @return string $str: The output string
    **/
    public function showValidateName($name)
    {
        $camelcaseLabel = $this->objLanguage->languageText('mod_wiki2_camelcase', 'wiki2');
        $lettersonlyLabel = $this->objLanguage->languageText('mod_wiki2_lettersonly', 'wiki2');
        $existsLabel = $this->objLanguage->languageText('mod_wiki2_exists', 'wiki2');
        
        $errors = array();
        
        // check name
        $data = $this->objDbwiki->getPage($name);
        if(!empty($data)){
            $errors[] = $existsLabel;
        }
        
        // check alpha only
        if(preg_match('/\P{L}/', $name) == 1){
            $errors[] = $lettersonlyLabel;
        }
        
        // check camel case first
        if(!ereg('^([A-Z]([a-z]+)){2,}$', $name)){
            $errors[] = $camelcaseLabel;
        }        
        
        if(!empty($errors)){
            $string = '<ul>';
            foreach($errors as $error){
                $string .= '<li><font class="warning">'.$error.'</font></li>';
            }
            $string .= '</ul>';
        }else{
            $string = '';
        }

        echo $string;
    }
}
?>