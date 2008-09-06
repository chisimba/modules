<?php

/**
 * Scorm controller
 * 
 * Controller class for the Scorm Module in Chisimba
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
 * @package   scorm
 * @author    Paul Mungai <pwando@uonbi.ac.ke>
 * @copyright 2008 Paul Mungai
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: controller.php 9085 2008-05-08 17:55:52Z paul $ //correct this
 * @link      http://avoir.uwc.ac.za
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check


/**
 * scorm controller
 * 
 * Controller class for the Context Content Module in Chisimba
 * 
 * @category  Chisimba
 * @package   contextcontent
 * @author    Paul Mungai <pwando@uonbi.ac.ke>
 * @copyright 2008 Paul Mungai
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 */
class scorm extends controller
{
    public $objLanguage;
    public $objUser;
    public $objReadXml;
    public $objFolders;
    public $objTreeNode;
    public $objTreeMenu;
    /**
    * Constructor
    */
    public function init()
    {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objUser = $this->getObject('user', 'security');
            // Load Scorm Classes
            $this->objReadXml =& $this->getObject('readxml_scorm', 'scorm');
	    $this->objFolders = $this->getObject('dbfolder','filemanager');
 	    $this->objTreeMenu =& $this->getObject('treemenu', 'tree');
	    $this->objTreeNode =& $this->loadClass('treenode', 'tree');


    }

    /**
    * Dispatch Method to run required action
    * @param string $action
    */
    public function dispatch($action)
    {
        switch ($action)
        {            
            case 'viewscorm':
		//$selectedParts=$this->getArrayParam('arrayList');
		$folderId = $this->getParam('folderId', NULL);
		$this->setVarByRef('folderId',$folderId);
                return 'handle_scorm_tpl.php';
            default:
		$folderId = $this->getParam('folderId', NULL);
		$this->setVarByRef('folderId',$folderId);
                return 'handle_scorm_tpl.php';
	}
    }
}
?>
