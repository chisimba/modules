

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

 $product = $this->getObject('product'); 
 $product->loadProduct($productID);
 $content = $product->getContentManager();
 $existingContent = $content->getContentByContentID($path);
 

?>
  <div class="mainContentHolder">
        	<div class="subNavigation"></div>
            <div class="wideTopContentHolderDiv">
            	
                <div class="topHeadingDiv">
              <div class="breadCrumb">
    <?php
    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
    $abLink->link = 'UNESCO OER Products';
    $abLink->cssClass = "blueText noUnderline";
    echo $abLink->show();
    ?>
    <!--    <a href="#" class="blueText noUnderline">UNESCO OER Products</a> -->
    |
    <a href="#" class="blueText noUnderline">
    <!--                        Model Curriculum for Journalism Education-->
    <?php
    
    $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $productID)));
    $abLink->link = $product->getTitle();
    $abLink->cssClass = "blueText noUnderline";
    

   echo $abLink->show();
    ?>
     | <a class="blueText noUnderline">
       Section View
     </a>
    </a>
</div>
                </div>
            </div>
        	<!-- Left Wide column DIv -->
            <div class="productsBackgroundColor">
            
            	<div class="leftTopImage tenPixelLeftPadding tenPixelTopPadding">
                     <?php
                $thumbnailPath = $product->getThumbnailPath();
                $imageTag = "<img src='$thumbnailPath' alt='Placeholder' width='121' height='156'<br>";
                echo $imageTag;
                ?>
                	
                </div>
        		
                <div class="rightTopContentAndLinks tenPixelLeftPadding tenPixelTopPadding">
                   	  <h2 class="blueText">
                              <?php
                                echo $product->getTitle();   
                                 ?> 
                             
                          </h2>
                      <div class="listTopLinks">
                        <div class="productLinksViewDiv">
                            
                            
                            <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>
                        </div>
              	</div>
                        
                      <div class="listTopLinks">
                        <div class="productLinksViewDiv">
                        
                            <div class="textNextToTheListingIconDiv wideradaptationLink">
                                <?php
                                
                                  if ($this->objUser->isLoggedIn()) {
                                      
                                  $adaptationImg = '<img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight"> <div class="textNextToTheListingIconDiv wideradaptationLink">'; 
                $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $productID , 'nextAction' => 'ViewProduct', 'cancelAction' => 'ViewProduct', 'cancelParams'=> "id=$productID"));
                $adaptLink = new link($uri);
                $adaptLink->cssClass = "adaptationLinks";
                $linkText = $this->objLanguage->languageText('mod_unesco_oer_product_new_adaptation', 'unesco_oer');
                $adaptLink->link = $linkText;

                echo $adaptationImg;
            
                echo $adaptLink->show();
                echo '</div>
                     </div>
                     </div>';
                                  }
                      ?>      
                         
                        
                        <div class="productLinksViewDiv">
                            <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv wideradaptationLink">
                                
                                
                                   <?php

                                $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $productID)));
                                $CommentLink->cssClass = 'adaptationLinks';
                                $CommentLink->link = ' See existing Adaptaions ('. $this->objDbProducts->getNoOfAdaptations($productID) . ')';
                                echo $CommentLink->show();
                                
                                
                                ?>
                            </div>
                        </div>
                        
                      </div>

                  </div>
            
            <div class="hunderedPercentGreyHorizontalLine"></div>
            
            <div class="LeftWiderColumnDiv">
              
                <div class="pageBreadCrumb">
                 
                  <?php
                 $navigation = '';
                 $treelevel = $existingContent->getParentList();
                 foreach($treelevel as $currentlevel){
                     $navigation .= $currentlevel . " | ";
        
                 }
                 $rest = substr($navigation, 0, -3);
                 echo $rest;
        
                 ?>
                    
                    <br><br>
                </div>
                
                <div class="headingHolder">
                	<div class="heading2"><h2 class="greyText">
                               
                             <?php
                                
                                echo $existingContent->getTitle();
                    
                                ?>
                                
                               </h2></div>
                    <div class="icons2">
<!--                    	<a href="#"><img src="skins/unesco_oer/images/icon-edit-section.png" alt="Edit" width="18" height="18"></a>-->
<!--                        <a href="#"><img src="skins/unesco_oer/images/icon-delete.png" width="18" height="18" alt="Delete"></a>-->
                   
                      
                      <?php
if ($this->objUser->isLoggedIn()) {
    $uri = $this->uri(array('action' => 'saveProductMetaData', 'productID' => $productID, 'nextAction' => 'ViewProduct', 'cancelAction'=>'ViewProduct', 'cancelParams'=>"id=$productID" ));
    $editLink = new link($uri);
    $editLink->title = $this->objLanguage->languageText('mod_unesco_oer_products_edit_metadata', 'unesco_oer');;
    $linkText = '<img src="skins/unesco_oer/images/icon-edit-section.png" alt="Print" width="19" height="15">';
    $editLink->link = $linkText;
    echo $editLink->show();

    $uri = $this->uri(array('action' => 'deleteProduct', 'productID' => $productID, 'prevAction' => 'home'));
    $deleteLink = new link($uri);
    $deleteLink->title = $this->objLanguage->languageText('mod_unesco_oer_products_delete', 'unesco_oer');;
    $deleteLink->cssId = "deleteProduct";
    $linkText = '<img src="skins/unesco_oer/images/icon-delete.png" alt="Print" width="19" height="15">';
    $deleteLink->link = $linkText;
    echo $deleteLink->show();

    $hiddenInput = new hiddeninput('hasAdaptations');
    $hiddenInput->value = $product->hasAdaptation();
    $hiddenInput->extra = "id='hasAdaptations'";
    echo $hiddenInput->show();

    $uri = $this->uri(array('action' => "createFeaturedProduct", 'id' => $productID));
    $editLink = new link($uri);
    $editLink->title = $this->objLanguage->languageText('mod_unesco_oer_products_make_featured', 'unesco_oer');;
    $linkText = '<img src="skins/unesco_oer/images/icon-content-top-email.png" alt="Print" width="19" height="15">';
    $editLink->link = $linkText;
    echo $editLink->show();
    
    
    
    
    
}

//$products = $this->objDbProducts->getProductByID($productID);
//echo $this->objProductUtil->populatebookmark($products);
?>

                    </div>
                </div>
                
                <div class="contentDivThreeWider">
                  

                 <?php
               
        
              echo  $existingContent->showReadOnlyInput(); 
              
              
              $createPdf = False;
             $objPdf = $this->getObject('tcpdfwrapper', 'pdfmaker');
        //Write pdf
        $objPdf->initWrite();
        $text = '<html> <h1> gggggggggggggggg </h1><br> gegerge</html>\r\n';
      
        $objPdf->partWrite($text);
        $objPdf->show();
          
      
                
         
                 
                 ?>
                </div>  
            </div>
            
            
            <div class="rightColumnDivWide rightColumnPadding">
                <fieldset>
                    
           
              <?php
            
              $content = $product->getContentManager();
              echo  $content->getContentTree(FALSE);
           
              ?>
                
                </fieldset> 
                
            </div>
            
            
            </div>
      </div>
         <script type="text/javascript">

                        jQuery(document).ready(function(){
//
                            jQuery("a[id=deleteProduct]").click(function(){
                                if(jQuery("#hasAdaptations").val()==true){
                                    alert('This product has adaptations, you may not delete it.');
                                } else {
                                    var r=confirm( "Are you sure you want to delete this product?");
                                    if(r== true){
                                        window.location=this.href;
                                    }
                                }
                                    return false;
                                }

                            );
                        }
//
                    );
                    </script>