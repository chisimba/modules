<?php
set_time_limit(0);
/**
 *
 * Class to recurse the UWC website and generate XML output
 *
 * This class will recurse the static website pages and generate XML that can be used
 * for the purpose of recreating the UWC portal content as XML or storing it in a 
 * database.  This is a quick and dirty project for a single use so I may not add 
 * comments or multilingual text. I have tried to write the code in a self-documenting
 * kind of way.
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
    public $sCOnfig;
    public $startDir;
    public $xmlPath;
    public $xmlFile;
    public $xmlOut;

    /**
    *
    * Intialiser for the portalimporter module
    * @access public
    *
    */
    public function init()
    {
        $this->sConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->startDir = $this->sConfig->getValue('mod_portalimporter_sitepath', 'portalimporter');
        $this->contentStart = $this->sConfig->getValue('mod_portalimporter_contentstart', 'portalimporter');
        $this->contentEnd = $this->sConfig->getValue('mod_portalimporter_contentend', 'portalimporter');
        $this->xmlPath = $this->sConfig->getValue('mod_portalimporter_xmlpath', 'portalimporter');
        $this->xmlFile = $this->sConfig->getValue('mod_portalimporter_xmlfile', 'portalimporter');
        $this->xmlOut = $this->xmlPath . "/" . $this->xmlFile;
    }

    /**
    * 
    * Method to read a path, and create an array of all the directories and all
    * the files in it.
    * 
    * @param String $path The path. Use "start" to start from the configured path
    * @param Integer $level The level to work from (normally 1)
    * @param Integer $last The last level to work from (normally 4 to go 4 directories deep)
    * @param String array $dirs The array of directories
    * @param String array $files
    * 
    * @return boolean TRUE|FALSE depending on whether there are any files or not
    * @access public
    *  
    */
    public function readPath($path, $level,$last,&$dirs,&$files) {
        if ($path == "start") {
            $path=$this->startDir;
        }
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
    
    public function listFilesWithDelimiters()
    {
        $ret= "";
    	if (!empty($this->files)) {
            $count=0;
            $strCount;
            $legacyCount=0;
            $sPattern = "/$this->contentStart(.*)$this->contentEnd/iseU";
            foreach ($this->files as $filename) {
                $count++;
                $extension = $this->fileExtension($filename);
                $lcExt = strtolower($extension);
                if ($lcExt == "htm" || $lcExt == "html") {
                    $contents = file_get_contents($filename);
                    if (preg_match($sPattern, $contents, $elems)) { 
                        $ret .= $count. ". " . $filename . " <font color='green'>STRUCTURED PAGE</font><br />";
                        $strCount++;
                    } else {
                    	$ret .= $count. ". " . $filename . " <font color='red'>LEGACY PAGE</font><br />";
                        $legacyCount++;
                    }
                }
            }
            $totalHtml = $legacyCount + $strCount;
            $ret = "<h1>Listing files per structured or legacy content</h1>"
              . "<br /><h2><font color='green'>Structured pages: $strCount</font>"
              . "<br /><font color='red'>Legacy pages: $legacyCount</font>"
              . "<br /><font color='blue'>Total HTML pages: $totalHtml</font>"
              . "<br /><font color='purple'>Total files parsed: $count</font></h2>" 
              . $ret;
        }
        return $ret;
    }
    
    function xmlToFile()
    {
        $fh = fopen($this->xmlOut, 'w')  or die("Cannot open file for writing");
        fwrite($fh, $this->xmlHeader());
        fclose($fh);
        $this->showFilesAsXML("file");
        return "Finished";        
    }
    
    public function xmlHeader()
    {
    	return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n<document>\n\n";
    }
    
    public function closeXml()
    {
    	return "\n\n</document>\n\n";
    }
    
    public function storeData()
    {
        $db = $this->getObject("dbcontent","portalimporter");
        foreach ($this->files as $filename) {
            $count++;
            $extension = $this->fileExtension($filename);
            $lcExt = strtolower($extension);
            if ($lcExt == "htm" || $lcExt == "html") {
                $filepath = $filename;
                $filetype=$extension;
                $portalPath = $this->getPortalPath($filename);
                $portal = $this->getLevel($portalPath, "portal");
                $section =$this->getLevel($portalPath, "section");
                $subportal = $this->getLevel($portalPath, "subportal");
                $page = $this->getLevel($portalPath, "page");
                $this->getContent($filename, $outStyle="database");
                $ar = array(
                  'filepath' => $filepath,
                  'filetype' => $filetype,
                  'portalpath' => $portalPath,
                  'portal' => $portal,
                  'section' => $section,
                  'subportal' => $subportal,
                  'page' => $page,
                  'structuredcontent' => $this->contentStructured,
                  'rawcontent' => $this->contentRaw
                );
                $db->insert($ar);
            }
        }
    	return "All done";
    }
    
    public function getLevel($portalPath, $level)
    {
        $pStr = explode("/", $portalPath);
        $items = count($pStr);
        if ($items == 1 || empty($pStr)) {
            $portal = "/";
            $section = "";
            $subportal ="";
            $page = $pStr[0];
        }
        if ($items == 2 || empty($pStr)) {
            $portal = $pStr[0];
            $section = "";
            $subportal ="";
            $page = $pStr[1];
        }
        if ($items == 3 || empty($pStr)) {
            $portal = $pStr[0];
            $section = $pStr[1];
            $subportal ="";
            $page = $pStr[2];
        }
        if ($items == 4 || empty($pStr)) {
            $portal = $pStr[0];
            $section = $pStr[1];
            $subportal =$pStr[2];
            $page = $pStr[3];
        }
        switch($level) {
        	case "portal":
                return $portal;
                break;
            case "section":
                return $section;
                break;
            case "subportal":
                return $subportal;
                break;
            case "page":
                return $page;
            default:
                return "";
                break;
        }
    }


    public function showFilesAsXML($outPutMethod="screen")
    {
        if (!empty($this->files)) {
            $ret="";
            $count=0;
            if ($outPutMethod == "file") {
                $fh = fopen($this->xmlOut, 'a')  or die("Cannot open file for writing");
            }
            foreach ($this->files as $filename) {
                $count++;
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
                if ($outPutMethod == "file") {
                	fwrite($fh, $ret);
                    $ret="";
                }
            }
            if ($outPutMethod == "file") {
                fwrite($fh, $this->closeXml());
                fclose($fh);
            }
            return $ret;
        } else { 
           return NULL;
        }
    }

    public function getContent($filename, $outStyle="xml")
    {
        $contents = file_get_contents($filename);
        $pattern = "/$this->contentStart(.*)$this->contentEnd/iseU";
        if ($outStyle=="xml") {
        	$strOpen = "<useraw>FALSE</useraw>\n<structuredcontent>";
            $strClose = "</structuredcontent>\n<rawcontent />\n";
            $rawOpen = "<useraw>TRUE</useraw>\n<structuredcontent />\n<rawcontent>";
            $rawClose = "</rawcontent>\n";
        } else {
            $strOpen = "";
            $strClose = "";
            $rawOpen = "";
            $rawClose = "";
        }
        
        if (preg_match($pattern, $contents, $elems)) { 
            $ret = $strOpen
              . $this->resetImages($this->getContentStructured($contents))
              . $strClose;
            if ($outStyle=="xml") {
                return $ret;
            } else {
                $this->contentStructured = $ret;
                $this->contentRaw="";
                return TRUE;
            }
        } else {
            $ret = $rawOpen 
              . $this->resetImages($this->getBody($contents))
              . $rawClose;
            if ($outStyle=="xml") {
                return $ret;
            } else {
                $this->contentStructured = "";
                $this->contentRaw=$ret;
                return TRUE;
            }
        }
    }
    
    /**
     * 
     * Method to extract the body of a page
     * @param String $contents All the HTML from which to extract the body
     * @return String The contents of what is between the &lt;body&gt; and &lt;/body&gt; tags
     * 
     */
    public function getBody(& $contents)
    {
        if (preg_match("/<body.*>(.*)<\/body>/iseU", $contents, $elems)) { 
            $page = $elems[1];
            $page = $this->unCrapify($page);
        } else {
            $page = "No data in page";
        }
        return $page;
    }
    
    public function getContentStructured(& $contents)
    {
        $pattern = "/$this->contentStart(.*)$this->contentEnd/iseU";
    	if (preg_match($pattern, $contents, $elems)) { 
            $page = $elems[1];
            $page = $this->unCrapify($page);
        } else {
        	$page = "No data in page";
        }
        return $page;
    }

    public function getLevels($filename)
    {
        $fTmp = str_replace(START_DIR . "/", "", $filename);
        $pStr = explode("/", $fTmp);
        return count($pStr) - 1;
    }

    public function getPortalPath($filename)
    {
        return str_replace($this->startDir . "/", "", $filename);
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
    
//------------MOVE file methods ---- put in a different class
    
    /**
    * 
    * Method to move any image assets to a repository
    * 
    * @access Public
    * 
    */
    public function moveImagesToRepository()
    {
        $successes = 0;
        $failures = 0;
        $count=0;
        $str = "";
        $failedFiles="";
        foreach ($this->files as $filename) {
            $extension = $this->fileExtension($filename);
            $portalPath = $this->getPortalPath($filename);
            $lcExt = strtolower($extension);
            if ($this->isImage($lcExt)) {
                $count++;
                $source = $filename;
                $destination = $this->getDestination("image", $portalPath);
                //echo $source . "-------->" . $destination . "<br />";
            	if (copy($source, $destination)) {
            		$successes++;
            	} else {
            		$failures ++;
                    $failedFiles .= "Failed to copy <em>$source</em> to the destination of <em>$destination";
            	}
                    
            }
        }
        $str .= "<br /><br />Image files located: $count<br />"
          . "Succeeded: $successes<br />"
          . "Failed: $failures<br />";
        if ($failures > 0) {
        	$str .= "List of failed copies: "
              . $failedFiles;
        }
        return $str;
    }
    
    /**
    * 
    * Method to get the destination for a file by file type
    * @access public
    * @param String $ext The extension to test
    * @return String The file destination full path
    *   
    */
    private function getDestination($assetType, & $portalPath)
    {
        $assetBase = $this->sConfig->getValue('mod_portalimporter_repository', 'portalimporter');
        $assetBase .= $assetType;
        //Create the asset base for this asset type if it does not exist
        if (!file_exists($assetBase)) {
            mkdir($assetBase, 0777, TRUE);
        }
        $pStr = explode("/", $portalPath);
        //Pop off the file name from the array
        array_pop($pStr);
        $curPath = $assetBase;
        foreach ($pStr as $directory) {
        	$curPath .= "/" . $directory;
            //echo $curPath . "<br />";
            if (!file_exists($curPath)) {
               mkdir($curPath, 0777, TRUE);
            }
        }
    	return $assetBase . "/" . $portalPath;
    }

    /**
    * 
    * Method to determine if a file extension is an image or not
    * @access public
    * @param String $ext The extension to test
    *   
    */
    public function isImage($ext)
    {
    	$images=array("jpg", "jpeg", "png", "gif", "svg");
        if (in_array($ext, $images)) {
            return TRUE;
        } else {
        	return FALSE;
        }
    }
    
    /**
    * 
    * Method to reset the image tags in the database so that they
    * point to the new image location wich is used to update 
    * image tags in imported content so that they point to the 
    * repository.
    * 
    * @access public
    * @return String The URL path to images
    * 
    */
    public function resetImages($contents)
    {
        if ($this->imageBaseUrl=="") {
        	$this->imageBaseUrl = $this->sConfig->getValue('mod_portalimporter_imageurl', 'portalimporter');
        }
        $pattern="/<img.*src=['\"](.*)['\"].*>/iseU";
    	if (preg_match_all($pattern, $contents, $matches)) {
            $str = "";
            foreach ($matches[1] as $matched) {
                $newLink = $this->imageBaseUrl . "/" . $matched;
              	$contents = str_ireplace($matched, $newLink, $contents);
            }
            return $contents;
        } else {
        	return NULL;
        }
        
    }

//----------------- END MOVE file ------------------------------

    public function fileExtension($filename)
    {
        $pathInfo = pathinfo($filename);
        return $pathInfo['extension'];
    }


    function getSize($path=NULL)
    {
        if ($path==NULL) {
        	$path = $this->startDir;
        }
        if(!is_dir($path))return filesize($path);
        $dir = opendir($path);
        while($file = readdir($dir)) {
            if(is_file($path."/".$file))$size+=filesize($path."/".$file);
            if(is_dir($path."/".$file) && $file!="." && $file !="..")$size +=$this->getSize($path."/".$file);
       }
       return $size;
   }
   
   public function unCrapify(&$content)
   {
        $options = array("clean" => true,
           "drop-proprietary-attributes" => true,
           "drop-empty-paras" => true,
           "hide-comments" => true); 
        $tidy = tidy_parse_string($content, $options);
        tidy_clean_repair($tidy);
        return $tidy;
   }

}
?>
