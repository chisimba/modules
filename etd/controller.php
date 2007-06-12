<?php
/**
* etd class extends controller
* @package etd
* @filesource
*/

/* security check - must be included in all scripts - removed for accessing via metalib
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
*/

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
        try{
            // Set the permissions
            $this->objGroup = $this->getObject('groupadminmodel', 'groupadmin');
            $this->objUser = $this->getObject('user', 'security');
            $this->userId = $this->objUser->userId();
            $this->userPkId = $this->objUser->PKId();
            $this->setGroupPermissions();
            
            // Create objects of the classes
            $this->etdTools = $this->getObject('etdtools', 'etd');
            $this->etdResource = $this->getObject('etdresource', 'etd');
            $this->etdFiles = $this->getObject('etdfiles', 'etd');
            $this->manage = $this->getObject('management', 'etd');
            $this->submit = $this->getObject('submit', 'etd');
            $this->config = $this->getObject('configure', 'etd');
            $this->dbIntro = $this->getObject('dbintro', 'etd');
            $this->dbStats = $this->getObject('dbstatistics', 'etd');
            $this->dbCitations = $this->getObject('dbcitations', 'etd');
            $this->objFaculty = $this->getObject('dbfaculty', 'etd');
            $this->dbThesis = $this->getObject('dbthesis');
            $this->dbThesis->setSubmitType('etd');
    
            $this->etdSearch = $this->getObject('search', 'etd');
            $this->etdSearch->setMetaType('thesis', 'etd');
    
            $this->emailResults = $this->getObject('emailresults');
            $this->emailResults->setModuleName('etd');
    
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->objUser = $this->getObject('user', 'security');
            $this->objLangCode = $this->getObject('languagecode', 'language');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->setVarByRef('objLanguage', $this->objLanguage);
    
            $this->objBlocks = $this->newObject('blocks', 'blocks');
            $this->objFeeder = $this->newObject('feeder', 'feed');
            
            $this->loadClass('link', 'htmlelements');
           
        }catch(Exception $e){
            throw customException($e->message());
            exit();
        }
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
        $this->unsetSession('resourceId');

        switch($action){

            /* *** Functions for displaying the resources *** */

            case 'viewauthor':
            case 'viewtitle':
                $this->unsetSession('resource');
                $metaId = $this->getParam('id');
                $resource = $this->dbThesis->getMetadata($metaId);
                $citationList = $this->dbCitations->getList($resource['submitid']);
                $metaTags = $this->etdResource->getMetadataTags($resource);
                $this->setVarByRef('resource', $resource);
                $this->setVarByRef('citationList', $citationList);
                $this->setVarByRef('metaTags', $metaTags);
                $this->dbStats->recordVisit($metaId);
                $this->setSession('resourceId', $metaId);
                $leftSide = $this->objBlocks->showBlock('resourcemenu', 'etd', '','','', FALSE);
                $this->etdTools->setLeftSide($leftSide);
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
                $this->emailResults->setSubject($subject, FALSE);
                $this->emailResults->setMessage($message);
                $this->emailResults->setEmailBody($resource);
                $email = $this->emailResults->showEmail();
                $this->setVarByRef('search', $email);
                return 'search_tpl.php';

            case 'registerdownload':
                $this->dbStats->recordDownload();
                break;

            /* *** Functions for browsing the repository *** */

            case 'viewfaculty':
                $faculty = $this->getParam('id');
                if(!empty($faculty)){
                    $this->setSession('faculty', $faculty);
                }
                $faculty = $this->getSession('faculty');
            
                $this->unsetSession('resource');
                // set a session to use when returning from a resource or from emailing a resource.
                $session = array();
                $session['searchForLetter'] = $this->getParam('searchForLetter');
                $session['displayLimit'] = $this->getParam('displayLimit');
                $session['displayStart'] = $this->getParam('displayStart');
                $session['action'] = 'viewfaculty';
                $this->setSession('return', $session);

                $objTitle = $this->getObject('viewfaculty', 'etd');
                $objTitle->setBrowseType('title');
                $this->setVar('num', 3);
                $this->setVarByRef('browseType', $objTitle);
                $this->setVarByRef('pageTitle', $faculty);
                return 'browse_tpl.php';
                

            case 'browsefaculty':
                $this->unsetSession('resource');
                $this->unsetSession('faculty');
                $display = $this->objFaculty->listFaculties();
                $this->setVarByRef('search', $display);
                return 'search_tpl.php';
            
            /*
                $this->unsetSession('resource');
                $this->unsetSession('faculty');
                // set a session to use when returning from a resource or from emailing a resource.
                $session = array();
                $session['searchForLetter'] = $this->getParam('searchForLetter');
                $session['displayLimit'] = $this->getParam('displayLimit');
                $session['displayStart'] = $this->getParam('displayStart');
                $session['action'] = 'browsefaculty';
                $this->setSession('return', $session);

                $objFaculty = $this->getObject('dbfaculty', 'etd');
                $objFaculty->setBrowseType('faculty');
                $this->setVar('num', 1);
                $this->setVarByRef('browseType', $objFaculty);
                return 'browse_tpl.php';
            */

            case 'viewdepartment':
                $department = $this->getParam('id');
                if(!empty($department)){
                    $this->setSession('department', $department);
                }
                $department = $this->getSession('department');
            
                $this->unsetSession('resource');
                // set a session to use when returning from a resource or from emailing a resource.
                $session = array();
                $session['searchForLetter'] = $this->getParam('searchForLetter');
                $session['displayLimit'] = $this->getParam('displayLimit');
                $session['displayStart'] = $this->getParam('displayStart');
                $session['action'] = 'viewdepartment';
                $this->setSession('return', $session);

                $objTitle = $this->getObject('viewdepartment', 'etd');
                $objTitle->setBrowseType('title');
                $this->setVar('num', 3);
                $this->setVarByRef('browseType', $objTitle);
                $this->setVarByRef('pageTitle', $department);
                return 'browse_tpl.php';
                

            case 'browsedepartment':
                $this->unsetSession('resource');
                $this->unsetSession('department');
                $objDept = $this->getObject('viewdepartment', 'etd');
                $display = $objDept->listDepartment();
                $this->setVarByRef('search', $display);
                return 'search_tpl.php';

            case 'browseauthor':
                $this->unsetSession('resource');
                // set a session to use when returning from a resource or from emailing a resource.
                $session = array();
                $session['searchForLetter'] = $this->getParam('searchForLetter');
                $session['displayLimit'] = $this->getParam('displayLimit');
                $session['displayStart'] = $this->getParam('displayStart');
                $session['action'] = 'browseauthor';
                $this->setSession('return', $session);

                $objAuthor = $this->getObject('dbthesis', 'etd');
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

                $objTitle = $this->getObject('dbthesis', 'etd');
                $objTitle->setBrowseType('title');
                $this->setVar('num', 3);
                $this->setVarByRef('browseType', $objTitle);
                return 'browse_tpl.php';

            /* ** Functions for searching ** */

            case 'search':
                $search = $this->etdSearch->showSearch();
                $this->etdTools->setLeftBlocks(FALSE, TRUE, FALSE);
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
                $objViewBrowse = $this->getObject('viewbrowse', 'etd');
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
                $this->etdTools->setLeftBlocks(FALSE, TRUE, FALSE);
                return 'search_tpl.php';

            case 'printsearch':
                $pageTitle = $this->objLanguage->languageText('phrase_searchresults');
                $objViewBrowse = $this->getObject('viewbrowse', 'etd');
                $objViewBrowse->create($this->etdSearch);
                $search = $objViewBrowse->getResults();
                $this->setVarByRef('search', $search);
                return 'print_tpl.php';

            case 'emailsearch':
                $pageTitle = $this->objLanguage->languageText('phrase_searchresults');
                $shortName = $this->objConfig->getinstitutionShortName().':';
                $subject = $this->objLanguage->code2Txt('mod_etd_requestedsearchresults', 'etd', array('shortname' => $shortName));
                $message = $this->objLanguage->languageText('mod_etd_attachmentsearchresults', 'etd');
                $objViewBrowse = $this->getObject( 'viewbrowse', 'etd' );
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
                
                $objLink = new link($this->uri($return));
                $objLink->link = $link;
                $search = '<p class="confirm">'.$confirm.'</p><p>'.$objLink->show().'</p>';
                $this->setVarByRef('search', $search);
                return 'search_tpl.php';

            /* ** Functions for managing the archive ** */

            case 'managesubmissions':
                $mode = $this->getParam('mode');
                $display = $this->manage->show($mode);
                $this->etdTools->setLeftBlocks(FALSE, TRUE, FALSE);
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
                $this->etdTools->setLeftBlocks(FALSE, TRUE, FALSE);
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

            /* *** Functions for students submissions *** */

            case 'submit':
                $mode = $this->getParam('mode');
                $display = $this->submit->show($mode);
                $this->etdTools->setLeftBlocks(FALSE, TRUE, FALSE);
                $this->setVarByRef('search', $display);
                return 'search_tpl.php';
                break;

            case 'savesubmit':
                $save = $this->getParam('save');
                $mode = $this->getParam('mode');
                $nextmode = $this->getParam('nextmode');
                if(!empty($save)){
                    $this->submit->show($mode);
                }
                if(!empty($nextmode)){
                    return $this->nextAction('submit', array('mode' => $nextmode));
                }
                return $this->nextAction('');
                break;

            /* *** Additional Functionality *** */

            case 'viewstats':
                $display = $this->dbStats->showAll();
                $this->setVarByRef('search', $display);
                return 'search_tpl.php';
                
            case 'showrss':
                $institution = $this->objConfig->getinstitutionName();
                $title = $this->objLanguage->code2Txt('mod_etd_etdrss', 'etd', array('institution' => $institution));
                $objHead = new htmlheading();
                $objHead->str = $title;
                $objHead->type = 1;
                $rssLink = $objHead->show();
                $objLink = new link($this->uri(array('action' => 'rss')));
                $objLink->link = $this->uri(array('action' => 'rss'));
                $rssLink .= '<p style="padding-top: 10px; font-size: 125%;">'.$objLink->show().'</p>';
                $this->setVarByRef('search', $rssLink);
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
                        $itemLink = html_entity_decode($itemLink);
               	        $this->objFeeder->addItem($itemTitle, $itemLink, $itemDescription, $link, $itemAuthor);
                    }
                }

                $feed = $this->objFeeder->output();
                echo $feed;
                break;
                
            case 'metalib':
                $term = $this->getParam('keyword');
                $count = 0;
                if(!empty($term)){
                    $res = $this->dbThesis->search2($term);
                    $data = $res[0];
                    $count = $res[1];
                    
                    //echo $term.'<pre>'; print_r($data); echo '</pre>';
                }else{
                    $return = $this->getSession('return');
                    $count = $return['count'];
                }
                $display = '<B>'.$count.'</B> records found';
                //$this->setVarByRef('search', $display);
                //return 'search_tpl.php';
                //$pageTitle = '<b>'.$count.'</b> records found';
                
                $this->unsetSession('resource');
                // set a session to use when returning from a resource or from emailing a resource.
                $session['displayLimit'] = $this->getParam('displayLimit');
                $session['displayStart'] = $this->getParam('displayStart');
                $session['action'] = 'metalib';
                $session['count'] = $count;
                $this->setSession('return', $session);

                $pageTitle = $this->objLanguage->languageText('phrase_searchresults');
                $objViewBrowse = $this->getObject('viewbrowse', 'etd');
                $objViewBrowse->create($this->etdSearch);
                $objViewBrowse->setAccess( FALSE );
                $objViewBrowse->showAlpha(FALSE);
                $objViewBrowse->showPrint();
                $objViewBrowse->setNumCols(3);
                $objViewBrowse->setPageTitle($pageTitle);
                $objViewBrowse->addExtra($display);
                
                $this->objLink = new link($this->uri(array('action'=>'search')));
                $this->objLink->link = $this->objLanguage->languageText('phrase_newsearch');
                $search = $objViewBrowse->show();
                $this->setVarByRef('search', $search);
                $this->etdTools->setLeftBlocks(FALSE, TRUE, FALSE);
                return 'search_tpl.php';
                break;

            default:
                $this->dbStats->recordHit();
                return $this->home();
        }
    }

    /**
    * Method to display the etd front page, depending on the access level of the user.
    */
    private function home()
    {   
        $txtIntro = $this->dbIntro->getParsedIntro();
        $this->setVarByRef('txtIntro', $txtIntro);
        
        return 'home_tpl.php';
    }
    
    /**
    * Temporary fix for context permissions.
    * The method builds an array of groups in which the user is a member. The group determines the users level of access in the site.
    *
    * @access private
    * @return void
    */
    private function setGroupPermissions()
    {
        if($this->objUser->isLoggedIn()){
            $access = $this->getSession('accessLevel');
            if(!(isset($access) && !empty($access))){
                $accessLevel = array();
                $accessLevel[] = 'user';
                $groupId = $this->objGroup->getLeafId(array('ETD Managers'));
                if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
                    $accessLevel[] = 'manager';
                }
                $groupId = $this->objGroup->getLeafId(array('ETD Editors'));
                if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
                    $accessLevel[] = 'editor';
                }
                $groupId = $this->objGroup->getLeafId(array('ETD Exam Board'));
                if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
                    $accessLevel[] = 'board';
                }
                $groupId = $this->objGroup->getLeafId(array('Students'));
                if($this->objGroup->isGroupMember($this->userPkId, $groupId)){
                    $accessLevel[] = 'student';
                }
                $this->setSession('accessLevel', $accessLevel);
            }
        }
    }

    function patchData()
    {
        $this->dbDublinCore = $this->getObject('dbdublincore', 'etd');
        
        $this->dbDublinCore->patch();
    }

    /**
    * Method to set login requirement to False
    * Required to be false. - will be extended to set the ction items where login is required
    *
    * @access public
    */
    public function requiresLogin($action)
    {
        switch($action){
            case 'viewauthor':
            case 'viewtitle':
            case 'printresource':
            case 'emailresource':
            case 'viewdepartment':
            case 'browsedepartment':
            case 'viewfaculty':
            case 'browsefaculty':
            case 'browseauthor':
            case 'browsetitle':
            case 'search':
            case 'advsearch':
            case 'printsearch':
            case 'emailsearch':
            case 'sendemail':
            case 'viewstats':
            case 'showrss':
            case 'rss':
            case 'metalib';
            case '';
                return FALSE;
        }
        return TRUE;
    }
} // end of controller class
?>