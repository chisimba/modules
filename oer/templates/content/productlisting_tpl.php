<?php
ob_start();
$objFix = $this->getObject('cssfixlength', 'htmlelements');
$objFix->fixThree();
?>
<div id="threecolumn">
    <div id="Canvas_Content_Body_Region1">
         {
        "display" : "block",
        "module" : "oeruserdata",
        "block" : "myuserdetails"
        }
        {
        "display" : "block",
        "module" : "language",
        "block" : "language"
        }

        
        <div id="leftdynamic_area" class="leftdynamic_area_layer"></div>
        <div id="leftfeedback_area" class="leftfeedback_area_layer"></div>
    </div>
    <div id="Canvas_Content_Body_Region3">
        {
        "display" : "block",
        "module" : "oer",
        "block" : "featuredoriginalproduct"
        }

       
        <div id="rightdynamic_area" class="rightdynamic_area_layer">
           
        </div>
        <div id="rightfeedback_area" class="rightfeedback_area_layer"></div>
    </div>
    <div id="Canvas_Content_Body_Region2">
        {
        "display" : "block",
        "module" : "oer",
        "block" : "originalproductslisting",
        <?php
        echo '"configData":';
        echo '"' . $mode . '"';
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
$pageContent = ob_get_contents();
ob_end_clean();
$this->setVar('pageContent', $pageContent);
?>