<?php
/**
*  A main content template for Image gallery
*  Author: Kevin Cyster kcyster@gmail.com
*  Date: June 14, 2012, 11:07 am
*
*/
ob_start();
$objFix = $this->getObject('cssfixlength', 'htmlelements');
$objFix->fixThree();
?>

<div id="twocolumn">
    <div id="Canvas_Content_Body_Region1">
        {
            "display" : "block",
            "module" : "gallery",
            "block" : "galleryleft"
        }
        <div id="leftdynamic_area" class="leftdynamic_area_layer"></div>
        <div id="leftfeedback_area" class="leftfeedback_area_layer"></div>
    </div>
    <div id="Canvas_Content_Body_Region2">
        {
            "display" : "block",
            "module" : "gallery",
            "block" : "gallerygalleries"
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