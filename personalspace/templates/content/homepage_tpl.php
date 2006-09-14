<?php
	echo "<h3>Home Page</h3>";
?>
<table>
<tr>
<td>
<?php
	echo "<h3>Visitors to Your Home Page</h3>";
	$table =& $this->getObject("htmltable","htmlelements");
	$table->border = 0;
	$table->width = Null;	
	$table->startRow();
	$table->addCell("", 20, "bottom");
	for ($dow=0;$dow<7;$dow++) {
		if ($usage[$dow]==0) {
			$div = "<div style=\"
				background-color: #FFFFFF;
				width: 20px;
				height: 10px;\">
				0
				</div>";		    
		}
		else {
			$div = "
				<div style=\"
				background-color: #FFFFFF;
				width: 20px;
				height: ".(($usage[$dow]+1)*10)."px;\">
				".$usage[$dow]."
					<div style=\"
					background-color: #000000;
					width: 20px;
					height: ".(($usage[$dow])*10)."px;
					padding:1px;\">
						<div style=\"background-color: #8080FF;
						width: 20px;
						height: ".(($usage[$dow])*10)."px;\">
						</div>
					</div>
				</div>";
		}
		$table->addCell($div, 20, "bottom");
		$table->addCell("", 20, "bottom");
	}
	$table->endRow();
	$table->addCell("", 10, "bottom");
	$table->addCell("S", 20);
	$table->addCell("", 10, "bottom");
	$table->addCell("M", 20);
	$table->addCell("", 10, "bottom");
	$table->addCell("T", 20);
	$table->addCell("", 10, "bottom");
	$table->addCell("W", 20);
	$table->addCell("", 10, "bottom");
	$table->addCell("T", 20);
	$table->addCell("", 10, "bottom");
	$table->addCell("F", 20);
	$table->addCell("", 10, "bottom");
	$table->addCell("S", 20);
	$table->addCell("", 10, "bottom");
	echo "<div style=\"background-color: #000000; padding:1px;\">";
	echo "<div style=\"background-color: #FFFFFF;\">";
	echo "<br/>";
	echo $table->show();
	echo "</div>";
	echo "</div>";
	$total = 0;
	for ($dow=0;$dow<7;$dow++) {
		$total += $usage[$dow];
	}
	echo"<b>Total page views</b> : ".$total;
?>
</td>
<td>
<?php	
	echo "<a href=\"" . 
		$this->uri(array(
			'module'=>'personalspace',
			'action'=>'viewhomepage',
			'userId'=>$objUser->userId()
		))	
	. "\"><h3>".$objLanguage->languageText("word_view")."</h3></a>";
	//echo "<br/>";
	echo "<a href=\"" . 
		$this->uri(array(
			'module'=>'personalspace',
			'action'=>'edithomepage',
			'userId'=>$objUser->userId()
		))	
	. "\"><h3>".$objLanguage->languageText("word_edit")."</h3></a>";	
?>
</td>
</tr>
</table>
