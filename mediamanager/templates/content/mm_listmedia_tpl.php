<style type="text/css">
	<!--
	.imgBorder {
		height: 96px;
		border: 1px solid threedface;
		vertical-align: middle;
	}
	.imgBorderHover {
		height: 96px;
		border: 1px solid threedface;
		vertical-align: middle;
		background: #FFFFCC;
		cursor: hand;
	}

	.buttonHover {
		border: 1px solid;
		border-color: ButtonHighlight ButtonShadow ButtonShadow ButtonHighlight;
		cursor: hand;
		background: #FFFFCC;
	}

	.buttonOut
	{
	 border: 0px;
	}

	.imgCaption {
		font-size: 9pt;
		font-family: "MS Shell Dlg", Helvetica, sans-serif;
		text-align: center;
	}
	.dirField {
		font-size: 9pt;
		font-family: "MS Shell Dlg", Helvetica, sans-serif;
		width:110px;
	}
	-->
	</style>
<?php
$table = & $this->newObject('htmltable' , 'htmlelements');

$rowCount = 4;
$cnt = 0;
$table->startRow();

if($files)
{
	foreach($files as $file)
	{
		$cnt++;
		
		$smalltable = & $this->newObject('htmltable' , 'htmlelements');
		$smalltable->startRow();
		$smalltable->addCell('<img src="'.$file['path'].'" width="80" height="77" alt="'.$file['name'].' - 3.31 Kb" border="0">');
		$smalltable->endRow();
		
		$smalltable->startRow();
		$smalltable->addCell($file['name']);	
		$smalltable->endRow();
		
		//$smalltable->startRow();
		//$smalltable->addCell($file['name']);	
		//$smalltable->endRow();
		$table->addCell($smalltable->show());
		
		
		if($cnt > $rowCount)
		{
			$table->endRow();
			$table->startRow();
			$cnt = 1;
		}
		
	}

} else {
	$table->addCell('<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No Images Found</div>');
}
	$table->endRow();
echo $table->show();
?>