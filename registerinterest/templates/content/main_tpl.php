<?php
/**
*  A main content template for Register interest
*  Author: Derek Keats derek@dkeats.com
*  Date: November 28, 2012, 8:24 am
*
*/
ob_start();
$objFix = $this->getObject('cssfixlength', 'htmlelements');
$objFix->fixThree();
?>

<div id="threecolumn">
    <div id="Canvas_Content_Body_Region1">
        {
            "display" : "block",
            "module" : "registerinterest",
            "block" : "riform"
        }
        <div id="leftdynamic_area" class="leftdynamic_area_layer"></div>
        <div id="leftfeedback_area" class="leftfeedback_area_layer"></div>
    </div>
    <div id="Canvas_Content_Body_Region3">
        {
            "display" : "block",
            "module" : "registerinterest",
            "block" : "registerinterestright"
        }
        <div id="rightdynamic_area" class="rightdynamic_area_layer"></div>
        <div id="rightfeedback_area" class="rightfeedback_area_layer"></div>
    </div>
    <div id="Canvas_Content_Body_Region2">
        {
            "display" : "block",
            "module" : "registerinterest",
            "block" : "registerinterestmiddle"
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