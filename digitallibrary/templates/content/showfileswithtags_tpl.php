<?php
ob_start();
$objFix = $this->getObject('cssfixlength', 'htmlelements');
$objFix->fixThree();
?>
<div id="twocolumn">
    <div id="Canvas_Content_Body_Region1">



        <div id="leftdynamic_area" class="leftdynamic_area_layer"></div>
        <div id="leftfeedback_area" class="leftfeedback_area_layer"></div>
    </div>
    <div id="Canvas_Content_Body_Region3">

        <div id="rightdynamic_area" class="rightdynamic_area_layer">&nbsp;</div>
        <div id="rightfeedback_area" class="rightfeedback_area_layer">&nbsp;</div>
    </div>
    <div id="Canvas_Content_Body_Region2">
        {
        "display" : "block",
        "module" : "digitallibrary",
        "block" : "fileswithtag",
        <?php
        echo '"configData":';
        echo '"' . $tag . '"';
        ?>

        }


        <div id="middledynamic_area" class="middledynamic_area_layer">&nbsp;</div>
        <div id="middlefeedback_area" class="middlefeedback_area_layer">&nbsp;</div>
    </div>

</div>

<?php
// Get the contents for the layout template 
$this->setVar('errors', Null);
$pageContent = ob_get_contents();
ob_end_clean();
$this->setVar('pageContent', $pageContent);
?>