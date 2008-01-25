<?php

/**
 * Context Content controller
 * 
 * Controller class for the Context Content Module in Chisimba
 * 
 * PHP version 5
 * 
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation; either version 2 of the License, or 
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the 
 * Free Software Foundation, Inc., 
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * 
 * @category  Chisimba
 * @package   contextcontent
 * @author    Tohir Solomons <tsolomons@uwc.ac.za>
 * @copyright 2008 Tohir Solomons
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check


/**
 * Context Content controller
 * 
 * Controller class for the Context Content Module in Chisimba
 * 
 * @category  Chisimba
 * @package   contextcontent
 * @author    Tohir Solomons <tsolomons@uwc.ac.za>
 * @copyright 2008 Tohir Solomons
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 */
class contextcontent extends controller
{
    

    /**
    * @var string $contextCode Context Code of Current Context
    */

    protected $contextCode;
    

    /**
    * Constructor
    */
    public function init()
    {
        try {
            // Load Chapter Classes
            $this->objChapters =& $this->getObject('db_contextcontent_chapters');
            $this->objContextChapters =& $this->getObject('db_contextcontent_contextchapter');
            $this->objContentOrder =& $this->getObject('db_contextcontent_order');
            // $this->objContentTitles =& $this->getObject('db_contextcontent_titles');
            
            // Load Content Classes
            $this->objContentPages =& $this->getObject('db_contextcontent_pages');
            $this->objContentOrder =& $this->getObject('db_contextcontent_order');
            $this->objContentTitles =& $this->getObject('db_contextcontent_titles');
            $this->objContentInvolvement =& $this->getObject('db_contextcontent_involvement');
            
            // Load Context Object
            $this->objContext =& $this->getObject('dbcontext', 'context');
            
            // Store Context Code
            $this->contextCode = $this->objContext->getContextCode();
            
            $this->objLanguage =& $this->getObject('language', 'language');
            $this->objUser =& $this->getObject('user', 'security');
            
            $this->objMenuTools =& $this->getObject('tools', 'toolbar');
            $this->objConfig =& $this->getObject('altconfig', 'config');
            $this->setVar('pageSuppressXML',TRUE);
        }
        catch(customException $e) {
            //oops, something not there - bail out
            echo customException::cleanUp();
            //we don't want to even attempt anything else right now.
            die();
        }
    }

    

    /**
    * Method to override login requirement for certain actions
    * @param string $action Action user is taking
    * @return boolean
    */
    function requiresLogin($action)
    {
        if ($action=='' || $action == 'viewpage') {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    

    /**
    * Dispatch Method to run required action
    * @param string $action
    */
    public function dispatch($action)
    {
        if ($this->contextCode == '' && $action != 'notincontext') {
            $action = 'notincontext';
        }
        
        $this->setLayoutTemplate('layout_chapter_tpl.php');
        
        switch ($action)
        {
            
            case 'notincontext':
                return 'tpl_notincontext.php';
            case 'switchcontext':
                die('Switch Context'); // Fix Up
            case 'addpage':
                return $this->addPage($this->getParam('chapter'), $this->getParam('id', ''), $this->getParam('context', ''));
            case 'savepage':
                return $this->savePage();
            case 'editpage':
                return $this->editPage($this->getParam('id'), $this->getParam('context'));
            case 'updatepage':
                return $this->updatePage();
            case 'viewpage':
                return $this->viewPage($this->getParam('id'));
            case 'deletepage':
                return $this->deletePage($this->getParam('id'), $this->getParam('context'));
            case 'deletepageconfirm':
                return $this->deletePageConfirm();
            case 'fixleftright':
                return $this->fixLeftRightValues();
            case 'movepageup':
                return $this->movePageUp($this->getParam('id'));
            case 'movepagedown':
                return $this->movePageDown($this->getParam('id'));
            case 'savechapter':
                return $this->saveChapter();
            case 'addchapter':
                return $this->addChapter();
            case 'editchapter':
                return $this->editChapter($this->getParam('id'));
            case 'updatechapter':
                return $this->updateChapter();
            case 'deletechapter':
                return $this->deleteChapter($this->getParam('id'));
            case 'deletechapterconfirm':
                return $this->deleteChapterConfirm();
            case 'movechapterup':
                return $this->moveChapterUp($this->getParam('id'));
            case 'movechapterdown':
                return $this->moveChapterDown($this->getParam('id'));
            case 'viewchapter':
                return $this->viewChapter($this->getParam('id'));
            case 'viewprintchapter':
                return $this->viewPrintChapter($this->getParam('id'));
            case 'changenavigation':
                return $this->changeNavigation($this->getParam('type'), $this->getParam('id'));
            case 'movetochapter':
                return $this->moveToChapter($this->getParam('id'), $this->getParam('chapter'));
            case 'changebookmark':
                return $this->changeBookMark();
            case 'search':
                return $this->search($this->getParam('contentsearch'));
            default:
                //return $this->home_debug();
                return $this->showContextChapters();
        }
    }

    

    /**
    * Method to display the list of chapters in a context
    *
    * This is also the home page of the module
    */
    protected function showContextChapters()
    {
        //$this->objContentOrder->checkPagesNotInChapter($this->contextCode);
        
        
        $numContextChapters = $this->objContextChapters->getNumContextChapters($this->contextCode);
        
        $this->setVarByRef('numContextChapters', $numContextChapters);
        
        if ($numContextChapters == 0) {
            
            $this->setLayoutTemplate(NULL);
            
            // If user can create chapter, show create chapter form
            if ($this->isValid('savechapter')) {
                return 'tpl_nochapters.php';
            } else { // Else notify user there is no content
                return 'tpl_nocontent.php';
            }
        } else {
            $chapters = $this->objContextChapters->getContextChapters($this->contextCode);
            $this->setVarByRef('chapters', $chapters);
            
            $this->setLayoutTemplate('layout_firstpage_tpl.php');
            return 'tpl_listchapters.php';
        }
    }
    

    /**
    * Method to add a new chapter
    */
    protected function addChapter()
    {
        $this->setVar('mode', 'add');
        
        return 'tpl_addeditchapter.php';
    }
    

    /**
    * Method to save a newly create chapter
    */
    protected function saveChapter()
    {
        $title = $this->getParam('chapter');
        $intro = $this->getParam('intro');
        $visibility = $this->getParam('visibility');
        
        $chapterId = $this->objChapters->addChapter('', $title, $intro);
        
        $result = $this->objContextChapters->addChapterToContext($chapterId, $this->contextCode, $visibility);
        
        if ($result == FALSE) {
            return $this->nextAction(NULL, array('error'=>'couldnotcreatechapter'));
        } else {
            return $this->nextAction(NULL, array('message'=>'chaptercreated', 'id'=>$result));
        }
    }
    

    /**
    * Method to edit a chapter
    *
    * @param string $id Record Id of the Chapter
    */
    protected function editChapter($id)
    {
        $chapter = $this->objContextChapters->getChapter($id);
        
        if ($chapter == FALSE) {
            return $this->nextAction(NULL, array('error'=>'editchapterdoesnotexist'));
        } else {
            $this->setVar('mode', 'edit');
            
            $this->setVarByRef('chapter', $chapter);
            $this->setVarByRef('id', $id);
            
            return 'tpl_addeditchapter.php';
        }
    }
    

    /**
    * Method to update a chapter
    */
    protected function updateChapter()
    {
        
        $id = $this->getParam('id');
        $chaptercontentid = $this->getParam('chaptercontentid');
        $contextchapterid = $this->getParam('contextchapterid');
        $title = $this->getParam('chapter');
        $intro = $this->getParam('intro');
        $visibility = $this->getParam('visibility');
        
        if ($id == '' || $chaptercontentid == '' || $contextchapterid == '') {
            return $this->nextAction(NULL, array('error'=>'noidprovided'));
        } else {
            $objChapterContent = $this->getObject('db_contextcontent_chaptercontent');
            
            $chapter = $objChapterContent->getRow('id', $chaptercontentid);
            
            if ($chapter == FALSE) {
                return $this->nextAction(NULL, array('error'=>'invalididprovided'));
            } else if ($chapter['chapterid'] != $id) {
                return $this->nextAction(NULL, array('error'=>'invalididprovided'));
            } else {
                $objChapterContent->updateChapter($chaptercontentid, $title, $intro);
                $this->objContextChapters->updateChapterVisibility($contextchapterid, $visibility);
                
                return $this->nextAction(NULL, array('message'=>'chapterupdated', 'id'=>$id));
            }
        }
        
    }
    
    /**
     * Method to delete a chapter
     *
     * This function generates a confirmation form
     * 
     * @param string $id Record Id of the Chapter
     */
    protected function deleteChapter($id)
    {
        $chapter = $this->objContextChapters->getChapter($id);
        
        if ($chapter == FALSE) {
            return $this->nextAction(NULL, array('error'=>'editchapterdoesnotexist'));
        } else {
            
            if ($this->objContextChapters->isContextChapter($this->contextCode, $id)) {
                $this->setVar('mode', 'edit');
                
                $this->setVarByRef('chapter', $chapter);
                $this->setVarByRef('id', $id);
                
                $numPages = $this->objContentOrder->getContextPages($this->contextCode, $id);
                $this->setVar('numPages', count($numPages));
                
                return 'tpl_deletechapter.php';
            } else {
                return $this->nextAction(NULL, array('error'=>'chapternotinthiscontext'));
            }
            
        }
    }
    
    /**
     * Method to delete the chapter after confirmation
     *
     * If confirmation is not received, chapter is not deleted.
     */
    protected function deleteChapterConfirm()
    {
        
        
        $confirmation = $this->getParam('confirmation', 'N');
        $context = $this->getParam('context');
        $id = $this->getParam('id');
        
        // Check that confirmation has been received
        if ($confirmation == 'Y') {
            // Check that Context Matches
            if ($context != $this->contextCode) {
                return $this->nextAction(NULL, array('message'=>'attempttodeletechapteroutofcontext', 'id'=>$id));
            }
            
            // Check That Chapter is In Context
            if ($this->objContextChapters->isContextChapter($this->contextCode, $id)) {
                
                // Check how many other chapters also have this context
                $numContextWithChapter = $this->objContextChapters->getNumContextWithChapter($id);
                
                $chapter = $this->objContextChapters->getContextChapterTitle($id);
                
                // If only one, do full delete
                if ($numContextWithChapter == 1) {
                    // Delete Chapter
                    $this->objContextChapters->removeChapterFromContext($id, $this->contextCode);
                    $this->objChapters->deleteChapter($id);
                    
                    // Delete Pages in Chapter
                    $pages = $this->objContentOrder->getContextPages($this->contextCode, $id);
                    
                    if (count($pages) > 0) {
                        foreach ($pages as $page)
                        {
                            $this->objContentTitles->deleteTitle($page['titleid']);
                            //$this->objContentOrder->deletePage($page['id']);
                        }
                    }
                } else { // Else simply remove the chapter from this context.
                    $this->objContextChapters->removeChapterFromContext($id, $this->contextCode);
                }
                
                // Return Message
                return $this->nextAction(NULL, array('message'=>'chapterdeleted', 'chapter'=>$chapter));
                
            } else {
                return $this->nextAction(NULL, array('message'=>'chapternotincontext', 'id'=>$id));
            }
        } else {
            return $this->nextAction(NULL, array('message'=>'deletechaptercancelled', 'id'=>$id));
        }
    }
    
    
    /**
    * Method to add a page
    * @param string $chapter Record Id of Chapter under which page will be placed
    * @param string $parent Record Id of the Parent
    * @param string $contextCode Context Code
    */
    protected  function addPage($chapter, $parent='', $contextCode='')
    {
        if ($contextCode != '' && $contextCode != $this->contextCode) {
            return $this->nextAction('switchcontext');
        }
        
        $this->setLayoutTemplate(NULL);
        
        $this->setVar('mode', 'add');
        $this->setVar('formaction', 'savepage');
        $this->setVarByRef('chapter', $chapter);
        $this->setVarByRef('currentChapter', $chapter);
        
        $tree = $this->objContentOrder->getTree($this->contextCode, $chapter, 'dropdown');
        $this->setVarByRef('tree', $tree);
        
        return 'tpl_addeditpage.php';
    }
    

    /**
    * Method to save a newly added page
    */
    protected  function savePage()
    {
        
        $menutitle = stripslashes($this->getParam('menutitle'));
        $headerscripts = stripslashes($this->getParam('headerscripts'));
        $language = 'en';
        $pagecontent = stripslashes($this->getParam('pagecontent'));
        $parent = stripslashes($this->getParam('parentnode'));
        $chapter = stripslashes($this->getParam('chapter'));
        
        $titleId = $this->objContentTitles->addTitle('', $menutitle, $pagecontent, $language, $headerscripts);
        
        
        $pageId = $this->objContentOrder->addPageToContext($titleId, $parent, $this->contextCode, $chapter);
        
        $this->setVar('mode', 'add');
        $this->setVar('formaction', 'savepage');
        
        return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'pagesaved'));
    }

    

    /**
    * Method to view a page
    * @param string $pageId Record Id of the Page
    */
    protected  function viewPage($pageId='')
    {
        if ($pageId == '') {
            return $this->nextAction(NULL);
        }
        
        $page = $this->objContentOrder->getPage($pageId, $this->contextCode);
        
        if ($page == FALSE) {
            //echo 'page does not exist';
            return $this->nextAction(NULL, array('error'=>'pagedoesnotexist'));
        }
        
        $this->setVarByRef('page', $page);
        $this->setVarByRef('currentPage', $pageId);
        $this->setVarByRef('currentChapter', $page['chapterid']);
        
        $this->setVarByRef('nextPage', $this->objContentOrder->getNextPage($this->contextCode, $page['chapterid'], $page['lft']));
        $this->setVarByRef('prevPage', $this->objContentOrder->getPreviousPage($this->contextCode, $page['chapterid'], $page['lft']));
        $this->setVarByRef('isFirstPageOnLevel', $this->objContentOrder->isFirstPageOnLevel($page['id']));
        $this->setVarByRef('isLastPageOnLevel', $this->objContentOrder->isLastPageOnLevel($page['id']));
        
        $breadcrumbs = $this->objContentOrder->getBreadcrumbs($this->contextCode, $page['chapterid'], $page['lft'], $page['rght']);
        
        $chapterTitle = $this->objContextChapters->getContextChapterTitle($page['chapterid']);
        
        $this->setVarByRef('currentChapterTitle', $chapterTitle);
        
        if ($chapterTitle != FALSE) {
            $chapterLink = new link ($this->uri(array('action'=>'viewchapter', 'id'=>$page['chapterid'])));
            $chapterLink->link = $this->objLanguage->languageText('word_chapter', 'word', 'Chapter').': '.$chapterTitle;
            
            array_unshift($breadcrumbs, $chapterLink->show());
            //array_unshift($breadcrumbs, 'Chapter: '.$chapterTitle);
        }
        
        $this->objMenuTools->addToBreadCrumbs($breadcrumbs);
        
        
        $chapters = $this->objContextChapters->getContextChapters($this->contextCode);
        $this->setVarByRef('chapters', $chapters);
        
        return 'tpl_viewpage.php';
    }
    

    /**
    * Method to edit a page
    * @param string $pageId Record Id of the Page
    */
    protected function editPage($pageId)
    {
        if ($pageId == '') {
            return $this->nextAction(NULL);
        }
        
        $page = $this->objContentOrder->getPage($pageId, $this->contextCode);
        
        if ($page == FALSE) {
            return $this->nextAction(NULL, array('error'=>'pagedoesnotexist'));
        }
        
        $this->setLayoutTemplate(NULL);
        
        $this->setVarByRef('page', $page);
        $this->setVarByRef('currentChapter', $page['chapterid']);
        
        $tree = $this->objContentOrder->getTree($this->contextCode, $page['chapterid'], 'dropdown', $page['parentid'], 'contextcontent', $page['id']);
        $this->setVarByRef('tree', $tree);
        
        $this->setVar('mode', 'edit');
        $this->setVar('formaction', 'updatepage');
        
        return 'tpl_addeditpage.php'; 
    }
    
    /**
     * Method to Update a Page
     */
    protected function updatePage()
    {
        $pageId = $this->getParam('id');
        $contextCode = $this->getParam('context');
        $menutitle = stripslashes($this->getParam('menutitle'));
        $headerScripts = stripslashes($this->getParam('headerscripts'));
        $pagecontent = stripslashes($this->getParam('pagecontent'));
        $parentnode = stripslashes($this->getParam('parentnode'));
        
        if ($contextCode != '' && $contextCode != $this->contextCode) {
            return $this->nextAction('switchcontext');
        } else {
            $page = $this->objContentOrder->getPage($pageId, $this->contextCode);
            $parentPage = $this->objContentOrder->getPage($parentnode, $this->contextCode);
            
            if ($page == FALSE) {
                return $this->nextAction(NULL, array('error'=>'pagedoesnotexist'));
            } else {
                $this->objContentPages->updatePage($page['pageid'], $menutitle, $pagecontent, $headerScripts);
                
                if ($parentnode != $page['parentid']) {
                //if ($parentnode != $page['parentid'] && ($page['lft'] > $parentPage['lft']) && ($page['rght'] < $parentPage['rght'])) {
                    
                    
                    $this->objContentOrder->changeParent($this->contextCode, $page['chapterid'], $pageId, $parentnode);
                }
                
                return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'pageupdated'));
            }
        }
    }

    
    /**
     * Method to delete a page
     *
     * This method presents a confirmation form for users wanting to delete a page
     * 
     * @param string $pageId Record Id of the Page
     */
    protected function deletePage($pageId)
    {
        if ($pageId == '') {
            return $this->nextAction(NULL);
        }
        
        $page = $this->objContentOrder->getPage($pageId, $this->contextCode);
        
        if ($page == FALSE) {
            return $this->nextAction(NULL, array('error'=>'pagedoesnotexist'));
        }
        
        $children = $page['rght'] - $page['lft'] - 1;
        
        if ($children != 0) {
            return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'pagehassubpages'));
        }
        
        $this->setVarByRef('page', $page);
        
        return 'tpl_deletepage.php'; 
    }
    

    /**
    * Method to delete a page once confirmation has been received.
    */
    protected function deletePageConfirm()
    {
        $confirmation = $this->getParam('confirmation', 'N');
        $pageId = $this->getParam('id');
        $context = $this->getParam('context');
        
        if ($pageId == '' || $context == '') {
            return $this->nextAction(NULL, array('error'=>'pagedoesnotexist', 'attemptedaction'=>'delete'));
        }
        
        $page = $this->objContentOrder->getPage($pageId, $this->contextCode);
        
        if ($page == FALSE) {
            return $this->nextAction(NULL, array('error'=>'pagedoesnotexist', 'attemptedaction'=>'delete'));
        } else {
            $children = $page['rght'] - $page['lft'] - 1;
            if ($children == 0) {
                if ($confirmation == 'Y') {
                    
                    $nextPage = $this->objContentOrder->getNextPageSQL($this->contextCode, $page['chapterid'], $page['lft']);
                    
                    $this->objContentTitles->deleteTitle($page['titleid']);
                    
                    $this->objContentOrder->rebuildChapter($this->contextCode, $page['chapterid']);
                    
                    if (is_array($nextPage)) {
                        return $this->nextAction('viewpage', array('id'=>$nextPage['id'], 'message'=>'pagedeleted', 'title'=>urlencode($page['menutitle'])));
                    } else {
                        return $this->nextAction('viewchapter', array('id'=>$page['chapterid'], 'message'=>'pagedeleted', 'title'=>urlencode($page['menutitle'])));
                    }
                    
                } else {
                    return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'deletecancelled'));
                }
            } else {
                return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'pagehassubpages'));
            }
        }
    }
    
    
    
    /**
    * Method to move a page up
    * @param string $id Record Id of the Page
    */
    protected function movePageUp($id)
    {
        $result = $this->objContentOrder->movePageUp($id);
        
        return $this->nextAction('viewpage', array('id'=>$id, 'message'=>'movepageup', 'result'=>$result));
    }
    
    
    /**
    * Method to move a page down
    * @param string $id Record Id of the Page
    */
    protected function movePageDown($id)
    {
        //$result = $this->objContextChapters->moveChapterDown($id);
        $result = $this->objContentOrder->movePageDown($id);
        
        return $this->nextAction('viewpage', array('id'=>$id, 'message'=>'movepageup', 'result'=>$result));
    }
    

    /**
    * Method to move a chapter up
    * @param string $id Record Id of the Chapter
    */
    protected function moveChapterUp($id)
    {
        $result = $this->objContextChapters->moveChapterUp($id);
        
        return $this->nextAction(NULL, array('id'=>$id, 'message'=>'movechapterup', 'result'=>$result));
    }
    

    /**
    * Method to move a chapter down
    * @param string $id Record Id of the Chapter
    */
    protected function moveChapterDown($id)
    {
        $result = $this->objContextChapters->moveChapterDown($id);
        
        return $this->nextAction(NULL, array('id'=>$id, 'message'=>'movechapterdown', 'result'=>$result));
    }
    
    
    /**
    * Method to view a chapter
    *
    * This method redirects to the first page in a chapter
    * 
    * @param string $id Record Id of the Chapter
    */
    protected function viewChapter($id)
    {
        $firstPage = $this->objContentOrder->getFirstChapterPage($this->contextCode, $id);
        
        if ($firstPage == FALSE) {
            $this->setVar('errorTitle', $this->objLanguage->languageText('mod_contextcontent_chapterhasnocontent', 'contextcontent', 'Chapter has no content'));
            $this->setVar('errorMessage', $this->objLanguage->languageText('mod_contextcontent_chapterhasnocontentinstruction', 'contextcontent', 'The chapter you have tried to view does not have any content, or had content which has now been deleted. Please choose another chapter'));
            return 'tpl_errormessage.php';
        } else {
            return $this->nextAction('viewpage', array('id'=>$firstPage['id'], 'message'=>$this->getParam('message')));
        }
    }
    
    
    /**
    * Method to Render a Chapter in PDF
    * @param string $id Record Id of Chapter
    * @return PDF File
    */
    private function viewPrintChapter($id)
    {
        // Load Class to clean up paths
        $objCleanUrl = $this->getObject('cleanurl', 'filemanager');
        
        // Get all the pages of the chapter
        $pages = $this->objContentOrder->getPages($id, $this->contextCode);
        
        //$contextCode = sha1($this->contextCode);
        $contextCode = ($this->contextCode);
        
        // Set the Path where the Files will be stored
        $path = $this->objConfig->getcontentBasePath().'/contextcontent/'.$contextCode;
        
        // Get Site Root Path
        $siteRoot = $this->objConfig->getSitePath().'/';
        
        // Clean up slashes
        $objCleanUrl->cleanUpUrl($siteRoot);
        
        // Load Class for Creating Directories
        $objMkdir = $this->newObject('mkdir', 'files');
        // Recursively create directories if it does not exist
        $objMkdir->mkdirs($path);
        
        // If Chapter has no pages
        if (count($pages) == 0) {
            // Send Error Message. Chapter has no Pages / Content
            $this->setVar('errorTitle', $this->objLanguage->languageText('mod_contextcontent_chapterhasnocontent', 'contextcontent', 'Chapter has no content'));
            $this->setVar('errorMessage', $this->objLanguage->languageText('mod_contextcontent_chapterhasnocontentinstruction', 'contextcontent', 'The chapter you have tried to view does not have any content, or had content which has now been deleted. Please choose another chapter'));
            
            return 'tpl_errormessage.php';
        } else {
            // Create Absolute Path to where PDF is stored
            $destination = $this->objConfig->getcontentBasePath().'/contextcontent/'.$contextCode.'/chapter_'.$id.'.pdf';
            // Clean Up slashes
            $objCleanUrl->cleanUpUrl($destination);
            
            // If PDF file exists
            if (file_exists($destination)) {
                // Redirect to PDF
                
                // Create Local Path to PDF
                $redirect = $this->objConfig->getcontentPath().'/contextcontent/'.$contextCode.'/chapter_'.$id.'.pdf';
                
                // Redirect
                header('location: '.$redirect);
                
            } else {
                // Else, Create PDF file
                
                /* Creating the PDF Process:
                Get All Pages
                Export all Pages into HTML format
                Use HTML Doc to convert HTML into PDF
                */
                
                // Array consisting of list of paths to HTML files
                $pagePath = array();
                
                // Loop through Chapter Pages
                foreach ($pages as $page)
                {
                    // Get Page Content
                    $pageContent = $this->objContentOrder->getPage($page['id'], $this->contextCode);
                    
                    // Create HTML filename
                    $filename = $path.'/'.$page['id'].'.html';
                    
                    // Add HTML Path to array
                    $pagePath[] = $filename;
                    
                    // Load File Object
                    $objFile = $this->getObject('dbfile', 'filemanager');
                    
                    /*
                    To get images to work properly, we need to use the absolute
                    path to the image.
                    
                    This code basically uses regex to get all items that use 
                    filemanager, and convert them to absolute paths.
                    */
                    
                    // Page Content
                    $pageText = $pageContent['pagecontent'];
                    
                    // Replace XHTML
                    $pageText = str_replace('&amp;', '&', $pageText);
                    
                    
                    // Get All links to files from file manager
                    preg_match_all('%"'.$siteRoot.'index\\.php\\?module=filemanager&action=file&id=(?P<id>.+?)&.+?"%i', $pageText, $results, PREG_PATTERN_ORDER);
                    
                    // Start Counter
                    $counter = 0;
                    
                    // Loop through all matches
                    foreach ($results['id'] as $fileId)
                    {
                        // Get Full Path to File
                        $filePath = $objFile->getFullFilePath($fileId);
                        
                        // Replace Item with Full Path to the File
                        $pageText = str_replace($results[0][$counter], '"'.$filePath.'"', $pageText);
                        // Increase Counter
                        $counter++;
                    }
                    
                    // Create HTML Document
                    $content = '<html><head>';
                    $content .= '<title>'.$pageContent['menutitle'].'</title>';
                    $content .= '</head><body>';
                    
                    $content .= $pageText;
                    
                    $content .= '</body></html>';
                    
                    // Write HTML Document to File System
                    $handle = fopen($filename, 'w');
                    fwrite($handle, $content);
                    fclose($handle);
                    
                    
                }
                
                // Load HTML Doc Class
                $objHtmlDoc = $this->getObject('htmldoc', 'htmldoc');
                
                // Create Parameters for HTML Doc Source Inputs
                $htmlSources = '';
                
                foreach ($pagePath as $htmlPage)
                {
                    $htmlSources .= $htmlPage.' ';
                }
                
                // Render to PDF
                $objHtmlDoc->render($htmlSources, TRUE, FALSE, $destination);
                
                // Check if PDF Exists - Prove that file was successfully created.
                if (file_exists($destination)) {
                    $redirect = $this->objConfig->getcontentPath().'/contextcontent/'.$contextCode.'/chapter_'.$id.'.pdf';
                    header('location: '.$redirect);
                } else {
                    // Else Show Error Message
                    $this->setVar('errorTitle', $this->objLanguage->languageText('mod_contextcontent_couldnotcreatepdf', 'contextcontent', 'Could not create PDF Document'));
                    $this->setVar('errorMessage', ' ');
                    return 'tpl_errormessage.php';
                }
            }
        }
    }
    
    
    /**
    * Method to change the navigation approach for context
    * This method is a response to an ajax call
    * @param string $type Type of navigation to switch to
    * @param string $pageId Record Id of the Page
    */
    private function changeNavigation($type, $pageId='')
    {
        
        $page = $this->objContentOrder->getPage($pageId, $this->contextCode);
        
        if ($page == FALSE) {
            echo ''; // Return Nothing - AJAX won't do anything
        } else {
            if ($type == 'twolevel') {
                $this->setSession('navigationType', 'twolevel');
                echo $this->objContentOrder->getTwoLevelNav($this->contextCode, $page['chapterid'], $pageId);
                echo '<p><a href="javascript:changeNav(\'tree\');">'.$this->objLanguage->languageText('mod_contextcontent_viewastree', 'contextcontent', 'View as Tree').'...</a>';
                echo '<br /><a href="javascript:changeNav(\'bookmarks\');">'.$this->objLanguage->languageText('mod_contextcontent_viewbookmarkedpages', 'contextcontent', 'View Bookmarked Pages').'</a></p>';
                
                
            } else if ($type == 'tree') {
                $this->setSession('navigationType', 'tree');
                echo $this->objContentOrder->getTree($this->contextCode, $page['chapterid'], 'htmllist', $pageId, 'contextcontent');
                echo '<p><a href="javascript:changeNav(\'twolevel\');">'.$this->objLanguage->languageText('mod_contextcontent_viewtwolevels', 'contextcontent', 'View Two Levels at a time').' ...</a><br /><a href="javascript:changeNav(\'bookmarks\');">'.$this->objLanguage->languageText('mod_contextcontent_viewbookmarkedpages', 'contextcontent', 'View Bookmarked Pages').'</a></p>';
                
                
            } else if ($type == 'bookmarks') {
                $this->setSession('navigationType', 'bookmarks');
                
                echo $this->objContentOrder->getBookmarkedPages($this->contextCode, $page['chapterid'], $pageId, 'contextcontent');
                
                echo '<p><a href="javascript:changeNav(\'twolevel\');">'.$this->objLanguage->languageText('mod_contextcontent_viewtwolevels', 'contextcontent', 'View Two Levels at a time').' ...</a><br /><a href="javascript:changeNav(\'tree\');">'.$this->objLanguage->languageText('mod_contextcontent_viewastree', 'contextcontent', 'View as Tree').'...</a></p>';
                
                
            } else {
                echo ''; // Unknown Type - Return Nothing - AJAX won't do anything
            }
        }
    }

    
    /**
     * Method to move a page to another a chapter
     * @param string $pageId Record Id of the Page
     * @param string $chapter Record Id of the Chapter
     */
    private function moveToChapter($pageId, $chapter)
    {
        $result = $this->objContentOrder->movePageToChapter($pageId, $chapter, $this->contextCode);
        
        return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>$result));
    }
    
    /**
     * Method to toggle the status of a bookmarked page
     */
    protected function changeBookMark()
    {
        $id = $this->getParam('id');
        $type = $this->getParam('type', 'on');
        
        if ($type == 'on') {
            $this->objContentOrder->bookmarkPage($id);
            echo '<a href="javascript:changeBookmark(\'off\');">'.$this->objLanguage->languageText('mod_contextcontent_removebookmark', 'contextcontent', 'Remove Bookmark').'</a>';
        } else {
            $this->objContentOrder->removeBookmark($id);
            echo '<a href="javascript:changeBookmark(\'on\');">'.$this->objLanguage->languageText('mod_contextcontent_bookmarkpage', 'contextcontent', 'Bookmark Page').'</a>';
        }
        
    }
    
    /**
     * Method to search for text within a context content
     * @param string $searchText Text to search for
     */
    protected function search($searchText)
    {
        $chapters = $this->objContextChapters->getContextChapters($this->contextCode);
        $this->setVarByRef('chapters', $chapters);
        
        $this->setLayoutTemplate('layout_firstpage_tpl.php');
        
        $objSearchResults = $this->getObject('searchresults', 'search');
        $searchResults = $objSearchResults->displaySearchResults($searchText, 'contextcontent', $this->contextCode);
        
        $this->setVarByRef('searchText', $searchText);
        $this->setVarByRef('searchResults', $searchResults);
        
        return 'tpl_searchresults.php';
    }
    
    /**
     * Method to get current session settings, as weill as list of chapters for a context
     * This is used for debugging purposes
     */
    public function home_debug()
    {
        echo '<pre>';
        //print_r($_SESSION);
        
        $numPages = $this->objContentOrder->getNumContextPages($this->contextCode);
        echo $numPages.'<br /><br />';
        
        $firstPage = $this->objContentOrder->getFirstPage($this->contextCode);
        print_r($firstPage);
        
        echo $this->contextCode;
        
        echo '<hr />';
        
        echo $this->objContextChapters->getContextChaptersSQL($this->contextCode);
        
        echo '<hr />';
        
        echo $this->objContextChapters->getNumContextChapters($this->contextCode);
        
        $results = $this->objContextChapters->getContextChapters($this->contextCode);
        
        print_r($results);
    }
    
    /**
    * Method to fix left right value - debugging purpose
    */
    protected function fixLeftRightValues()
    {
        $this->objContentOrder->rebuildContext($this->contextCode);
    }
    



}





?>