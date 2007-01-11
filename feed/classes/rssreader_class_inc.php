<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

class rssreader extends object
{
	public $rss;
	public $objLanguage;

	public function init()
	{
		try {
			$this->objLanguage = $this->getObject('language', 'language');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}

		if (!@include_once ('XML/RSS.php')) {
            throw new customException($this->objLanguage->languageText("mod_feed_sanity_xmlrssnotfound", "blog"));
        }
        else {
			require_once "XML/RSS.php";
        }

	}

	public function parseRss($url)
	{
		$this->rss =& new XML_RSS($url);
		return $this->rss->parse();
	}

	public function getRssItems()
	{
		return $this->rss->getItems();
	}

	public function getRssStruct()
	{
		return $this->rss->$rss->getStructure();
	}

	public function getChanInfo()
	{
		return $this->rss->getChannelInfo();
	}

	public function getRssImages()
	{
		return $this->rss->getImages();
	}

	public function getRssTextInputs()
	{
		return $this->rss->getTextinputs();
	}
}