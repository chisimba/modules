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
                }
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
                $templateContent = $this->objDisplay->showMain('', '', 'list');
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;
                
            case 'view_page':
                $name = $this->getParam('name');
                $version = $this->getParam('version');
                $mode = $this->getParam('mode');
                $tab = $this->getParam('tab', 0);
                $templateContent = $this->objDisplay->showMain($name, $version, $mode, $tab);
                $this->setVarByRef('templateContent', $templateContent);
                return 'template_tpl.php';
                break;

            case 'restore_page':
                $name = $this->getParam('name');
                $version = $this->getParam('version');
                $this->objDbwiki->restorePage($name, $version);
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

            default:
                return $this->nextAction('view_page');
                break;
        }
    }
}
?>