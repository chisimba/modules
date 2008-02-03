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
    public $codeLanguage;
    public $lineNumbers;
    public $width;
    public $height;
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
    
    public function working____renderEditor($filename, $areaName="codetext")
    {
        $formAction=$this->uri(array(
            "action" => "save",
            "file" => $filename), 
          "codewriter");
        $ret = '<form id="myEditorForm" action="' . $formAction .'" method="post">';
        $ret .='<input type="submit" value="Save" />';
    	$ret .= '<textarea name="codetext" '
          . 'id="cwEditor" class="codepress '
          . $this->codeLanguage 
          . ' linenumbers-' . $this->lineNumbers . '" '
          . ' style="width:' . $this->width . ';'
              . 'height:' . $this->height . ';"'
          . '">' . $this->code 
          . '</textarea>';// . '<textarea name="testing" id="testing" class="codepress javascript linenumbers-on">Testing here</textarea>';
        
        $ret .="</form>";
        return $ret;
    }
    public function renderEditor($filename, $areaName="codetext")
    {
        $formAction=$this->uri(array(
            "action" => "save",
            "file" => $filename), 
          "codewriter");
        $ret = '<form id="myEditorForm" action="' . $formAction .'" method="post">';
        $ret .='<input type="submit" value="Save" onClick="cwEditor.toggleEditor(); myEditorForm.form.submit();"/>';
        $ret .='<input type="button" value="Toggle edit mode" onClick="cwEditor.toggleEditor();"/>';
        $ret .= '<textarea name="codetext" '
          . 'id="cwEditor" class="codepress '
          . $this->codeLanguage 
          . ' linenumbers-' . $this->lineNumbers . '" '
          . ' style="width:' . $this->width . ';'
              . 'height:' . $this->height . ';"'
          . '">' . $this->code 
          . '</textarea>';
        
        $ret .="</form>";
        return $ret;
    }

    
    public function buildLeftPanel($workingDir)
    {
        $ret ="";
        $this->sConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $projectPath = $this->sConfig->getValue('mod_codewriter_projectpath', 'codewriter');
        $fileUtils = $this->getObject("cwfileutils", "codewriter");
        $dirs = $fileUtils->getDirs($projectPath);
        
        $ret .= $this->objLanguage->languageText('mod_codewriter_workingdir', 'codewriter');
        if ($workingDir !== NULL) {
            $ret .= "<br />" . $this->getFolderIconStaticExpanded() . "<em>" . $workingDir . "</em><br /><br />";
        }
        $ico = $this->getFolderIconStatic();
        foreach ($dirs as $dir) {
        	$ret .= $ico . " " . $dir . "<br />";
        }
        $ret .= count($dirs);
        return $ret;
    }
    
    public function buildRightPanel()
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
        return $objIcon->show();
    }

    public function getFolderIconStaticExpanded()
    {
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objIcon->setIcon('tree/folder-expanded', 'gif');
        $objIcon->title="Current";   
        return $objIcon->show();
    }
    
    public function show()
    {
    	return $this->renderEditor();
    }
}
?>
