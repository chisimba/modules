<?php

class dbnewsfilemanagement extends object
{

    public function init()
    {
		$this->objUser = $this->getObject('user', 'security');
        
        $this->loadClass('treemenu', 'tree');
        $this->loadClass('treenode', 'tree');
        $this->loadClass('htmllist', 'tree');
        
        $this->objFolders = $this->getObject('dbfolder', 'filemanager');
        $this->objFiles = $this->getObject('dbfile', 'filemanager');
    }
    
    public function getFiles($id)
    {
        if ($id == '') {
            return '';
        }
        
        if ($id == 'root') {
            $folder = 'users/'.$this->objUser->userId();
        } else {
            $folder = $this->objFolders->getFolderPath($id);
        }
        
        if ($folder == '' || $folder == FALSE) {
            return FALSE;
        }
        
        $results = array();
        
        $files = $this->objFiles->getFolderFiles($folder);
        
        if (count($files) > 0) {
            foreach ($files as $file)
            {
                if ($file['category'] == 'images' && (strtolower($file['datatype']) == 'jpg' || strtolower($file['datatype']) == 'jpeg' || strtolower($file['datatype']) == 'gif' || strtolower($file['datatype']) == 'png')) {
                    $results[] = $file;
                }
            }
        }
        
        return $results;
    }
    
    public function getFolders()
    {
        
        //Create a new tree
		$menu  = new treemenu();
        
        
        $icon         = 'folder.gif';
		$expandedIcon = 'folder-expanded.gif';
        
        $allFilesNode = new treenode(array('text' => 'My Files', 'link' => 'javascript:loadFolder(\\\'root\\\');', 'icon' => $icon, 'expandedIcon' => $expandedIcon));
        
        
        
        $refArray = array();

        $refArray['/users/'.$this->objUser->userId()] =& $allFilesNode;
        
        $folders = $this->objFolders->getUserFolders($this->objUser->userId());
        
        if (count($folders) > 0) {
            foreach ($folders as $folder)
            {
                $folderText = basename($folder['folderpath']);
                
                    $cssClass = '';
                
                
                $node =& new treenode(array('text' => $folderText, 'link' => 'javascript:loadFolder(\\\''.$folder['id'].'\\\');', 'icon' => $icon, 'expandedIcon' => $expandedIcon, 'cssClass'=>$cssClass));
                
                $parent = '/'.dirname($folder['folderpath']);
                
                //echo $folder['folderpath'].' - '.$parent.'<br />';
                if (array_key_exists($parent, $refArray)) {
                    $refArray['/'.dirname($folder['folderpath'])]->addItem($node);
                }
                
                $refArray['/'.$folder['folderpath']] =& $node;
            }
        }
        
        $menu->addItem($allFilesNode);
        
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('TreeMenu.js', 'tree'));
        $this->setVar('pageSuppressXML', TRUE);
        
        $objSkin =& $this->getObject('skin', 'skin');
        $treeMenu = &new dhtml($menu, array('images' => 'skins/_common/icons/tree', 'defaultClass' => 'treeMenuDefault'));
        return $treeMenu->getMenu();
    }
    

}
?>