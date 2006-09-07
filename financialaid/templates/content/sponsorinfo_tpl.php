<?php
$this->objLanguage = &$this->getObject('language','language');
$this->objUser =& $this->getObject('user','security');
$this->objDBFinAid =& $this->getObject('dbfinaid','financialaid');
$sponsorid = $this->getParam('sponsorid', NULL);
$content = "";
$oddEven = 'odd';
$sponsors = $this->objDBFinAid->getSponsor($sponsorid);

if(isset($sponsors)){
   if(count($sponsors) > 0){
    $table =& $this->getObject('htmltable','htmlelements');

	$table->width = '100%';
	$table->cellpadding = 5;
	$table->cellspacing = 2;

    $table->row_attributes = " class = \"$oddEven\"";

	$table->startRow();
    $table->addCell('Bursor Code');
    $table->addCell($sponsors[0]->BRSCDE);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';
 
	$table->startRow();
    $table->addCell('Bursor');
    $table->addCell(htmlspecialchars($sponsors[0]->XTRLNGDSC));
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

    $table->startRow();
    $table->addCell('Contact');
    $table->addCell(htmlspecialchars($sponsors[0]->XXLNGDSC));
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

	$table->startRow();
    $table->addCell('Category');
    $table->addCell($sponsors[0]->BRSCTGY);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';
 
	$table->startRow();
    $table->addCell('Address 1');
    $table->addCell($sponsors[0]->AD1);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

	$table->startRow();
    $table->addCell('Address 2');
    $table->addCell($sponsors[0]->AD2);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

	$table->startRow();
    $table->addCell('Address 3');
    $table->addCell($sponsors[0]->AD3);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

	$table->startRow();
    $table->addCell('Postcode');
    $table->addCell($sponsors[0]->PSTCDE);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';


    $content = $table->show();

  }
}


$content = "<center>".$content. "</center>";

echo $content;
?>
