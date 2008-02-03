<?php
/**
 *
 * Code authoring tool
 *
 * Code authoring tool for PHP, CSS, javascript, etc based on codepress
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   codewriter
 * @author    Derek Keats _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
* A file system utility class for the module codewriter  
*
* @author Derek Keats
* @package codewriter
*
*/
class cwfileutils extends object
{

    /**
    *
    * Intialiser for the codewriter controller
    * @access public
    *
    */
    public function init()
    {
    }
    
    public function getDirs($path)
    {
    	$dp=opendir($path);
        $ret = array();
        while (false!=($file=readdir($dp))) {
        	if ($file!="." && $file!=".." && $file!="CVS" && $file!="CVSROOT") {
        		if (is_dir($path."/".$file)) {
                    $ret[] = $file;
                }
        	}
        }
        return $ret;
    }
    
    public function getFileContents($filename)
    {
    	return htmlentities(file_get_contents($filename));
    }
    
    public function fileExtension($filename)
    {
        $pathInfo = pathinfo($filename);
        return $pathInfo['extension'];
    }
    
    public function getCodeLanguage($ext)
    {
        $ext =strtolower($ext);
    	$arLanguages=array(
            "php"=>"php",
            "js"=>"javascript",
            "css"=>"css",
            "html"=>"html",
            "csharp"=>"csharp",
            "csh"=>"csharp",
            "asp" => "asp",
            "xsl" => "xsl",
            "xslt" => "xsl"
            );
        if (in_array($ext, $arLanguages)) {
        	return $arLanguages[$ext];
        } else {
        	return "generic";
        }
    }

}
?>
