<?php

class imap //extends object
{
	public $server;
	public $user;
	public $pass;
	public $protocol = "imap";
	public $mailbox = "INBOX";
	public $port = 143;
	public $overview;
	public $alerts;
	private $conn;
	private $headers;
	private $numEmails;
	private $mailHeader;


	public function setconn($server, $user, $pass, $port = 143, $protocol = "imap", $mailbox = "INBOX")
	{
		$this->server = $server;
		$this->user = $user;
		$this->pass = $pass;
		$this->protocol = $protocol;
		$this->mailbox = $mailbox;
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


	public function __destruct()
	{
		@imap_close($this->conn);
		return TRUE;
	}
}
?>