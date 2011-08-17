
<html>
    <head>
 <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
  <script type="text/javascript" src="packages/unesco_oer/resources/jquery.validate.js"></script>
<script  >
   

function edit(section_id){
    //$('.root').hide();
    
    $('.root').load('index.php?module=unesco_oer&action=saveContent&option=edit&pair=' + section_id + '&productID=' + $('.product_id').attr('id'));
     
   
}

function newSection(path){
   
    $('.root').load('index.php?module=unesco_oer&action=saveContent&option=new&pair=' + path + '&productID=' + $('.product_id').attr('id'),
     function(){$('#form_add').validate()});
}




$('#upload').live('click', function() {
  $("#form_add_products_ui").validate();
});




</script>
    </head>
    
    </html>


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

$this->setLayoutTemplate('maincontent_layout_tpl.php');

?>
    <div class ="productsBackgroundColor ">
        <div class="greyText">
      <div class="test">
              
    <?php
    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
    if (!$product->isAdaptation()){
        $abLink->link = 'UNESCO OER Products';
        $abLink->cssClass = "blueText noUnderline";
    } else {
        $abLink->link = 'Product adaptation';
        $abLink->cssClass = "orangeListingHeading";
    }
    
    echo $abLink->show();
    ?>
    <!--    <a href="#" class="blueText noUnderline">UNESCO OER Products</a> -->
    |
    <a href="#" class="blueText noUnderline">
    <!--                        Model Curriculum for Journalism Education-->
    <?php

    $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product->getIdentifier())));
    $abLink->link = $product->getTitle();

    if (!$product->isAdaptation()){
        $abLink->cssClass = "blueText noUnderline";
    } else {
        $abLink->cssClass = "orangeListingHeading";
    }

      echo $abLink->show();
    ?>
     | <a class="blueText noUnderline">
       Section View
     </a>
    </a>
</div> 
    
    
    
    
    
    <?php

echo '<div id="sections">
    
<fieldset>
<legend> Edit Sections</legend>

<div class="leftColumnDiv" >';



echo $contentManager->getContentTree(TRUE);
echo '</div>';



echo '<div class="centerColumnDiv"">';
echo $contentManager->showInput($this->getParam('prevAction'));
echo "</div></fieldset></div></div></div>";

?>
