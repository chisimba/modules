<?php
/**
 * List all the main menu items of the bookmark module
 * @author James Kariuki Njenga
 * @version $Id$
 * @copyright 2005, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package context
*/

//Load all the required classes and objects
$this->loadClass('textarea','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass("dropdown","htmlelements");
$this->loadClass('checkbox','htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('geticon', 'htmlelements');
$this->loadClass('link','htmlelements');

//Table to list the links and icons of the bookmark menu
$objTableClass=$this->newObject('htmltable','htmlelements');
$objTableClass->width='99%';
$objTableClass->attributes=" border='0'";
$objTableClass->cellspacing='2';
$objTableClass->cellpadding='2';

$bookmarks="";
$action=$this->getParam('action');
$folderId=$this->getParam('folderId');
$titleLine=NULL;

$sortOrder=$this->getParam('sortOrder');
if (!(isset($sortOrder))){
    $sortOrder="ASC";
}

if (isset($folderId)){
    $titleLine=" >".$this->folderByName($folderId);
}
$status=$this->getParam('status');
$statusMsg=$this->getParam('title');
if (isset($listUsersWithBookmarks)) {
    $userCount=count($listUsersWithBookmarks);
    $folders="";
    if ($userCount>0) {
	    
        foreach($listUsersWithBookmarks as $lineOfUsers) {
		    $listFoldersWithBookmarks = $this->objDbGroup->getShared4User($lineOfUsers['creatorid']);
            $owner= $this->objUser->fullname($lineOfUsers['creatorid']);
	    $objTableFolders=$this->newObject('htmltable','htmlelements');
            $objTableFolders->width='99%';
            $objTableFolders->attributes=" border='0'";
            $objTableFolders->cellspacing='2';
            $objTableFolders->cellpadding='2';
			//$objTableFolders->addHeader($owner);
            $objTableFolders->startHeaderRow();
            $objTableFolders->addCell("<b>".$owner."</b>","",NULL,NULL,NULL,"colspan='2'");
            $objTableFolders->endHeaderRow();
            $objIcons=$this->newObject('geticon','htmlelements');
            $objIcons->setIcon('folder');
            $folderIcon=$objIcons->show();
	        if (isset($listFoldersWithBookmarks)){    
                //get a separate icon for open and closed folder
                $folderCount=count($listFolders);
                if ($folderCount>0) {
                    foreach($listFoldersWithBookmarks as $line) {
			$folderText=stripslashes($line['title']);
                        $visitFolder="<a href=\"".$this->uri(array('action'=>'shared','folderId'=>$line['id']))."\" class='".$objTableFolders->trClass."'>".$folderIcon.$folderText."</a>";//."[".$this->objUser->fullname($line['creatorid'])."]";
                        $objTableFolders->row_attributes=" onmouseover=\"this.className='tbl_ruler';\" onmouseout=\"this.className='".$objTableFolders->trClass."'; \"";
                        $objTableFolders->startRow();
                        $objTableFolders->addCell($visitFolder,"",NULL,NULL,NULL,"colspan='2'");
                        $objTableFolders->endRow();
                    }
                } else {
                    $noSharedFolders="<span class=\"noRecordsMessage\">". $this->objLanguage->LanguageText('mod_bookmark_noneshared','kbookmark') ."</span>";
                    $objTableFolders->startRow();
                    $objTableFolders->addCell($noSharedFolders,"",NULL,NULL,NULL,"");
                    $objTableFolders->endRow();
                }
            }
			$folders.=$objTableFolders->show();
        }
	}
}	

$folderTitle=$this->objLanguage->languageText('mod_bookmark_sharedfolders','kbookmark');

$folderFieldset = $this->getObject('fieldset', 'htmlelements');
$folderFieldset->setLegend($folderTitle);
$folderFieldset->addContent($folders);
$folderOutput= $folderFieldset->show();

if (isset($listFolderContent)) {

    $bkForm=new form('bkForm');

    $myFormAction=$this->uri(array('action'=>'manage', 'item'=>'bookmark','folderId'=>$folderId));
    $bkForm->setAction($myFormAction);

	$folderId=$this->getParam('folderId');
    $sortOrder=(($sortOrder=="ASC")?"DESC":"ASC");
    $titleLink="<a href='".$this->uri(array('action'=>$action,'folderId'=>$folderId,'folderOrder'=>'title','sortOrder'=>$sortOrder))."' >".$this->objLanguage->LanguageText('word_title')."</a>";
    $dateAccessedLink="<a href='".$this->uri(array('action'=>$action,'folderId'=>$folderId,'folderOrder'=>'datelastaccessed','sortOrder'=>$sortOrder))."' >".$this->objLanguage->LanguageText('phrase_dateaccessed')."</a>";
    $hitsLink="<a href='".$this->uri(array('action'=>$action,'folderId'=>$folderId,'folderOrder'=>'visitcount','sortOrder'=>$sortOrder))."' >".$this->objLanguage->LanguageText('word_hits')."</a>";

    $row=array('',$titleLink,$dateAccessedLink,$hitsLink,'','');
    $objTableClass->startRow();
    $objTableClass->addCell($titleLink,"", Null, 'center', 'heading', "");
    $objTableClass->addCell($hitsLink,"", Null, 'center', 'heading', "");
    $objTableClass->addCell($dateAccessedLink,"", Null, 'center', 'heading', "");
    $objTableClass->endRow();
    $word_delete=$this->objLanguage->LanguageText('word_delete');
    
    $count=count($listFolderContent);
    if ($count>0) {
        //Looping through all the folder's content
        foreach ($listFolderContent as $line){

             $newLink=new link(($this->uri(array( 'module'=> 'bookmarks', 'action' => 'openpage', 'id' => $line['id'],'folderId'=>$line['groupid']))));
             $newLink->link=stripslashes($line['title']);
             $newLink->title=$line['url'];
             $newLink->target='_blank';
             $bkLink=$newLink->show().' - '.stripslashes($line['description']);
             //$visitLink="<a href=\"".$this->uri(array('action'=>'visit','id'=>$line['id']))."\" class='".$objTableClass->trClass."'>";
             
             $objTableClass->row_attributes=" onmouseover=\"this.className='tbl_ruler';\" onmouseout=\"this.className='".$objTableClass->trClass."'; \"";
             $objTableClass->startRow();
             $objTableClass->addCell($bkLink,"50%", NULL, NULL, NULL,"");
             $objTableClass->addCell($line['visitcount'],"20", NULL, NULL, NULL,"");
             if (($line['datelastaccessed'])=='0000-00-00 00:00:00'){
			    $dateAccessed=$this->objLanguage->LanguageText('mod_bookmarks_notaccessed','kbookmark');
			 } else {
			    $dateAccessed= $line['datelastaccessed'];
			 }   
             $objTableClass->addCell($dateAccessed,"150", NULL, NULL, NULL,"");
             $objTableClass->endRow();
         }
    } else {
        $noBookmark ="<span class=\"noRecordsMessage\">". $this->objLanguage->LanguageText('mod_bookmarks_notfound','kbookmark') ."</span>";        
        $objTableClass->startRow();
        $objTableClass->addCell($noBookmark,"",NULL,NULL,NULL,"colspan='5'");
        $objTableClass->endRow();
    }
    $bookmarks=$bkForm->addToForm($objTableClass->show());
    $bookmarks=$bkForm->show();
}

//table of the final layout frame
$objTableFrame=$this->newObject('htmltable','htmlelements');
$objTableFrame->width='99%';
$objTableFrame->align='top';

$objTableFrame->startRow();
$objTableFrame->addCell($folderOutput,"20%","top",NULL,'',NULL);
$objTableFrame->addCell($bookmarks."",NULL,"top",NULL,'',NULL);
$objTableFrame->endRow();

//planning and formating for output
$this->header = new htmlheading();
$this->header->type=1;
$this->header->str=$this->objLanguage->languageText('mod_bookmark_sharedbookmarks','kbookmark').$titleLine;
echo $this->header->show();
echo "<span class='confirm'>".$statusMsg."</span>";

$mainFrame=$objTableFrame->show();
echo $mainFrame;

$link = $this->newObject('link','htmlelements');
$link->href = $this->uri(array('action'=>''));
$link->link=$this->objLanguage->languageText("word_back");
echo $link->show();

?>
