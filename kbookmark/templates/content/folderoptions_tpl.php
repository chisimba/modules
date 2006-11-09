<?php
/**
 * List all folders and options to manage them
 * @author James Kariuki Njenga
 * @version $Id: zdd_tpl.php,v 1.0 2005/02/08 06:26:45 karitz
 * @copyright 2005, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package context
*/

//load all classes that will be used

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


$titleLine=$this->objLanguage->languageText('mod_bookmark_folderoptions','kbookmark');
$statusMsg="";

$status=$this->getParam('status');
if (isset($status)){
   $statusMsg=$this->getParam('title');
}

$objIcons=$this->newObject('geticon','htmlelements');
$objIcons->setIcon('folder');
$folderIcon=$objIcons->show();

$objIcons=$this->newObject('geticon','htmlelements');
$objIcons->setIcon('edit');
$editIcon=$objIcons->show();

$objIcons=$this->newObject('geticon','htmlelements');
$objIcons->setIcon('delete');
$deleteIcon=$objIcons->show();

$objIcons=$this->newObject('geticon','htmlelements');
$objIcons->setIcon('create_folder');
$objIcons->alt=$this->objLanguage->languageText('word_add','Add');
$newIcon=$objIcons->show();

$objButton= new button('delete');
$objButton->setToSubmit();
$objButton->setValue($this->objLanguage->languageText('mod_bookmark_deleteselected','kbookmark'));
$deleteButton = $objButton->show();



$folderAdd="<a href='".$this->uri(array('action'=>'add','item'=>'folder','options'=>'options'))."'>".$newIcon."</a>";

$folderForm= new form('folderForm');

$objRadio=new radio ('default');

$title=$this->objLanguage->languageText('word_title');
$default=$this->objLanguage->languageText('word_Home');
$edit=$this->objLanguage->languageText('word_edit');
$delete=$this->objLanguage->languageText('word_delete');

$folderForm= new form('folderForm');
$folderAction=$this->uri(array('action'=>'manage', 'item'=>'folder'));
$folderForm->setAction($folderAction);

$objTableFolders=$this->newObject('htmltable','htmlelements');
$objTableFolders->width='99%';
$objTableFolders->attributes=" border='0'";
$objTableFolders->cellspacing='2';
$objTableFolders->cellpadding='2';

$row=array('',$title,$edit,$delete);
$objTableFolders->addHeader($row,'bookmarkHeading');

if (isset($listFolders))
{

    foreach($listFolders as $line) {
        $folderCount=count($listFolders);
        if ($folderCount>0) {
            if ($line['isdefault']=='1') {
                $folderText=$line['title']."*";
            } else {
                $folderText=$line['title'];
            }
            $visitFolder="<a href=\"".$this->uri(array('action'=>'viewContent','folderId'=>$line['id']))."\" class='".$objTableFolders->trClass."'>".$folderIcon.$folderText."</a>"."-".$line['description'];           
            $editLink="<a href=\"".$this->uri(array('action'=>'edit','folderId'=>$line['id'],'item'=>'folder'))."\" class='".$objTableFolders->trClass."'>".$editIcon."</a>";
            $deleteLink="<a href=\"".$this->uri(array('action'=>'delete','folderId'=>$line['id'],'item'=>'folder'))."\" class='".$objTableFolders->trClass."'>".$deleteIcon."</a>";
            $objTableFolders->row_attributes=" onmouseover=\"this.className='tbl_ruler';\" onmouseout=\"this.className='".$objTableFolders->trClass."'; \"";
            $objTableFolders->startRow();
            $objTableFolders->addCell("<input type='checkbox' name='folders[]' value='".$line['id']."'></input>","20", NULL, NULL, NULL,"");
            $objTableFolders->addCell($visitFolder,"",NULL,NULL,NULL,"");
            $objTableFolders->addCell($editLink,"",NULL,NULL,NULL,"");
            $objTableFolders->addCell($deleteLink,"",NULL,NULL,NULL,"");
            $objTableFolders->endRow();
        }
    }

    $objTableFolders->startRow();
    $objTableFolders->addCell($deleteButton,"",NULL,NULL, NULL,"colspan = '5'");    
    $objTableFolders->endRow();

    $homeText=$this->objLanguage->LanguageText('mod_bookmark_homefolder','kbookmark');
    $objTableFolders->startRow();
    $objTableFolders->addCell("* ". $homeText,"",NULL,NULL, NULL,"colspan = '5'");
    $objTableFolders->endRow();
}
$this->header = new htmlheading();
$this->header->type=1;
$this->header->str=$this->objLanguage->languageText('mod_bookmark_bookmarkfolders','kbookmark')." > ".$titleLine.$folderAdd."<BR />";
echo $this->header->show();
echo "<span class='confirm'>".$statusMsg."</span>";
$folderForm->addToForm($objTableFolders->show());

$link = $this->newObject('link','htmlelements');
$link->href = $this->uri(array('action'=>''));
$link->link=$this->objLanguage->languageText("word_back");
$folderForm->addToForm($link->show());
echo $folderForm->show();



?>
