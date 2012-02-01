<?php
/**
*  A main content template for OER User Data
*  Author: Derek Keats derek@dkeats.com
*  Date: February 1, 2012, 8:34 am
*
*/
ob_start();
$objFix = $this->getObject('cssfixlength', 'htmlelements');
$objFix->fixThree();
?>
This is main template

<div id="threecolumn">
    <div id="Canvas_Content_Body_Region1">
        {
            "display" : "block",
            "module" : "oeruserdata",
            "block" : "oeruserdataleft"
        }
        <div id="leftdynamic_area" class="leftdynamic_area_layer"></div>
        <div id="leftfeedback_area" class="leftfeedback_area_layer"></div>
    </div>
    <div id="Canvas_Content_Body_Region3">
        {
            "display" : "block",
            "module" : "oeruserdata",
            "block" : "oeruserdataright"
        }
        <div id="rightdynamic_area" class="rightdynamic_area_layer"></div>
        <div id="rightfeedback_area" class="rightfeedback_area_layer"></div>
    </div>
    <div id="Canvas_Content_Body_Region2">
        {
            "display" : "block",
            "module" : "oeruserdata",
            "block" : "oeruserdatamiddle"
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