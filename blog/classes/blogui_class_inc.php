<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class to handle blog UI elements
 * 
 * This object can be used elsewhere in the system to render certain aspects of the interface
 *
 * @author Paul Scott
 * @copyright GNU/GPL, AVOIR
 * @package blog
 * @access public
 */

class blogui extends object 
{
	public $objblogOps;
	public $leftCol;
	public $rightCol;
	public $middleCol;
	public $tplHeader;
	public $cssLayout;
	public $leftMenu;
	public $objUser;
	
	public function init()
	{
		// load up the blogops class
		$this->objblogOps = $this->getObject('blogops');
		// user class
		$this->objUser = $this->getObject('user', 'security');
		// load up the htmlelements
		$this->loadClass('href', 'htmlelements');
		// get the css layout object
		$this->cssLayout = $this->newObject('csslayout', 'htmlelements');
		// get the sidebar object
		$this->leftMenu = $this->newObject('usermenu', 'toolbar');
		// initialise the columns
		// left column
		$this->leftCol = NULL;
		// right column
		$this->rightCol = NULL;
		// middle column
		$this->middleCol = NULL;
	}
	
	public function myblog()
	{
		
	}
	
	public function threeCols()
	{
		// Set columns to 3
		$this->cssLayout->setNumColumns(3);
		$this->tplHeader = $this->cssLayout;
		return $this->tplHeader;
	}
	
	public function leftBlocks($userid)
	{
		$leftMenu = $this->newObject('usermenu', 'toolbar');
		// init the variable
		$leftCol = NULL;
		// get all the left column blocks
		if($this->objUser->isLoggedIn())
		{
			$guestid = $this->objUser->userId();
			if($guestid == $userid)
			{
				$leftCol .= $leftMenu->show();
				$leftCol .= $this->objblogOps->showProfile($userid);
			}
			else {
				$leftCol .= $this->objblogOps->showFullProfile($userid);
			}
				$leftCol .= $this->objblogOps->showAdminSection(TRUE);
		}
		else {
			$leftCol = $this->objblogOps->loginBox(TRUE);
			$leftCol .= $this->objblogOps->showFullProfile($userid);
		}
		//show the feeds section
		$leftCol .= $this->objblogOps->showFeeds($userid, TRUE);
		$leftCol .= $this->objblogOps->showBlinks($userid, TRUE);
		$leftCol .= $this->objblogOps->showBroll($userid, TRUE);
		$leftCol .= $this->objblogOps->showPages($userid, TRUE);
		
		return $leftCol;
	}
	
	public function rightBlocks($userid, $cats = NULL)
	{
		$rightSideColumn = NULL;
		$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);
		$rightSideColumn .= $this->objblogOps->archiveBox($userid, TRUE);
		$rightSideColumn .= $this->objblogOps->blogTagCloud($userid);
		$rightSideColumn .= $this->objblogOps->showCatsMenu($cats, TRUE, $userid);
		if($this->objUser->isLoggedIn())
		{
			$rightSideColumn .= $this->objblogOps->quickCats(TRUE);
			//$rightSideColumn .= $this->objblogOps->quickPost($userid, TRUE);
		}
		
		return $rightSideColumn;
	}
	
}
?>