<?

$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module','studentenquiry'));

$details = "Testing....Testing";

$left =& $this->getObject('financialaidleftblock');


$left = $left->show();
$this->studentinfo =& $this->getObject('dbstudentinfo','studentenquiry');
$this->objDbFinAid =& $this->getObject('dbfinaid');


$table =& $this->newObject('htmltable','htmlelements');
        $table =& $this->newObject('htmltable','htmlelements');
        $table->startHeaderRow();
        $table->addHeaderCell('Allowance Code');
        $table->addHeaderCell('Amt');

        $table->endHeaderRow();

for($i = 1; $i < 9; $i++)
{
    $result = $this->objDbFinAid->getBursaryAllowance($i, 'ALWCDE');
    $oddEven = 'odd';
    if (is_array($result)){
        foreach($result as $data){
  			    $oddEven = $oddEven == 'odd'?'even':'odd';
                $table->row_attributes = " class = \"$oddEven\"";
                $table->startRow();
  	            $table->addCell($data->ALWCDE);
  	            $table->addCell($data->AMT);
                $table->endRow();
        }
    }

}
$content = "<center>".$details." ".$table->show()."</center>";


// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();

?>
