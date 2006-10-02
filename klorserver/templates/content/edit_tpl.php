<?
$this->loadClass('form','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$form =& new form('editForm',$this->uri(array('action'=>'editconfirm','mode'=>$mode,'filetype'=>$filetype,'id'=>$id)));
if ($mode == 'standalone') {
    switch($filetype){
    	case 'file': 
    		$filetypestring = 'Documents';
    		break;
    	default:
            $filetypestring = ucfirst($filetype);
    } // switch
    $form->addToForm("<h3>".$objLanguage->languageText('mod_groupdocuments_edit')." - ".$filetypestring."</h3>");
}

$objTable =& $this->newObject('htmltable','htmlelements');
$objTable->cellpadding = "5";
$objTable->cellspacing = "5";
$row = array($objLanguage->languageText('mod_groupdocuments_file')." : ",$name);
$objTable->addRow($row, 'even');
$textinput = new textinput('title',$title,null,50);
$row = array($objLanguage->languageText('mod_groupdocuments_title')." : ",$textinput->show());
$objTable->addRow($row, 'even');
$textarea = new textarea('description',$description, 4, 50);
$row = array($objLanguage->languageText('mod_groupdocuments_description')." : ", $textarea->show());
$objTable->addRow($row, 'even');
$textinput = new textinput('version',$version);
$row = array($objLanguage->languageText('mod_groupdocuments_version')." : ", $textinput->show());
$objTable->addRow($row, 'even');
//$form->addToForm("<br/>".$objLanguage->languageText('mod_contextview_file')." : ");
//$form->addToForm("<input type='file' name='file'>");
$row = array("<input type='submit' class='button' value='".$this->objLanguage->languageText('word_submit')."'>", "&nbsp;");
$objTable->addRow($row, 'even');
$form->addToForm($objTable->show());
echo $form->show();

?>
