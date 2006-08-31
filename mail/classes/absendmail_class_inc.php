<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* Abstract class defining methods and properties that must be present
* in mail sending classes that implement it as well as setting up the
* get and setValue methods
*
* @author Derek Keats
* @category Chisimba
* @package mail
* @copyright AVOIR
* @licence GNU/GPL
*
*/
abstract class absendmail extends engine
{

    /**
    *
    * @var string $to String or array to hold the value of the mail to address(s)
    *
    */
	 protected $to;

    /**
    *
    * @var string $cc String or array to hold the value of the mail cc address(s)
    *
    */
	 protected $cc;

    /**
    *
    * @var string $bcc String or array to hold the value of the mail bcc address(s)
    *
    */
	 protected $bcc;

    /**
    *
    * @var string $from String to hold the value of the mail from address
    *
    */
	 protected $from;

    /**
    *
    * @var string $fromName String to hold the value of the mail from Name of person
    *
    */
	 protected $fromName;

    /**
    *
    * @var string $priority String to hold the value of the mail priority
    *
    */
	 protected $priority;

    /**
    *
    * @var string $subject String to hold the value of the mail subject
    *
    */
	 protected $subject;

    /**
    *
    * @var string $body String to hold the value of the mail body (in HTML)
    *
    */
	 protected $body;

    /**
    *
    * @var string $wordWrap String to hold the value of the word wrap length
    *
    */
	 protected $wordWrap;

    /**
    *
    * @var string $altBody String to hold the value of the mail body (in plain text)
    *
    */
	 protected $altBody;

    /**
    *
    * @var string $mailer String to hold the value of the mailer (smtp, sendmail, PHP mail)
    *
    */
	 protected $mailer;


    /**
    *
    * Method to set the values of protected/private properties. Note that it
    * prevents the sloppy approach of adding poperties that are not defined.
    *
    * @param string $itemName The name of the property whose value is being set.
    * @param string $itemValue The value of the property being set
    *
    */
    public function setValue($itemName, $itemValue)
    {
		  if (property_exists($this,$itemName)) {
		      $this->$itemName = $itemValue;
		      return TRUE;
		  } else {
		      return FALSE;
		  }
    }

    /**
    *
    * Method to set the values of protected/private properties
    *
    * @param string $itemName The name of the property whose value is being retrieved.
    *
    */
    public function getValue($itemName)
    {
    	  if (isset($this->$itemName)) {
            return $this->$itemName;
        } else {
            return NULL;
        }
    }


	/**
	*
	* Check if given email is valid
	*
	* @param string $email  email to be checked
	*
	*/
	public static function isValid($email)
	{
		return eregi("^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}\$", $email) != 0;
	}
}
?>