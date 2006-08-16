<?php

class imap //extends object
{
	public $server;
	public $user;
	public $pass;
	public $protocol;
	public $mailbox;
	public $port;
	public $overview;
	public $alerts;
	public $imapdsn = array(
	'imapserver'  => false,
	'imapuser' => false,
	'imappass' => false,
	'imapprotocol' => false,
	'imapport'     => false,
	'imapmailbox' => false,
	);

	private $conn;
	private $headers;
	private $numEmails;
	private $mailHeader;
	private $currdsn;

	public function init()
	{

	}

	public function factory($dsn)
	{
		$this->setconn($dsn);
		$this->connect();
		//$this->getHeaders();
		if($this->alerts)
		{
			return $this->alerts;
		}
	}

	private function setconn($dsn)
	{
		$this->currdsn = $dsn;
		$conarr = $this->parseDSN($dsn);
		//print_r($conarr);
		$this->server = $conarr['imapserver'];
		$this->user = $conarr['imapuser'];
		$this->pass = $conarr['imappass'];
		$this->protocol = $conarr['imapprotocol'];
		$this->mailbox = $conarr['imapmailbox'];
		$this->port = $conarr['imapport'];
	}

	private function connect()
	{
		$this->conn = @imap_open("{".$this->server. ":" . $this->port . "/" . $this->protocol . "}" . $this->mailbox, $this->user, $this->pass);
		if(!$this->conn)
		{
			throw new /*custom*/Exception("Could not connect to " . $this->protocol . " Server at " . $this->server . " on port " . $this->port . " using mailbox " . $this->mailbox);
		}
		else {
			//get any alerts (like mailbox full or something)
			$this->alerts = imap_alerts();
			//the alerts will return false on no alerts
			if ($this->alerts == FALSE)
			{
				return TRUE;
			}
			else {
				return $this->alerts;
			}
		}
	}

	public function setAddress($user, $domain, $name)
	{
		return imap_rfc822_write_address($user, $domain, $name);
	}

	public function checkMailboxStatus()
	{
		$check = imap_mailboxmsginfo($this->conn);
		return $check;
	}

	public function getACL()
	{
		return @imap_getacl($this->conn, $this->mailbox);
	}

	public function pingServer()
	{
		if(!(imap_ping($this->conn)))
		{
			$this->connect();
		}
		else {
			return TRUE;
		}
	}

	public function listMailBoxes()
	{
		$list = imap_getmailboxes($this->conn, "{$this->server}", "*");
		if (is_array($list)) {
   			return $list;
			//foreach ($list as $key => $val) {
       		//echo "($key) ";
       		//echo imap_utf7_decode($val->name) . ",";
       		//echo "'" . $val->delimiter . "',";
       		//echo $val->attributes . "<br />\n";
   		//}
		} else {
   			return FALSE;
		}
	}

	public function getQuotas()
	{
		$quota_values = @imap_get_quotaroot($this->conn, $this->mailbox);
		if (is_array($quota_values)) {
   			return $quota_values;
		}
		//	$storage = $quota_values['STORAGE'];
   		//	echo "STORAGE usage level is: " .  $storage['usage'];
   		//	echo "STORAGE limit level is: " .  $storage['limit'];

   		//	$message = $quota_values['MESSAGE'];
   		//	echo "MESSAGE usage level is: " .  $message['usage'];
   		//	echo "MESSAGE limit is: " .  $message['limit'];

	}

	public function getHeaders()
	{
		$this->headers = imap_headers($this->conn);
		return $this->headers;
	}

	public function getHeaderInfo($messageNum)
	{
		$this->mailHeader = @imap_headerinfo($this->conn, $messageNum);
		$headers = $this->mailHeader;
		return $headers;
		/*
		$from = $this->mailHeader->fromaddress;
		$subject = strip_tags($this->mailHeader->subject);
		$date = @$this->mailHeader->date;
		*/
	}

	public function numMails()
	{
		return sizeof($this->headers);
	}

	public function checkMbox()
	{
		$this->overview = imap_check($this->conn);
		$nummsgs = $this->overview->Nmsgs;
		$overview = imap_fetch_overview($this->conn,"1:$nummsgs",0);
		return $overview;
	}

	public function getMessage($messageNum)
	{
		//fetch the structure
		$struct = imap_fetchstructure($this->conn, $messageNum);
		if(isset($struct->parts))
		{
			$parts = $struct->parts;
		}
		else {
			$parts = NULL;
		}
		$i = 0;

		if (!$parts) {
			// Simple message, only 1 piece
			$attachment = array();
			// No attachments
			$content = @imap_body($this->conn, $messageNum);
		} else {
			// Complicated message, multiple parts
			$endwhile = false;
			// Stack while parsing message
			$stack = array();
			// Content of message
			$content = "";
			// Attachments
			$attachment = array();
			while (!$endwhile) {
				if (!isset($parts[$i])) {
					if (count($stack) > 0) {
						$parts = $stack[count($stack)-1]["p"];
						$i    = $stack[count($stack)-1]["i"] + 1;
						array_pop($stack);
					} else {
						$endwhile = true;
					}
				}

				if (!$endwhile) {
					// Create message part first (example '1.2.3')
					$partstring = "";
					foreach ($stack as $s) {
						$partstring .= ($s["i"]+1) . ".";
					}
					$partstring .= ($i+1);

					if(!isset($parts[$i]->disposition))
					{
						$parts[$i]->disposition = NULL;
					}

					// Attachment
					if (strtoupper($parts[$i]->disposition) == "ATTACHMENT") {
						$attachment[] = array("filename" => $parts[$i]->parameters[0]->value,
						"filedata" => imap_fetchbody($this->conn, $messageNum, $partstring));
						// Message
					} elseif (strtoupper($parts[$i]->subtype) == "PLAIN") {
						$content .= imap_fetchbody($this->conn, $messageNum, $partstring);
					}
				}

				if (isset($parts[$i]->parts)) {
					$stack[] = array("p" => $parts, "i" => $i);
					$parts = $parts[$i]->parts;
					$i = 0;
				} else {
					$i++;
				}
			}
		}

		$messagearr = array($content, $attachment);
		return $messagearr;
	}

	private function parseDSN($dsn)
	{
		$parsed = $this->imapdsn;

		if (is_array($dsn)) {
			$dsn = array_merge($parsed, $dsn);
			return $dsn;
		}
		//find the protocol
		if (($pos = strpos($dsn, '://')) !== false) {
			$str = substr($dsn, 0, $pos);
			$dsn = substr($dsn, $pos + 3);
		} else {
			$str = $dsn;
			$dsn = null;
		}
		if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
			$parsed['imapprotocol']  = $arr[1];
			$parsed['imapprotocol'] = !$arr[2] ? $arr[1] : $arr[2];
		} else {
			$parsed['imapprotocol']  = $str;
			$parsed['imapprotocol'] = $str;
		}

		if (!count($dsn)) {
			return $parsed;
		}
		// Get (if found): username and password
		if (($at = strrpos($dsn,'@')) !== false) {
			$str = substr($dsn, 0, $at);
			$dsn = substr($dsn, $at + 1);
			if (($pos = strpos($str, ':')) !== false) {
				$parsed['imapuser'] = rawurldecode(substr($str, 0, $pos));
				$parsed['imappass'] = rawurldecode(substr($str, $pos + 1));
			} else {
				$parsed['imapuser'] = rawurldecode($str);
			}
		}

		//server
		if (($col = strrpos($dsn,':')) !== false) {
			$strcol = substr($dsn, 0, $col);
			$dsn = substr($dsn, $col + 1);
			if (($pos = strpos($strcol, '/')) !== false) {
				$parsed['imapserver'] = rawurldecode(substr($strcol, 0, $pos));
			} else {
				$parsed['imapserver'] = rawurldecode($strcol);
			}
		}

		//now we are left with the port and mailbox so we can just explode the string and clobber the arrays together
		$pm = explode("/",$dsn);
		$parsed['imapport'] = $pm[0];
		$parsed['imapmailbox'] = $pm[1];
		$dsn = NULL;

		return $parsed;
	}

	public function __destruct()
	{
		@imap_close($this->conn);
		return TRUE;
	}
}
?>