
<!--<script type="text/javascript" src="packages/unesco_oer/resources/bubble-tooltip.js"></script> -->
<script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script> 
<script type="text/javascript" src="packages/unesco_oer/resources/jquery.validate.js"></script>
<!--<link href="packages/unesco_oer/resources/bubble-tooltip.css" rel="stylesheet" type="text/css"/>-->


<script>
   


    $(document).ready(
    function()
    {
            
        $("#form_add_products_ui").validate();
       
       
    });




</script>
<div class ="productsBackgroundColor ">
<?php
$homelink = new link('home');
$homelink->href = $this->uri(array("action" => "home"));
$homelink->link = 'Home';

$productNavCap = $this->objLanguage->languageText('mod_unesco_oer_new_product', 'unesco_oer');
if ($product->isAdaptation()) {
    $homelink->href = "?module=unesco_oer&action=FilterProducts&adaptationstring=parent_id+is+not+null+and+deleted+%3D+0&page=2a_tpl.php";
    $homelink->link = 'Product Adaptations';
    $productID = $product->getIdentifier();
    if (empty($productID)) {
        $productNavCap = $this->objLanguage->languageText('mod_unesco_oer_add_data_newAdaptation', 'unesco_oer');
    } else {
        $productNavCap = $this->objLanguage->languageText('mod_unesco_oer_edit_adaptation', 'unesco_oer');
    }
} else {
    if (!$product->isDeleted())
        $productNavCap = $this->objLanguage->languageText('mod_unesco_oer_edit_product', 'unesco_oer');
}
?>
<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul>
            <li><?php echo $homelink->show(); ?></li>
            <li><?php echo $productNavCap; ?></li>
        </ul>
    </div>

</div>
<?php
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

//  $userid = $this->objUser->userId();
//
//if ($this->ObjDbUserGroups->ismemberOfAnygroup($userid)) {
//    
//  echo '  <div class="gridViewGroupBackgroundColor">
//        <div class="paddingGroubGridListingTable">
//            <table class="groupListingTable" cellspacing="0" cellpadding="0">
//                <td>';
//
//             
//                    $objTable = $this->getObject('htmltable', 'htmlelements');
//                    //$objTable->cssClass = "gridListingTable";
//                    //$objTable->width = NULL;
//
//                    $groups = $this->objDbGroups->getAllGroups();
//                    $newRow = true;
//                    $count = 0;
//                    foreach ($groups as $group) {
//                        $count++;
//                        if ($newRow) {
//                            $objTable->startRow();
//                            $objTable->addCell($this->objGroupUtil->content($group));
//                            $newRow = false;
//                        } else {
//                            $objTable->addCell($this->objGroupUtil->content($group));
//                        }
//                        if ($count == 3) {
//                            $newRow = true;
//                            $objTable->endRow();
//                            $count = 0;
//                        }
//                    }
//                    echo $objTable->show();
//
////                $fieldset1 = $this->newObject('fieldset', 'htmlelements');
////                $fieldset1->setLegend();
////                $fieldset1->addContent($objTable->show());
////                echo $fieldset1->show();
//                    // echo $objTable->show();
//               echo'
//                </td>
//            </table>
//        </div>
//    </div>';
//               
//          
//}
// if ($this->ObjDbUserGroups->getGroupsByUserID($this->objUser->userId()) == null) {
//     echo 'no group';
//     
//     
// } else echo 'groups';

echo $product->showMetaDataInput($this->getParam('nextAction'), $this->getParam('cancelAction'), $this->getParam('cancelParams'));

//var_dump($product->dummyValue);
?>
<div id="bubble_tooltip">
    <div class="bubble_top"><span></span></div>
    <div class="bubble_middle"><span id="bubble_tooltip_content">Content is comming here as you probably can see.Content is comming here as you probably can see.</span></div>
    <div class="bubble_bottom"></div>
</div>
</div>