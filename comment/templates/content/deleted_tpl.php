<?php
$objIcon = $this->getObject('geticon', 'htmlelements');
$objIcon->setIcon('close');
$closeLink = "<A href=\"javascript: self.close ()\">"
  . $objIcon->show() . "</A>";
   echo "<table align=\"center\"><tr>"
     . "<td width=\"100%\" valign=\"top\" style=\"border-top: 1px #DFDFDF solid; border-bottom: 2px #CCCCCC solid;\">"
     . "<b>" . $this->objLanguage->languageText('mod_comment_deletedit','comment')
     . "</b></tr><tr><td valign=\"top\" height=\"155px\" >" 
      . $closeLink . "</td></tr></tr><table>";

?>
