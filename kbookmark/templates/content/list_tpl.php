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
$this->loadClass('form','htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass("dropdown","htmlelements");
$this->loadClass('checkbox','htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('geticon', 'htmlelements');
$this->loadClass('link','htmlelements');
$this->loadClass("hiddeninput", "htmlelements");

//Table to list the links and icons of the bookmark menu
$objTableClass=$this->newObject('htmltable','htmlelements');
$objTableClass->width='99%';
$objTableClass->attributes=" border='0'";
$objTableClass->cellspacing='2';
$objTableClass->cellpadding='2';

$folderCount=0;
$sortOrder=$this->getParam('sortOrder');
if (!(isset($sortOrder))){
    $sortOrder="ASC";
}
//$newBookmark="";

$objIcons=$this->newObject('geticon','htmlelements');
$objIcons->setIcon('add');
$objIcons->alt=$this->objLanguage->languageText('mod_bookmark_addbookmark','kbookmark');
$newBookmarkIcon=$objIcons->show();

$objIcons->setIcon('add');
$objIcons->alt=$this->objLanguage->languageText('mod_bookmark_addfolder','kbookmark');
$newFolderIcon=$objIcons->show();

$action=$this->getParam('action');
$folderId=$this->getParam('folderId');
$titleLine='';
$title='';

$icon = $this->getObject('geticon', 'htmlelements');


if (isset($listFolders)){
    if (count($listFolders)>0){
    $icon->setIcon('add');
    $icon->title = $objLanguage->code2Txt('mod_bookmark_addbookmark','kbookmark');
    $icon->alt = $objLanguage->code2Txt('mod_bookmark_addbookmark','kbookmark');
    $addLink = new link ($this->uri(array('action'=>'add','folderId'=>$folderId,'item'=>'bookmark')));
    $addLink->link = $icon->show();
    $newBookmark=$addLink->show();
    //    $newBookmark="<a href=\"".$this->uri(array('action'=>'add','folderId'=>$folderId,'item'=>'bookmark'))."\" >".$newBookmarkIcon."</a>";
    }
}

if (isset($folderId)){
    $titleLine=" >".$this->folderByName($folderId);
}
$status=$this->getParam('status');
$statusMsg=$this->getParam('title');

//show all the users folders
$searchTitle=$this->objLanguage->languageText('mod_bookmark_search','kbookmark');
$bkSearchForm=new form('bkSearchForm');
$mySearchAction=$this->uri(array('action'=>'search'));
$bkSearchForm->setAction($mySearchAction);

$searchLabel=new label('', 'input_searchTerm');

//search term
$bkObject = new textinput('searchTerm');
$bkObject->size=30;
$bkObject->fldType="text";
$bkSearchForm->addToForm($searchLabel->show());
$bkSearchForm->addToForm($bkObject->show());
$bkFolderId = new textinput('folderId');
$bkFolderId->size=30;
$bkFolderId->fldType="hidden";
$bkFolderId->setValue($folderId);
$bkSearchForm->addToForm($bkFolderId->show());
$objButtonSearch = new button('search');
$objButtonSearch->setToSubmit();
$objButtonSearch->setValue('Search');
$bkSearchForm->addToForm("".$objButtonSearch->show());
$searchForm=$bkSearchForm->show();

$searchFieldset = $this->getObject('fieldset', 'htmlelements');
$searchFieldset->setLegend($searchTitle);
$searchFieldset->addContent($searchForm);
$searchOutput= $searchFieldset->show();

$xbelTitle=$this->objLanguage->languageText('mod_bookmark_xbel','kbookmark');
$xbelUpload=$this->objLanguage->languageText('mod_bookmark_xbelupload','kbookmark');
$xbelView=$this->objLanguage->languageText('mod_bookmark_xbelmanage','kbookmark');


//Create the link Object
$link = $this->newObject('link','htmlelements');
$link->href = $this->uri(array('action'=>'xbelparse'));
$link->link=$this->objLanguage->languageText('mod_bookmark_in_xbel','kbookmark');

$xbelFieldset = $this->getObject('fieldset', 'htmlelements');
$xbelFieldset->setLegend($xbelView);
$xbelFieldset->addContent($link->show());
$xbelOutput= $xbelFieldset->show();

if (isset($listFolderContent)) {

    $bkForm=new form('bkForm');

    $myFormAction=$this->uri(array('action'=>'manage', 'item'=>'bookmark','folderId'=>$folderId));
    $bkForm->setAction($myFormAction);
    
    $objIcons->setIcon('delete');
    $objIcons->alt=$this->objLanguage->LanguageText('word_delete','Delete');
    $deleteIcon=$objIcons->show();
    
    $objIcons->setIcon('edit');
    $objIcons->alt=$this->objLanguage->LanguageText('word_edit','Edit');
    $editIcon=$objIcons->show();
    
    $objIcons->setIcon('move');
    $objIcons->alt=$this->objLanguage->LanguageText('word_move','Move');
    $moveIcon=$objIcons->show();

    $objButtonDelete = new button('delete');
	$objButtonDelete->setToSubmit();
	$objButtonDelete->setValue('Delete');

	//create and display a button
	$objButtonMove = new button('move');
	$objButtonMove->setToSubmit();

	$objButtonMove->setValue('Move');
	
	$move="";
	
	$folderId=$this->getParam('folderId');

    if (!isset($folderId)) {
        $folderId=$this->getDefaultFolder($this->objUser->userId());

    }
    if (count($listFolders)>1) {
        $objDropdown = new dropdown("parent");
        foreach ($listFolders as $line){
           if (!($line['id']==$folderId)) {
                $objDropdown->addOption($line['id'],$line["title"]);
            }
        }
        $move=$objDropdown->show()."  ".$objButtonMove->show();
    }

    $count=count($listFolderContent);
	
    if ($count>0) {
	$sortOrder=(($sortOrder=="ASC")?"DESC":"ASC");
    $titleLink="<a href='".$this->uri(array('action'=>$action,'folderId'=>$folderId,'folderOrder'=>'title','sortOrder'=>$sortOrder))."' >".$this->objLanguage->LanguageText('word_title')."</a>";
    $dateCreatedLink="<a href='".$this->uri(array('action'=>$action,'folderId'=>$folderId,'folderOrder'=>'datecreated','sortOrder'=>$sortOrder))."' >".$this->objLanguage->LanguageText('word_date_created')."</a>";
    $dateAccessedLink="<a href='".$this->uri(array('action'=>$action,'folderId'=>$folderId,'folderOrder'=>'datelastaccessed','sortOrder'=>$sortOrder))."' >".$this->objLanguage->LanguageText('word_date_accessed')."</a>";
    $hitsLink="<a href='".$this->uri(array('action'=>$action,'folderId'=>$folderId,'folderOrder'=>'visitcount','sortOrder'=>$sortOrder))."' >".$this->objLanguage->LanguageText('word_hits')."</a>";

    $row=array('',$titleLink,$dateCreatedLink,$dateAccessedLink,$hitsLink,'','');
    $objTableClass->startRow();
    $objTableClass->addCell("","", Null, 'center', 'heading', "");
    $objTableClass->addCell($titleLink,"", Null, 'center', 'heading', "");
    $objTableClass->addCell($hitsLink,"", Null, 'center', 'heading', "");
    $objTableClass->addCell($dateAccessedLink,"", Null, 'center', 'heading', "");
    $objTableClass->addCell("","", Null, 'center', 'heading', "");
    $objTableClass->addCell("","", Null, 'center', 'heading', "");
    $objTableClass->endRow();
    $word_delete=$this->objLanguage->LanguageText('word_delete','Delete');

    //Looping through all the folder's content
        foreach ($listFolderContent as $line)
        {
             $newLink=new link(($this->uri(array( 'module'=> 'bookmarks', 'action' => 'openpage', 'id' => $line['id'],'folderId'=>$line['groupId']))));
             $newLink->link=htmlentities(stripslashes($line['title']));
             $newLink->title=$line['url'];
             $newLink->target='_blank';
             $bkLink=$newLink->show().' - '.htmlentities(stripslashes($line['description']));
             $visitLink="<a href=\"".$this->uri(array('action'=>'visit','id'=>$line['id']))."\" class='".$objTableClass->trClass."'>";
             $editLink="<a href=\"".$this->uri(array('action'=>'edit','id'=>$line['id'],'item'=>'bookmark'))."\" class='".$objTableClass->trClass."'>".$editIcon."</a>";
             $deleteLink="<a href=\"".$this->uri(array('action'=>'delete','id'=>$line['id'],'folderId'=>$line['groupId'],'item'=>'bookmark'))."\" class='".$objTableClass->trClass."'>".$deleteIcon."</a>";
             $objTableClass->row_attributes=" onmouseover=\"this.className='tbl_ruler';\" onmouseout=\"this.className='".$objTableClass->trClass."'; \"";
             $objTableClass->startRow();
             $objTableClass->addCell("<input type='checkbox' name='bookmark[]' value='".$line['id']."'></input>","20", NULL, NULL, NULL,"");
             $objTableClass->addCell('&nbsp;'.$bkLink."","50%", NULL, NULL, NULL,"");
             $objTableClass->addCell($line['visitcount'],"20", NULL, NULL, NULL,"");
			 if (($line['datelastaccessed'])=='0000-00-00 00:00:00'){
			    $dateAccessed=$this->objLanguage->LanguageText('mod_bookmarks_notaccessed','kbookmark');
			 } else {
			    $dateAccessed= $line['datelastaccessed'];
			 }   
             $objTableClass->addCell($dateAccessed,"150", NULL, NULL, NULL,"");
                         
             $objTableClass->addCell($editLink,"20", NULL, NULL, NULL,"");
             $objTableClass->addCell($deleteLink,"20", NULL, NULL, NULL,"");

             $objTableClass->endRow();
         }
         $objTableClass->startRow();
	     $objTableClass->addCell($move."    ".$objButtonDelete->show(), "", Null, 'center', '', "colspan='6'");
	     $objTableClass->endRow();
    } else {
        $noBookmark ="<span class=\"noRecordsMessage\">". $this->objLanguage->LanguageText('mod_bookmarks_notfound','kbookmark') ."</span>";
        //$noBookmark=$this->objLanguage->LanguageText('mod_bookmarks_notfound');
        $objTableClass->startRow();
        $objTableClass->addCell($noBookmark,"",NULL,NULL,NULL,"colspan='5'");
        $objTableClass->endRow();
    }

}

//option to show the folders only
if (isset($listFolders)){
    $objTableFolders=$this->newObject('htmltable','htmlelements');
    $objTableFolders->width='99%';
    $objTableFolders->attributes=" border='0'";
    $objTableFolders->cellspacing='2';
    $objTableFolders->cellpadding='2';
    
    $objIcons=$this->newObject('geticon','htmlelements');
    $objIcons->setIcon('folder');
    $folderIcon=$objIcons->show();
    $folderAdd="<a href='".$this->uri(array('action'=>'add','item'=>'folder'))."'>".$newFolderIcon."</a>";
    //get a separate icon for open and closed folder
    $folderTitle=$this->objLanguage->languageText('mod_bookmark_myfolders','kbookmark');
    $folderOptionLink="<a href='".$this->uri(array('action'=>'options'))."'>".$this->objLanguage->LanguageText('mod_bookmarks_managefolders','kbookmark')."</a>";
    $sharedFolderLink="<a href='".$this->uri(array('action'=>'shared'))."'>".$this->objLanguage->LanguageText('mod_bookmarks_viewshared','kbookmark')."</a>";

    //output as a drop down list
    $foldersDropdown= new dropdown('folderId');
    

    foreach($listFolders as $line) {
        $folderCount=count($listFolders);
        if ($folderCount>0) {
            if ($line['title']=='_root') {
                $folderText="root";
            } else {
                $folderText=$line['title'];
            }

            $foldersDropdown->addOption($line["id"],htmlentities(stripslashes($line["title"])));
            if (isset($folderId)) {
                $foldersDropdown->setSelected($folderId);
            }
        }
    }
    $objTableFolders->startRow();
    $objTableFolders->addCell($folderOptionLink,"50%",NULL,NULL,NULL,"");
    $objTableFolders->endRow();


    $userId=$this->objUser->userId();
    if (($folderCount==0) || ($this->objDbGroup->getDefaultId($userId)==Null)) {
        
        $this->objDbGroup->createDefaultFolder($userId);        
        
    }
}

//form to output the folders as a dropdown
$form = new form("changefolders", $this->uri(array('module'=>'kbookmark','action'=>'viewContent')));

$moduleHiddenInput = new hiddeninput('module', 'kbookmark');
$actionHiddenInput = new hiddeninput('action', 'viewContent');

$form->addToForm($moduleHiddenInput->show());
$form->addToForm($actionHiddenInput->show());

$form->setDisplayType(3);

$form->method = 'GET';

$form->addToForm($foldersDropdown);

$form->addToForm("&nbsp;");
$button = new button("submit", $objLanguage->languageText("word_go"));
$button->setToSubmit();
$form->addToForm($button);

$folders=$objTableFolders->show();
               
$foldersFieldset = $this->getObject('fieldset', 'htmlelements');
$foldersFieldset->setLegend($folderTitle);
$foldersFieldset->addContent($form->show() . $folders);
$foldersOutput= $foldersFieldset->show();

$sharedTitle=$this->objLanguage->languageText('mod_bookmark_sharedfolders','kbookmark');

$sharedFieldset = $this->getObject('fieldset', 'htmlelements');
$sharedFieldset->setLegend($sharedTitle);
$sharedFieldset->addContent($sharedFolderLink);
$sharedOutput= $sharedFieldset->show();

$bookmarks=$bkForm->addToForm($objTableClass->show());
$bookmarks=$bkForm->show();
//table of the final layout frame
$objTableFrame=$this->newObject('htmltable','htmlelements');
$objTableFrame->width='99%';
$objTableFrame->align='top';

$objTableFrame->startRow();
$objTableFrame->addCell($foldersOutput."<br />".$sharedOutput."<br />".$xbelOutput."<br />".$searchOutput,"20%","top",NULL,'',NULL);
$objTableFrame->addCell($bookmarks,NULL,"top",NULL,'',NULL);
$objTableFrame->endRow();

//planning and formating for output
$this->header = new htmlheading();
$this->header->type=1;
$this->header->str=$this->objLanguage->languageText('mod_bookmark_bookmarkfolders','kbookmark').$titleLine.$newBookmark;
echo $this->header->show();
if (isset($statusMsg)){
    echo "<span class='confirm'>".$statusMsg."</span>";
}
if (!isset($searchResults) || count($searchResults) == 0){
    echo "<span>&nbsp;</span>";
}
$mainFrame=$objTableFrame->show();
echo $mainFrame;

if (isset($searchResults)){
    $objSearchTable=$this->newObject('htmltable','htmlelements');
    $objSearchTable->width='99%';
    $objSearchTable->attributes=" border='0'";
    $objSearchTable->cellspacing='2';
    $objSearchTable->cellpadding='2';
    
    $titleLink=$this->objLanguage->LanguageText('word_title');
    $dateAccessedLink=$this->objLanguage->LanguageText('word_date_accessed');
    $hitsLink=$this->objLanguage->LanguageText('word_hits');
    $ownerLink=$this->objLanguage->LanguageText('word_owner');

    $row=array('',$titleLink,$dateAccessedLink,$hitsLink,'','');
    $objSearchTable->startRow();

    $objSearchTable->addCell($titleLink,"", Null, 'center', 'heading', "");
    $objSearchTable->addCell($hitsLink,"", Null, 'center', 'heading', "");
    $objSearchTable->addCell($dateAccessedLink,"", Null, 'center', 'heading', "");
    $objSearchTable->addCell($ownerLink,"", Null, 'center', 'heading', "");
    $objSearchTable->endRow();
    $word_delete=$this->objLanguage->LanguageText('word_delete','Delete');
    $title=$this->objLanguage->languageText('mod_bookmark_searchresults','kbookmark');
    $numCount=count($searchResults);
    if ((count($searchResults))>0) {
        foreach ($searchResults as $line) {
            
            $newLink=new link(($this->uri(array( 'module'=> 'bookmarks', 'action' => 'openpage', 'id' => $line['id'],'folderId'=>$line['groupId']))));
            $newLink->link=htmlentities(stripslashes($line['title']));
            $newLink->title=$line['url'];
            $newLink->target='_blank';
            $bkLink=$newLink->show().' - '.htmlentities(stripslashes($line['description']));
            $owner=$this->objUser->fullname($line['creatorid']);
            $visitLink="<a href=\"".$this->uri(array('action'=>'visit','id'=>$line['id']))."\" class='".$objTableClass->trClass."'>";
            $objSearchTable->row_attributes=" onmouseover=\"this.className='tbl_ruler';\" onmouseout=\"this.className='".$objTableClass->trClass."'; \"";
            $objSearchTable->startRow();
            $objSearchTable->addCell('&nbsp;'.$bkLink."","50%", NULL, NULL, NULL,"");
            $objSearchTable->addCell($line['visitcount'],"20", NULL, NULL, NULL,"");
            $objSearchTable->addCell($line['datelastaccessed'],"150", NULL, NULL, NULL,"");
            $objSearchTable->addCell($owner,"150", NULL, NULL, NULL,"");

            $objSearchTable->endRow();
         }
    } else {
        $noBookmark ="<span class=\"noRecordsMessage\">". $this->objLanguage->LanguageText('mod_bookmark_noresults','kbookmark') ." <B>".$searchTerm."</B></span>";
        $objSearchTable->startRow();
        $objSearchTable->addCell($noBookmark,"",NULL,NULL,NULL,"colspan='5'");
        $objSearchTable->endRow();
    }
    
    $legendString = $this->objLanguage->languageText('mod_bookmark_searchresults','kbookmark').":<b>".$searchTerm.": </b>".$numCount;
    $searchFieldset = $this->getObject('fieldset', 'htmlelements');
    $searchFieldset->setLegend($legendString);
    $searchFieldset->addContent($objSearchTable->show());
    $searchOutput= $searchFieldset->show();
    echo $searchOutput;
}
if (isset($xbookmark))
{
  print_r($xbookmark);
  print_r($folder);
}

?>
