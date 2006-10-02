<?
//echo $this->getJavascriptFile('TreeMenu.js','tree');
$objConfig =& $this->getObject('config', 'config');
$siteRoot = $objConfig->siteRoot();
//$userfiles = $objConfig->userfiles();
//$siteRootPath = $objConfig->siteRootPath();
//$contentPath = $objConfig->contentPath();
//echo "[$siteRoot]";
//echo "[$userfiles]";
//echo "[".$mode."]";
//echo "[".$filetype."]";
//echo "[".$contextCode."]";
//$contextCode = 'comsys';
//if ($contextCode == NULL) {
    //die($objLanguage->languageText('mod_contextview_notincontext'));
//}
//echo "FILETYPE==$filetype<br/>";
$this->loadClass('form','htmlelements');
$this->loadClass('dropdown','htmlelements');
$form =& new form('filetypeForm','index.php');//$this->uri(array('action'=>'changefiletype', 'mode'=>$mode)
$form->method="get";
$form->addToForm("<input type=\"hidden\" name=\"module\" value=\"userfiles\">");
$form->addToForm("<input type=\"hidden\" name=\"action\" value=\"changefiletype\">");
$form->addToForm("<input type=\"hidden\" name=\"mode\" value=\"{$mode}\">");

echo $form->show();
if (
        $filetype == 'images'
        || $filetype == 'file'
        || $filetype == 'media'
        || $filetype == 'flash'
    ) {

    $objFiles = & $this->newObject('dbgroupdocuments','fileview');
    $data= $objFiles->getFiles($this->objUser->userId(), $filetype);
    if (count($data)==0){ // if the file has been deleted
        $left = "";
    }
    else {
        $table = & $this->newObject('htmltable', 'htmlelements');
        $table->cellspacing = 0;
        $table->cellpadding = 2;
        //$table->height=200;
        //$table->width=200;
        $count = 0;
        foreach ($data as $line)
        {
            $id=$line['id'];
            $name=$line['name'];
            $size=$line['size'];
            $type=$line['datatype'];
            $category=$line['category'];
            if (true) {
            	$oddOrEven = ($count == 0) ? 'odd' : 'even';
            	$table->startRow();
                //$formname = $this->getParam('formname');
                //$textareaname = $this->getParam('textareaname');
                $objLink = & $this->newObject('link', 'htmlelements');
            	$objLink->href = $this->uri(array(
                    'action' => 'list',
                    'id' =>$id,
                    'mode'=>$mode,
                    'filetype'=>$filetype
                )); //, 'formname'=>$formname, 'textareaname'=>$textareaname
            	$objLink->link = $name;
            	$table->addCell($objLink->show(),null,null,null,$oddOrEven);
                $objLink2 =& $this->newObject('link', 'htmlelements');
            	$objLink2->href = $this->uri(array('action' => 'editfileid', 'id' =>$id, 'mode'=>$mode, 'filetype'=>$filetype));
            	$objLink2->link = "Edit";
                $objLink2->cssClass = "pseudobutton";
            	$table->addCell($objLink2->show(),null,null,null,$oddOrEven);
                $objLink3 =& $this->newObject('link', 'htmlelements');
            	$objLink3->href = $this->uri(array('action' => 'deletefileid', 'id' =>$id, 'mode'=>$mode, 'filetype'=>$filetype));
            	$objLink3->link = "Delete";
                $objLink3->cssClass = "pseudobutton";
            	$table->addCell($objLink3->show(),null,null,null,$oddOrEven);
                $table->endRow();

            	$count = ($count == 0) ? 1:0;

            }
        }
        $left = $table->show();
    }
}

$this->leftNav = &$this->newObject('layer','htmlelements');
//$this->leftNav->id = "left";
$this->leftNav->overflow = 'auto';
$this->leftNav->height='150px';
$this->leftNav->width='140px';
$this->leftNav->postition = 'absolute';
$this->leftNav->addToStr($left);
echo $this->leftNav->show();

?>