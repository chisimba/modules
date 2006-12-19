<?php
/* ----------- controller class extends controller for tbl_quotes------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}


/**
*
* Controller class for blogcomments module. The blogcomment module allows
* the associating of blog style comments with any table in the database. It
* is used, for example, with the blog module.
*
* @author Paul Scott
* @package blogcomments
* @version $Id$
* @copyright 2006 GNU GPL AVOIR
* @access public
* @filesource
*/
class blogcomments extends controller
{
    /**
    *
    * @var string $action The action parameter from the querystring
    *
    */
    public $action;

    /**
    *
    * @var object $objUser String to hold instance of the user object
    *
    */
    public $objUser;

    /**
    *
    * @var $objLanguage $objUser String to hold instance of the language object
    *
    */
    public $objLanguage;

    public $objComm;

    public $objDbcomm;

    /**
    *
    * Standard constructor method to retrieve the action from the
    * querystring, and instantiate the user and lanaguage objects
    *
    */
  public  function init()
    {
    	try {
    		$this->objDbcomm = $this->getObject('dbblogcomments');
    		$this->objComm = $this->getObject('commentapi');
        	//Retrieve the action parameter from the querystring
        	$this->action = $this->getParam('action', Null);
        	//Create an instance of the User object
        	$this->objUser =  & $this->getObject("user", "security");
        	//Create an instance of the language object
        	$this->objLanguage = &$this->getObject("language", "language");
    	}
    	catch (customException $e)
    	{
    		customException::cleanUp();
    		exit;
    	}
    }

    /**
    * Standard dispatch method to handle adding and saving
    * of comments
    *
    * @access public
    * @param void
    * @return void
    */
 	public  function dispatch()
    {
        $action = $this->getParam('action');
    	switch ($action) {
            case null:
            	$this->setVarByRef('errmsg', $this->objLanguage->languageText("mod_blogcomments_phrase_noaction", "blogcomments"));
            	return 'noaction_tpl.php';
            	break;
            case 'add':
            	//check if the user is logged in
				if($this->objUser->isLoggedIn() == TRUE)
				{
					$this->setVar('pageSuppressToolbar', TRUE);
                	$this->setVar('pageSuppressBanner', TRUE);
                	$this->setVar('pageSuppressIM',TRUE);
                	//Suppress footer in the page (keep it simple)
                	$this->setVar('suppressFooter', TRUE);
                	return "input_tpl.php";
                }
                else {
                	return 'notloggedin_tpl.php';
                }
                break;

            case 'addtodb':
            	$addinfo = $this->objComm->addToDb(NULL);
            	$addinfo['postid'] = $this->getParam('postid');
            	$addinfo['table'] = $this->getParam('table');
            	$addinfo['mod'] = $this->getParam('mod');
            	$addinfo['aurl'] = $this->getParam('url');
            	$addinfo['ctype'] = $this->getParam('type');
            	$addinfo['comment'] = $this->getParam('comment');
            	$this->objDbcomm->addComm2Db($addinfo);

            	$this->nextAction('viewsingle',array('postid' => $addinfo['postid'], 'userid' => $this->objUser->userId()), $addinfo['mod']);
        }
    }
}
?>