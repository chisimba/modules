<?php
    echo "Essay Table Updated Succesfully";
	$this->loadClass('htmltable','htmlelements');
	$table = new htmltable();
	$essays=$this->objComments->getstudentEssays();

	foreach($essays as $essay){
	 $table->startRow();
	 $table->addCell($essay['title']);
	 $table->addCell($essay['content']);
	 $table->endRow();
	}
	echo $table->show();
?>
