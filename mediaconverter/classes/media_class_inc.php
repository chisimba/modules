<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

class media extends object 
{
	public $ffmpeg;
	
	public function init()
	{
		try {
			$this->ffmpeg = $this->getResourcePath('ffmpeg');
			$this->objConfig = $this->getObject('altconfig', 'config');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}
	}
	
	public function convert3gp2flv($file)
	{
		if(!file_exists($this->objConfig->getcontentbasePath().'mediaconverter/'))
		{
			mkdir($this->objConfig->getcontentbasePath().'mediaconverter/');
			chmod($this->objConfig->getcontentbasePath().'mediaconverter/', 0777);
		}
		$siteroot = $this->objConfig->getSiteRoot();
		$rfile = basename($file, ".3gp");
		$newfile = $rfile.time().".flv";
		$res = $this->objConfig->getcontentbasePath().'mediaconverter/'.$newfile;
		system("$this->ffmpeg -i $file -acodec mp3 -ar 22050 -ab 32 -f flv -s 320x240 $res", $results);
		if($results == 0)
		{
			return $siteroot.'usrfiles/mediaconverter/'.$newfile;
		}
		else {
			return FALSE;
		}
	}
}
?>