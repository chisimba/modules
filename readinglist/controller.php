<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Controller class for readinglist module
* @author John Abakpa
* @copyright 2005 University of the Western Cape
*/

class readinglist extends controller{

var $objUser;
var $objLanguage;
var $objDbReadingList;
var $contextId;
var $contextTitle;

/**
* The Init function
*/
function init ()
{
	// Get the user object.
	$this->objUser =& $this->getObject('user', 'security');
	// Get the language object.
	$this->objLanguage =& $this->getObject('language','language');
	// Get the DB object.
	$this->objDbReadingList =& $this->getObject('dbreadinglist');
	//Get the activity logger class
	$this->objLog=$this->newObject('logactivity', 'logger');
	//Log this module call
	$this->objLog->log();
				
}

/**
* The dispatch fucntion
* @param string $action The action
* @return string The content template file
*/
function dispatch($action=null)
{
// Set the layout template.
        $this->setLayoutTemplate("layout_tpl.php");


	//$this->setVarByRef('objUser', $this->objUser);
	$this->setVarByRef('objLanguage', $this->objLanguage);
        // Get the context
        $objDbContext = &$this->getObject('dbcontext','context');
        $contextCode = $objDbContext->getContextCode();
        // If we are not in a context...

	if ($contextCode == null) {
	    $this->contextId = "root";
	    $this->setVarByRef('contextId', $this->contextId);
	    $this->contextTitle = "Lobby";
	    $this->setVarByRef('contextTitle', $this->contextTitle);
	 }
	// ... we are in a context
        else {
            $this->contextId = $contextCode;
            $this->setVarByRef('contextId', $this->contextId);
            $contextRecord = $objDbContext->getContextDetails($contextCode);
            $this->contextTitle = $contextRecord['title'];
            $this->setVarByRef('contextTitle', $this->contextTitle);
        }
	switch ($action) {
	case "add":
		return "add_tpl.php";
	case "addconfirm":
		$this->nextAction(
		$this->objDbReadingList->insertSingle(
			$this->contextId,
			$this->getParam('author', NULL),
			$this->getParam('title', NULL),
			$this->getParam('publisher', NULL),
			$this->getParam('publishingYear', NULL),
			$this->getParam('link', NULL),
			$this->getParam('publication', NULL)
		));
		break;
	case "edit":
		$id = $this->getParam('id', null);
		$this->setVarByRef('id',$id);
		$list = $this->objDbReadingList->listSingle($id);
		$author = $list[0]['author'];
		$title = $list[0]['title'];
		$publisher = $list[0]['publisher'];
		$publishingYear = $list[0]['publishingyear'];
		$link = $list[0]['link'];
		$publication = $list[0]['publication'];
		$this->setVarByRef('author',$author);
		$this->setVarByRef('title',$title);
		$this->setVarByRef('publisher',$publisher);
		$this->setVarByRef('publishingYear',$publishingYear);
		$this->setVarByRef('link',$link);
		$this->setVarByref('publication',$publication);
		return "edit_tpl.php";
	case "editconfirm":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbReadingList->updateSingle(
			$id,
			$this->getParam('author', NULL),
			$this->getParam('title', NULL),
			$this->getParam('publisher', NULL),
			$this->getParam('publishingYear', NULL),
			$this->getParam('link', NULL),
			$this->getParam('publication', NULL)
		));
		break;
	case "deleteconfirm":
		$this->nextAction(
		$id = $this->getParam('id', null),
		$this->objDbReadingList->deleteSingle(
			$id
		));
		break;
	default:
	}
	$list = $this->objDbReadingList->listAll($this->contextId);
	$this->setVarByRef('list', $list);
	return "view_tpl.php";
}
}
?>
