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
* A renderer class for the module codewriter which renders the key
* interface elements so that they are reusable
*
* @author Derek Keats
* @package codewriter
*
*/
class cwrenderer extends object
{
    /**
    *
    *  @var string $codeLanguage The language which the editor should use to provide syntax highlighting.
    *  @access public
    * 
    */
    public $codeLanguage;
    /**
    *
    *  @var string $lineNumbers The code for turning on/off line numbers
    *  @access public
    * 
    */
    public $lineNumbers;
    /**
    *
    *  @var string $width The width of the editor.
    *  @access public
    * 
    */
    public $width;
    /**
    *
    *  @var string $height The width of the editor.
    *  @access public
    * 
    */
    public $height;
    /**
    *
    *  @var string $code The text contents of the code box.
    *  @access public
    * 
    */
    public $code;
    /**
    *
    * @var string $objLanguage String to hold instance of the language object
    *
    */
    public $objLanguage;
    
    /**
    *
    * Intialiser for the codewriter controller
    * @access public
    *
    */
    public function init()
    {
        $this->codeLanguage = "php";
        $this->lineNumbers ="on";
        $this->width = "100%";
        $this->height = "500px";
        $this->code = "<php\n\n?>";
        $this->objLanguage = $this->getObject("language", "language");
        
    }
    
    public function setAll($codeLanguage, $lineNumbers, $width, $height, $code=NULL)
    {
    	$this->codeLanguage = $codeLanguage; 
        $this->lineNumbers = $lineNumbers;
        $this->width = $width;
        $this->height = $height;
        if ($code !==NULL) {
        	$this->code = $code;
        }
    }
    
    public function showLanguageSelection()
    {
    	return "LANGUAGE SELECTION HERE";
    }
    
    /**
    * 
    * Method to render the script that intercepts the submit call
    * and passes if off to the jQuery forms plugin that handles
    * ajax calls.
    * 
    * @access public
    * @return String A string containing the script.
    *    
    */
    public function renderFormScript()
    {
    	return "<script type=\"text/javascript\"> 
        // wait for the DOM to be loaded 
        jQuery(document).ready(function() { 
            // bind 'myEditorForm' and provide a simple callback function 
            jQuery('#myEditorForm').ajaxForm(function() { 
                alert(\"Saved!\"); 
            }); 
        }); 
        </script> ';";
    }
    
    /**
    * 
    * Method to render the form and inputs, including the code editor itself
    * 
    * @access public
    * @param string $filename The full path to the file being edited
    * @param string $areaName THe name of the textarea used for the code editor
    * @return String A string containing the form of the edit area.
    *    
    */
    public function renderEditor($filename, $areaName="codetext")
    {
        $wordSave = $this->objLanguage->languageText('word_save');
        $togleEdit = $this->objLanguage->languageText('mod_codewriter_togleedit', 'codewriter');
        $togleLno = $this->objLanguage->languageText('mod_codewriter_toglelno', 'codewriter');
        $togleAc = $this->objLanguage->languageText('mod_codewriter_togleac', 'codewriter');
        $formAction=$this->uri(array(
            "action" => "save",
            "file" => $filename), 
          "codewriter");
        $ret = '<form name="myEditorForm" id="myEditorForm" action="' . $formAction .'" method="post">';
        $ret .='<input type="submit" value="' . $wordSave . '" onClick="cwEditor.toggleEditor();"/>';
        $ret .='<input type="button" value="' . $togleEdit . '" onClick="cwEditor.toggleEditor();"/>';
        $ret .= '<textarea name="codetext" '
          . 'id="cwEditor" class="codepress '
          . $this->codeLanguage 
          . ' linenumbers-' . $this->lineNumbers . '" '
          . ' style="width:' . $this->width . ';'
              . 'height:' . $this->height . ';"'
          . '">' . $this->code 
          . '</textarea>';
        $ret .='<input type="button" value="' . $togleLno . '" onClick="cwEditor.toggleLineNumbers();"/>';
        $ret .='<input type="button" value="' . $togleAc . '" onClick="cwEditor.toggleAutoComplete();"/>';
        $ret .="</form>";
        return $ret;
    }

    /**
    *
    * Method to build the left panel of the 3 column editor layout
    * @param string $workingDir The name of the working directory 
    *   (usually refers to a Chisimba module code) 
    * @return String A string containing the content of the left panel
    * 
    */
    public function buildLeftPanel($workingDir)
    {
        $ret ="";
        $this->sConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $projectPath = $this->sConfig->getValue('mod_codewriter_projectpath', 'codewriter');
        $fileUtils = $this->getObject("cwfileutils", "codewriter");
        $dirs = $fileUtils->getDirs($projectPath);
        
        $ret .= $this->objLanguage->languageText('mod_codewriter_workingdir', 'codewriter');
        if ($workingDir != NULL) {
            $ret .= "<br />" . $this->getFolderIconStaticExpanded() . "&nbsp;<em>" . $workingDir . "</em>";
            $ret .= "<br />" . $this->getWorkingDirFiles($workingDir, $projectPath) . "<br /><br />";
        } else {
        	$ret .= "<br />" . $this->getFolderIconStaticGreyed() . "<em>" . $workingDir . "</em><br /><br />";
        }
        $ico = $this->getFolderIconStatic();
        foreach ($dirs as $dir) {
        	$ret .= $ico . " " . $this->getLinked($dir) . "<br />";
        }
        $ret .= count($dirs);
        return $ret;
    }
    
    /**
    *
    * Method to build the right panel of the 3 column editor layout
    * @param string $workingDir The name of the working directory 
    *   (usually refers to a Chisimba module code) 
    * @return String A string containing the content of the right panel
    * 
    */
    public function buildRightPanel($workingDir)
    {
        return "Right panel";
    }
    
    public function getSave($filename)
    {
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objIcon->setIcon('save_submit');
        $objIcon->title="Save";   
    	$saveIcon = $objIcon->show();
        $saveUri = $this->uri(array(
            "action" => "save",
            "file" => $filename),
            "codewriter");
        return $saveIcon;
    }
    
    public function getFolderIconStatic()
    {
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objIcon->setIcon('tree/folder', 'gif');
        $objIcon->title="Directory";   
        $objIcon->align="absmiddle";
        return $objIcon->show();
    }

    public function getFolderIconStaticExpanded()
    {
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objIcon->setIcon('tree/folder-expanded', 'gif');
        $objIcon->title="Current";   
        $objIcon->align="absmiddle";
        return $objIcon->show();
    }
    
    public function getFolderIconStaticGreyed()
    {
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objIcon->setIcon('tree/folder_grey', 'gif');
        $objIcon->title="None";
        $objIcon->align="absmiddle";
        return $objIcon->show();
    }
    
    public function getLinked($folderName)
    {
    	$link = $this->uri(array(
          "action"=>"editcode",
          "project"=>$folderName), "codewriter");
        return "<a href=\"" . $link . "\">" . $folderName . "</a>";
    }
    
    public function getLinkedFile($filePath, $folderName, $linkText)
    {
        $link = $this->uri(array(
          "action"=>"editcode",
          "project"=>$folderName,
          "file"=>$filePath), "codewriter");
        return "<a href=\"" . $link . "\">" . $linkText . "</a>";
    }
    
    public function getWorkingDirFiles($workingdir, $basePath)
    {
        $fileDir = $basePath . "/" . $workingdir;
        try{
            /*** class create new DirectoryIterator Object ***/
            $ret = "";
            foreach( new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($fileDir)
                ) as $item ) {
                if ( $item->isFile() && !$this->isCvs($item) ) {
                    $linkText = str_replace($fileDir, "", $item);
                    $linkItem = $this->getLinkedFile($item, $workingdir, $linkText);
                    $ret .= $linkItem .' <br />';
                }
            }
        } catch(customException $e) {
            customException::cleanUp();
            exit();
        }
     	return $ret;
    }
    
    public function isCvs($item)
    {
    	if ( strpos($item,"CVS")==0 ) {
    		return FALSE;
    	} else {
    		return TRUE;
    	}
    }
    
    
    public function show()
    {
    	return $this->renderEditor();
    }
}
?>
