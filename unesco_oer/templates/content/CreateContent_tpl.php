
<html>
    <head>
        <script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script> 
   
        <?php
        $base = '<script language="JavaScript" src="' . $this->getResourceUri('ckeditor/ckeditor.js', 'ckeditor') . '" type="text/javascript"></script>';
        $baseajax = '<script language="JavaScript" src="' . $this->getResourceUri('ckeditor/_source/core/ajax.js', 'ckeditor') . '" type="text/javascript"></script>';
        echo $base;
        echo $baseajax;
        ?>
        <script  language="JavaScript" type="text/javascript">
   

            function edit(section_id){
                //$('.root').hide();
    
                $('.root').load('index.php?module=unesco_oer&action=saveContent&option=edit&pair=' + section_id + '&productID=' + $('.product_id').attr('id'));

                $("head").append("<link>");
                css = $("head").children(":last");
                css.attr({
                    rel:  "stylesheet",
                    type: "text/css",
                    href: "packages/unesco_oer/resources/bubble-tooltip.css"
                });
                $.getScript("packages/unesco_oer/resources/bubble-tooltip.js");
                  
            }

            function newSection(path){
    
                $('.root').load('index.php?module=unesco_oer&action=saveContent&option=new&pair=' + path + '&productID=' + $('.product_id').attr('id'),
                function()
                {$('#form_add').validate();
            
                });

                $("head").append("<link>");
                css = $("head").children(":last");
                css.attr({
                    rel:  "stylesheet",
                    type: "text/css",
                    href: "packages/unesco_oer/resources/bubble-tooltip.css"
                });
                $.getScript("packages/unesco_oer/resources/bubble-tooltip.js");
            }


      





            $('#upload').live('click', function() {   
                $("#form_add_products_ui").validate();
//                
//                  varError = "";
//            var objCKeditor1 =  document.getElementById('cke_remark');
//            if(CKeditorAPI.GetInstance(objCKeditor1.id).GetXHTML(true) =="")
//            {
//                varError += "Entering text in fckeditor is mandatory.\n";
//            }
//            if(varError !='')
//            {
//                alert(varError);
//                return false;
//            }
//                
//                
                
           
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
?>
<div class ="productsBackgroundColor ">
    <div class="greyText">
<!--        <div class="test">-->

            <?php
            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
            if (!$product->isAdaptation()) {
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

                if (!$product->isAdaptation()) {
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
$output = "Create and Edit Sections:";
echo '<div id="sections">
    
<fieldset>
<legend>' . $output . '</legend>
<div class="wideLeftFloatDiv" style="width:100%;" >
<div class="leftColumnDiv" >';



echo $contentManager->getContentTree(TRUE);
echo '</div>';



//echo '<div class="centerColumnDiv"">';
echo '<div class="rightWideColumnDiv" style="width:75%;" ">';
echo $contentManager->showInput($this->getParam('prevAction'));
echo "</div></div></fieldset></div>";

$link = '<link href="' . $this->getResourceUri('bubble-tooltip.css') . '" rel="stylesheet" type="text/css"/>';
       $this->appendArrayVar('headerParams', $link);
?>
    <div id="bubble_tooltip">
    <div class="bubble_top"><span></span></div>
    <div class="bubble_middle"><span id="bubble_tooltip_content">Content is comming here as you probably can see.Content is comming here as you probably can see.</span></div>
    <div class="bubble_bottom"></div>
    </div>

</div>

<!--        </div>
    </div>-->
<!--<link href="chapter3.html" type="text/html" />-->