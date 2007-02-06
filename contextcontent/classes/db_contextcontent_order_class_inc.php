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
        
        $this->loadClass('treemenu','tree');
		$this->loadClass('treenode','tree');
		$this->loadClass('htmllist','tree');
		$this->loadClass('htmldropdown','tree');
        
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
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @return array Details of the Page, FALSE if does not exist
    * @access public
    */
    public function getPage($pageId, $contextCode)
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_order.parentid, tbl_contextcontent_pages.menutitle, pagecontent, headerscripts, lft, rght, tbl_contextcontent_pages.id as pageid, tbl_contextcontent_order.titleid
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
    
    public function getContextPages($context)
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_order.parentid, tbl_contextcontent_pages.menutitle FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id) 
        WHERE tbl_contextcontent_order.contextcode= \''.$context.'\'
        ORDER BY lft';
        
        return $this->getArray($sql);
    }
    
    /**
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @return array Details of the Page, FALSE if does not exist
    * @access public
    */
    public function getTree($context, $type='dropdown', $defaultSelected='', $module='contextcontent')
    {
        $results = $this->getContextPages($context);
        
        switch ($type)
        {
            case 'dropdown': 
                return $this->generateDropdownTree($results, $defaultSelected);
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
            
            if ($treeItem['parentid'] == 'root') {
                $treeMenu->addItem($node);
            } else {
                if (array_key_exists($treeItem['parentid'], $nodeArray)) {
                    $nodeArray[$treeItem['parentid']]->addItem($node);
                }
            }
        }
        
        $tree = &new htmllist($treeMenu, array('inputName'=>'parentnode', 'id'=>'input_parentnode'));
        
        return $tree->getMenu();
    }
    
    /**
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @return array Details of the Page, FALSE if does not exist
    * @access private
    */
    private function generateDropdownTree($results, $defaultSelected='')
    {
        $treeMenu = new treemenu();
        
        $nodeArray = array();
        
        $rootnode =& new treenode (array('text'=>'[- Root -]'));
        
        foreach ($results as $treeItem)
        {
            $node =& new treenode (array('text'=>htmlentities($treeItem['menutitle']), 'link'=>$treeItem['id']));
            $nodeArray[$treeItem['id']] =& $node;
            
            if ($treeItem['parentid'] == 'root') {
                $rootnode->addItem($node);
            } else {
                if (array_key_exists($treeItem['parentid'], $nodeArray)) {
                    $nodeArray[$treeItem['parentid']]->addItem($node);
                }
            }
        }
        
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
    public function addPageToContext($titleId, $parentId, $context)
    {
        $lastRight = $this->getLastRight($context, $parentId);
        $leftPointer = $lastRight;
        
        if ($parentId == '') {
            $leftPointer++;
        }
        $rightPointer = $leftPointer+1;
        
        if ($parentId == '') {
            $pageOrder = 1;
        } else {
            $this->updateLeftRightPointers($lastRight-1);
        }
        
        $pageOrder = $this->getLastOrder($context, $parentId)+1;
        
        return $this->insertTitle($context, $titleId, $parentId, $leftPointer, $rightPointer, $pageOrder);
    }
    
    
    
    private function insertTitle($context, $titleId, $parentId, $left, $right, $pageOrder=1, $visibility='Y')
    {
        $lastId = $this->insert(array(
                'contextcode' => $context,
                'titleid' => $titleId,
                'parentid' => $parentId,
                'lft' => $left,
                'rght' => $right,
                'pageorder' => $pageOrder,
                'visibility' => $visibility,
                'creatorid' => $this->objUser->userId(),
                'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
            ));
        
        // Extra Step to Prevent Null Values
        if ($parentId == '') {
            $this->update('id', $lastId, array('parentid'=>'root'));
        }
        
        return $lastId;
    }
    
    private function getLastRight($context, $parent='')
    {
        if ($parent == '') {
            $result = $this->getAll('WHERE contextcode =\''.$context.'\' ORDER BY rght DESC LIMIT 1');
        } else {
            $result = $this->getAll('WHERE id =\''.$parent.'\' AND contextcode =\''.$context.'\' ORDER BY rght DESC LIMIT 1');
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
    
    private function updateLeftRightPointers($base, $amount=2)
    {
        $sqlLeft = 'UPDATE tbl_contextcontent_order SET rght=rght+'.$amount.' WHERE rght > '.$base;
        $sqlRight = 'UPDATE tbl_contextcontent_order SET lft=lft+'.$amount.' WHERE lft > '.$base;
        
        $this->query($sqlLeft);
        $this->query($sqlRight);
    }
    
    
    
    public function getPreviousPage($context, $leftValue='', $module='contextcontent')
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_pages.menutitle
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE contextcode =\''.$context.'\' AND lft < '.$leftValue.'
        ORDER BY lft DESC LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return '';
        } else {
            $page = $results[0];
            $link = new link ($this->uri(array('action'=>'viewpage', 'id'=>$page['id']), $module));
            $link->link = 'Previous Page: '.htmlentities($page['menutitle']);
            return '&#171; '.$link->show();
        }
    }
    
    public function getNextPage($context, $leftValue='', $module='contextcontent')
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_pages.menutitle
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE contextcode =\''.$context.'\' AND lft > '.$leftValue.'
        ORDER BY lft LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return '';
        } else {
            $page = $results[0];
            $link = new link ($this->uri(array('action'=>'viewpage', 'id'=>$page['id']), $module));
            $link->link = 'Next Page: '.htmlentities($page['menutitle']);
            return $link->show().' &#187;';
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
    public function getBreadcrumbs($context, $leftValue, $rightValue)
    {
        $sql = 'SELECT tbl_contextcontent_order.id, tbl_contextcontent_pages.menutitle
        FROM tbl_contextcontent_order 
        INNER JOIN tbl_contextcontent_titles ON (tbl_contextcontent_order.titleid = tbl_contextcontent_titles.id) 
        INNER JOIN tbl_contextcontent_pages ON (tbl_contextcontent_pages.titleid = tbl_contextcontent_titles.id AND original=\'Y\') 
        WHERE contextcode =\''.$context.'\' AND lft <= '.$leftValue.' AND rght >= '.$rightValue.'
        ORDER BY lft ';
        
        //echo $sql;
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return '';
        } else {
            $returnString = '';
            $separator = '';
            $counter = 1;
            
            foreach ($results as $page)
            {
                if ($counter == count($results)) {
                    $returnString .= $separator.htmlentities($page['menutitle']);
                } else {
                    $link = new link ($this->uri(array('action'=>'viewpage', 'id'=>$page['id'])));
                    $link->link = htmlentities($page['menutitle']);
                    $returnString .= $separator.$link->show();
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
    public function rebuildContext($context)
    {
        $this->orderArray = array();
        $this->_rebuild_tree($context, 'root', 0, 1);
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
    private function _rebuild_tree($context, $parent, $left, $level) 
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
        $result = $this->getAll(' WHERE  contextcode =\''.$context.'\' AND parentid=\''.$parent.'\' ORDER BY pageorder');
        
        foreach ($result as $row)
        {
            $right = $this->_rebuild_tree($context, $row['id'], $right, $level+1);
            
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
        
        $nextPageSQL = ' WHERE parentid=\''.$page['parentid'].'\' AND contextcode   	 =\''.$page['contextcode'].'\' AND pageorder < '.$page['pageorder'].' ORDER BY pageorder DESC';
        $nextPage = $this->getAll($nextPageSQL);
        
        if (count($nextPage) == 0) {
            return FALSE;
        } else {
            $nextPage = $nextPage[0];
            
            $this->update('id', $page['id'], array('pageorder'=>$nextPage['pageorder']));
            $this->update('id', $nextPage['id'], array('pageorder'=>$page['pageorder']));
            
            $this->rebuildContext($page['contextcode']);
            
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
            
            $this->rebuildContext($page['contextcode']);
            
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
    function reOrderItems($context, $string, $splitter='&', $obfuscator='_')
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
            $this->rebuildContext($context);
        }
        
        return;
    }


}


?>