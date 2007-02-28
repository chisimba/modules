<?php
/**
* LatestAudioFile
* This class gets the latest audio file for the realtime module.
* @author Jessie
* @copyright (c) 2006 University of the Western Cape
* @package realtime
* @version 1
*/

function getUploadDir()
{
   return getcwd()."/audio";
}

/*
* getLatest
* Returns the most recent audio file in given directory
*/
function getLatest($uploadDir)
{
   if (is_dir($uploadDir))
   {
   	if (!($dh = opendir($uploadDir)))
   		die("Error: Realtime open directory ".$uploadDir);

   	$newestFileDate = 0;
   	$newestFile = null;
   	while (($filename = readdir($dh)) !== false) {
	   $filepath = $uploadDir . "/" . $filename;
	   if (is_file($filepath))
	   {
		   // Finds file's last date of modification;
		   $fmtime = filemtime($filepath);
		   if ($fmtime > $newestFileDate)
		   {
			$newestFileDate = $fmtime;
			$newestFile = $filename;
		   }
	   }
	}
   	closedir($dh);
   } else
   	die("Error: Realtime audio directory bad: ".$uploadDir);
      
   return $newestFile;
}

$uploadDir = getUploadDir();
if (!is_null($newFile = getLatest($uploadDir)))
{
   echo htmlspecialchars($newFile);
}
?>
