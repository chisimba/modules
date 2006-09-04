<?php

//apache log file parser class

class logparser extends object
{
	public $objFile;

	public function init()
	{
		$this->objFile = $this->getObject('dbfile', 'filemanager');
	}

	public function log2arr($file)
	{
		$fpath = $this->objFile->getFullFilePath($file);
		$file = file($fpath);
		return $file;
	}

	public function logfileStats($file)
	{
		$fname = $this->objFile->getFileName($file);
		$fsize = $this->objFile->getFileSize($file);
		$fpath = $this->objFile->getFullFilePath($file);

		return array('filesize' => $fsize, 'filename' => $fname, 'filepath' => $fpath);
	}

	public function parselogEntry($line)
	{
		$stuff = explode('"',$line);
		//unset the blanks
		unset($stuff[4]);
		unset($stuff[6]);
		//split the first line into ip and date
		$ipdate = explode(" - - ", $stuff[0]);
		$ip = $ipdate[0];
		$date = $ipdate[1];
		//fix up the date to be more readable
		$date = str_replace("[","",$date);
		$date = str_replace("]","",$date);
		$date = $this->fixDates($date);
		$request = $stuff[1];
		$servercode = $stuff[2];
		$requrl = $stuff[3];
		$useragent = $stuff[5];

		$requestarr = array('fullrecord' => $line, 'ip' => $ip, 'date' => $date, 'request' => $request, 'servercode' => $servercode, 'requrl' => $requrl, 'useragent' => $useragent);

		return $requestarr;
	}

	private function fixDates($datetime)
	{
		$date = strtotime($datetime);
		$ref = date('r', $date);
		return $ref;
	}
}