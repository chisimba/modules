<?php

class contextcontent extends controller
{
    
    protected $contextCode;
    
    public function init()
    {
        $this->objContentPages =& $this->getObject('db_contextcontent_pages');
        $this->objContentOrder =& $this->getObject('db_contextcontent_order');
        $this->objContentTitles =& $this->getObject('db_contextcontent_titles');
        $this->objContentInvolvement =& $this->getObject('db_contextcontent_involvement');
        
        $this->objContext =& $this->getObject('dbcontext', 'context');
        $this->contextCode = $this->objContext->getContextCode();
        
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    function requiresLogin($action)
    {
        if ($action=='' || $action == 'viewpage') {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function dispatch($action)
    {
        if ($this->contextCode == '' && $action != 'notincontext') {
            $action = 'notincontext';
        }
        
        $this->setLayoutTemplate('layout_contextcontent_tpl.php');
        
        switch ($action)
        {
            
            case 'notincontext':
                return 'tpl_notincontext.php';
            case 'switchcontext':
                die('Switch Context'); // Fix Up
            case 'addpage':
                return $this->addPage($this->getParam('id', ''), $this->getParam('context', ''));
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
            default:
                //return $this->home_debug();
                return $this->showContextTOC();
        }
    }
    
    public function home_debug()
    {
        $numPages = $this->objContentOrder->getNumContextPages($this->contextCode);
        echo $numPages.'<br /><br />';
        
        $firstPage = $this->objContentOrder->getFirstPage($this->contextCode);
        print_r($firstPage);
    }
    
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
    
    public function addPage($parent='', $contextCode='')
    {
        if ($contextCode != '' && $contextCode != $this->contextCode) {
            return $this->nextAction('switchcontext');
        }
        
        $this->setVar('mode', 'add');
        $this->setVar('formaction', 'savepage');
        
        $tree = $this->objContentOrder->getTree($this->contextCode, 'dropdown', $parent);
        $this->setVarByRef('tree', $tree);
        
        return 'tpl_addeditpage.php';
    }
    
    public function savePage()
    {
        
        $menutitle = stripslashes($this->getParam('menutitle'));
        $headerscripts = stripslashes($this->getParam('headerscripts'));
        $language = 'en';
        $pagecontent = stripslashes($this->getParam('pagecontent'));
        $parent = stripslashes($this->getParam('parentnode'));
        
        $titleId = $this->objContentTitles->addTitle('', $menutitle, $pagecontent, $language, $headerscripts);
        
        
        $pageId = $this->objContentOrder->addPageToContext($titleId, $parent, $this->contextCode);
        
        $this->setVar('mode', 'add');
        $this->setVar('formaction', 'savepage');
        
        return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'pagesaved'));
    }
    
    public function viewPage($pageId='')
    {
        if ($pageId == '') {
            return $this->nextAction(NULL);
        }
        
        $page = $this->objContentOrder->getPage($pageId, $this->contextCode);
        
        if ($page == FALSE) {
            echo 'page does not exist';
            //return $this->nextAction(NULL, array('error'=>'pagedoesnotexist'));
        }
        
        $this->setVarByRef('page', $page);
        
        $this->setVarByRef('nextPage', $this->objContentOrder->getNextPage($this->contextCode, $page['lft']));
        $this->setVarByRef('prevPage', $this->objContentOrder->getPreviousPage($this->contextCode, $page['lft']));
        $this->setVarByRef('breadcrumbs', $this->objContentOrder->getBreadcrumbs($this->contextCode, $page['lft'], $page['rght']));
        
        return 'tpl_viewpage.php';
    }
    
    function editPage($pageId)
    {
        if ($pageId == '') {
            return $this->nextAction(NULL);
        }
        
        $page = $this->objContentOrder->getPage($pageId, $this->contextCode);
        
        if ($page == FALSE) {
            return $this->nextAction(NULL, array('error'=>'pagedoesnotexist'));
        }
        
        $this->setVarByRef('page', $page);
        
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
        
        if ($contextCode != '' && $contextCode != $this->contextCode) {
            return $this->nextAction('switchcontext');
        } else {
            $page = $this->objContentOrder->getPage($pageId, $this->contextCode);
            
            if ($page == FALSE) {
                return $this->nextAction(NULL, array('error'=>'pagedoesnotexist'));
            } else {
                $this->objContentPages->updatePage($page['pageid'], $menutitle, $pagecontent, $headerScripts);
                
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
                    
                    $this->objContentOrder->rebuildContext($this->contextCode);
                    
                    return $this->nextAction(NULL);
                } else {
                    return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'deletecancelled'));
                }
            } else {
            
                return $this->nextAction('viewpage', array('id'=>$pageId, 'message'=>'pagehassubpages'));
            }
        }
    }
    
    function fixLeftRightValues()
    {
        $this->objContentOrder->rebuildContext($this->contextCode);
        
        
    }
    
    function movePageUp($id)
    {
        $result = $this->objContentOrder->movePageUp($id);
        
        return $this->nextAction('viewpage', array('id'=>$id, 'message'=>'movepageup', 'result'=>$result));
    }
    
    function movePageDown($id)
    {
        $result = $this->objContentOrder->movePageDown($id);
        
        return $this->nextAction('viewpage', array('id'=>$id, 'message'=>'movepageup', 'result'=>$result));
    }

}


?>