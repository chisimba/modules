<?php
/**
*  A main content template for OER group editor
*  Author: Paul Mungai paulwando@gmail.com
*  Date: February 08, 2012 21:42 am
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
            "module" : "oer",
            "block" : "viewinstitution",
        <?php
        echo '"configData":';
        echo '"' . $id . '"';
        ?>
        }

        <div id="middledynamic_area" class="middledynamic_area_layer">&nbsp;</div>
    </div>
</div>
<?php
// Get the contents for the layout template
$pageContent = ob_get_contents();
ob_end_clean();
$this->setVar('pageContent', $pageContent);
?>