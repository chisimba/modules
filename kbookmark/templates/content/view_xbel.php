<?  

$header = $this->getObject('htmlheading', 'htmlelements');
$header->str = $this->objLanguage->languageText('mod_bookmark_xbelexporttitle','kbookmark');
$header->type = 1;

echo $header->show();

$textArea = $this->getObject('textarea', 'htmlelements');
$textArea->setRows(30);
$textArea->setColumns(100);
$textArea->setContent($xbelOutput);
echo '<p>'.$textArea->show().'</p>';

$link = $this->newObject('link','htmlelements');
$link->href = $this->uri(NULL);
$link->link=$this->objLanguage->languageText("word_back");
echo '<p>'.$link->show().'</p>';
?>
