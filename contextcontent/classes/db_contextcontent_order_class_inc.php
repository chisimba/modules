<?php

/**
* Class to Arrange the order of pages
*
*
*/
class db_contextcontent_order extends dbtable
{

    /**
    * Constructor
    */
    public function init()
    {
        parent::init('tbl_contextcontent_order');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objConfig =& $this->getObject('altconfig', 'config');
        
        $this->loadClass('treemenu','tree');
		$this->loadClass('treenode','tree');
		$this->loadClass('htmllist','tree');
		$this->loadClass('htmldropdown','tree');
		$this->loadClass('dhtml','tree');
        
        $this->loadClass('link', 'htmlelements');
    }
    
    /**
    * Method to get the number of pages in a context
    * @param string $contextCode Code of Context to get num pages
    */
    public function getNumContextPages($contextCode)
    {
        return $this->getRecordCount('WHERE contextcode=\''.$contextCode.'\'');
    }
    
    /**
    * Method to get the first content page in a context.
    * @param string $contextCode Context Code
    * @access public
    */
    public function getFirstPage($contextCode)
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_order.parentid, tbl_contextcontent_pages.menutitle, pagecontent, headerscripts, lft, rght
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE contextcode=\''.$contextCode.'\' AND parentid = \'root\'
        ORDER BY lft, pageorder LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
	
	/**
    * Method to get the first content page in a context.
    * @param string $contextCode Context Code
    * @access public
    */
    public function getFirstChapterPage($contextCode, $chapter)
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_order.parentid, tbl_contextcontent_pages.menutitle, pagecontent, headerscripts, lft, rght
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE tbl_contextcontent_order.chapterid=\''.$chapter.'\' AND contextcode=\''.$contextCode.'\' AND parentid = \'root\'
        ORDER BY lft, pageorder LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    
    /**
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @return array Details of the Page, FALSE if does not exist
    * @access public
    */
    public function getPage($pageId, $contextCode)
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_order.chapterid, tbl_contextcontent_order.parentid, tbl_contextcontent_pages.menutitle, pagecontent, headerscripts, lft, rght, tbl_contextcontent_pages.id as pageid, tbl_contextcontent_order.titleid
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE tbl_contextcontent_order.id=\''.$pageId.'\' AND contextcode=\''.$contextCode.'\'
        ORDER BY lft LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
	
    public function checkPagesNotInChapter($context)
    {
        $pagesNotInChapter = $this->getPagesNotInChapters($context);
        
        if (count($pagesNotInChapter) > 0) {
            $title = 'Chapter 1';
            $intro = '<p>This chapter has been autocreated during an upgrade. Please ask the lecturer in this course to change this introduction</p>';
            $visibility = 'Y';
            
            $objChapters =& $this->getObject('db_contextcontent_chapters');
            $objContextChapters =& $this->getObject('db_contextcontent_contextchapter');
            
            $chapterId = $objChapters->addChapter('', $title, $intro);
            
            $result = $objContextChapters->addChapterToContext($chapterId, $context, $visibility);
            
            if ($result) {
                foreach ($pagesNotInChapter as $page)
                {
                    $this->update('id', $page['id'], array('chapterid'=>$chapterId));
                }
            }
        }
    }
    
    private function getPagesNotInChapters($context)
    {
        return $this->getAll(' WHERE contextcode= \''.$context.'\' AND chapterid IS NULL ');
    }
    
    /**
    *
    *
    */
    public function getContextPages($context, $chapter='')
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_order.parentid, tbl_contextcontent_pages.menutitle, lft, rght, tbl_contextcontent_order.bookmark, tbl_contextcontent_order.isbookmarked FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id) 
        WHERE tbl_contextcontent_order.contextcode= \''.$context.'\'  ';
        
        if ($chapter != '') {
            $sql .= ' AND tbl_contextcontent_order.chapterid= \''.$chapter.'\'';
        }
        
        $sql .= ' ORDER BY lft';
        
        return $this->getArray($sql);
    }
    
	public function getBookmarkedPages($context, $chapter='', $defaultSelected='', $module='contextcontent')
	{
		$results = $this->getContextPages($context, $chapter);
		
		$str = '<ul>';
		foreach($results as $page)
		{
			if($page['isbookmarked'] == 'Y')
			{
				$link = new link($this->uri(array('action'=>'viewpage', 'id'=>$page['id'])));
				$link->link = $page['menutitle'];
				$str .= '<li>'.$link->show().'</li>';
			}
		}
		$str .= '</ul>';

		return $str;
	}

    /**
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @param string $module Module to point URIs to
    * @param string $disabledNode Record Id of Node to disable it, and its children when editing
    * @return array Details of the Page, FALSE if does not exist
    * @access public
    */
    public function getTree($context, $chapter='', $type='dropdown', $defaultSelected='', $module='contextcontent', $disabledNode='')
    {
        $results = $this->getContextPages($context, $chapter);
        
        if ($defaultSelected != '') {
            $this->defaultSelected = $this->getRow('id', $defaultSelected);
            
            if ($this->defaultSelected == FALSE) {
                $this->defaultSelected = '';
            }
        } else {
            $this->defaultSelected = '';
        }
        
        if ($disabledNode != '') {
            $this->disabledNode = $this->getRow('id', $disabledNode);
            
            if ($this->disabledNode == FALSE) {
                $this->disabledNode = '';
                $hasDisabledNode = FALSE;
            } else {
                $hasDisabledNode = TRUE;
            }
        } else {
            $this->disabledNode = '';
            $hasDisabledNode = FALSE;
        }
        
        switch ($type)
        {
            case 'dropdown': 
                return $this->generateDropdownTree($results, $defaultSelected, $hasDisabledNode);
                break;
            case 'dhtml': 
                return $this->generateDHTMLTree($results, $defaultSelected, $module);
                break;
            default:
                return $this->generateHtmllistTree($results, $defaultSelected, $module);
                break;
        }
        
        
    }
    
    /**
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @return array Details of the Page, FALSE if does not exist
    * @access private
    */
    private function generateHtmllistTree($results, $defaultSelected='', $module)
    {
        $treeMenu = new treemenu();
        
        $nodeArray = array();
        
        foreach ($results as $treeItem)
        {
            $nodeDetails = array('text'=>htmlentities($treeItem['menutitle']), 'link'=>$this->uri(array('action'=>'viewpage', 'id'=>$treeItem['id']), $module));
            
            if ($treeItem['id'] == $defaultSelected) {
                $nodeDetails['cssClass'] = 'confirm';
            }
            
            $node =& new treenode ($nodeDetails);
            $nodeArray[$treeItem['id']] =& $node;
            
		//if($treeItem['isbookmarked'] == 'Y'){
            if ($treeItem['parentid'] == 'root') {
                $treeMenu->addItem($node);
            } else {
                if (array_key_exists($treeItem['parentid'], $nodeArray)) {
                    $nodeArray[$treeItem['parentid']]->addItem($node);
                }
	}
            }
        //}
        
        $tree = &new htmllist($treeMenu, array('topMostListClass'=>'htmlliststyle'));
        
        return $tree->getMenu();
    }
    
    /**
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @return array Details of the Page, FALSE if does not exist
    * @access private
    */
    private function generateDHTMLTree($results, $defaultSelected='', $module)
    {
        $treeMenu = new treemenu();
        
        $nodeArray = array();
        
        $icon         = 'folder.gif';
		$expandedIcon = 'folder-expanded.gif';
        
        foreach ($results as $treeItem)
        {
            $nodeDetails = array('text'=>htmlentities($treeItem['menutitle']), 'link'=>$this->uri(array('action'=>'viewpage', 'id'=>$treeItem['id']), $module), 'icon' => $icon, 'expandedIcon' => $expandedIcon);
            
            if ($treeItem['id'] == $defaultSelected) {
                $nodeDetails['cssClass'] = 'confirm';
            }
            
            $node =& new treenode ($nodeDetails);
            $nodeArray[$treeItem['id']] =& $node;
            
		//if($treeItem['isbookmarked'] == 'Y'){
            if ($treeItem['parentid'] == 'root') {
                $treeMenu->addItem($node);
            } else {
                if (array_key_exists($treeItem['parentid'], $nodeArray)) {
                    $nodeArray[$treeItem['parentid']]->addItem($node);
                }
}
            }
        //}
        
        $tree = &new htmllist($treeMenu, array('inputName'=>'parentnode', 'id'=>'input_parentnode'));
        
        $treeMenu = &new dhtml($treeMenu, array('images' => 'kins/_common/icons/tree', 'defaultClass' => 'treeMenuDefault'));
        
        return $treeMenu->getMenu();
    }
    
    /**
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @param boolean $hasDisabledNodes Flag on whether some nodes are disabled
    * @return array Details of the Page, FALSE if does not exist
    * @access private
    */
    private function generateDropdownTree($results, $defaultSelected='', $hasDisabledNodes=FALSE)
    {
        $treeMenu = new treemenu();
        
        $nodeArray = array();
        
        $rootnode =& new treenode (array('text'=>'[- Root -]'));
        
        foreach ($results as $treeItem)
        {
            $nodeDetails = array('text'=>htmlentities($treeItem['menutitle']), 'link'=>$treeItem['id']);
            
            if ($hasDisabledNodes && $treeItem['lft'] >= $this->disabledNode['lft'] && $treeItem['rght'] <= $this->disabledNode['rght']) {
                $nodeDetails['extra'] = 'disabled="disabled" title="This page is on a lower level than the current page you are editing"';
            }
            
            $node =& new treenode ($nodeDetails);
            $nodeArray[$treeItem['id']] =& $node;
            //var_dump($treeItem);die;
		//if($treeItem['isbookmarked'] == 'Y'){
            if ($treeItem['parentid'] == 'root') {
                	$rootnode->addItem($node);
            } else {
                if (array_key_exists($treeItem['parentid'], $nodeArray)) {
                    $nodeArray[$treeItem['parentid']]->addItem($node);
                }
            }
        }
        //}
        
        $treeMenu->addItem($rootnode);
        
        $tree = &new htmldropdown($treeMenu, array('inputName'=>'parentnode', 'id'=>'input_parentnode', 'selected'=>$defaultSelected));
        
        return $tree->getMenu();
    }
    
    /**
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @return array Details of the Page, FALSE if does not exist
    * @access private
    */
    public function addPageToContext($titleId, $parentId, $context, $chapter='', $bookmark='', $isBookmark='')
    {
        $lastRight = $this->getLastRight($context, $parentId, $chapter);
        $leftPointer = $lastRight;
        
        if ($parentId == '') {
            $leftPointer++;
        }
        $rightPointer = $leftPointer+1;
        
        if ($parentId == '') {
            $pageOrder = 1;
        } else {
            $this->updateLeftRightPointers($chapter, $lastRight-1);
        }
        
        $pageOrder = $this->getLastOrder($context, $parentId)+1;
        
        $this->clearChapterPDF($chapter, $context);
        
        return $this->insertTitle($context, $chapter, $titleId, $parentId, $leftPointer, $rightPointer, $pageOrder, 'Y', $bookmark, $isBookmark);
    }
    
    
    
    private function insertTitle($context, $chapter='', $titleId, $parentId, $left, $right, $pageOrder=1, $visibility='Y', $bookmark='', $isBookmark='N')
    {
        $lastId = $this->insert(array(
                'contextcode' => $context,
                'titleid' => $titleId,
                'parentid' => $parentId,
                'chapterid' => $chapter,
                'lft' => $left,
                'rght' => $right,
                'pageorder' => $pageOrder,
                'visibility' => $visibility,
                'creatorid' => $this->objUser->userId(),
                'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
		'bookmark' => $bookmark,
		'isbookmarked' => $isBookmark
            ));
        
        // Extra Step to Prevent Null Values
        if ($parentId == '') {
            $this->update('id', $lastId, array('parentid'=>'root'));
        }
        
        // Delete existing PDF version
        $this->clearChapterPDF($chapter, $context);
        
        return $lastId;
    }
    
    /**
    * Method to Delete a PDF Version of a Chapter
    *
    * This is done everytime a page is added, edited, deleted or moved.
    * The next time the PDF is requested, it gets created.
    *
    * @param string $chapterId Record Id of the Chapter
    * @param string $contextCode Context the Chapter is In
    */
    private function clearChapterPDF($chapterId, $contextCode)
    {
        // Load Class to clean up paths
        $objCleanUrl = $this->getObject('cleanurl', 'filemanager');
        
        // Set path where file will be
        $destination = $this->objConfig->getcontentBasePath().'/contextcontent/'.$contextCode.'/chapter_'.$chapterId.'.pdf';
        
        // Clean Up file name
        $objCleanUrl->cleanUpUrl($destination);
        
        // If PDF file exists
        if (file_exists($destination)) {
            // Delete it!
            @unlink($destination);
        }
    }
    
    private function getLastRight($context, $parent='', $chapter='')
    {
        if ($parent == '') {
            $result = $this->getAll('WHERE contextcode =\''.$context.'\' AND chapterid=\''.$chapter.'\' ORDER BY rght DESC LIMIT 1');
        } else {
            $result = $this->getAll('WHERE id =\''.$parent.'\' AND contextcode =\''.$context.'\' AND chapterid=\''.$chapter.'\' ORDER BY rght DESC LIMIT 1');
        }
        
        
        if (count($result) == 0) {
            return 0;
        } else {
            return $result[0]['rght'];
        }
    }
    
    private function getLastOrder($context, $parent='')
    {
        $sql = 'WHERE parentid =\''.$parent.'\' AND contextcode =\''.$context.'\' ORDER BY pageorder DESC LIMIT 1';
        $result = $this->getAll($sql);
        
        if (count($result) == 0) {
            return 0;
        } else {
            return $result[0]['pageorder'];
        }
    }
    
    private function updateLeftRightPointers($chapter, $base, $amount=2)
    {
        $sqlLeft = 'UPDATE tbl_contextcontent_order SET rght=rght+'.$amount.' WHERE rght > '.$base.' AND chapterid=\''.$chapter.'\'';
        $sqlRight = 'UPDATE tbl_contextcontent_order SET lft=lft+'.$amount.' WHERE lft > '.$base.' AND chapterid=\''.$chapter.'\'';
        
        $this->query($sqlLeft);
        $this->query($sqlRight);
    }
    
    
    
    public function getPreviousPage($context, $chapter, $leftValue='', $module='contextcontent')
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_pages.menutitle
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE contextcode =\''.$context.'\' AND lft < '.$leftValue.' AND chapterid=\''.$chapter.'\'
        ORDER BY lft DESC LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return '';
        } else {
            $page = $results[0];
            $link = new link ($this->uri(array('action'=>'viewpage', 'id'=>$page['id']), $module));
            $link->link = '&#171; '.'Previous Page: '.htmlentities($page['menutitle']);
            return $link->show();
        }
    }
    
    /**
    *
    *
    *
    */
    public function isFirstPageOnLevel($id)
    {
        $record = $this->getRow('id', $id);
        
        if ($record['parentid'] == 'root') {
            if ($record['lft'] == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $parent = $this->getRow('id', $record['parentid']);
            
            if ($parent['lft']+1 == $record['lft']) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    
    /**
    *
    *
    *
    */
    public function isLastPageOnLevel($id)
    {
        $record = $this->getRow('id', $id);
        
        if ($record['parentid'] == 'root') {
            
            $lastRight = $this->getLastRight($record['contextcode'], '', $record['chapterid']);
            
            if ($record['rght'] == $lastRight) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $parent = $this->getRow('id', $record['parentid']);
            
            if ($parent['rght'] == $record['rght']+1) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    
    /**
    *
    *
    *
    */
    public function getNextPage($context, $chapter, $leftValue='', $module='contextcontent')
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_pages.menutitle
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE contextcode =\''.$context.'\' AND lft > '.$leftValue.' AND chapterid=\''.$chapter.'\'
        ORDER BY lft LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return '';
        } else {
            $page = $results[0];
            
            $link = new link ($this->uri(array('action'=>'viewpage', 'id'=>$page['id']), $module));
            $link->link = 'Next Page: '.htmlentities($page['menutitle']).' &#187;';
            return $link->show();
        }
    }
    
    /**
     * Method to get the Breadcrumbs to a page
     *
     * @param string $context Context page is in
     * @param int $leftValue Left Value of Page
     * @param int $rightValue Right Value of Page
     * @return string completed Breadcrumbs
     */
    public function getBreadcrumbs($context, $chapter, $leftValue, $rightValue, $linkLastItem=FALSE)
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_pages.menutitle
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE contextcode =\''.$context.'\' AND chapterid =\''.$chapter.'\' AND lft <= '.$leftValue.' AND rght >= '.$rightValue.'
        ORDER BY lft ';
        
        //echo $sql;
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return '';
        } else {
            $returnString = array();
            $separator = '';
            $counter = 1;
            
            foreach ($results as $page)
            {
                if ($counter == count($results)) {
                    $returnString[] = htmlentities($page['menutitle']);
                } else {
                    $link = new link ($this->uri(array('action'=>'viewpage', 'id'=>$page['id'])));
                    $link->link = htmlentities($page['menutitle']);
                    $returnString[] = $link->show();
                }
                
                $separator = ' &#187; ';
                $counter++;
            }
            
            return $returnString;
        }
    }
    
    /**
     * Method to Commence Rebuilding a Tree
     * 
     * This function is used to start the process of fixing up the left and right values
     * in the modified preorder traversal approach
     *
     * @param string $context Context Code of Context to Fix
     */
    public function rebuildContext($context, $chapter)
    {
        $this->orderArray = array();
        $this->_rebuild_tree($context, $chapter, 'root', 0, 1);
        
        // Delete existing PDF version
        $this->clearChapterPDF($chapter, $context);
    }
    
    /**
    * Method to Rebuild a Tree
    *
    * This function recursives itself to update the left and right values of a tree
    *
    * @access private
    * @param string $context Context the Node is in
    * @param string $parent Record Id of the Parent post
    * @param int $left Left Value of the Parent
    * @param int $level Level of the Post
    */
    private function _rebuild_tree($context, $chapter, $parent, $left, $level) 
    {
        // the right value of this node is the left value + 1
        $right = $left+1;
        
        // if ($parent == 'root') {
            // $name = 'root';
        // } else {
            $name = $parent;
        //}
        
        $thisRow = $this->getRow('id', $parent);
        //echo $parent.'<br />';
        if (!array_key_exists($thisRow['parentid'], $this->orderArray)) {
            $this->orderArray[$thisRow['parentid']] = 1;
        } else {
            $this->orderArray[$thisRow['parentid']] = $this->orderArray[$thisRow['parentid']]+1;
        }
        
       
        // get all children of this node
        $result = $this->getAll(' WHERE  contextcode =\''.$context.'\' AND parentid=\''.$parent.'\' AND chapterid=\''.$chapter.'\' ORDER BY pageorder');
        
        foreach ($result as $row)
        {
            $right = $this->_rebuild_tree($context, $chapter, $row['id'], $right, $level+1);
            
        }

        if ($thisRow != FALSE) {
        
            $this->update('id', $parent, array('lft'=>$left, 'rght'=>$right, 'pageorder'=>$this->orderArray[$thisRow['parentid']]));
        }
        
        
        //echo 'Id - '.$parent.', left - '.$left.', right - '.$right.', order - '.$this->orderArray[$name].'<br />';
        
        
        
       // return the right value of this node + 1
       return $right+1;
    }
    
    /**
     * Method to delete a page
     *
     * @param string $id Record Id of the Page
     * @return boolean Result of deletion
     */
    function deletePage($id)
    {
        return $this->delete('id', $id);
    }
    
    /**
    * Method to move a page up
    * @param string $id Record Id of the Page
    * @return boolean Result of Page Move
    */
    function movePageUp($id)
    {
        $page = $this->getRow('id', $id);
        
        if ($page == FALSE) {
            return FALSE;
        }
        
        $nextPageSQL = ' WHERE parentid=\''.$page['parentid'].'\' AND contextcode =\''.$page['contextcode'].'\' AND pageorder < '.$page['pageorder'].' ORDER BY pageorder DESC';
        $nextPage = $this->getAll($nextPageSQL);
        
        if (count($nextPage) == 0) {
            return FALSE;
        } else {
            $nextPage = $nextPage[0];
            
            $this->update('id', $page['id'], array('pageorder'=>$nextPage['pageorder']));
            $this->update('id', $nextPage['id'], array('pageorder'=>$page['pageorder']));
            
            $this->rebuildContext($page['contextcode'], $page['chapterid']);
            
            // Delete existing PDF version
            $this->clearChapterPDF($page['chapterid'], $page['contextcode']);
            
            return TRUE;
        }
        
        
    }
    
    /**
    * Method to move a page down
    * @param string $id Record Id of the Page
    * @return boolean Result of Page Move
    */
    function movePageDown($id)
    {
        $page = $this->getRow('id', $id);
        
        if ($page == FALSE) {
            return FALSE;
        }
        
        $nextPageSQL = ' WHERE parentid=\''.$page['parentid'].'\' AND contextcode   	 =\''.$page['contextcode'].'\' AND pageorder > '.$page['pageorder'].' ORDER BY pageorder ';
        $nextPage = $this->getAll($nextPageSQL);
        
        if (count($nextPage) == 0) {
            return FALSE;
        } else {
            $nextPage = $nextPage[0];
            
            $this->update('id', $page['id'], array('pageorder'=>$nextPage['pageorder']));
            $this->update('id', $nextPage['id'], array('pageorder'=>$page['pageorder']));
            
            $this->rebuildContext($page['contextcode'], $page['chapterid']);
            
            // Delete existing PDF version
            $this->clearChapterPDF($page['chapterid'], $page['contextcode']);
            
            return TRUE;
        }
        
        
    }
    
    /**
    * Method to reorder items by passing a string with items
    *
    * This method is particularly designed to work with Scriptaculous's Sortables
    * @param string $context Context we are working with
    * @param string $string String containing data
    * @param string $splitter Character or String which can be used to separate items
    * @param string $obfuscator (Optional) Scriptaculous does not allow you to use underscore,
    * Therefore developers will probably replace them with another character like
    * an asterisk. The default is underscore for those who do not use this feature
    */
    function reOrderItems($context, $chapter, $string, $splitter='&', $obfuscator='_')
    {
        // Explode Items
        $items = explode($splitter, $string);
        
        // Only perform updates if there are more than one item
        if (count($items) > 0) {
            // Start Counter
            $counter = 1;
            // Loop through items
            foreach ($items as $item)
            {
                // Replace Obfuscator with proper underscore
                $item = str_replace($obfuscator, '_', $item);
                // Do Update
                $this->update('id', $item, array('pageorder'=>$counter));
                // Increase Counter
                $counter++;
            }
            // Rebuild Tree
            $this->rebuildContext($context, $chapter);
            
            // Delete existing PDF version
            $this->clearChapterPDF($chapter, $context);
        }
        
        return;
    }
    
	/**
	*
	*
	*/
    function changeParent($context, $chapter, $node, $newParent)
    {
        if ($newParent =='') {
            $newParent = 'root';
        }
        $this->update('id', $node, array('parentid'=>$newParent));
        $this->rebuildContext($context, $chapter);
    }
	
	/**
	*
	*
	*/
	function getTwoLevelNav($context, $chapter, $id)
	{
		$record = $this->getRow('id', $id);
		// Fix up if record dows not exist
		if ($record == FALSE) {
			return 'RECORD DOES NOT EXIST';
		}
		// Create Menu for Nodes
		$treeMenu = new treemenu();
		// Create Array for Nodes
        	$nodeArray = array();
		// Option 1 - Node is Root Node on First Level
		if ($record['parentid'] == 'root') {
			// Get Siblings
			$firstLevelNodes = $this->getPages($chapter, $context, ' AND parentid=\'root\'');
			// Loop through siblings
			foreach ($firstLevelNodes as $treeItem)
			{
				//var_dump($treeItem);die;
				// Create Array with Node Details
				$nodeDetails = array('text'=>htmlentities($treeItem['menutitle']), 'link'=>$this->uri(array('action'=>'viewpage', 'id'=>$treeItem['id'])));
				//var_dump($nodeDetails);die;
				// Add style if current node
				if ($treeItem['id'] == $id) {
					unset($nodeDetails['link']); // Disable Link
					$nodeDetails['cssClass'] = 'confirm';
				}
				
				// Create Node
				$node =& new treenode ($nodeDetails);
				
				// Check If current Item and has childen
				if ($treeItem['id'] == $id && ($record['rght']-$record['lft']-1 > 0)) {
					
					// Get immediate Children
					$childrenNodes = $this->getPages($chapter, $context, ' AND parentid=\''.$id.'\'');
					
					// Add Childen
					foreach ($childrenNodes as $childNode)
					{
						// Create Array with Child Node Details
						$childNodeDetails = array('text'=>htmlentities($childNode['menutitle']), 'link'=>$this->uri(array('action'=>'viewpage', 'id'=>$childNode['id'])));
						
						// Create Child Node
						$childNode =& new treenode ($childNodeDetails);
						
						// Add to Current Node
						$node->addItem($childNode);
						
					}
				}
				
				// Add to Menu
				//if($treeItem['isbookmarked'] == 'Y')
					$treeMenu->addItem($node);
			}
			
			// Create Menu Display
			$tree = &new htmllist($treeMenu, array('topMostListClass'=>'twolevelstyle'));
			
			// Return Menu Display
			return $tree->getMenu();
			
		// OPTION 2: Not Root node, but doesn't have children
		} else if ($record['rght']-$record['lft']-1 == 0) {
			
			// Get Siblings
			$siblings = $this->getPages($chapter, $context, ' AND parentid=\''.$record['parentid'].'\'');
			
			// Loop through siblings
			foreach ($siblings as $treeItem)
			{
				// Create Array with Node Details
				$nodeDetails = array('text'=>htmlentities($treeItem['menutitle']), 'link'=>$this->uri(array('action'=>'viewpage', 'id'=>$treeItem['id'])));
				
				// Add style if current node
				if ($treeItem['id'] == $id) {
					unset($nodeDetails['link']); // Disable Link
					$nodeDetails['cssClass'] = 'confirm';
				}
				
				// Create Node
				$node =& new treenode ($nodeDetails);
				// Add to Menu
				$treeMenu->addItem($node);
				
			}
			
			// Create Menu Display
			$tree = &new htmllist($treeMenu);
			
			// Return Menu Display
			return $tree->getMenu();
			
		// Option 3 - Not Root Node, has Children
		} else {
			
			$recordInfo = $this->getPages($chapter, $context, ' AND tbl_contextcontent_order.id=\''.$id.'\'');
			
			$nodeDetails = array('text'=>htmlentities($recordInfo[0]['menutitle']), 'cssClass'=>'confirm', 'link'=>'afasf');
			
			$node = new treenode ($nodeDetails);
			$node->text = htmlentities($recordInfo[0]['menutitle']);
			
			// Get immediate Children
			$childrenNodes = $this->getPages($chapter, $context, ' AND parentid=\''.$id.'\'');
			
			// Add Childen
			foreach ($childrenNodes as $childNode)
			{
				// Create Array with Child Node Details
				$childNodeDetails = array('text'=>htmlentities($childNode['menutitle']), 'link'=>$this->uri(array('action'=>'viewpage', 'id'=>$childNode['id'])));
				
				// Create Child Node
				$childNode =& new treenode ($childNodeDetails);
				
				// Add to Current Node
				$node->addItem($childNode);
				
			}
			
			// Add to Menu
			$treeMenu->addItem($node);
			
			// Create Menu Display
			$tree = &new htmllist($treeMenu);
			
			// Return Menu Display
			return $tree->getMenu();
			
			
		}
		
		return 'asfasf';
	}
	
	function getPages($chapter, $contextCode, $where='', $order='lft')
	{
	$sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_order.chapterid, tbl_contextcontent_order.parentid, tbl_contextcontent_pages.menutitle, lft, rght, tbl_contextcontent_pages.id as pageid, tbl_contextcontent_order.titleid, tbl_contextcontent_order.bookmark, tbl_contextcontent_order.isbookmarked
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE contextcode=\''.$contextCode.'\' AND tbl_contextcontent_order.chapterid=\''.$chapter.'\' '.$where.'
        ORDER BY '.$order;
		
		return $this->getArray($sql);
	}
    
    function movePageToChapter($pageId, $chapter, $context)
    {
        // Get Page and Run a few checks
        $page = $this->getPage($pageId, $context);
        
        // Check if page exists
        if ($page == FALSE) {
            return 'pagedoesnotexist';
        }
        
        // Check that it is not the same chapter
        if ($page['chapterid'] == $chapter) {
            return 'pagemovedtosamechapter';
        }
        
        $objContextChapter = $this->getObject('db_contextcontent_contextchapter');
        $isContextChapter = $objContextChapter->isContextChapter($context, $chapter);
        
        // Check that chapter exists in the same context
        if (!$isContextChapter) {
            return 'newchapternotinthiscontext';
        }
        
        // Now it is ok to move page
        $children = $this->getAll(' WHERE contextcode =\''.$context.'\' AND chapterid =\''.$page['chapterid'].'\' AND lft >= '.$page['lft'].' AND rght <= '.$page['rght'].'
        ORDER BY lft ');
        
        // Move Each Child One by One
        foreach ($children as $child)
        {
            $this->update('id', $child['id'], array('chapterid'=>$chapter));
        }
        
        // Rebuild Old Context
        $this->rebuildContext($context, $page['chapterid']);
        
        // Rebuild New Context
        $this->rebuildContext($context, $chapter);
        
        return 'pagemovedtonewchapter';
    }


}


?>