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
        $table->addHeaderCell('ID');
        $table->addHeaderCell('Code');
        $table->addHeaderCell('Desc');

        $table->endHeaderRow();

for($i = 0; $i < 50; $i++)
{
    $result = $this->objDbFinAid->getParam($i);
    $oddEven = 'odd';
    if (is_array($result)){
        foreach($result as $data){
  			    $oddEven = $oddEven == 'odd'?'even':'odd';
                $table->row_attributes = " class = \"$oddEven\"";
                $table->startRow();
  	            $table->addCell($data['PRMIDN']);
  	            $table->addCell($data['PRMCDE']);
      	        $table->addCell($data['LNGDSC']);
                $table->endRow();
        }
    }

}
$content = "<center>".$details." ".$table->show();


// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();

?>
