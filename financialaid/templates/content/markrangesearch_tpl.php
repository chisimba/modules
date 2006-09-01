<?

$details = "<h2>Search By Average Marks Range</h2>";
$table =& $this->newObject('htmltable','htmlelements');

$years = new dropdown('resultyear','studentenquiry');
$date = date("Y");
$start = date("Y");
for($i = 0; $i < 16; $i ++){
	$years->addOption($start,$start);
	$start--;
}

$mark1 = new dropdown('lowermark',null,null,5);
$mark1->addOption('0','0');
$mark1->addOption('10','10');
$mark1->addOption('20','20');
$mark1->addOption('30','30');
$mark1->addOption('40','40');
$mark1->addOption('50','50');
$mark1->addOption('60','60');
$mark1->addOption('70','70');
$mark1->addOption('80','80');
$mark1->addOption('90','90');
$mark1->addOption('100','100');
$mark2 = new dropdown('uppermark',null,null,5);
$mark2->addOption('0','0');
$mark2->addOption('10','10');
$mark2->addOption('20','20');
$mark2->addOption('30','30');
$mark2->addOption('40','40');
$mark2->addOption('50','50');
$mark2->addOption('60','60');
$mark2->addOption('70','70');
$mark2->addOption('80','80');
$mark2->addOption('90','90');
$mark2->addOption('100','100');

$table->startRow();
$table->addCell('Marks Between');
$table->addCell($mark1->show());
$table->addCell('and');
$table->addCell($mark2->show());
$table->endRow();

$table->startRow();
$table->addCell('year');
$table->addCell($years->show());
$table->endRow();

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');

$table->startRow();
$table->addCell($ok->show());
$table->addCell($cancel->show());
$table->endRow();


$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'listmarkrange')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

echo $objForm->show();

?>
