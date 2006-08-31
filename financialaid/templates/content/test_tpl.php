<?
$details = "Testing....Testing";
$this->studentinfo =& $this->getObject('dbstudentinfo','studentenquiry');
$this->objDbFinAid =& $this->getObject('dbfinaid');


$table =& $this->newObject('htmltable','htmlelements');
        $table =& $this->newObject('htmltable','htmlelements');
        $table->startHeaderRow();
        $table->addHeaderCell('Code');
        $table->addHeaderCell('Pass');

        $table->endHeaderRow();

//for($i = 1; $i < 30; $i++)
//{
    $result = $this->objDbFinAid->getStudentSubjects('2448678');
    $oddEven = 'odd';
    if (is_array($result)){
        foreach($result as $data){
  			    $oddEven = $oddEven == 'odd'?'even':'odd';
                $table->row_attributes = " class = \"$oddEven\"";
                $table->startRow();
  	            $table->addCell($data->SBJCDE);
  	            $table->addCell($data->SBJPSSIND);
                $table->endRow();
        }
    }
//}
$content = "<center>".$details." ".$table->show()."</center>";

echo $content;

?>
