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

$lowerMark = new dropdown('lowermark',null,null,5);
$lowerMark->addOption('0','0');
$lowerMark->addOption('10','10');
$lowerMark->addOption('20','20');
$lowerMark->addOption('30','30');
$lowerMark->addOption('40','40');
$lowerMark->addOption('50','50');
$lowerMark->addOption('60','60');
$lowerMark->addOption('70','70');
$lowerMark->addOption('80','80');
$lowerMark->addOption('90','90');
$lowerMark->addOption('100','100');
$upperMark = new dropdown('uppermark',null,null,5);
$upperMark->addOption('0','0');
$upperMark->addOption('10','10');
$upperMark->addOption('20','20');
$upperMark->addOption('30','30');
$upperMark->addOption('40','40');
$upperMark->addOption('50','50');
$upperMark->addOption('60','60');
$upperMark->addOption('70','70');
$upperMark->addOption('80','80');
$upperMark->addOption('90','90');
$upperMark->addOption('100','100');

$table->startRow();
$table->addCell('Marks Between');
$table->addCell($lowerMark->show());
$table->addCell('and');
$table->addCell($upperMark->show());
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
