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
            	//$this->requiresLogin(FALSE);
            	if(!$this->objUser->isLoggedIn())
            	{
            		$captcha = $this->getParam('request_captcha');
            	}
            	$addinfo['useremail'] = $this->getParam('email');
            	$addinfo['postuserid'] = $this->getParam('userid');
            	$addinfo['postid'] = $this->getParam('postid');
            	$addinfo['table'] = $this->getParam('table');
            	$addinfo['mod'] = $this->getParam('mod');
            	$addinfo['aurl'] = $this->getParam('url');
            	$addinfo['ctype'] = $this->getParam('type');
            	$addinfo['comment'] = $this->getParam('comment');
            	$addinfo = $this->objComm->addToDb($addinfo);
            	//print_r($addinfo);
            	//check that the captcha is correct
            	if(!$this->objUser->isLoggedIn())
            	{
          			if (md5(strtoupper($captcha)) != $this->getParam('captcha') || empty($captcha))
          			{
          				//get a timeoutmessage and display it...
          				$tmsg = $this->getObject('timeoutmessage', 'htmlelements');
          				$tmsg->setMessage = $this->objLanguage->languageText("mod_blogcomments_badcaptcha", "blogcomments");
          				$msg = $tmsg->show();
          				$this->setVarByRef('msg', $msg);
          				$this->nextAction('viewsingle',array('postid' => $addinfo['postid'], 'userid' => $this->objUser->userId(), 'comment' => $addinfo['comment'], 'useremail' => $addinfo['useremail']), $addinfo['mod']);
          				exit;
          			}
          		}
            	//print_r($addinfo);die();
            	$this->objDbcomm->addComm2Db($addinfo);

            	$this->nextAction('viewsingle',array('postid' => $addinfo['postid'], 'userid' => $this->objUser->userId()), $addinfo['mod']);
            	
            case 'updatecomment':
            	$commid = $this->getParam('commid');
            	$edits = nl2br($this->getParam('newcomment'));
            	echo $this->objDbcomm->updateComment($commid, $edits);
            	break;
            	
            case 'deletecomment':
            	$commentid = $this->getParam('commentid');
            	$postid = $this->getParam('postid');
            	$module = 'blog';
            	$this->objDbcomm->deletecomment($commentid);
            	
            	$this->nextAction('viewsingle',array('postid' => $postid), $module);
            	
            default:
            	die("unknown action");
            	break;
        }
    }
    
    /**
     * Ovveride the login object in the parent class
     *
     * @param void
     * @return bool
     * @access public
     */
    public function requiresLogin()
    {
        return FALSE;
    }
}
?>