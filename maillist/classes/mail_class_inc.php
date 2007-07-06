<?php

/* ----------- logic class extends object for module maillist------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

//include the fun stuff
include('Net/POP3.php');
include('Mail/mimeDecode.php');

/**
 * Mail class to fetch html and plain text email from a pop3 server
 * @author Paul Scott
 * @copyright GNU/GPL UWC 2005
 * @package maillist
 */

class mail extends object
{
	/**
	 * @var string mailserv - the mail server hostname
	 */
	var $mailserv;

	/**
	 * @var string mailuser - the mail username
	 */
	var $mailuser;

	/**
	 * @var string mailpass - the mail server password
	 */
	var $mailpass;

	/**
	 * @var string basepath - the path for saving attachments to
	 */
	var $basepath;

	/**
	 * @var string spamword - spam filtering keyword
	 */
	var $spamword;

	/**
	 * Class constructor
	 * Standard init function
	 * @param void
	 * @return construction and class instantiation
	 */
	function init()
	{
		//The user object
		$this->objUser =  & $this->getObject("user", "security");
        //get the db table derived class for this module
        $this->objDb = &$this->getObject("dbmaillist",'maillist');

	}

	/**
	 * Setup method to properly instantiate the mail class by setting the properties
	 * Used in the log in and parsing of the POP3 mail
	 * @param mixed mailserv
	 * @param mixed mailpass
	 * @param mixed mailuser
	 * @param mixed basepath
	 * @return void property setup
	 */
	function setup($mailserv, $mailpass, $mailuser, $basepath)
	{
		$this->mailserv = $mailserv;
		$this->mailuser = $mailuser;
		$this->mailpass = $mailpass;
		$this->basepath = $basepath;
	}

	/**
	 * function to connect to the pop3 server Non SASL
	 * @param string $delete - default false
	 * @return array $msg - the mail message array
	 */
	function connectMail($delete = false)
	{
		// pop3 connection and log-in
		$pop3 =& new Net_POP3();
		$pop3->connect($this->mailserv, 110) or die("mailserver down");
		$pop3->login($this->mailuser, $this->mailpass) or die("bad password");;
		//successfully logged in, so lets go!

		$num_msgs = $pop3->numMsg();
		//no messages;
		if ($num_msgs == 0)
		{
			// pop3 disconnect
			$pop3->disconnect();
			$msg = 0;
			return $msg; // "\n ** No mail found **";
		}
		else
		{
			// message list
			$list = $pop3->getListing();
			//takes the array list and gets the messages
			//first check that we are getting an array!
			if(is_array($list))
			{
				for ($c = 0; $c < count($list); $c++)
				{
					$msg[$c] = $pop3->getMsg($list[$c]['msg_id']);
					if($delete == true)
					{
						$pop3->deleteMsg($list[$c]['msg_id']);
					}
				}
				// pop3 disconnect
				$pop3->disconnect();
			}
			return $msg;
		}
	}

	/**
	 * Function to decode the messages using Mail::MimeDecode
	 * @param array $messages
	 * @return array $insertbody - the mail messages
	 */
	function decodeMessages($messages)
	{
		//get the basepath
		$basepath = $this->basepath;
		// posts counter
		$post_cnt = 0;

		for($msg_cnt = 0; $msg_cnt < count($messages); $msg_cnt++)
		{
			$myDecoder[$msg_cnt] = new Mail_mimeDecode($messages[$msg_cnt]);
			//set up the decoding params
			$params['include_bodies'] = true;
			$params['decode_bodies']  = true;
			$params['decode_headers'] = true;

			$decoded_msg[$msg_cnt] =  $myDecoder[$msg_cnt]->decode($params);
			//print_r($decoded_msg);
			$posts[$post_cnt]['msg_id'] = $msg_cnt;

			//subject
			$posts[$post_cnt]['post_mail_subject'] = $decoded_msg[$msg_cnt]->headers['subject'];
			$subject = $posts[$post_cnt]['post_mail_subject'];

			//date
			$posts[$post_cnt]['post_mail_date'] = $decoded_msg[$msg_cnt]->headers['date'];
			$date = $posts[$post_cnt]['post_mail_date'];

			//From
			$posts[$post_cnt]['post_mail_from'] = stripslashes($decoded_msg[$msg_cnt]->headers['from']);
			$from = $posts[$post_cnt]['post_mail_from'];

			// user-agent || x-mailer
			if ($decoded_msg[$msg_cnt]->headers['user-agent'] == '')
			{
				$posts[$post_cnt]['post_mail_user_agent'] = $decoded_msg[$msg_cnt]->headers['x-mailer'];
			}
			else
			{
				$posts[$post_cnt]['post_mail_user_agent'] = $decoded_msg[$msg_cnt]->headers['user-agent'];
			}
			$useragent = $posts[$post_cnt]['post_mail_user_agent'];

			// mime type (ctype_primary/ctype_secondary)
			$posts[$post_cnt]['post_ctype'] = strtolower($decoded_msg[$msg_cnt]->ctype_primary . "/" .$decoded_msg[$msg_cnt]->ctype_secondary);
			$ctype = $posts[$post_cnt]['post_ctype'];

			//end message headers section

			//create a bunch of arrays to hold the images info
			$posts[$post_cnt]['images'] = array();
			$posts[$post_cnt]['images_mime'] = array();
			$posts[$post_cnt]['images_cid'] = array();
			$posts[$post_cnt]['images_tmp'] = array();

			//multipart
			//set up a filename
			$now = str_replace(' ', '', microtime()).rand();
			if(is_array($decoded_msg[$msg_cnt]->parts))
			{
				for($p = 0; $p < count($decoded_msg[$msg_cnt]->parts); $p++)
				{
					$text_plain = 0;
					$text_html = 0;
					$body = $decoded_msg[$msg_cnt]->parts[$p]->body;
					if(is_object($decoded_msg[$msg_cnt]->parts[$p]))
					{
						$original_filename = $decoded_msg[$msg_cnt]->parts[$p]->d_parameters[filename];
						$filename = NULL;
						if($decoded_msg[$msg_cnt]->parts[$p]->disposition == "attachment")
						{
							$mimetype = strtolower($decoded_msg[$msg_cnt]->parts[$p]->ctype_primary . "/" . $decoded_msg[$msg_cnt]->parts[$p]->ctype_secondary);
							$ext_type = $decoded_msg[$msg_cnt]->parts[$p]->ctype_secondary;

							$filename = $filename . $decoded_msg[$msg_cnt]->parts[$p]->d_parameters[filename];
							$filename = $filename."_".time();

							$fp = @fopen ($this->basepath . $filename, 'wb');
							@fwrite ($fp, $decoded_msg[$msg_cnt]->parts[$p]->body);
							@fclose($fp);
							//now we dump it into the storage db

							$textbodyplain = $decoded_msg[$msg_cnt]->parts[0]->body;
							if(!empty($textbodyplain))
							{
								$insertbody = array('body' => $textbodyplain,'subject' =>$subject, 'from' => $from, 'fileid' => $filename);
								$this->objDb->saveRecord('add', $this->objUser->userId(),$insertbody);

							}

						}
						if($decoded_msg[$msg_cnt]->parts[$p]->disposition == "inline")
						{
							//now we dump it into the storage db
							$fileid = null;
							//insert body text to mysql
							if(!empty($body))
							{
								$insertbody = array('body' => $body,'subject' => $subject, 'from' =>$from, 'fileid' => $filename);
								$this->objDb->saveRecord('add', $this->objUser->userId(),$insertbody);
							}
							$filename = $filename . $decoded_msg[$msg_cnt]->parts[$p]->d_parameters[filename];
							$filename = $filename."_".time();

							$fp = @fopen ($this->basepath . $filename, 'wb');
							@fwrite ($fp, $decoded_msg[$msg_cnt]->parts[$p]->body);
							@fclose($fp);

						}
					}

				}
			}//if is_array
			else
			{
				//plaintext - no multipart
				$body = $decoded_msg[$msg_cnt]->body;
				if(!empty($body))
				{
					$fileid = null;
					$insertbody = array('body' => $body,'subject' => $subject, 'from' =>$from, 'fileid' => $filename);
					$this->objDb->saveRecord('add', $this->objUser->userId(),$insertbody);
				}
			}
		}
		//return $insertbody;
	}//end function

}//end class
?>