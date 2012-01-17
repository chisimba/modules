<?php
ob_start();
$objFix = $this->getObject('cssfixlength', 'htmlelements');
$objFix->fixTwo();
?>
<div id="twocolumn">
    <div id="Canvas_Content_Body_Region1">
        {
        "display" : "block",
        "module" : "oer",
        "block" : "originalproductformnav",
        <?php
        echo '"configData":';
        echo '"' . $id . '|' . $step . '"';
        ?>
        }

        <div id="leftdynamic_area" class="leftdynamic_area_layer"></div>
        <div id="leftfeedback_area" class="leftfeedback_area_layer"></div>
    </div>

    <div id="Canvas_Content_Body_Region2">
        {
        "display" : "block",
        "module" : "oer",
        "block" : "originalproductform",

        <?php
        echo '"configData":';
        echo '"' . $id . '|' . $step . '"';
        ?>
        }

        <div id="middledynamic_area" class="middledynamic_area_layer">&nbsp;</div>
        <div id="middlefeedback_area" class="middlefeedback_area_layer">&nbsp;</div>
    </div>
    {
    "display" : "block",
    "module" : "oer",
    "block" : "footer"
    }
</div>

<?php
// Get the contents for the layout template 
$this->setVar('errors', $errors);
$this->setVar('mode', $mode);
//$this->setVar('title',$title);
$pageContent = ob_get_contents();
ob_end_clean();
$this->setVar('pageContent', $pageContent);
?>