<?php
/**
*  A main content template for schoolusers
*  Author: Kevin Cyster kcyster@gmail.com
*  Date: March 21, 2012, 9:18 pm
*
*/
ob_start();
$objFix = $this->getObject('cssfixlength', 'htmlelements');
$objFix->fixThree();
?>

<div id="onecolumn">
    <div id="Canvas_Content_Body_Region2">
        {
            "display" : "block",
            "module" : "schools",
            "block" : "schoolsinvalid"
        }
        <div id="middledynamic_area" class="middledynamic_area_layer">&nbsp;</div>
        <div id="middlefeedback_area" class="middlefeedback_area_layer">&nbsp;</div>
    </div>
</div>
<?php
// Get the contents for the layout template
$pageContent = ob_get_contents();
ob_end_clean();
$this->setVar('pageContent', $pageContent);
?>