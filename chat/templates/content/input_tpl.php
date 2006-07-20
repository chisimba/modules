<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textarea","htmlelements");
	$this->loadClass("dropdown","htmlelements");
	$this->loadClass("button","htmlelements");
	$this->loadClass("geticon","htmlelements");
	// Display the input form for the submission of posts to the chat room.
	echo "<form id=\"inputForm\" name=\"inputForm\" action=\"" . 		
		$this->uri(array(
	    	'module'=>'chat',
			'action'=>'input',
			'context'=>$context,
		))	
	. "\" method=\"post\">";
?>
	<table>
	<tr>
	<td>
<?php
	echo "<table>";
	echo "<tr>";
	echo "<td rowspan=\"2\">";
?>
    <!-- The text area for the post. -->
	<!--
		rows="5" 
		cols="30" 
	-->
	<textarea 
		name="text"
		id="input_text"
		style="
			width: 200px; 
			height: 50px;
			font-family: <?php echo $this->getSession('family') ?>,verdana,arial,helvetica;
            font-size: <?php echo $this->getSession('size') ?>pt;
            color: <?php echo $this->getSession('color') ?>;
		"
		onKeyPress="return submitenter(this,event)"
	></textarea>
<?php
	echo "</td>";
    // Display a dropdown list of users for private messages.
	$dropdown = new dropdown("recipient");
	$dropdown->addOption("All","All");
	foreach ($users as $item) {
		$dropdown->addOption($item["username"],$item["firstName"] . " " . $item["surname"]);
	}
	echo "<td>";
	echo $dropdown->show();
	echo "</td>";
	echo "</tr>";
    // Display the submit button.
	$icon = $this->getObject('geticon','htmlelements');
	$icon->setIcon('chat/submitpost');
	$icon->align=false;
	$icon->alt = $objLanguage->languageText('word_submit');
	echo "<tr>";
	echo "<td>";
    echo "<a href=\"#\" onclick=\"inputForm.submit();\">" . $icon->show() . "</a>";
	//echo "<a href=\"javascript:;\" onclick=\"inputForm.submit();\">" . $icon->show() . "</a>";
	//echo "<a href=\"javascript:inputForm.submit();\">" . $icon->show() . "</a>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
?>
	</td>
	<td>
	<table>
	<tr>
	<td>
	<script language="JavaScript">
	toggleSmileys=0;
	</script>
	<a href="javascript:;" onClick="
	if (toggleSmileys==0) {
		document.getElementById('smileysdiv').style.visibility='visible';
		toggleSmileys=1;
	}
	else {
		document.getElementById('smileysdiv').style.visibility='hidden';
		toggleSmileys=0;
	}
	">
<?php
    // Display the smileys button.
	$icon = $this->getObject('geticon','htmlelements');
	$icon->setIcon('chat/smiley');
	$icon->align=false;
	$icon->alt = $objLanguage->languageText("chat_smileys",'chat');
	echo $icon->show();
?>
	</a>
	</td>
	</tr>
	<tr>
	<td>
	<script language="JavaScript">
	toggleFont=0;
	</script>
	<a href="javascript:;" onClick="
	if (toggleFont==0) {
		document.getElementById('fontdiv').style.visibility='visible';
		toggleFont=1;
	}
	else {
		document.getElementById('fontdiv').style.visibility='hidden';
		toggleFont=0;
	}
	">
<?php
    // Display the change font button.
	$icon = $this->getObject('geticon','htmlelements');
	$icon->setIcon('chat/font');
	$icon->align=false;
	$icon->alt = $objLanguage->languageText('chat_change_font','chat');
	echo $icon->show();
?>
	</a>
	</td>
	</tr>
	</table>
	</td>
	<td width="200" valign="top">
<?php
    // Display the smileys layer.
	echo "<div id=\"smileysdiv\" name=\"smileysdiv\" class=\"odd\"
	style=\"
		position: absolute;
		visibility: hidden;
	\">";
    /**
    * Function that returns a clickable smiley.
    * @param string The filename for the smiley
    * @param object The icon object
    * @return string The tagged smiley.
    */
	function Smiley($filename, $icon)
	{
		$icon->setIcon("smileys/" . $filename);
		$icon->align=false;
		return "<a href=\"javascript:;\""
		. " onClick=\"
		document.inputForm.text.value=document.inputForm.text.value+'[" . $filename . "]';
		document.getElementById('smileysdiv').style.visibility='hidden';
		toggleSmileys=0;
		document.getElementById('text').focus();
		\">"
		. $icon->show()
		. "</a> ";
	}
    // Display the smileys.
	$icon = $this->getObject('geticon','htmlelements');
	echo Smiley("smile0",$icon);
	echo Smiley("smile1",$icon);
	echo Smiley("smile2",$icon);
	echo Smiley("smile4",$icon);
	echo Smiley("smile6",$icon);
	echo Smiley("smile7",$icon);
	echo Smiley("smile8",$icon);
	echo Smiley("smile10",$icon);
	echo Smiley("smile11",$icon);
	echo Smiley("smile13",$icon);
	echo "<br/>";
	echo Smiley("smile14",$icon);
	echo Smiley("smile15",$icon);
	echo Smiley("smile16",$icon);
	echo Smiley("smile18",$icon);
	echo Smiley("smile19",$icon);
	echo Smiley("smile20",$icon);
	echo Smiley("smile21",$icon);
	echo Smiley("smile24",$icon);
	echo Smiley("smile26",$icon);
	echo Smiley("smile27",$icon);
	echo "<br/>";
	echo Smiley("smile28",$icon);
	echo Smiley("smile29",$icon);
	echo Smiley("smile30",$icon);
	echo Smiley("smile36",$icon);
	echo Smiley("smile39",$icon);
	echo Smiley("smile41",$icon);
	echo Smiley("smile44",$icon);
	echo Smiley("smile46",$icon);
	echo Smiley("smile90",$icon);
	echo Smiley("smile91",$icon);
	echo "</div>";
?>
<script language="JavaScript">
function UpdateStyle()
{
	document.getElementById('input_text').style.fontFamily=document.getElementById('input_family').value+',verdana,arial,helvetica';
	document.getElementById('input_text').style.fontSize=document.getElementById('input_size').value+'pt';
	document.getElementById('input_text').style.color=document.getElementById('input_color').value;
}
</script>
<?php
	// Display the font layer.
	echo "<div id=\"fontdiv\" name=\"fontdiv\" class=\"odd\"
	style=\"
		position: absolute;
		visibility: hidden; 
	\">";
	// Family
	$dropdown = new dropdown("family");
	$dropdown->addOption("Arial", "Arial");
	$dropdown->addOption("Comic Sans MS", "Comic Sans MS");
	$dropdown->addOption("Courier", "Courier");
	$dropdown->addOption("Garamond", "Garamond");
	$dropdown->addOption("Haettenschweiler", "Haettenschweiler");
	$dropdown->addOption("Helvetica", "Helvetica");
	$dropdown->addOption("Impact", "Impact");
	$dropdown->addOption("Symbol", "Symbol");
	$dropdown->addOption("Tahoma", "Tahoma");
	$dropdown->addOption("Times New Roman", "Times New Roman");
	$dropdown->addOption("Verdana", "Verdana");
	$dropdown->addOption("Webdings", "Webdings");
	$dropdown->addOption("Wingdings", "Wingdings");
	$dropdown->addOption("Zapf Dingbats", "Zapf Dingbats");
	$dropdown->setSelected($this->getSession("family"));	
	$dropdown->extra = " onChange=\"UpdateStyle();\"";
	echo $dropdown->show();
	// Size
	$dropdown = new dropdown("size");
	$dropdown->addOption("4","4");
	$dropdown->addOption("6","6");
	$dropdown->addOption("8","8");
	$dropdown->addOption("10","10");
	$dropdown->addOption("12","12");
	$dropdown->addOption("14","14");
	$dropdown->addOption("16","16");
	$dropdown->addOption("18","18");
	$dropdown->addOption("20","20");
	$dropdown->addOption("22","22");
	$dropdown->addOption("24","24");
	$dropdown->addOption("26","26");
	$dropdown->addOption("28","28");
	$dropdown->setSelected($this->getSession("size"));	
	$dropdown->extra = " onChange=\"UpdateStyle();\"";
	echo $dropdown->show();
	// Color
	$dropdown = new dropdown("color");
	$dropdown->addOption("Black", "Black");
	$dropdown->addOption("Blue", "Blue");
	$dropdown->addOption("Brown", "Brown");
	$dropdown->addOption("Gray", "Gray");
	$dropdown->addOption("Green", "Green");
	$dropdown->addOption("Khaki", "Khaki");
	$dropdown->addOption("Maroon", "Maroon");
	$dropdown->addOption("Orange", "Orange");
	$dropdown->addOption("Navy", "Navy");
	$dropdown->addOption("Pink", "Pink");
	$dropdown->addOption("Red", "Red");
	$dropdown->addOption("SkyBlue", "SkyBlue");
	$dropdown->addOption("Yellow", "Yellow");
	$dropdown->setSelected($this->getSession("color"));	
	$dropdown->extra = " onChange=\"UpdateStyle();\"";
	echo $dropdown->show();
	echo "</div>";
?>
	</td>
	</tr>
	</table>
<?php
	echo "</form>";
?>