<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
 * The kbookmark controller manages
 * the kbookmark module
 * @author James Kariuki Njenga
 * @version $Id$
 * @copyright 2005, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package context
*/
class kbookmark extends controller
{
    /**
    * @var string $id
    */
    public $id;

    /**
    * @var object $objUser
    */
    public $objUser;
    
    /**
    * @var object $objLanguage
    */
    public $objLanguage;


    /**
    * @var object $objDbBookmark
    */
    public $objDbBookmark;
    
    /**
    * @var object $objDbGroup
    */
    public $objDbGroup;
    
    /**
    * @var object $objLink: Use to create links
    */
    public $objLink;
    
    /**
    * @var object $objIcon
    */
    public $objIcon;

    /**
    * @var string $xbelOutput: Store xbel format output
    */
    public $xbelOutput="";
   

    /**
    * Method to initialize the controller
    *
    */
    
     
     
    function init()
    {
        $this->objLanguage=& $this->getObject('language', 'language');
        $this->objIcon=& $this->getObject('geticon','htmlelements');
        $this->objUser= & $this->getObject('user','security');
        $this->objLink=&$this->newObject('link','htmlelements');
        $this->objDbBookmark=& $this->newObject('dbbookmark','kbookmark');
        $this->objDbGroup=& $this->newObject('dbgroup','kbookmark');
        $this->xbel=& $this->newObject('xbookmark','kbookmark');
    }
    
    /**
    * The standard dispatch method for the module.
    * he dispatch() method returns the name of
    * a page body template which will render the module output
    *
    * @return string The template to display
    */
    
    function dispatch ($action)
    {
        //set the layout template
        $this->setLayoutTemplate('user_layout_tpl.php');
        $userId=$this->objUser->userId();
        $folderId=$this->getParam('folderId');
        
        $folderId=$this->objDbGroup->getDefaultId($userId);
        // Get the action to execute and execute it
        switch ($action) {       
            case 'add':
                $userId=$this->objUser->userId();
                $filter="where creatorid='$userId'";
                $listFolders=$this->objDbGroup->getAll($filter);
                $this->setVarByRef('listFolders',$listFolders);
                return 'add_tpl.php';
            
            case 'save_add':
                $options=$this->getParam('options');
                $item=$this->getParam('item');
                $cancel=$this->getParam('cancel');
                if (isset($cancel)){
                    if ($item=='folder') {
                        return $this->nextAction('options','','');
                    } else {
                        return $this->nextAction('',array('folderId'=>$folderId));
                    }
                } else {
                    return $this->doParse($item,$options);
                }
                
            case 'save_edit':
                $item=$this->getParam('item');
                $folderId=$this->getParam('folderId');
                $cancel=$this->getParam('cancel');
                if ($item=='folder') {
                    if (isset($cancel)){
                        return $this->nextAction('options','','');
                    } else {
                        $this->parse4Update($item);
                        $title=$this->objLanguage->languageText('mod_bookmark_editsaved ','kbookmark');
                        return $this->nextAction('options',array('folderId'=>$folderId,'title'=>$title,'status'=>'success'));
                    }
                } else {
                     if (isset($cancel)){
                        return $this->nextAction('',array('folderId'=>$folderId));
                    } else {
                         $folderId=$this->getParam('parent');
                         $this->parse4Update($item);
                         $title=$this->objLanguage->languageText('mod_bookmark_editsaved ','kbookmark');
                         return $this->nextAction('',array('folderId'=>$folderId,'title'=>$title,'status'=>'success'));
                     }
                 }
                break;
            
            case 'edit':
                $item=$this->getParam('item');
                if ($item=='folder') {
                    $id=$this->getParam('folderId');
                } else {
                    $id=$this->getParam('id');
                }
                return $this->editItem($item,$id);

            case 'options':
                return $this->showFoldersOptions($userId);


            case 'setdefault':
                $folderId=$this->getParam('folderId');
                $this->setDefaultFolder($folderId);
                return $this->showFoldersOptions($userId);

            case 'delete':
                $item=$this->getParam('item');
                return $this->deleteItem($item);
                
            case 'openpage':
                $pageId=$this->getParam('id');
                $folderId=$this->getParam('folderId');
                $this->openPage($pageId);
                return $this->nextAction('',array('folderId'=>$folderId));
            
            case 'manage':
                $item=$this->getParam('item');
                $move=$this->getParam('move');
                $folderId=$this->getParam('folderId');
                if (isset($move)){
                    $operation=$this->getParam('move');
                } else {
                    $operation=$this->getParam('delete');
                }                
                $this->manage($operation, $item);
                
            case 'shared':
                 $sortOrder=$this->getParam('folderOrder');
				 $sortOption=$this->getParam('sortOrder');
                 if (isset($sortOrder)) {
                     $sortFilter="ORDER BY '$sortOrder' $sortOption";
                 } else {
                     $sortFilter="";
                 }
                 $filterFolder="WHERE isprivate='0'";
                 $listFolders=$this->objDbGroup->getAll($filterFolder);
				 $listFoldersWithBookmarks=$this->objDbGroup->getSharedWithBookmarks();
				 $listUsersWithBookmarks=$this->objDbGroup->getUsersWithSharedBookmarks();
                 $folderId=$this->getParam('folderId');
                 if (isset($folderId)){
                     $bookmarkFilter="WHERE isprivate='0' AND groupid='$folderId' ".$sortFilter;
                     $listFolderContent=$this->objDbBookmark->getAll($bookmarkFilter);
                 }
                 $this->setVarByRef('listFolderContent',$listFolderContent);
				 $this->setVarByRef('listFoldersWithBookmarks', $listFoldersWithBookmarks);
				 $this->setVarByRef('listUsersWithBookmarks', $listUsersWithBookmarks);
                 $this->setVarByRef('listFolders',$listFolders);
                 return "listshared_tpl.php";
                 
             case 'search':
                 $userId=$this->objUser->userId();
                 $searchTerm=$this->getParam('searchTerm');
                 $searchResults=$this->objDbBookmark->search($searchTerm,$userId);
                 $this->setVarByRef('searchResults',$searchResults);
                 $folderId=$this->getParam('folderId');
                 if (!isset($folderId)) {
                     $folderId=$this->getDefaultFolder($userId);
                 }
                 $filter="WHERE groupid='$folderId' and creatorid='$userId'";
                 $listFolderContent=$this->objDbBookmark->getAll($filter);
                 $this->setVarByRef('listFolderContent',$listFolderContent);
                 $filterFolder="where creatorid='$userId'";
                 $listFolders=$this->objDbGroup->getAll($filterFolder);
                 $this->setVarByRef('listFolders',$listFolders);
                 $this->setVarByRef('searchTerm',$searchTerm);
                 return 'list_tpl.php';
             case 'all':
			     $allBookmarks=$this->objDbBookmark->listAll();
				 $this->setVarByRef('allBookmarks',$allBookmarks);
				 return 'list_all_tpl.php';
				 break;
             case 'xbelparse':
                   $uploadXbel=$this->getParam('upload');
                   $viewXbel=$this->getParam('viewxbel');
                   if (isset($uploadXbel)) {
                         if (is_uploaded_file($_FILES['xbel']['tmp_name'])) {
                             if ($this->xbel->isAllowedFile($_FILES['xbel']['name'])=='.xml') {
                                 if ($this->xbel->xbelbookmark($_FILES['xbel']['tmp_name'])) {
                                     $this->xbel->xbelInsert();
                                     $status="success";
                                     $title=$this->objLanguage->languageText('mod_bookmark_xbeladded','kbookmark');
                                 } else {
                                     $status='failed';
                                     $title=$this->xbel->Error;
                                 }
                             } else {
                                 $status='failed';
                                 $title=$this->objLanguage->languageText('mod_bookmark_unrecognised','kbookmark');
                             }
                     } else {
                         $status='failed';
                         $title=$this->objLanguage->languageText('mod_bookmark_xbelnot','kbookmark');
                     }
                     return $this->nextAction('xbelparse',array('status'=>$status,'title'=>$title));
                    
                  // return 'list_tpl.php';
                   }  else {
                       if (isset($viewXbel)) {
                           $xbelOutput=$this->xbel->xbel();
                           $this->setVarByRef('xbelOutput',$xbelOutput);
                       }
                   }
                   return "xbel_tpl.php";
                   break;
            case 'viewxbel':
				$xbelOutput=$this->xbel->xbel($this->objUser->userId());
                $this->setVarByRef('xbelOutput',$xbelOutput);
				$this->setPageTemplate('no_page_tpl.php');

				//return "view_xbel.php";
/*
		$filename = "/home/bookmarkExport.xml";
		if (is_writable($filename)) {
		   // In our example we're opening $filename in append mode.
		   // The file pointer is at the bottom of the file hence
		   // that's where $somecontent will go when we fwrite() it.
		   if (!$handle = fopen($filename, 'w')) {
		         echo "Cannot open file ($filename)";
		         exit;
		   }
		   // Write $somecontent to our opened file.
		   if (fwrite($handle, $somecontent) === FALSE) {
		       echo "Cannot write to file ($filename)";
		       exit;
		   }
  
		   echo "Success, wrote ($somecontent) to file ($filename)";
  
		   fclose($handle);
		} else {
		   echo "The file $filename is not writable";
			}	

$data = "This is a new file entry!\n";  
$file = "/home/newfile.txt";   
if (!$file_handle = fopen($file,"w")) { echo "Cannot open file"; }  
if (!fwrite($file_handle, $data)) { echo "Cannot write to file"; }  
echo "You have successfully written data to $file";   
fclose($file_handle);   			
*/

				return "no_page_tpl.php"; 
				break;
			
		case 'allxbel':
			$xbelOutput=$this->xbel->xbel();
                	$this->setVarByRef('xbelOutput',$xbelOutput);
			return "view_xbel.php";
			break;

           case Null:
                $sortOrder=$this->getParam('folderOrder');
		$sortOption=$this->getParam('sortOrder');
                //$userId=$this->objUser->userId();
                 if (isset($sortOrder)) {
                     $sortFilter="ORDER BY '$sortOrder' $sortOption";
                 } else {
                     $sortFilter="";
                 }
                $folderId=$this->getParam('folderId');
                if (!isset($folderId)) {
                    $folderId=$this->objDbGroup->getDefaultId($userId);
                }
                $filter="WHERE groupid='$folderId' and creatorid='$userId' ".$sortFilter;
                $listFolderContent=$this->objDbBookmark->getAll($filter);
                $this->setVarByRef('listFolderContent',$listFolderContent);
                $filterFolder="where creatorid='$userId'";
                $listFolders=$this->objDbGroup->getAll($filterFolder);
                $this->setVarByRef('listFolders',$listFolders);
                return "list_tpl.php";

            default:
                $sortOrder=$this->getParam('folderOrder');
                //$userId=$this->objUser->userId();
                 if (isset($sortOrder)) {
                     $sortFilter="ORDER BY '$sortOrder'";
                 } else {
                     $sortFilter="";
                 }
                $folderId=$this->getParam('folderId');
                if (!isset($folderId)) {
                    $folderId=$this->objDbGroup->getDefaultId($userId);
                }
                $filter="WHERE groupid='$folderId' and creatorid='$userId' ".$sortFilter;
                $listFolderContent=$this->objDbBookmark->getAll($filter);
                $this->setVarByRef('listFolderContent',$listFolderContent);
                $filterFolder="where creatorid='$userId'";
                $listFolders=$this->objDbGroup->getAll($filterFolder);
                $this->setVarByRef('listFolders',$listFolders);
                return "list_tpl.php";
        }

    }
    
    /**
    * Function to return the name of a group or folder
    *
    * @param folderId string
    * return name
    */
    
    function folderByName($folderId)
    {
        $folderName=$this->objDbGroup->getRow('id',$folderId);
        return $folderName['title'];
    }
    
    /**
    * function to return the default user folder
    *
    * return the folder id
    */
    function getDefaultFolder($userId)
    {
        $list=$this->objDbGroup->getDefaultId($userId);
        return $list;
    }
    
    /**
    * Method to parse inputs and save them in the database
    *
    * @var item string
    *
    */
    
    function doParse($item,$options)
    {
        if ($item=='folder') {
            $title=$_POST['title'];
            $description=$_POST['description'];
            $isprivate=$_POST['private'];
            $datecreated=mktime();
            $datemodified='0000-00-00 00:00:00';
            $isdefault='0';
            $creatorid=$this->objUser->userId();
            $this->objDbGroup->insertSingle($title,$description,
            $isprivate,$datecreated,$datemodified,$isdefault,$creatorid);
            $titleLine=$this->objLanguage->languageText('mod_bookmark_foldersaved','kbookmark');
            if ($options=='options') {
                return $this->nextAction('options', array('status'=>'success','title'=>$titleLine));
            } else {
                return $this->nextAction('options', array('status'=>'success','title'=>$titleLine));
            }

        } else {
            $groupid=$_POST['parent'];
            $title=$_POST['title'];
            $url=$_POST['url'];
            $description=$_POST['description'];
            $datecreated=mktime();
            $isprivate=$_POST['private'];
            $datelastaccessed='0000-00-00 00:00:00';
            $creatorid=$this->objUser->userId();
            $visitcount='0';
            $datemodified='0000-00-00 00:00:00';
            $isdeleted='0';
            $this->objDbBookmark->insertSingle($groupid,$title, $url,
            $description, $datecreated, $isprivate, $datelastaccessed,
            $creatorid, $visitcount, $datemodified);
            $titleLine=$this->objLanguage->languageText('mod_bookmark_foldersaved','kbookmark');
            return $this->nextAction('view', array('status'=>'success', 'folderId'=>$groupid, 'title'=>$titleLine));
        }
    }
    
    /**
    * Method to parse inputs and save them in the database
    *
    * @var item string
    *
    */
    function parse4Update($item)
    {
        if ($item=='folder') {
            $title=$_POST['title'];
            $id=$_POST['id'];
            $description=$_POST['description'];
            $datemodified=strftime('%Y-%m-%d %H:%M:%S',mktime());
            $isprivate=$_POST['isprivate'];
            $this->objDbGroup->update('id',$id, array('title'=>$title,
            'description'=>$description, 'isprivate'=>$isprivate,
            'datemodified'=>$datemodified));
        } else {
            $groupid=$_POST['parent'];
            $id=$_POST['id'];
            $title=$_POST['title'];
            $url=$_POST['url'];
            $description=$_POST['description'];
            //$datecreated=mktime();
            $isprivate=$_POST['private'];
            //$datelastaccessed='0000-00-00 00:00:00';
            //$creatorid=$this->objUser->userId();
            $visitcount='0';
            $datemodified=strftime('%Y-%m-%d %H:%M:%S',mktime());
            //$isdeleted='0';
            $this->objDbBookmark->update('id',$id, array('groupid'=>$groupid,
            'title'=>$title, 'url'=>$url, 'description'=>$description,
            'isprivate'=>$isprivate, 'datemodified'=>$datemodified));
        }
    }
    /**
    * Method to set the default folder. The default folder is
    * displayed by default when a user accesses his/her folders
    *
    * @param folderId string
    *
    */
    
    function setDefaultFolder($folderId)
    {
        return $this->objDbGroup->setDefault($folderId);
    }
    
    /**
    * Method to display folders and folder options for editing and updating
    * @param userId string
    *
    * return folderoptions_tpl.php
    **/
    function showFoldersOptions($userId)
    {
        $filterFolder="where creatorid='$userId'";
        $listFolders=$this->objDbGroup->getAll($filterFolder);
        $this->setVarByRef('listFolders',$listFolders);
        return 'folderoptions_tpl.php';
    }
    
    /**
    * Method to delete a single entry from the database.
    * @param item string, indicates if the item to be deleted
    * is a folder or a bookmark. If its a folder, a check is done to ensure
    * that the folder is empty.
    */
    function deleteItem($item)
    {
        $title='';
        $status='success';
        $folderId=$this->getParam('folderId');
        if ($item=='folder'){
            
            $isEmpty=$this->objDbBookmark->isEmpty($folderId);
            $isRoot=$this->isRoot($this->folderByName($folderId));
            //check if is empty
            if (!$isEmpty) {
                $title=$this->objLanguage->languageText('mod_bookmarkgroup_notempty');
                $status='failed';
                //return $this->nextAction('options',array('status'=>'failed', 'title'=>$title));
            }
            if ($isRoot) {
                $title=$this->objLanguage->languageText('mod_bookmarkgroup_isroot');
                $status='failed';
                    //return $this->nextAction('options',array('status'=>'failed','title'=>$title));
            }
            if ($status!='failed'){
                $this->objDbGroup->delete('id',$folderId);
                $title=$this->objLanguage->languageText('word_deleted');
                $status='success';
                    //return $this->nextAction('options',array('status'=>'success','title'=>$title));
            }
            return $this->nextAction('options',array('status'=>$status,'title'=>$title));
        }
        if ($item=='bookmark') {
           $bkId=$this->getParam('id');
           $this->objDbBookmark->delete('id',$bkId);
           $title=$this->objLanguage->languageText('word_deleted');
           return $this->nextAction('',array('folderId'=>$folderId,'title'=>$title));
        }
    }
    
    /**
    * Method to open a bookmark on a new page
    *
    * returns string
    *
    */
    function openPage($pageId)
    {
        $this->objDbBookmark->updateVisitHit($pageId);
        $list=$this->objDbBookmark->getAll("where id='$pageId'");
        $this->setVarByRef('list',$list);
        foreach ($list as $line) {
            $url=$line['url'];
        }
        header("Location: ".$url);
        exit(0);
    }

    /**
    * Method to manage the actions: Move, Delete
    * Move: Moves the checked bookmark to another folder/group
    * Delete: deletes the bookmark
    *
    * @param $operation string operation to be carried out
    * @param @item stringbookmark or folder
    */
    
    function manage($operation, $item)
    {
            $status="";
        if ($item=='bookmark') {
            $bookmarks=$this->getParam('bookmark');
            if (count($bookmarks)>0) {
                if ($operation=='Delete') {
                    foreach ($bookmarks as $list) {
                        $this->objDbBookmark->delete('id',$list);
                    }
                    $title=$this->objLanguage->languageText('mod_bookmark_deleted','kbookmark');
                } else {
                    $folderTo=$this->getParam('parent');
                    foreach ($bookmarks as $list) {
                        $this->objDbBookmark->update('id',$list, array('groupid'=>$folderTo));
                    }
                    $title=$this->objLanguage->languageText('mod_bookmark_moved','kbookmark');
                }
            } else {
                 $title = $this->objLanguage->languageText('mod_bookmark_noselect','kbookmark');
                 $status="failed";
             }
            $folderId=$this->getParam('folderId');
            return $this->nextAction('',array('folderId'=>$folderId, 'status'=>$status,'title'=>$title));
        } else {
            $folders=$this->getParam('folders');
            if (count($folders)>0) {
                foreach ($folders as $list){
                    $isEmpty = $this->objDbBookmark->isEmpty($list);
                    if ($isEmpty) {
                        $this->objDbGroup->delete('id',$list);
                        $status="success";
                        $title=$this->objLanguage->languageText('mod_bookmark_deleted','kbookmark');
                    } else {
                        $title=$this->objLanguage->languageText('mod_bookmarkgroup_notempty','kbookmark');
                        $status="failed";
                    }
                }
                
            } else {
                $title = $this->objLanguage->languageText('mod_bookmark_noselect','kbookmark');
                $status="failed";
            }
        }
        return $this->nextAction('options',array('status'=>$status,'title'=>$title));
    }
    
    /**
    * Method to present form to edit the detaills of a bookmark
    * @param item string
    * @param id string
    *
    * return edit_tpl.php
    */
    function editItem($item,$id)
    {
        $userId=$this->objUser->userId();
        $filter="where id='$id'";
        if ($item=='folder'){
            $list=$this->objDbGroup->getAll($filter);
            $this->setVarByRef('listFolders',$list);
            return 'edit_tpl.php';
        } else {
            $filterFolder="where creatorid='$userId'";
            $listFolders=$this->objDbGroup->getAll($filterFolder);
            $this->setVarByRef('listFolders',$listFolders);
            $list=$this->objDbBookmark->getAll($filter);
            $this->setVarByRef('listContent',$list);
            return 'edit_tpl.php';
        }
    }
    
    /**
    * Function to check of a given folder is the root folder
    *
    * @param $folder
    * return boolean
    */
    
    function isRoot($folder)
    {
        if ($folder==$this->objLanguage->LanguageText('mod_bookmark_defaultfolder','kbookmark')) {
          return True;
        } else {
            return False;
        }
    }



}; //class
?>
