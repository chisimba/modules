<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

/**
 * Trackback class for the blog module
 *
 * @author Paul Scott
 * @copyright AVOIR
 * @access Public
 * @filesource
 */

class trackback extends object
{
	public $objTrackBack;

	public function init()
	{
		require_once('modules/blog/resources/Trackback.php');
	}

	public function setup($data, $options)
	{
		$this->objTrackBack = Services_Trackback::create($data, $options);

	}

	public function autodiscCode()
	{
		return $this->objTrackBack->getAutodiscoveryCode();
	}

	public function setVal($key, $value)
	{
		$this->objTrackBack->set($key, $value);
	}

	public function autoDisc()
	{
		return $this->objTrackBack->autodiscover();
	}

	public function sendTB($sendData)
	{
		$tracker = $this->objTrackBack->send($sendData);
		if(PEAR::isError($tracker))
		{
			return $tracker->getMessage();
		}
		else {
			return $tracker;
		}
	}
}