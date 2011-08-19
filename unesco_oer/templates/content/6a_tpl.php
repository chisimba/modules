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


 //var_dump($selectedproducts);
 $this->loadClass('link', 'htmlelements');
     $this->loadClass('textinput', 'htmlelements');
    $product = $this->getObject('product', 'unesco_oer');
                                 
                                
                                $product->loadProduct($selectedproducts[0]);
                                $temp = $product->getParentID();
                       
                                $product->LoadProduct($temp);

?>

<html>


<body>
	
        <div class="mainContentHolder">
        	<div class="subNavigation"></div>
                 <div class="breadCrumb tenPixelLeftPadding">
                <a href="#" class="productBreadCrumbColor">
                <?php
    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
    $abLink->link = 'UNESCO OER Products';
    $abLink->cssClass = "blueText noUnderline";
    echo $abLink->show();
    ?>
                
                
                
                </a> | 
                <a href="#" class="productBreadCrumbColor">
                <?php
    
    $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $productID)));
    $abLink->link = $product->getTitle();
    $abLink->cssClass = "blueText noUnderline";
    

   echo $abLink->show();
    ?>
                
              </a> |
                Compare
            </div>
                
            <div class="wideTopContentHolderDiv">
            	
                <div class="topHeadingDiv">
                <div class="productsBackgroundColor twelvePixelLeftPadding tenPixelTopPadding">
                     <?php
                    
                  
                                echo ' <div class="leftTopImage"><img src="'. $product->getThumbnailPath() .' " width="45" height="49" ></div> ';
                               echo '   <div class="rightTopContentAndLinks">
                   	  <h2 class="blueText">'.$product->getTitle().' </h2>';
                    
                    
                    
                    ?>
                	
           
                      	
                      <div class="listTopLinks">
                        <div class="productLinksViewDiv">
                            <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>
                        </div>
              </div>
                        
                      

                  </div>
                </div>
                </div>
            </div>
            
        	<!-- Left Wide column DIv -->
          <div class="CompareWideListDiv">
            	
            <div class="compareTools">
               <div class="compareTools">
                <div class="compareToolsHeading">Compare Tools</div>
                      <img src="skins/unesco_oer/images/icon-add-to-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">Add to adaptation</a></div>
                        
                      <a href="#"><img src="images/icon-find-related.png" alt="Find Related" width="18" height="18"class="smallLisitngIcons"></a>
                        <div class="textNextToTheListingIconDiv"><a href="#" class="greySmallLink">Find related</a></div>
                        
                      <img src="skins/unesco_oer/images/icon-compare-adaptations-off.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                      <div class="textNextToTheListingIconDiv"><a href="#" id="compare" class="adaptationLinks">Compare Selected</a></div>
                        
                        <img src="skins/unesco_oer/images/icon-clear-selection-off.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                      <div class="textNextToTheListingIconDiv"><span class="nonActiveLinks">Clear selection</span></div>
                        
                      <div class="compareSearchBoxHolder">
                          <?php
                           $textinput = new textinput('SearchInput');
                             $textinput->cssClass = "compareSearchBox";
                             $textinput->cssId = 'search';
                          
                          ?>
                          
                          
                         </div><?php echo $textinput->show()?>
                      <a href="#" id="searchlink" onclick= javascript:test()><img src="skins/unesco_oer/images/icon-search.png" width="18" height="18"></a>                   
           	  </div>
            	</div>
            </div>
            
            <div class="slide">
            
            	<div class="toggleBoxProduct">
                    <?php
                
                                echo ' <div class="leftTopImage"><img src="'. $product->getThumbnailPath() .' " width="27" height="29" ></div> ';
                               echo '  <h4 class="blueText">'.$product->getTitle().'</h4>';
                    
                    ?>
                               
                              
                                <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv">
                            	<a href="#" class="productsLink">Full view of product</a>
                            	</div>
                            
                                <div class="treeFrame">
                              
                                      <?php
                                
                               
                                 $content = $product->getContentManager();
                                   
                                  
                                echo     $content->getContentTree(FALSE,TRUE,False,$selectedproducts);
                                
                                
                                ?>
                                    
                                    
                                    
                                </div>
                                
                                <?php
                                $product = $this->getObject('product', 'unesco_oer');
                                foreach ($selectedproducts as $selectedproduct){
//                                    $product = $this->newObject('product', 'unesco_oer');
                                  
                                    $product->loadProduct($selectedproduct);
                      
                                    $content = $product->getContentManager();
                                    
                                    $contentHTML = $content->getContentTree(FALSE,TRUE, TRUE,$selectedproducts);
                                    
                               
                                      
           
                                    echo '   
                                <div class="toogle">
                                <div class="collapseIcon"><input type="checkbox"></div> <div class="collapseText">Toggle</div> 
                                <img src="skins/unesco_oer/images/icon-product-closed-folder.png" width="18" height="18" class="collapseIcon"> 
                                <div class="collapseText"><a href="" class="greyTextLink">Collapse all</a></div>
                                <img src="skins/unesco_oer/images/icon-product-opened-folder.png" width="18" height="18" class="collapseIcon"> 
                                <div class="collapseText"><a href="" class="greyTextLink">Expand all</a></div>
                            </div>
						</div>
                        
                        <div class="spaceNextToToggleBoxes"></div>
                        <div class="toggleBoxAdaptation">
							<div class="leftTopImage"><img src="'. $product->getThumbnailPath() .'" width="27" height="29" ></div>
                                <h4><a href="#" class="adaptationListingLink">'.$product->getTitle().'</a></h4>
                                <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                
                                
                                <div class="treeFrame">
                            	<ul class="ulMinusPublish">'. $contentHTML . '
                      
                        </ul> 
                            </div>
                                       ';
                                    
                              
                                }
                                
                                
                           
                                ?>
                                
                                
                                
                             
            
          </div>
         </div>
      <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
              <script>
                  function highlight(id,productid)
                  {
//                      $('#' + id).parent().css('background-color', 'red');  
                        //$('#' + id).parent().toggleClass('highlight');
                        
                        var hiddenclass = $('#' + id).attr('class');
                        $('.highlight').toggleClass('highlight');
                        $('.' + hiddenclass).parent().toggleClass('highlight');
                        
                        var link = '/unesco_oer/index.php?module=unesco_oer&action=CompareSelected&id=' + id + '&productid=' + productid;
                        $('#compare').attr('href',link);
                  }
                  
                  
                  function test(value){
                      
                     //var ourArray = new Array();
                    var temp = $('#search').attr('value');
                   
                  
                    $().parent().toggleClass('highlight');
                     
                        
                        
                      

                      //alert(ourArray[0]);
                      
                      
                      
                  }
                  
                  
              </script>
      <style>
      .highlight { background:yellow; }
      </style>
       
</body>
</html>