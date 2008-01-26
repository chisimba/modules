<?php
/**
 *
 * Class to recurse the UWC website and generate XML output
 *
 * This class will recurse the static website pages and generate XML that can be used
 * for the purpose of recreating the UWC portal content as XML or storing it in a database
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
 * @package   portaltools
 * @author    dkeats@uwc.ac.za
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

// Set the base directory where the website is stored
define(START_DIR, '/home/dkeats/websites/portal/www.uwc.ac.za');

/**
*
* Database accesss class for Chisimba for the module _MODULECODE
*
* @author Derek Keats
* @package portalimport
*
*/
class portalfileutils extends object
{

    public $dirs=array();
    public $files=array();

    /**
    *
    * Intialiser for the  
    * @access public
    *
    */
    public function init()
    {

    }

    public function printDir($path=START_DIR)
    {

    }

    public function readPath($path=START_DIR,$level,$last,&$dirs,&$files){
        $dp=opendir($path);
        while (false!=($file=readdir($dp)) && $level <= $last){
            if ($file!="." && $file!="..")
            {
                if (is_dir($path."/".$file))
                {
                    $this->readPath($path."/".$file,$level+1,$last,$dirs,$files); // uses recursion
                    $dirs[] = "$path/$file";  // reads the dir into an array
                } else {
                    $files[] = "$path/$file"; // reads the file into an array
                }
            }
        }
        $this->dirs=$dirs;
        $this->files=$files;
        if (!empty($this->dirs) || !empty($this->files)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    *
    * A method to simply list all the directories found using the readPath method
    *
    *   @return string HTML formatted line for each directory or NULL if no directories found
    *   @access Public
    * 
    */
    public function showDirs()
    {
        if (!empty($this->dirs)) {
            $ret="";
            foreach ($this->dirs as $directory) {
                $ret .= $directory . "<br />";
            }
            return $ret;
        } else { 
           return NULL;
        }
    }

    /**
    *
    * A method to simply list all the files found using the readPath method
    *
    *   @return string HTML formatted line for each file or NULL if no files found
    *   @access Public
    * 
    */
    public function showFiles()
    {
        if (!empty($this->files)) {
            $ret="";
            foreach ($this->files as $file) {
                $ret .= $file . "<br />";
            }
            return $ret;
        } else { 
           return NULL;
        }
    }


    public function showFilesAsXML()
    {
        if (!empty($this->files)) {
            $ret="";
            $count=0;
            foreach ($this->files as $filename) {
                $count++;
if ($count<=425) {
                $extension = $this->fileExtension($filename);
                $portalPath = $this->getPortalPath($filename);
                $lcExt = strtolower($extension);
                $ret .= "<file counter=\"" . $count . "\">\n";
                $ret .= "<filepath>" . $filename . "</filepath>\n";
                $ret .= "<filetype>" . $extension . "</filetype>\n";
                $ret .= "<depth>" . $this->getLevels($filename) . "</depth>\n";
                $ret .= "<portalpath>" . $portalPath . "</portalpath>\n";
                $ret .= $this->getPortalStructure($portalPath);
                if ($lcExt == "htm" || $lcExt == "html") {
                    $ret .= "<content>\n\n" . $this->getContent($filename) . "\n</content>\n\n";
                } else {
                    $ret .= "<content />\n";
                }
                $ret .= "</file>\n\n\n";
}
            }
            return $ret;
        } else { 
           return NULL;
        }
    }

    public function getContent($filename)
    {
        $cStart = "<!--CONTENT_BEGIN-->";
        $cEnd = "<!--CONTENT_END-->";
        $pattern = "/" . $cStart . "(.*?)" . $cEnd . "/";
        $ret = file_get_contents($filename);
        if (preg_match_all($pattern, $ret, $results, PREG_PATTERN_ORDER)) {
            return "Contents found";
        } else {
            return "Pattern not found in file";
        }
    }

    public function getLevels($filename)
    {
        $fTmp = str_replace(START_DIR . "/", "", $filename);
        $pStr = explode("/", $fTmp);
        return count($pStr) - 1;
    }

    public function getPortalPath($filename)
    {
        return str_replace(START_DIR . "/", "", $filename);
    }

    public function getPortalStructure($portalPath)
    {
        $pStr = explode("/", $portalPath);
        $items = count($pStr);
        if ($items == 1 || empty($pStr)) {
            $portal = "<portal>/</portal>\n";
            $section = "<section />\n";
            $subportal ="<subportal />\n";
        }
        if ($items == 2 || empty($pStr)) {
            $portal = "<portal>" . $pStr[0] ."</portal>\n";
            $section = "<section />\n";
            $subportal ="<subportal />\n";
        }
        if ($items == 3 || empty($pStr)) {
            $portal = "<portal>" . $pStr[0] ."</portal>\n";
            $section = "<section>" . $pStr[1] . "</section>\n";
            $subportal ="<subportal />\n";
        }
        if ($items == 4 || empty($pStr)) {
            $portal = "<portal>" . $pStr[0] ."</portal>\n";
            $section = "<section>" . $pStr[1] . "</section>\n";
            $subportal ="<subportal>" . $pStr[2] . "</subportal>\n";
        }
        return $portal . $section . $subportal;
    }


    public function fileExtension($filename)
    {
        $pathInfo = pathinfo($filename);
        return $pathInfo['extension'];
    }


    function getSize($path=START_DIR)
    {
        if(!is_dir($path))return filesize($path);
        $dir = opendir($path);
        while($file = readdir($dir)) {
            if(is_file($path."/".$file))$size+=filesize($path."/".$file);
            if(is_dir($path."/".$file) && $file!="." && $file !="..")$size +=$this->getSize($path."/".$file);
       }
       return $size;
   }

}
?>
