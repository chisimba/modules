<?php

/**
* Controller Class for Context Content Module
* @author Tohir Solomons
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
            case 'movechapterup':
                return $this->moveChapterUp($this->getParam('id'));
            case 'movechapterdown':
                return $this->moveChapterDown($this->getParam('id'));
			case 'viewchapter':
				return $this->viewChapter($this->getParam('id'));
            case 'testpdf':
                return $this->testPDF();
            case 'viewprintchapter':
                return $this->viewPrintChapter($this->getParam('id'));
            case 'changenavigation':
                return $this->changeNavigation($this->getParam('type'), $this->getParam('id'));
            case 'movetochapter':
                return $this->moveToChapter($this->getParam('id'), $this->getParam('chapter'));
            default:
                //return $this->home_debug();
                return $this->showContextChapters();
        }
    }
    
    /**
    *
    *
    *
    */
    protected function showContextChapters()
    {
        $this->objContentOrder->checkPagesNotInChapter($this->contextCode);
        
        
        $numContextChapters = $this->objContextChapters->getNumContextChapters($this->contextCode);
        
        $this->setVarByRef('numContextChapters', $numContextChapters);
        
        if ($numContextChapters == 0) {
            
            $this->setLayoutTemplate(NULL);
            
            if ($this->isValid('savechapter')) {
                return 'tpl_nochapters.php';
            } else {
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
    *
    *
    *
    */
    function addChapter()
    {
        $this->setVar('mode', 'add');
        
        return 'tpl_addeditchapter.php';
    }
    
    /**
    *
    *
    *
    */
    function saveChapter()
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
    *
    *
    *
    */
	function editChapter($id)
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
    *
    *
    *
    */
    function updateChapter()
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
    *
    *
    *
    */
    public function home_debug()
    {
        $numPages = $this->objContentOrder->getNumContextPages($this->contextCode);
        echo $numPages.'<br /><br />';
        
        $firstPage = $this->objContentOrder->getFirstPage($this->contextCode);
        print_r($firstPage);
    }
    
    /**
    *
    *
    *
    */
    public function showContextTOC()
    {
        $numPages = $this->objContentOrder->getNumContextPages($this->contextCode);
        
        if ($numPages > 0) {
            $firstPage = $this->objContentOrder->getFirstPage($this->contextCode);
            //return $this->viewPage($firstPage['id']);
            return $this->nextAction('viewpage', array('id'=>$firstPage['id']));
        } else {
            
            return 'tpl_contenthome.php';
        }
    }
    
    /**
    *
    *
    *
    */
    public function addPage($chapter, $parent='', $contextCode='')
    {
        if ($contextCode != '' && $contextCode != $this->contextCode) {
            return $this->nextAction('switchcontext');
        }
        
        $this->setLayoutTemplate(NULL);
        
        $this->setVar('mode', 'add');
        $this->setVar('formaction', 'savepage');
        $this->setVarByRef('chapter', $chapter);
        $this->setVarByRef('currentChapter', $chapter);
        
        $tree = $this->objContentOrder->getTree($this->contextCode, $chapter, 'dropdown', $parent);
        $this->setVarByRef('tree', $tree);
        
        return 'tpl_addeditpage.php';
    }
    
    /**
    *
    *
    *
    */
    public function savePage()
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
    *
    *
    *
    */
    public function viewPage($pageId='')
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
			$chapterLink->link = 'Chapter: '.$chapterTitle;
			
            array_unshift($breadcrumbs, $chapterLink->show());
            //array_unshift($breadcrumbs, 'Chapter: '.$chapterTitle);
        }
        
        $this->objMenuTools->addToBreadCrumbs($breadcrumbs);
        
        
        $chapters = $this->objContextChapters->getContextChapters($this->contextCode);
        $this->setVarByRef('chapters', $chapters);
        
        return 'tpl_viewpage.php';
    }
    
    /**
    *
    *
    *
    */
    function editPage($pageId)
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
    
    function updatePage()
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
    
    function deletePage($pageId)
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
    *
    *
    *
    */
    function deletePageConfirm()
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
                    $this->objContentTitles->deleteTitle($page['titleid']);
                    $this->objContentOrder->deletePage($page['id']);
                    
                    $this->objContentOrder->rebuildContext($this->contextCode, $page['chapterid']);
                    
                    return $this->nextAction(NULL);
                } else {
                    return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'deletecancelled'));
                }
            } else {
            
                return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'pagehassubpages'));
            }
        }
    }
    
    /**
    *
    *
    *
    */
    function fixLeftRightValues()
    {
        $this->objContentOrder->rebuildContext($this->contextCode);
        
        
    }
    
    /**
    *
    *
    *
    */
    function movePageUp($id)
    {
        $result = $this->objContentOrder->movePageUp($id);
        
        return $this->nextAction('viewpage', array('id'=>$id, 'message'=>'movepageup', 'result'=>$result));
    }
    
    /**
    *
    *
    *
    */
    function movePageDown($id)
    {
        //$result = $this->objContextChapters->moveChapterDown($id);
        $result = $this->objContentOrder->movePageDown($id);
        
        return $this->nextAction('viewpage', array('id'=>$id, 'message'=>'movepageup', 'result'=>$result));
    }
    
    /**
    *
    *
    *
    */
    function moveChapterUp($id)
    {
        $result = $this->objContextChapters->moveChapterUp($id);
        
        return $this->nextAction(NULL, array('id'=>$id, 'message'=>'movechapterup', 'result'=>$result));
    }
    
    /**
    *
    *
    *
    */
    function moveChapterDown($id)
    {
        $result = $this->objContextChapters->moveChapterDown($id);
        
        return $this->nextAction(NULL, array('id'=>$id, 'message'=>'movechapterdown', 'result'=>$result));
    }
	
    /**
    *
    *
    *
    */
	function viewChapter($id)
	{
		$firstPage = $this->objContentOrder->getFirstChapterPage($this->contextCode, $id);
		
		if ($firstPage == FALSE) {
			$this->setVar('errorTitle', 'Chapter has no content');
            $this->setVar('errorMessage', 'HTML Doc - expand me');
            return 'tpl_errormessage.php';
		} else {
			return $this->nextAction('viewpage', array('id'=>$firstPage['id']));
		}
	}
    
    /**
    *
    *
    *
    */
    function testPDF()
    {
        $objHtmlDoc = $this->getObject('htmldoc', 'htmldoc');
        
        echo $objHtmlDoc->render('http://ipsa4/islamicstudies/mcl/', TRUE, FALSE, '/home/tohir/www/chisimba_framework/app/usrfiles/test.pdf');
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
            $this->setVar('errorTitle', 'Chapter has no content');
            $this->setVar('errorMessage', 'HTML Doc');
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
                    $this->setVar('errorTitle', 'Could not create PDF Document');
                    $this->setVar('errorMessage', 'HTML Doc');
                    return 'tpl_errormessage.php';
                }
            }
            
        }
        
        
        
    }
    
    /**
    *
    *
    *
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
                echo '<p><a href="javascript:changeNav(\'tree\');">View as Tree...</a></p>';
                
                
            } else if ($type == 'tree') {
                $this->setSession('navigationType', 'tree');
                echo $this->objContentOrder->getTree($this->contextCode, $page['chapterid'], 'htmllist', $pageId, 'contextcontent');
                echo '<p><a href="javascript:changeNav(\'twolevel\');">View as Index ...</a></p>';
                
                
            } else {
                echo ''; // Unknown Type - Return Nothing - AJAX won't do anything
            }
        }
    }
    
    private function moveToChapter($pageId, $chapter)
    {
        $result = $this->objContentOrder->movePageToChapter($pageId, $chapter, $this->contextCode);
        
        return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>$result));
    }

}


?>