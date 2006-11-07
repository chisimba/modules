<?php
/**
* etd class extends controller
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Controller class for etd module
* @author Megan Watson
* @author Jonathan Abrahams
* @copyright (c) 2004 UWC
* @version 1.0
* @modified Megan Watson 2006-10-24 Porting to 5ive
*/

class etd extends controller
{
    /**
    * var $accessLevel The allowed access for the user.
    *
    public $accessLevel = 0;

    /**
    * Method to construct the class.
    */
    public function init()
    {
        $this->etdTools =& $this->getObject('etdtools', 'etd');
        $this->manage =& $this->getObject('management', 'etd');
        $this->config =& $this->getObject('configure', 'etd');
        $this->dbStats =& $this->getObject('dbstatistics', 'etd');
        $this->dbThesis =& $this->getObject('dbthesis');
        $this->dbThesis->setSubmitType('etd');
        
        $this->etdSearch =& $this->getObject('search', 'etd');
        $this->etdSearch->setMetaType('thesis', 'etd');
        
        $this->emailResults =& $this->getObject('emailresults');
        $this->emailResults->setModuleName('etd');
        
        $this->objConfig =& $this->getObject('altconfig', 'config');
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->setVarByRef('objLanguage', $this->objLanguage);
        
        $this->objBlocks = & $this->newObject('blocks', 'blocks');
        $this->objFeeder = & $this->newObject('feeder', 'feed');
        /*
        $this->etdSubmit =& $this->getObject('submit');
        $this->dbSubmit =& $this->getObject('dbsubmissions');
        $this->dbThesisMeta =& $this->getObject('dbthesis');
        $this->dbFiles =& $this->getObject('dbfiles');
        $this->dbCollection =& $this->getObject('dbcollection');
        $this->dbCollectSubmit =& $this->getObject('dbcollectsubmit');
        $this->etdConfig =& $this->getObject('configure');
        $this->dbEtdConfig =& $this->getObject('dbetdconfig');
        $this->dbInstitute =& $this->getObject('dbinstituteinfo');
        $this->dbEmbargo =& $this->getObject('dbembargo');
        $this->xmlMetaData =& $this->getObject('xmlmetadata');
        $this->objKeyword =& $this->getObject('keyword');

        $this->objDate =& $this->getObject('simplecal', 'datetime');
        $this->objGroup =& $this->getObject('groupadminmodel', 'groupadmin');
        $this->objUser =& $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->userPkId = $this->objUser->PKId();

        $this->objFile =& $this->getObject('fileupload', 'filestore');
        $this->objFile->changeTables('tbl_etd_filestore', 'tbl_etd_blob');

        // Log this call if registered
        $this->objModules =& $this->newObject('modulesadmin','modulelist');
        if(!$this->objModules->checkIfRegistered('logger', 'logger')){
            //Get the activity logger class
            $this->objLog=$this->newObject('logactivity', 'logger');
            //Log this module call
            $this->objLog->log();
        }

        // Get audit trail class
        $this->objAudit =& $this->getObject('audit_facet', 'audit');

        // Determine the users access level
        $this->accessLevel();
        */
    }

    /**
    * Standard dispatch function
    *
    * @access public
    * @param string $action The action to be performed
    * @return string Template to be displayed
    */
    public function dispatch($action)
    {
        /*
        // Access control for browse pages
        $isManager = FALSE;

        // unset keywords session
        if($action != 'addkeywords' && $action != 'managekeywords'){
            $this->unsetSession('keyForm');
            $this->unsetSession('keyField');
            $this->unsetSession('keyManage');
        }
        */
        $this->unsetSession('resourceId');

        switch($action){
            
            /* *** Functions for displaying the resources *** */
            
            case 'viewauthor':
            case 'viewtitle':
                $this->unsetSession('resource');
                $metaId = $this->getParam('id');
                $resource = $this->dbThesis->getMetadata($metaId);
//                $files = $this->dbFiles->getFiles($etd['submitId']);
                $this->setVarByRef('resource', $resource);
//                $this->setVarByRef('files', $files);
                $this->dbStats->recordVisit($metaId);
                $this->setSession('resourceId', $metaId);
                $rightSide = $this->objBlocks->showBlock('resourcemenu', 'etd');
                $this->etdTools->setRightSide($rightSide);
                return 'showetd_tpl.php';

            case 'printresource':
                $search = $this->getSession('resource');
                $this->setVarByRef('search', $search);
                return 'print_tpl.php';

            case 'emailresource':
                $resource = $this->getSession('resource');
                $head = $this->objLanguage->languageText('phrase_emailresource');
                $message = $this->objLanguage->languageText('mod_etd_attachmentresource', 'etd');
                $shortName = $this->objConfig->getinstitutionShortName().':';
                $subject = $this->objLanguage->code2Txt('mod_etd_requestedresource', 'etd', array('shortname' => $shortName));
                $this->emailResults->setHeading($head);
                $this->emailResults->setSubject($subject, TRUE);
                $this->emailResults->setMessage($message);
                $this->emailResults->setEmailBody($resource);
                $email = $this->emailResults->showEmail();
                $this->setVarByRef('search', $email);
                return 'search_tpl.php';

            /* *** Functions for browsing the repository *** */

//            case 'browsecollection':
//                $objCollection = & $this->getObject( 'dbcollection', 'etd' );
//                $this->setVar( 'browseType', $objCollection );
//                $this->setVar( 'isManager', FALSE );
//                return 'browse_tpl.php';

            case 'browseauthor':
                $this->unsetSession('resource');
                // set a session to use when returning from a resource or from emailing a resource.
                $session = array();
                $session['searchForLetter'] = $this->getParam('searchForLetter');
                $session['displayLimit'] = $this->getParam('displayLimit');
                $session['displayStart'] = $this->getParam('displayStart');
                $session['action'] = 'browseauthor';
                $this->setSession('return', $session);
                
                $objAuthor = & $this->getObject('dbthesis', 'etd');
                $objAuthor->setBrowseType('author');
                $this->setVar('num', 3);
                $this->setVarByRef('browseType', $objAuthor);
                return 'browse_tpl.php';

            case 'browsetitle':
                $this->unsetSession('resource');
                // set a session to use when returning from a resource or from emailing a resource.
                $session = array();
                $session['searchForLetter'] = $this->getParam('searchForLetter');
                $session['displayLimit'] = $this->getParam('displayLimit');
                $session['displayStart'] = $this->getParam('displayStart');
                $session['action'] = 'browsetitle';
                $this->setSession('return', $session);
                
                $objTitle = & $this->getObject('dbthesis', 'etd');
                $objTitle->setBrowseType('title');
                $this->setVar('num', 3);
                $this->setVarByRef('browseType', $objTitle);
                return 'browse_tpl.php';
            
            /* ** Functions for searching ** */
            
            case 'search':
                $search = $this->etdSearch->showSearch();
                $this->etdTools->setRightBlocks(FALSE, TRUE, FALSE);
                $this->setVarByRef('search', $search);
                return 'search_tpl.php';
                
            case 'advsearch':
                $this->unsetSession('resource');
                // set a session to use when returning from a resource or from emailing a resource.
                $session['displayLimit'] = $this->getParam('displayLimit');
                $session['displayStart'] = $this->getParam('displayStart');
                $session['action'] = 'advsearch';
                $this->setSession('return', $session);
                
                $pageTitle = $this->objLanguage->languageText('phrase_searchresults');
                $objViewBrowse = & $this->getObject('viewbrowse', 'etd');
                $objViewBrowse->create($this->etdSearch);
                $objViewBrowse->setAccess( FALSE );
                $objViewBrowse->showAlpha(FALSE);
                $objViewBrowse->showPrint();
//                $objViewBrowse->useSortTable();
                $objViewBrowse->setNumCols(3);
                $objViewBrowse->setPageTitle($pageTitle);
                $this->objLink = new link($this->uri(array('action'=>'search')));
                $this->objLink->link = $this->objLanguage->languageText('phrase_newsearch');
                $criteria = $this->etdSearch->getSession('criteria');
                $objViewBrowse->addExtra($criteria.'<p>'.$this->objLink->show().'</p>');
                $search = $objViewBrowse->show();
                $this->setVarByRef('search', $search);
                $this->etdTools->setRightBlocks(FALSE, TRUE, FALSE);
                return 'search_tpl.php';
                
            case 'printsearch':
                $pageTitle = $this->objLanguage->languageText('phrase_searchresults');
                $objViewBrowse = & $this->getObject('viewbrowse', 'etd');
                $objViewBrowse->create($this->etdSearch);
                $search = $objViewBrowse->getResults();
                $this->setVarByRef('search', $search);
                return 'print_tpl.php';

            case 'emailsearch':
                $pageTitle = $this->objLanguage->languageText('phrase_searchresults');
                $shortName = $this->objConfig->getinstitutionShortName().':';
                $subject = $this->objLanguage->code2Txt('mod_etd_requestedsearchresults', 'etd', array('shortname' => $shortName));
                $message = $this->objLanguage->languageText('mod_etd_attachmentsearchresults', 'etd');
                $objViewBrowse = & $this->getObject( 'viewbrowse', 'etd' );
                $objViewBrowse->create($this->etdSearch);
                $search = $objViewBrowse->getResults();
                $this->emailResults->setEmailBody($search);
                $this->emailResults->setSubject($subject, TRUE);
                $this->emailResults->setMessage($message);
                $email = $this->emailResults->showEmail();
                $this->setVarByRef('search', $email);
                return 'search_tpl.php';
                
            case 'sendemail':
                $confirm = $this->objLanguage->languageText('mod_etd_confirmemailsent', 'etd');
                $link = $this->objLanguage->languageText('mod_etd_returnsearch', 'etd');
                $return = $this->getSession('return');
                $pos = strpos($return['action'], 'browse');
                if(!($pos === FALSE)){
                    $link = $this->objLanguage->languageText('mod_etd_returnbrowse', 'etd');
                }
                $email = $this->emailResults->sendEmail();
                $objLink =& $this->newObject('link', 'htmlelements');
                $objLink = new link($this->uri($return));
                $objLink->link = $link;
                $search = '<p class="confirm">'.$confirm.'</p><p>'.$objLink->show().'</p>';
                $this->setVarByRef('search', $search);
                return 'search_tpl.php';
                
            /* ** Functions for managing the archive ** */
            
            case 'managesubmissions':
                $mode = $this->getParam('mode');
                $display = $this->manage->show($mode);
                $this->etdTools->setRightBlocks(FALSE, TRUE, FALSE);
                $this->setVarByRef('search', $display);
                return 'search_tpl.php';
                
            case 'savesubmissions':
                $save = $this->getParam('save');
                $mode = $this->getParam('mode');
                $nextmode = $this->getParam('nextmode');
                if(!empty($save)){
                    $this->manage->show($mode);
                }
                return $this->nextAction('managesubmissions', array('mode' => $nextmode));

            /* *** Functions for configuring the archive *** */
            
            case 'showconfig':
                $mode = $this->getParam('mode');
                $display = $this->config->show($mode);
                $this->etdTools->setRightBlocks(TRUE, TRUE, FALSE);
                $this->setVarByRef('search', $display);
                return 'search_tpl.php';
                break;
                
            case 'saveconfig':
                $save = $this->getParam('save');
                $mode = $this->getParam('mode');
                $nextmode = $this->getParam('nextmode');
                if(!empty($save)){
                    $this->config->show($mode);
                }
                return $this->nextAction('showconfig', array('mode' => $nextmode));
                break;
            

        /*
            case 'showinfo':
                $heading = $this->objLanguage->languageText('mod_etd_name');
                $body = $this->objLanguage->languageText('mod_etd_intro');
                $this->setVarByRef('heading', $heading);
                $this->setVarByRef('body', $body);
                return 'etdinfo_tpl.php';

            case 'viewembargo':
                return $this->viewEmbargo();

            case 'viewetd':
                $mode = TRUE;

            case 'showetd': // xml based - pre archive
                $submitId = $this->getParam('submitId');
                $mode = $this->getParam('mode', FALSE);
                $submitStatus = $this->getParam('submitStatus', FALSE);
                $etd = $this->dbSubmit->getETD($submitId, $submitStatus);
                $files = $this->dbFiles->getFiles($submitId);
                $this->setVarByRef('mode', $mode);
                $this->setVarByRef('submitStatus', $submitStatus);
                $this->setVarByRef('etd', $etd);
                $this->setVarByRef('files', $files);
                return 'showetd_tpl.php';

            case 'showextra':
                $submitId = $this->getParam('submitId');
                $mode = $this->getParam('mode', FALSE);
                $submitStatus = $this->getParam('submitStatus', FALSE);
                $etd = $this->dbSubmit->getETD($submitId, $submitStatus);
                $this->setVarByRef('mode', $mode);
                $this->setVarByRef('etd', $etd);
                return 'showextra_tpl.php';

            case 'downloadfile':
                $this->setPageTemplate('download_page_tpl.php');
                return 'download_tpl.php';

            /* *** Configure ETD *** *

            case 'configureetd':
                $this->etdConfig->index();
                return 'configure_tpl.php';

            case 'configinfo':
                $exit = $this->getParam('exit', NULL);
                if(isset($exit) && !empty($exit)){
                    return $this->nextAction('configureetd');
                }
                $step = $this->getParam('step', 1);
                $extra = $this->getParam('extra', NULL);
                if($this->etdConfig->configureInfo($step, $extra)){
                    return 'configure_tpl.php';
                }
                return $this->nextAction('configureetd');

            case 'saveconfig':
                $exit = $this->getParam('exit', NULL);
                if(isset($exit) && !empty($exit)){
                    return $this->nextAction('configureetd');
                }
                $step = $this->getParam('step', 2);
                $extra = $this->getParam('extra', NULL);
                $saveaction = $this->getParam('saveaction', NULL);
                $this->objAudit->addAuditTrail('Save the configuration. Save action: '.$saveaction);
                $this->etdConfig->saveConfigInfo($saveaction);
                return $this->nextAction('configinfo', array('step'=>$step, 'extra'=>$extra));

            case 'deleteconfig':
                $id = $this->getParam('id');
                $step = $this->getParam('step', 3);
                $this->objAudit->addAuditTrail('Delete the configuration. Id: '.$id);
                $this->dbInstitute->deleteInfo($id);
                return $this->nextAction('configinfo', array('step'=>$step));

            case 'configprocess':
                $this->objAudit->addAuditTrail('');
                $this->etdConfig->configureApproval();
                return 'configure_tpl.php';

            case 'saveprocess':
                $exit = $this->getParam('exit', NULL);
                if(isset($exit) && !empty($exit)){
                    return $this->nextAction('configureetd');
                }
                $this->objAudit->addAuditTrail('Save the submission process.');
                $this->etdConfig->saveProcess();
                return $this->nextAction('configureetd');

            case 'configperms':
                $group = $this->getParam('group', NULL);
                $this->objAudit->addAuditTrail($group);
                $this->etdConfig->configurePerms($group);
                return 'configure_tpl.php';

            case 'saveconfperms':
                $button = $this->getParam('button', NULL);
                if($button == 'cancel'){
                    return $this->nextAction('configperms');
                }
                $this->objAudit->addAuditTrail('');
                $this->etdConfig->savePerms();
                return $this->nextAction('configperms');

            /* *** Keywords in submit generic class *** *

            case 'importkeywords':
                $search = $this->objKeyword->showUpload();
                $this->setVarByRef('search', $search);
                return 'print_tpl.php';

            case 'importcsv':
                $filepath = $_FILES['filepath']['tmp_name'];
                $this->objKeyword->importCSV($filepath);
                return 'submit_tpl.php';

            case 'managekeywords':
                $this->setSession('keyManage', TRUE);

            case 'addkeywords':
                $form = $this->getSession('keyForm', NULL);
                $field = $this->getSession('keyField');
                if(is_null($form)){
                    $form = $this->getParam('formname');
                    $field = $this->getParam('fieldname');
                    $this->setSession('keyForm', $form);
                    $this->setSession('keyField', $field);
                }
                $search = $this->objKeyword->showKeywords($form, $field);
                $this->setVarByRef('search', $search);
                return 'print_tpl.php';

            /* *** Submit etd *** *

            case 'submit':
                $submitId = $this->getParam('submitId', NULL);
                $page = $this->getParam('page', 1);
                $extra = $this->getParam('extra', NULL);
                $dispPage = $this->etdSubmit->createForm($submitId, $this->accessLevel, $page, $extra);
                if($dispPage === FALSE){
                    return $this->nextAction('');
                }
                $this->setVar('page', $dispPage);
                return 'submit_tpl.php';

            case 'saveetd':
                $exit = $this->getParam('exit', NULL);
                if(isset($exit) && !empty($exit)){
                    return $this->nextAction('');
                }
                $submitId = $this->getParam('submitId', NULL);
                $saveaction = $this->getParam('saveaction', 'start');
                $page = $this->getParam('page', 0)+1;
                $extra = $this->getParam('extra', NULL);
                $this->objAudit->addAuditTrail('Save edited metadata on an XML (pre-archived) document. Submission id: '.$submitId.'. Save action: '.$saveaction);
                $this->objAudit->addAuditTrail($saveaction.' '.$submitId);
                $submitId = $this->etdSubmit->saveEtd($saveaction, $submitId);
                $arr = array('submitId'=>$submitId, 'page'=>$page, 'extra'=>$extra);
                return $this->nextAction('submit', $arr);

            case 'deleteetd':
                $submitId = $this->getParam('submitId');
                $this->objAudit->addAuditTrail('XML format. Submission id: '.$submitId);
                $this->dbSubmit->deleteETD($submitId);
                $this->xmlMetaData->deleteXML('etd_'.$submitId);
                return $this->nextAction('');

            case 'archive':
                $submitId = $this->getParam('submitId');
                $this->objAudit->addAuditTrail('Move XML into dublincore table. Submission id: '.$submitId);
                $this->etdSubmit->archiveETD($submitId);
                return $this->nextAction('');

            case 'upload':
                $cancel = $this->getParam('exit', NULL);
                $submitId = $this->getParam('submitId');
                $nextAction = $this->getParam('nextAction', 'submit');
                $page = $this->getParam('page');
                $mode = $this->getParam('mode', 'add');
                if(isset($cancel) && !empty($cancel)){
                    return $this->nextAction($nextAction, array('page'=>$page, 'submitId'=>$submitId));
                }
                $this->etdSubmit->uploadEtd($submitId, $mode);
                return $this->nextAction($nextAction, array('page'=>$page, 'submitId'=>$submitId));

            case 'editfile':
                $submitId = $this->getParam('submitId');
                $dispPage = $this->etdSubmit->uploadEtd($submitId, 'edit');
                $this->objAudit->addAuditTrail('Edit an uploaded document. Submission id: '.$submitId);
                if($dispPage === FALSE){
                    return $this->nextAction('');
                }
                $this->setVar('page', $dispPage);
                return 'submit_tpl.php';

            /* *** Search Repository *** *





            /* *** Manage Collections in Repository *** *

            case 'managecollections':
                $objCollection = & $this->getObject( 'dbcollection', 'etd' );
                $this->setVar( 'browseType', $objCollection );
                $this->setVar( 'isManager', TRUE );
                return 'browse_tpl.php';

            case 'viewcollection':
                $id = $this->getParam('id', NULL);
                if(is_null($id)){
                    $id = $this->getParam('joinId');
                }
                $mode = $this->getParam('allowManage', FALSE);
                $data = $this->dbCollection->getCollection($id);
                $objTitle = & $this->getObject( 'dbthesis', 'etd' );
                $objTitle->setBrowseType( 'title' );

                $this->setVar( 'num', 3 );
                $this->setVar( 'browseType', $objTitle );
                $this->setVarByRef( 'isManager', $mode );
                $this->setVarByRef('collectionId', $id);
                $this->setVarByRef('data', $data);
                return 'viewcollection_tpl.php';

            case 'addcollection':
                $this->setVar('mode', 'add');
                return 'addcollection_tpl.php';

            case 'editcollection':
                $data = $this->dbCollection->getCollection($this->getParam('id'));
                $this->setVar('mode', 'edit');
                $this->setVarByRef('data', $data);
                return 'addcollection_tpl.php';

            case 'savecollection':
                $exit = $this->getParam('exit', NULL);
                if(isset($exit) && !empty($exit)){
                    return $this->nextAction('manageCollections');
                }
                $collectionId = $this->getParam('id', NULL);
                $this->objAudit->addAuditTrail('Add or edit a collection. Collection id: '.$collectionId);
                $this->dbCollection->saveCollection($this->userId, $collectionId);
                return $this->nextAction('manageCollections');

            case 'deletecollection':
                $collectionId = $this->getParam('id', NULL);
                if($this->dbCollection->deleteCollection($collectionId) === FALSE){
                    return $this->nextAction('manageCollections');
                }
                $this->objAudit->addAuditTrail('Delete a collection. Collection id: '.$collectionId);
                return $this->nextAction('manageCollections');

            case 'edittitle':
            case 'addtitle':
                return $this->addTitleToCollection();

            case 'deletetitle':
                $collectId = $this->getParam('joinId');
                $metaId = $this->getParam('id');
                $this->objAudit->addAuditTrail('Delete a title from a collection. Collection id: '.$collectionId.'. Submission metadata id: '.$metaId);
                $this->dbCollectSubmit->removeSubmissionFromCollection($metaId, $collectId);
                return $this->nextAction('viewcollection', array('id'=>$collectId, 'allowManage' => TRUE));


            /* *** Manage etd's in repository *** *

            case 'manageetd':
                return 'manageetd_tpl.php';

            case 'fetchetd':
                return $this->fetchEtd();

            case 'editmetadata':
                return $this->showEditPage();

            case 'savemeta':
                $exit = $this->getParam('exit', NULL);
                $submitId = $this->getParam('submitId', NULL);
                if(isset($exit) && !empty($exit)){
                    return $this->nextAction('showetd', array('submitId' => $submitId, 'mode' => TRUE));
                }
                $saveaction = $this->getParam('saveaction');
                $page = $this->getParam('page', 0)+1;
                $this->objAudit->addAuditTrail('Save edited metadata on an archived document. Submission id: '.$submitId.'. Save action: '.$saveaction);
                $submitId = $this->etdSubmit->saveEtd($saveaction, $submitId, TRUE);
                return $this->nextAction('editmetadata', array('page'=>$page, 'submitId'=>$submitId));

            */

            /* *** Additional Functionality *** */
            
            case 'viewstats':
                $display = $this->dbStats->showAll();
                $this->setVarByRef('search', $display);
                return 'search_tpl.php';
            
            case 'rss':
                $institution = $this->objConfig->getinstitutionName();
                $title = $this->objLanguage->code2Txt('mod_etd_etdrss', 'etd', array('institution' => $institution));
                $description = $this->objLanguage->languageText('mod_etd_etdrssdescription', 'etd');
                $link = $this->uri('');
                $feedURL = $this->uri(array('action' => 'rss'));
                $this->objFeeder->setupFeed(TRUE, $title, $description, $link, $feedURL);
                
                // Add items / content
                $data = $this->dbThesis->getAllMeta();
                if(!empty($data)){
                    foreach($data as $item){
                        $itemTitle = $item['dc_title'];
                        $itemDescription = substr($item['dc_subject'], 100, 0);
                        $itemAuthor = $item['dc_creator'];
                        $itemLink = $this->uri(array('action' => 'viewtitle', 'id' => $item['metaid']));// todo: build up item link $item['dc_identifier'];
               	        $this->objFeeder->addItem($itemTitle, $itemLink, $itemDescription, $link, $itemAuthor);
                    }
                }
                
                $feed = $this->objFeeder->output();
                echo $feed;
                break;

            default:
                $this->dbStats->recordHit();
                return $this->home();
        }
    }

    /**
    * Method to display the etd front page, depending on the access level of the user.
    */
    function home()
    {
        /*
        switch($this->accessLevel){
            // student home page
            case 1:
                $openData = $this->dbSubmit->getUserEtd($this->userId);
                $archiveData = $this->dbSubmit->getUserEtd($this->userId, 'archived');

                $head1 = $this->objLanguage->languageText('mod_etd_newsubmissions');
                $head2 = $this->objLanguage->languageText('mod_etd_recentcompletesubmissions');
                $this->setVar('statusCond', 'assembly');
                $this->setVar('dispLink', TRUE);
                break;

            // metadata editors
            case 2:
                $openData = $this->dbSubmit->getEtdByStatus('metadata');

                $head1 = $this->objLanguage->languageText('mod_etd_newsubmissions');
                $head2 = $this->objLanguage->languageText('mod_etd_recentcompletesubmissions');
                $this->setVar('statusCond', 'metadata');
                $this->setVar('dispLink', FALSE);
                break;

            // initial approvers
            case 3:
                $openData = $this->dbSubmit->getEtdByStatus('pending', 0);
                $minorData = $this->dbSubmit->getEtdByStatus('pending', 1);
                $majorData = $this->dbSubmit->getEtdByStatus('pending', 2);
                $secondData = $this->dbSubmit->getEtdByStatus('pending', 4);
                $openData = array_merge($openData, $minorData);
                $openData = array_merge($openData, $majorData);
                $openData = array_merge($openData, $secondData);

                $head1 = $this->objLanguage->languageText('mod_etd_newsubmissions');
                $head2 = $this->objLanguage->languageText('mod_etd_recentcompletesubmissions');
                $this->setVar('statusCond', 'pending');
                $this->setVar('dispLink', FALSE);
                break;

            // secondary approvers
            case 4:
                $openData = $this->dbSubmit->getEtdByStatus('pending', 3);
                $majorData = $this->dbSubmit->getEtdByStatus('pending', 5);
                $openData = array_merge($openData, $majorData);

                $head1 = $this->objLanguage->languageText('mod_etd_newsubmissions');
                $head2 = $this->objLanguage->languageText('mod_etd_recentcompletesubmissions');
                $this->setVar('statusCond', 'pending');
                $this->setVar('dispLink', FALSE);
                break;

            // managers
            case 5:
            // administrators
            case 6:
                $openData = $this->dbSubmit->getUserEtd($this->userId);

                $head1 = $this->objLanguage->languageText('mod_etd_newsubmissions');
                $head2 = $this->objLanguage->languageText('mod_etd_recentcompletesubmissions');
                $this->setVar('statusCond', 'assembly');
                $this->setVar('dispLink', TRUE);
                break;

            default: // case 0
                return $this->nextAction('browse_collection');
        }

        $this->setVarByRef('head1', $head1);
        $this->setVarByRef('head2', $head2);
        $this->setVarByRef('openData', $openData);
        $this->setVarByRef('archiveData', $archiveData);
        return 'etd_tpl.php';
        */
        return 'home_tpl.php';
    }

    /**
    * Method to display the reason for an embargo request or grant.
    */
    function viewEmbargo()
    {
        $heading = $this->objLanguage->languageText('mod_etd_reason');
        $id = $this->getParam('id');
        $type = $this->getParam('type');

        $data = $this->dbEmbargo->getField($id, $type);
        $body = $data[$type];

        $this->setVarByRef('heading', $heading);
        $this->setVarByRef('body', $body);
        return 'etdinfo_tpl.php';
    }

    /**
    * Method to display a form to add submissions to a given collection.
    * The method saves the submitting form information.
    */
    function addTitleToCollection()
    {
        $save = $this->getParam('save', NULL);
        $collectId = $this->getParam('joinId');

        if(isset($save) && !empty($save)){
            $submissions = $this->getParam('submissions', array());
            $audSubmits = implode(', ', $submissions);
            $this->objAudit->addAuditTrail('Add or edit a title in a collection. Collection id: '.$collectId.'. Submissions: '.$audSubmits);
            $this->dbCollectSubmit->addSubmitToCollection($submissions, $collectId);
            return $this->nextAction('viewcollection', array('id' => $collectId, 'allowManage' => TRUE));
        }

        $data = $this->dbCollection->getCollection($collectId);
        $meta = $this->dbThesisMeta->getAllMeta();
        $meta2 = $this->dbThesisMeta->getMetaInCollection($collectId);
        $this->setVarByRef('collection', $data);
        $this->setVarByRef('meta', $meta);
        $this->setVarByRef('meta2', $meta2);
        return 'addtitle_tpl.php';
    }

    /**
    * Method to fetch an etd for managing.
    */
    function fetchEtd()
    {
        $lbAuthor = $this->objLanguage->languageText('mod_etd_author');
        $lbTitle = $this->objLanguage->languageText('mod_etd_title');
        $lbStudent = $this->objLanguage->languageText('mod_etd_studentnumber');
        $lbDepartment = $this->objLanguage->languageText('mod_etd_department');
        $lbEtds = $this->objLanguage->languageText('mod_etd_etds');

        $unset = $this->getParam('unset', NULL);
        if(!is_null($unset)){
            $this->unsetSession('filter');
        }
        $author = $this->getParam('author', NULL);
        $title = $this->getParam('title', NULL);
        $student = $this->getParam('student', NULL);
        $department = $this->getParam('department', NULL);
        $start = $this->getParam( 'displayStart', 0 );

        $data = $this->dbSubmit->fetchETD('%'.$author.'%', '%'.$title.'%', '%'.$student.'%', '%'.$department.'%', $start, 'LIKE');

        $this->setVarByRef('results', $data);
        $this->setVar('find', TRUE);
        $header = array('col1'=>$lbTitle, 'col2'=>$lbAuthor, 'col3'=>$lbStudent, 'col4'=>$lbDepartment);

        $pageTitle = '';
        $objViewBrowse = & $this->getObject( 'viewbrowse', 'etd' );
        $objViewBrowse->create(NULL, FALSE, $lbEtds, 'etd', $data[0]);
        $objViewBrowse->displayLimit = 10;
        $objViewBrowse->displayStart = $start;
        $objViewBrowse->displayMax = $data[1];
        $objViewBrowse->setHeader($header);
        $objViewBrowse->setNumCols(4);
        $objViewBrowse->setAccess(FALSE);
        $objViewBrowse->showAlpha(FALSE);
        $objViewBrowse->showSearch(FALSE);
        $objViewBrowse->setPageTitle($pageTitle);
        $search = $objViewBrowse->show();
        $this->setVarByRef('search', $search);
        return 'manageetd_tpl.php';
    }

    /**
    * Method to display pages for editing metadata
    */
    function showEditPage()
    {
        $page = $this->getParam('page', 1);
        $submitId = $this->getParam('submitId');

        switch($page){
            case 1:
                $dispPage = $this->etdSubmit->createPage1($submitId, $page, $this->accessLevel, 'savemeta', TRUE);
                break;

            case 2:
                $dispPage = $this->etdSubmit->createPage2($submitId, $page, FALSE, 'savemeta', TRUE);
                break;

            case 3:
                $dispPage = $this->etdSubmit->createPage3($submitId, $page, '', 'savemeta', 'editmetadata');
                break;

            default:
                return $this->nextAction('showetd', array('submitId' => $submitId, 'mode' => TRUE));
        }

        if($dispPage === FALSE){
            return $this->nextAction('showetd', array('submitId' => $submitId, 'mode' => TRUE));
        }

        $this->setVar('page', $dispPage);
        return 'submit_tpl.php';
    }

    /**
    * Method to determine the level of access a user has in the site.
    * Levels: (5) Administrator - full access (audit trail, delete submissions).
    * (4) Managers - add/edit/approve/submit.
    * (3) Approvers - approve.
    * (2) Metadata editors/catalogers - edit metadata.
    * (1) Users 1 - browse & submit.
    * (0) Users 2 - browse with restrictions on embargoed items.
    */
    function accessLevel()
    {
        $groupId = $this->objGroup->getLeafId(array('etdAdmin'));
        if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
            $this->accessLevel = 6;
        }else{
            $groupId = $this->objGroup->getLeafId(array('etdManager'));
            if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
                $this->accessLevel = 5;
            }else{
                $groupId = $this->objGroup->getLeafId(array('etdApprover'));
                if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
                    $this->accessLevel = 4;
                }else{
                    $groupId = $this->objGroup->getLeafId(array('etdInitialApprover'));
                    if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
                        $this->accessLevel = 3;
                    }else{
                        $groupId = $this->objGroup->getLeafId(array('etdCataloger'));
                        if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
                            $this->accessLevel = 2;
                        }else{
                            $groupId = $this->objGroup->getLeafId(array('Students'));
                            if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
                                $this->accessLevel = 1;
                            }else{
                                $this->accessLevel = 0;
                            }
                        }
                    }
                }
            }
        }/*
        $this->accessLevel = 4;*/
    }
    
    /**
    * Method to set login requirement to False
    * Required to be false. - will be extended to set the ction items where login is required
    */
    function requiresLogin()
    {
        return FALSE;
    }
} // end of controller class
?>