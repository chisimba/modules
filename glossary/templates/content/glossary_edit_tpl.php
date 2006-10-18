<script language="JavaScript" type="text/JavaScript">

function confirmDelete(url, msg) {

	if (confirm(msg)) {
		location.href = url;
	}
}

</script>
<?php
/* A PHP template for the Home Page of the Glossary Module */

// Classes being used
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');


// Create Header Tag ' Edit Term
$this->titleAddTerm =& $this->getObject('htmlheading', 'htmlelements');
$this->titleAddTerm->type=1;
$this->titleAddTerm->str=$this->objLanguage->languageText('mod_glossary_name', 'glossary').' - '.$this->objLanguage->languageText('mod_glossary_edit', 'glossary').' "'.$record['term'].'"';
echo $this->titleAddTerm->show();


if ($message != '') {

    $timeoutObject = $this->getObject('timeoutmessage', 'htmlelements');
    $timeoutObject->setMessage($message);
    
    $editMessage = '<div class="" align="center">';
    $editMessage .= $timeoutObject->show();
    $editMessage .= '<br /></div>';
    
    echo $editMessage;
}


// Start of Form
$editTermForm = new form('editWord', $this->uri(array(
		'module' => 'glossary', 
		'action' => 'editconfirm', 
		'id'     => $record['item_id']
	)));

$editTermForm->displayType = 3;

$hiddenIdInput = new textinput('id');
$hiddenIdInput->fldType = 'hidden';
$hiddenIdInput->value = $record['item_id'];
$editTermForm->addToForm($hiddenIdInput->show());



$addTable = $this->getObject('htmltable', 'htmlelements');
$addTable->width='500';
$addTable->cellpadding = 10;

$addTable->startRow();
$termLabel = new label($this->objLanguage->languageText('mod_glossary_term', 'glossary'), 'input_term');
$addTable->addCell($termLabel->show(), 100);

$termInput = new textinput('term', htmlentities(stripslashes($record['term'])));
$termInput->size = 50;
$addTable->addCell($termInput->show(), 400);

$addTable->endRow();
$addTable->startRow();

$definitionLabel = new label($this->objLanguage->languageText('mod_glossary_definition', 'glossary'), 'input_definition');
$addTable->addCell($definitionLabel->show().':', null);

$definition = new textarea('definition', stripslashes($record['definition']));
$addTable->addCell($definition->show(), null);

$addTable->endRow();

$addTable->startRow();

$submitButton = new button('submit', $this->objLanguage->languageText('mod_glossary_updateTerm', 'glossary'));
$submitButton->setToSubmit();

$addTable->addCell(' ', null);
$addTable->addCell($submitButton->show(), null);
$addTable->endRow();


$editTermForm->addRule('term',$this->objLanguage->languageText('mod_glossary_termRequired', 'glossary'),'required');
$editTermForm->addRule('definition',$this->objLanguage->languageText('mod_glossary_defnRequired', 'glossary'),'required');

$editTermForm->addToForm($addTable);

echo $editTermForm->show();

// --------
?>
<table><tr><td width="50%" valign="top">

<?php
$seeAlsoFieldset = &$this->newObject('fieldset','htmlelements');

// Create Header Tag ' Edit Term
$this->seeAlsoTerm =& $this->getObject('htmlheading', 'htmlelements');
$this->seeAlsoTerm->type=3;
$this->seeAlsoTerm->str=$this->objLanguage->languageText('mod_glossary_seeAlso', 'glossary').': <em>'.$record['term'].'</em> '.$this->objLanguage->languageText('mod_glossary_linkToOthers', 'glossary');

$seeAlsoFieldset->addContent ( $this->seeAlsoTerm->show());

// -------

if ($seeAlsoNum == 0 && $numRecords > 1) 
{
	$seeAlsoFieldset->addContent ($this->objLanguage->languageText('mod_glossary_noTermsLinked', 'glossary').'. ');
	
} else {
	
$seeAlsoFieldset->addContent ('<ul>');	

	foreach ($seeAlsoList as $element) {
		
		$seeAlsoFieldset->addContent ('<li><p>');
		
		if ($element['item_id'] != $id) {

			$seeAlsoFieldset->addContent ($element['term1']);
			
		} else {
			
			$seeAlsoFieldset->addContent ($element['term2']);
			
		}
		
		
		// Delete Link
		$seeAlsoFieldset->addContent(' ');
		
		// URL Delete Link
		$deleteLinkIcon =& $this->getObject('geticon', 'htmlelements');
		$deleteLinkIcon->setIcon('delete');
		$deleteLinkIcon->alt=$objLanguage->languageText('mod_glossary_delete', 'glossary');
		$deleteLinkIcon->title=$objLanguage->languageText('mod_glossary_delete', 'glossary');
		
		$link = $this->uri(array(
				'module'=>'glossary', 
				'action'=>'deleteseealso', 
				'id'=>$record['item_id'] ,
				'seealso'=>$element['id']
			));
			
		$deleteLink = new link("javascript:confirmDelete('$link', '".$objLanguage->languageText('mod_glossary_pop_deleteseealso', 'glossary')."');");
		$deleteLink->link = $deleteLinkIcon->show();


		$seeAlsoFieldset->addContent ($deleteLink->show());
        
        $seeAlsoFieldset->addContent ('</p></li>');

	}
	
$seeAlsoFieldset->addContent('</ul>');

}

// -------

if ($numRecords == 1)
// Prevents logical error
// If only one record exists,
{
	$seeAlsoFieldset->addContent ('<p>'.$objLanguage->languageText('mod_glossary_onlyword', 'glossary').'</p>');

} else if ($notLinkedToNum == 0) {
	
	
		$seeAlsoFieldset->addContent ('<p>'.$record['term'].' '.$this->objLanguage->languageText('mod_glossary_isLinkedToAll', 'glossary').'</p>');

	
} else {

	// Form to Add See Also Link
	// Start of Form
	$addSeeAlsoForm = new form('addWord', $this->uri(array(
			'module'=>'glossary', 
			'action'=>'addseealsoconfirm' 
		)));
        
    $seeAlsoHiddenIdInput = new textinput('id');
    $seeAlsoHiddenIdInput->fldType = 'hidden';
    $seeAlsoHiddenIdInput->value = $record['item_id'];
    $addSeeAlsoForm->addToForm($seeAlsoHiddenIdInput->show());
	
	$seeAlso = new dropdown('seealso');
	
	foreach ($others as $element) {
	
		$seeAlso->addOption($element['id'], $element['term']);
	
	}
	
	// Instructions
    $seeAlsoLabel = new label($this->objLanguage->languageText('mod_glossary_selectTermLink', 'glossary'), 'input_seealso');
    $addSeeAlsoForm->addToForm($seeAlsoLabel->show().':', null);

	//$addSeeAlsoForm->addToForm($this->objLanguage->languageText('mod_glossary_selectTermLink', 'glossary').':');
	
	// Add Drop Down
	$addSeeAlsoForm->addToForm($seeAlso);
	
	
	$submitButton = new button('submit', $this->objLanguage->languageText('mod_glossary_add', 'glossary'));
	$submitButton->setToSubmit();
	
	
	$addSeeAlsoForm->addToForm($submitButton);
	$addSeeAlsoForm->displayType =3;
	
	$seeAlsoFieldset->addContent ('<p>'.$addSeeAlsoForm->show().'</p>');


}

echo $seeAlsoFieldset->show();

echo ('<br />');
// -------

$urlFieldset = &$this->newObject('fieldset','htmlelements');

// Create Header Tag ' Website Links
$this->urlLinks =& $this->getObject('htmlheading', 'htmlelements');
$this->urlLinks->type=3;
$this->urlLinks->str=$this->objLanguage->languageText('mod_glossary_websiteLinksFor', 'glossary').' <em>'.$record['term'].'</em>';

$urlFieldset->addContent($this->urlLinks->show());


if ($urlNum == 0) 
{
	$urlFieldset->addContent ('<ul><li>'.$this->objLanguage->languageText('mod_glossary_noUrlsFound', 'glossary').'. </li></ul>');
	
} else {
	
	$urlFieldset->addContent ('<ul>');	
	
	
	foreach ($urlList as $element) {

		$urlFieldset->addContent ('<li><p>');
		
		$itemLink = new link($element['url']);
		$itemLink->target = '_blank';
		$itemLink->link =$element['url'];
		
		$urlFieldset->addContent( $itemLink->show());
		
		$urlFieldset->addContent( ' - ' );

		// URL Delete Link
		$deleteLinkIcon =& $this->getObject('geticon', 'htmlelements');
		$deleteLinkIcon->setIcon('delete');
		$deleteLinkIcon->alt=$objLanguage->languageText('mod_glossary_delete', 'glossary');
		$deleteLinkIcon->title=$objLanguage->languageText('mod_glossary_delete', 'glossary');
		
		$link = $this->uri(array(
				'module'=>'glossary', 
				'action'=>'deleteurl', 
				'id'=>$record['item_id'] ,
				'link'=>$element['id']
			));
			
		$deleteLink = new link("javascript:confirmDelete('$link', '".$objLanguage->languageText('mod_glossary_pop_deleteurl', 'glossary')."');");
		$deleteLink->link = $deleteLinkIcon->show();


		$urlFieldset->addContent ($deleteLink->show());
		$urlFieldset->addContent ('</p></li>');
		

	}
	
	$urlFieldset->addContent ('</ul>');

}

// -------

// Start of Form
$addUrlForm = new form('addWord', $this->uri(array(
		'module'=>'glossary', 
		'action'=>'addurlconfirm'
//		'id'=>$record['id']
	)));

$hiddenIdInput = new textinput('id');
$hiddenIdInput->fldType = 'hidden';
$hiddenIdInput->value = $record['item_id'];
$addUrlForm->addToForm($hiddenIdInput->show());


$urlInput = new textinput('url');
$urlInput->size = 30;
$urlInput->extra = ' title ="asfas"';
$urlInput->value = 'http://';


$urlLabel = new label($this->objLanguage->languageText('mod_glossary_addUrl', 'glossary'), 'input_url');
$addUrlForm->addToForm($urlLabel->show().':', null);

$addUrlForm->addToForm(' &nbsp; '.$urlInput->show());


$submitButton = new button('submit', $this->objLanguage->languageText('mod_glossary_add', 'glossary'));
$submitButton->setToSubmit();


$addUrlForm->addToForm(' &nbsp; '.$submitButton->show());
$addUrlForm->displayType =3;

$urlFieldset->addContent( $addUrlForm->show());

$urlFieldset->addContent ('<br />');

echo $urlFieldset->show();
?>
</td><td valign="top">
<fieldset>
<h3><?php echo $this->objLanguage->languageText('mod_glossary_imagesfor', 'glossary').' '.$record['term']; ?></h3>


<iframe src="<?php echo $this->uri(array(
		'module' => 'glossary', 
		'action' => 'listimages', 
		'id'     => $record['item_id']
	)); ?>" width="99%" height="170" frameborder="0" scrolling="auto" style="overflow-x: hidden;" marginwidth="0" marginheight="0" hspace="0" vspace="0"></iframe>
</fieldset>
</td>

</tr>
</table>
<p align="center"><a href="<?php echo $this->uri(array('action'=>'search', 'term'=>$record['term'])); ?>"><?php echo $this->objLanguage->languageText('mod_glossary_returntoglossary', 'glossary'); ?></a></p>