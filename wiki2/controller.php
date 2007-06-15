<?php
// security check - must be included in all scripts
if ( !$GLOBALS['kewl_entry_point_run'] ) {
    die( "You cannot view this page directly" );
} 
// end security check
/**
 * The wiki version 2 controller manages
 * the wiki
 * 
 * @author Kevin Cyster 
 * @copyright 2007, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package wiki version 2
 */

class wiki2 extends controller {

    /**
    * @var object $objLanguage: The language class in the language module
    * @access public
    */
    public $objLangauge;
    
    /**
    * @var object $objDisplay: The display class in the sitepermissions module
    * @access public
    */
    public $objDisplay;
    
    /**
    * @var object $objDbwiki: The dbwiki class in the wiki version 2 module
    * @access public
    */
    public $objDbwiki;
    
    /**
    * @var object $objLock: The wikipagelock class in the wiki version 2 module
    * @access public
    */
    public $objLock;
    
    /**
    * @var object $objUser: The user class in the security module
    * @access public
    */
    public $objUser;
    
    /**
    * @var string $userId: The user id of the current logged in user
    * @access public
    */
    public $userId;
        
    /**
    * Method to initialise the controller
    * 
    * @access public
    * @return void
    */
    public function init()
    {
        $this->objLanguage = $this->getObject( 'language', 'language' );
        $this->objDisplay = $this->newObject('display', 'wiki2');
        $this->objDbwiki = $this->newObject('dbwiki', 'wiki2');        
        $this->objLock = $this->newObject('wikipagelock', 'wiki2');        
        $this->objUser = $this->newObject('user', 'security');
        $this->userId = $this->objUser->userId();        
    }
    
    /**
    * Method to check if login needed (depending on operation)
    * 
    * @access public 
    * @return boolean True or False
    */
    public function requiresLogin()
    {
        $action = $this->getParam("action", NULL);
        //Allow viewing anonymously
        switch($action){
            case 'add_page':
            case 'create_page':
            case 'update_page':
            case 'delete_page':
            case 'restore_page':
            case 'validate_name':
            case 'preview_page':
            case 'deleted_page':
            case 'check_lock':
            case 'view_watchlist':
            case 'delete_watch':
                return TRUE;
            case 'view_rules':
            case 'view_all':
            case 'view_page':
            case 'search_wiki':
            case 'view_authors':
            case 'add_rating':
            case 'view_ranking':
            case 'remove_watch':
            default:
                return FALSE;
        }
    }

    /**
    * Method the engine uses to kickstart the module
    * 
    * @access public
    * @param string $action: The action to be performed
    * @return void
    */
    function dispatch( $action )
    {
        switch($action){
            case 'view_rules':
                $templateContent = $this->objDisplay->showFormattingRules();
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('popup', TRUE);
                return 'template_tpl.php';
                break;
                
            case 'add_page':
                $name = $this->getParam('name');
                $templateContent = $this->objDisplay->showAddPage($name);
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;
                               
            case 'create_page':
                $cancel = $this->getParam('cancel', NULL);
                if(empty($cancel)){
                    $name = $this->getParam('name');
                    $sum = $this->getParam('summary');
                    $choice = $this->getParam('choice');
                    $content = $this->getParam('content');
                    if($choice == 'yes'){
                        $summary = substr($content, 0, 255);
                    }else{
                        $summary = $sum;
                    }
                    $data = array();
                    $data['wiki_id'] = 'init_1'; // TODO: extend for context etc.
                    $data['page_name'] = $name;
                    $data['page_summary'] = $summary;
                    $data['page_content'] = $content;
                    $data['version_comment'] = $this->objLanguage->languageText('mod_wiki2_newpage', 'wiki2');
                    $pageId = $this->objDbwiki->addPage($data); 
                    
                    $watch = $this->getParam('watch');
                    if(!empty($watch)){
                        $this->objDbwiki->addWatch($name);
                    }                   
                }else{
                    $name = '';
                }
                return $this->nextAction('view_page', array(
                    'name' => $name,
                ));
                break;
                
            case 'update_page':
                $cancel = $this->getParam('cancel', NULL);
                $name = $this->getParam('name');
                $id = $this->getParam('id');
                if(empty($cancel)){
                    $name = $this->getParam('name');
                    $main = $this->getParam('main');
                    $sum = $this->getParam('summary');                    
                    $comment = $this->getParam('comment');
                    $content = $this->getParam('content');
                    $choice = $this->getParam('choice');
                    if($choice == 'yes'){
                        $summary = substr($content, 0, 255);
                    }else{
                        $summary = $sum;
                    }

                    $data = array();
                    $data['wiki_id'] = 'init_1'; // TODO: extend for context etc.
                    $data['page_name'] = $name;
                    $data['main_page'] = $main;
                    $data['page_summary'] = $summary;
                    $data['version_comment'] = $comment;
                    $data['page_content'] = $content;
                    $pageId = $this->objDbwiki->addPage($data); 
                    $this->objDisplay->sendMail($name);                   
                }
                $this->objLock->unlockEdit('tbl_wiki2_pages', $id, $this->userId);
                return $this->nextAction('view_page', array(
                    'name' => $name,
                ));
                break;
                
            case 'delete_page':
                $name = $this->getParam('name');
                $this->objDbwiki->deletePage($name);
                return $this->nextAction('view_all');
                break;
                
            case 'view_all':
                $templateContent = $this->objDisplay->showAllPages();
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;
                
            case 'view_page':
                $name = $this->getParam('name');
                if(!empty($name)){
                    $page = $this->objDbwiki->getPage($name);
                    if(empty($page)){
                        return $this->nextAction('add_page', array(
                            'name' => $name,
                        ));
                    }elseif($page['page_status'] == 6){
                        return $this->nextAction('deleted_page', array(
                            'name' => $name,
                        ));
                    }
                }
                $version = $this->getParam('version');
                $tab = $this->getParam('tab', 0);
                $templateContent = $this->objDisplay->showMain($name, $version, $tab);            
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;

            case 'restore_page':
                $name = $this->getParam('name');
                $version = $this->getParam('version');
                $mode = $this->getParam('mode');
                if($mode == 'reinstate'){
                    $this->objDbwiki->reinstatePage($name, $version);
                }else{
                    $this->objDbwiki->restorePage($name, $version);
                }
                $templateContent = $this->objDisplay->showMain($name);
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;
                
            case 'search_wiki':
                $field = $this->getParam('field');
                $value = $this->getParam('value');
                if($field == 2){
                    $column = 'page_name';
                }elseif($field == 3){
                    $column = 'page_content';
                }else{
                    $column = 'both';
                }
                $data = $this->objDbwiki->searchWiki($column, $value);
                $templateContent = $this->objDisplay->showSearch($data);
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;
                
            case 'view_authors':
                $author = $this->getParam('author');
                $templateContent = $this->objDisplay->showAuthors($author);
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;
                
            case 'validate_name':
                $name = $this->getParam('name');
                $divContent = $this->objDisplay->showValidateName($name);
                return $divContent;
                break;

            case 'preview_page':
                $name = $this->getParam('name');
                $content = $this->getParam('content');
                $divContent = $this->objDisplay->showPreview($name, $content);
                return $divContent;
                break;
                
            case 'deleted_page':
                $name = $this->getParam('name');
                $templateContent = $this->objDisplay->showDeletedPage($name);
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;
                
            case 'check_lock':
                $id = $this->getParam('id');
        		// If user can edit , then lock it and continue
        		$canEdit = $this->objLock->canUserEdit($id, $this->userId);
                if($canEdit){
                    // If page is locked, force unlock
                    $isLocked = $this->objLock->isEditLocked('tbl_wiki2_pages', $id); 
                    if($isLocked){
                        $this->objLock->forceEditUnlock('tbl_wiki2_pages', $id);
                		$this->objLock->lockEdit('tbl_wiki2_pages', $id, $this->userId);
                    }
                    return $this->objDisplay->showLockedMessage(TRUE);
                }else{
                // Page is currently locked by another user notify user
                    return $this->objDisplay->showLockedMessage();
                }
                break;
                
            case 'lock_page':
                $id = $this->getParam('id');
                $this->objLock->lockEdit('tbl_wiki2_pages', $id, $this->userId);
                return $this->objDisplay->showLockedMessage('keeplocked');
                break;
                
            case 'add_rating':
                $name = $this->getParam('name');
                $rating = $this->getParam('rating');
                $this->objDbwiki->addRating($name, $rating);
                return $this->objDisplay->showRating($name, TRUE);
                break;
                
            case 'view_ranking':
                $templateContent = $this->objDisplay->showRanking();
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;
                
            case 'view_watchlist':
                $templateContent = $this->objDisplay->showWatchlist();
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;
                
            case 'delete_watch':
                $id =$this->getParam('id');
                $this->objDbwiki->deleteWatchById($id);
                return $this->nextAction('view_watchlist');
                break;
                
            case 'update_watch':
                $mode = $this->getParam('mode');
                $name = $this->getParam('name');
                if($mode == 'add'){
                    return $this->objDbwiki->addWatch($name);
                }else{
                    return $this->objDbwiki->deleteWatchByName($name);
                }
                break;
                
            case 'remove_watch':
                $name = $this->getParam('name');
                $userId = $this->getParam('id');
                $this->objDbwiki->deleteWatchByName($name, $id);
                return $this->nextAction('');
                break;
                
            default:
                return $this->nextAction('view_page');
                break;
        }
    }
}
?>