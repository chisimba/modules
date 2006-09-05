<?php
$this->objLanguage = &$this->getObject('language','language');
$this->objUser =& $this->getObject('user','security');
$this->objDBFinAid =& $this->getObject('dbfinaid','financialaid');
$sponsorid = getParam('id', NULL);
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
    $table->addCell($sponsors[$i]->BRSCDE);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';
 
	$table->startRow();
    $table->addCell('Bursor');
    $table->addCell($sponsors[$i]->XTRALNGDSC);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

    $table->startRow();
    $table->addCell('Contact');
    $table->addCell($sponsors[$i]->XXLNGDSC);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

	$table->startRow();
    $table->addCell('Category');
    $table->addCell($sponsors[$i]->BRSCTGY);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';
 
	$table->startRow();
    $table->addCell('Address 1');
    $table->addCell($sponsors[$i]->AD1);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

	$table->startRow();
    $table->addCell('Address 2');
    $table->addCell($sponsors[$i]->AD2);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

	$table->startRow();
    $table->addCell('Address 3');
    $table->addCell($sponsors[$i]->AD3);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';

	$table->startRow();
    $table->addCell('Postcode');
    $table->addCell($sponsors[$i]->PSTCDE);
	$table->endRow();
	$oddEven = $oddEven == 'odd'?'even':'odd';


    $content = $table->show();

  }
}


$content = "<center>".$content. "</center>";

echo $content;
?>
