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
                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv wideradaptationLink">
                            	<a href="#" class="adaptationLinks">Make new adaptation using this UNESCO product</a>
                            </div>
                        </div>
                        
                        
                        <div class="productLinksViewDiv">
                            <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv wideradaptationLink"><a href="#" class="adaptationLinks">See existing adaptations of this UNESCO product</a></div>
                        </div>
                        
                      </div>

                  </div>
            
            <div class="hunderedPercentGreyHorizontalLine"></div>
            
            <div class="LeftWiderColumnDiv">
              
                <div class="pageBreadCrumb">
                 
                    
                    <a href="#" class="greyText Underline">Barchelor</a> | 
                    <a href="#" class="greyText Underline">Year 1</a> | 
                    <a href="#" class="greyText Underline">Term 1</a> | 
                    <span class="greyText">Foundation of Journalism Writing</span>
                   
                    
                    <br><br>
                </div>
                
                <div class="headingHolder">
                	<div class="heading2"><h2 class="greyText">
                               
                             <?php
                                
                                echo $existingContent->getTitle();
                          
                                ?>
                                
                               </h2></div>
                    <div class="icons2">
                    	<a href="#"><img src="skins/unesco_oer/images/icon-edit-section.png" alt="Edit" width="18" height="18"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-delete.png" width="18" height="18" alt="Delete"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-add-to-adaptation.png" width="18" height="18" alt="Add to adaptation"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-content-top-print.png" width="19" height="15" align="Print"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-download.png" alt="download" width="18" height="18"></a> 
                    </div>
                </div>
                
                <div class="contentDivThreeWider">
                  
                        </legend
                 <?php
              //  echo  $existingContent->showCurric();
             //  echo  $existingContent->showReadOnlyInput();
                  echo $displaytype
                 
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
