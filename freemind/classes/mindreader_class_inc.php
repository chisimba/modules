<?php
/**
* Class to help kbook by reading the folders and files
* 
* @author Derek Keats 
*/

class kbookreader extends object 
{
    
    function preparePath($dir, $dirToJoin)
    {
        /* replace the windows backslash with unix slash which
           works on all systems */
        $dir=trim(str_replace("\\", "/", $dir));
        // Count the number of characters in the string
        $chars=strlen($dir);
        // Get the last character of the string
        $lastChar=trim(substr($dir, $chars-1, 1));
        // If the last characted is a slash OK, if not make it so Mr Data
        if ($lastChar != "/") {
            $dir .= "/";
        }
        return $dir.$dirToJoin."/";
    }
    
    function getIndex($dir, $httpPath)
    {
        $filename = $dir."index.htm";
        $f=fopen($filename, "r") or die ("error opening file");
        $contents = fread($f, filesize($filename));
        fclose($f);
        return $contents;
    }
    
    function prepForDisplay($str, $dir, $httpPath)
    {
        $str = str_replace("<img src=\"", "<img src=\"".$httpPath.$dir, $str);
        $str = str_replace("'images/", $httpPath.$dir."'images/", $str);
        return $str;
    }

 
} 
