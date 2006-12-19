<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Data access (db model) Class for the blogcomments module
 *
 * This is a database model class for the blogcomments module. All database transaactions will go through
 * this class. This class is derived from the top level dbTable superclass in the framework core.
 *
 * @author Paul Scott
 * @filesource
 * @copyright AVOIR
 * @package blogcomments
 * @category chisimba
 * @access public
 */

class dbblogcomments extends dbTable
{

	/**
	 * Standard init function - Class Constructor
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function init()
	{
		parent::init('tbl_blogcomments');
	}

	/**
	 * Method to add a comment to the comments table
	 * The commentarray is an associative array of field keys and values that are required for the insert
	 *
	 * @param array $commentarray
	 * @return bool
	 */
	public function addComm2Db($commentarray, $email = FALSE)
	{
		$userid = $commentarray['userid'];
		$commentauthor = $commentarray['commentauthor'];
		$authemail = $commentarray['useremail'];
		$authurl = htmlentities($commentarray['aurl']);
		$authip = $commentarray['ip'];
		$date = time();
		$cont = htmlentities($commentarray['comment']);
		$agent = $commentarray['useragent'];
		$type = $commentarray['ctype'];
		$pid = $commentarray['postid'];
		$pmod = $commentarray['mod'];
		$ptable = $commentarray['table'];


		$this->insert(array('userid' => $userid, 'comment_author' => $commentauthor, 'comment_author_email' => $authemail,
		  				    'comment_author_url' => $authurl, 'comment_author_ip' => $authip, 'comment_date' => $date,
							'comment_content' => $cont, 'comment_karma' => 0, 'comment_approved' => 1, 'comment_agent' => $agent,
							'comment_type' => $type, 'comment_parentid' => $pid, 'comment_parentmod' => $pmod, 'comment_parenttbl' => $ptable));
		//email the owner
		if($email == TRUE)
		{
			$this->objemail = $this->getObject('email', 'mail');
			$this->objemail->setBaseMailerProperty('from', "Blog Comments");
			$this->objemail->setBaseMailerProperty('fromName', "kng");
			$this->objemail->setBaseMailerProperty('subject', "Blog Comments");
			$this->objemail->setBaseMailerProperty('body', $cont);
			$this->objemail->setBaseMailerProperty('mailer', "smtp");
			$this->objemail->setBaseMailerProperty('to', "pscott@uwc.ac.za");
			$this->objemail->send();
		}

	}

	/**
	 * Method to return the comments for the post
	 * You need to supply a post id to the method. This will get all the extra data
	 * attached to that post from the blogcomments table.
	 *
	 * @param string $pid
	 * @return array
	 */
	public function grabComments($pid)
	{
		$filter = "WHERE comment_parentid = '$pid' ORDER BY comment_date DESC";
		return $this->getAll($filter);
	}

	/**
	 * Method to return the count of comments for a particular post id
	 *
	 * @param string post ID $pid
	 * @return array
	 */
	public function commentCount($pid)
	{
		$filter = "WHERE comment_parentid = '$pid'";
		return $this->getRecordCount($filter);
	}

}
?>