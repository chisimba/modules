<?php
/* ----------- blogcomments API class ------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
 * blogcomments public API class
 *
 * @author Paul Scott
 * @copyright AVOIR GNU/GPL
 * @access public
 * @filesource
 * @package blogcomments
 * @category chisimba
 */

class commentapi extends object
{
	/**
	 * The user object inherited from the security class
	 *
	 * @var object
	 */
	protected $objUser;

	/**
	 * The language Object inherited from the language object
	 *
	 * @var object
	 */
	protected $objLanguage;

	/**
	 * Standard init function to __construct the class
	 *
	 * @param void
	 * @return void
	 * @access public
	 */
	public function init()
	{
		try {
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objUser =  $this->getObject("user", "security");
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}

	}

	/**
	 * Method to return a nicely formatted form to add a comment
	 *
	 * @param postid $postid
	 * @param module $module
	 * @param table $table
	 * @param whether you want the htmleditor or not $editor
	 * @param Should it be displayed in a featurebox? $featurebox
	 * @param Do we want to show the types dropdown? $showtypes
	 * @return string form
	 */
	public function commentAddForm($postid, $module, $table, $postuserid = NULL, $editor = TRUE, $featurebox = TRUE, $showtypes = TRUE)
	{
		try {
			$this->loadClass('form', 'htmlelements');
			$this->loadClass('textinput', 'htmlelements');
			$this->loadClass('textarea', 'htmlelements');
			$this->loadClass('button', 'htmlelements');
			//$this->loadClass('htmlarea', 'htmlelements');
			$this->loadClass('dropdown', 'htmlelements');
			$this->loadClass('label', 'htmlelements');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}

		$cform = new form('commentadd', $this->uri(array('module' => 'blogcomments', 'action' => 'addtodb', 'table' => $table, 'mod' => $module, 'postid' => $postid, 'userid' => $postuserid)));
		$cfieldset = $this->getObject('fieldset', 'htmlelements');
		//$cfieldset->setLegend($this->objLanguage->languageText('mod_blogcomments_addcomment', 'blogcomments'));
		$ctbl = $this->newObject('htmltable', 'htmlelements');
		$ctbl->cellpadding = 5;

		//start the inputs
		//textinput for author url
		$url = new textinput('url');
		$urllabel = new label($this->objLanguage->languageText("mod_blogcomments_url", "blogcomments") . ':', 'comm_input_url');
		$ctbl->startRow();
		$ctbl->addCell($urllabel->show());
		$ctbl->endRow();
		$ctbl->startRow();
		$ctbl->addCell($url->show());
		$ctbl->endRow();

		//textinput for author email
		$email = new textinput('email');
		if($this->objUser->isLoggedIn())
		{
			$email->setValue($this->objUser->email());
		}
		$emaillabel = new label($this->objLanguage->languageText("mod_blogcomments_email", "blogcomments") . ':', 'input_email');
		$ctbl->startRow();
		$ctbl->addCell($emaillabel->show());
		$ctbl->endRow();
		$ctbl->startRow();
		$ctbl->addCell($email->show());
		$ctbl->endRow();

		//textarea for the comment
		$commlabel = new label($this->objLanguage->languageText('mod_blogcomments_Comment', 'blogcomments') .':', 'input_comminput');
		$ctbl->startRow();
		$ctbl->addCell($commlabel->show());
		$ctbl->endRow();
		$ctbl->startRow();
		if($editor == TRUE)
		{
			//echo "start";
			$comm = $this->getObject('htmlarea','htmlelements');
			$comm->setName('comment');
			$comm->height = 400;
			$comm->width = 420;
			$comm->setBasicToolBar();
			$ctbl->addCell($comm->showFCKEditor());
		}
		else {
			$comm = new textarea;
			$comm->setName('comment');
			$ctbl->addCell($comm->show());
		}
		$ctbl->endRow();
		//comment type dropdown
		if($showtypes == TRUE)
		{
			$objCat = $this->getObject('dbcommenttype', 'commenttypeadmin');
        	$tar = $objCat->getAll();
			$ddlabel = new label($this->objLanguage->languageText('mod_blogcomments_commenttype', 'blogcomments') .':', 'input_comtypeinput');
        	$ctype = $this->newObject("dropdown", "htmlelements");
        	$ctype->name = 'type';
        	$ctype->SetId('input_type');
        	$ctype->addOption("", $this->objLanguage->languageText("mod_blogcomments_selecttype",'blogcomments'));
        	$ctype->addFromDB($tar, 'title', 'type');
			$ctbl->startRow();
			$ctbl->addCell($ddlabel->show());
			$ctbl->endRow();
			$ctbl->startRow();
			$ctbl->addCell($ctype->show());
			$ctbl->endRow();
		}

		//$cform->addRule('comment', $this->objLanguage->languageText("mod_blogcomments_commentval",'blogcomments'), 'required');
		$cform->addRule('email', $this->objLanguage->languageText("mod_blogcomments_emailval",'blogcomments'), 'required');

 		//end off the form and add the buttons
		$this->objCButton = &new button($this->objLanguage->languageText('word_save', 'system'));
		$this->objCButton->setValue($this->objLanguage->languageText('word_save', 'system'));
		$this->objCButton->setToSubmit();

		$cfieldset->addContent($ctbl->show());
		$cform->addToForm($cfieldset->show());
		$cform->addToForm($this->objCButton->show());

		if($featurebox == TRUE)
		{
			$objFeaturebox = $this->getObject('featurebox', 'navigation');
			return $objFeaturebox->showContent($this->objLanguage->languageText("mod_blogcomments_formhead", "blogcomments"), $cform->show());
		}
		else {
			return $cform->show();
		}
	}

	/**
	 * Method to show the comments in the comments table to the user on a singleview post display
	 *
	 * @param string $pid
	 * @return string
	 */
	public function showComments($pid)
	{
		$washer = $this->getObject('washout', 'utilities');
		$this->objDbComm = $this->getObject('dbblogcomments');
		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$comms = $this->objDbComm->grabComments($pid);
		//loop through the trackbacks and build a featurebox to show em
		if(empty($comms))
		{
			//shouldn't happen except on permalinks....?
			return $objFeatureBox->showContent($this->objLanguage->languageText("mod_blogcomments_comment4post", "blogcomments"), "<em>".$this->objLanguage->languageText("mod_blogcomments_nocomments", "blogcomments")."</em>");
		}

		$commtext = NULL;
		foreach($comms as $comm)
		{
			//build up the display
			$ctable = $this->newObject('htmltable', 'htmlelements');
			$ctable->cellpadding = 2;
			//$ctable->width = '80%';
			//set up the header row
			$ctable->startHeaderRow();
			$ctable->addHeaderCell('');
			$ctable->addHeaderCell('');
			$ctable->endHeaderRow();

			//build in author with url if available, with [email] as fbox head
			//then content as the content
			//where did it come from?
			$auth = $comm['comment_author'];
			$authurl = $comm['comment_author_url'];
			$authemail = $comm['comment_author_email'];
			//do a check to see if the comment author is the viewer so that they can edit the comment inline
			//get the userid
			$viewerid = $this->objUser->userId();
			$vemail = $this->objUser->email($viewerid);
			if($vemail == $comm['comment_author_email'])
			{
				$scripts = '<script src="core_modules/htmlelements/resources/script.aculos.us/lib/prototype.js" type="text/javascript"></script>
                      <script src="core_modules/htmlelements/resources/script.aculos.us/src/scriptaculous.js" type="text/javascript"></script>
                      <script src="core_modules/htmlelements/resources/script.aculos.us/src/unittest.js" type="text/javascript"></script>';
        		$this->appendArrayVar('headerParams',$scripts);
				//display the inline editor
				$updateuri = 'index.php'; //$this->uri(array('module' =>'blogcomments','action' => 'updatecomment'));
				$commid = $comm['id'];
				$script = '<p id="editme2">'.stripslashes($comm['comment_content']).'</p>';
				$script .= '<script type="text/javascript">';
				$script .= "new Ajax.InPlaceEditor('editme2', '$updateuri', {rows:15,cols:40, callback: function(form, value) { return 'module=blogcomments&action=updatecomment&commid=' + escape('$commid') + '&newcomment=' +escape(value) }});";
				$script .= "</script>";
				
				//echo $updateuri; die();
				$this->objIcon = $this->getObject('geticon', 'htmlelements');
                $edIcon = $this->objIcon->getEditIcon($this->uri(array(
                    'action' => 'updatecomment',
                    'id' => $comm['id'],
                    'module' => 'blogcomments'
                )));
                //$editic = $edIcon->show();
                $delIcon = $this->objIcon->getDeleteIconWithConfirm($comm['id'], array(
                    'module' => 'blogcomments',
                    'action' => 'deletecomment',
                    'id' => $comm['id']
                ) , 'blogcomments');
                //$delic = $delIcon->show();
                
				$fboxcontent = $script; //stripslashes($comm['comment_content']); // . "<br /><br />" . $delIcon;
			}
			else {
				$fboxcontent = stripslashes($comm['comment_content']);	
			}
			//$link = new href(urlencode($authurl), $auth, NULL);
			//$link->show();

			$authemail = "[".$authemail."]";
			$authhead = $auth; // . " " . $authemail; // . " (".htmlentities($authurl).")";
			$fboxhead = $authhead; // . " " . $authemail;

			

			$commtext .= $objFeatureBox->showContent($fboxhead, $washer->parseText($fboxcontent));
		}
		return $commtext;
	}


	/**
	 * Adds a comment from user input to the database
	 *
	 * @param comment info array $cominfo
	 * @return bool
	 */
	public function addToDb($cominfo)
	{
		if(!isset($cominfo['useragent']))
		{
			$cominfo['useragent'] = $_SERVER['HTTP_USER_AGENT'];
		}
		if(!isset($cominfo['useremail']))
		{
			$cominfo['useremail'] = $this->objUser->email();
		}
		if(!isset($cominfo['userid']))
		{
			$cominfo['userid'] = $this->objUser->userId();
		}
		if(!isset($cominfo['commentauthor']))
		{
			$cominfo['commentauthor'] = $this->objUser->fullname($cominfo['userid']);
		}
		if(!isset($cominfo['ip']))
		{
			$cominfo['ip'] = $_SERVER['REMOTE_ADDR'];
		}
		if(!isset($cominfo['date']))
		{
			$cominfo['date'] = time();
		}

		//print_r($cominfo);
		return $cominfo;
	}

	/**
	 * Method to return the comment count (record count) for a post
	 *
	 * @param item ID $pid
	 * @return integer
	 */
	public function getCount($pid)
	{
		$this->objDbComm = $this->getObject('dbblogcomments');
		return $this->objDbComm->commentCount($pid);
	}

}//end class
?>