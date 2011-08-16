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


 var_dump($selectedproducts);
 
 

?>

<html>


<body>
	
        <div class="mainContentHolder">
        	<div class="subNavigation"></div>
            <div class="wideTopContentHolderDiv">
            	
                <div class="topHeadingDiv">
                <div class="productsBackgroundColor twelvePixelLeftPadding tenPixelTopPadding">
                	<div class="leftTopImage"><img src="images/adapted-product-grid-institution-logo-placeholder.jpg" width="45" height="49" ></div>
            <div class="rightTopContentAndLinks">
                   	  <h2 class="blueText">Model Curriculum for Journalism Education</h2>
                      	
                      <div class="listTopLinks">
                        <div class="productLinksViewDiv">
                            <img src="images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
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
                <div class="compareToolsHeading">Compare Tools</div>
                    <div class="compareToolsTextDiv">
                    <div class="leftFloat">Select/unselect item(s) from the list below or use the search to find section (courses):</div>
                    <div class="compareSearchBoxHolder"><input type="text" value="Ethics" class="compareSearchBox"></div>
                    <a href="#"><img src="images/icon-search.png" width="18" height="18"></a>   
                    
                    
                      <a id="compare" href="#"  >     <img src="images/icon-search.png" width="18" height="18"></a>   
            	</div>
            </div>
            
            <div class="slide">
            
            	<div class="toggleBoxProduct">
								<div class="leftTopImage"><img src="images/compare-selected-placeholder.jpg" width="27" height="29" ></div>
                                <h4 class="blueText">Model Curricula for Journalism</h4>
                                <img src="images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv">
                            	<a href="#" class="productsLink">Full view of product</a>
                            	</div>
                            
                                <div class="treeFrame">
                              
                                      <?php
                                
                                $product = $this->getObject('product', 'unesco_oer');
                                 
                                
                                $product->loadProduct($selectedproducts[0]);
                                $temp = $product->getParentID();
                       
                                $product->LoadProduct($temp);
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
                                <img src="images/icon-product-closed-folder.png" width="18" height="18" class="collapseIcon"> 
                                <div class="collapseText"><a href="" class="greyTextLink">Collapse all</a></div>
                                <img src="images/icon-product-opened-folder.png" width="18" height="18" class="collapseIcon"> 
                                <div class="collapseText"><a href="" class="greyTextLink">Expand all</a></div>
                            </div>
						</div>
                        
                        <div class="spaceNextToToggleBoxes"></div>
                        <div class="toggleBoxAdaptation">
							<div class="leftTopImage"><img src="images/compare-selected-placeholder.jpg" width="27" height="29" ></div>
                                <h4><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h4>
                                <img src="images/small-icon-make-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
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
                        
                        var link = '/unesco_oer/index.php?module=unesco_oer&action=CompareSelected&id=' + hiddenclass + '&productid=' + productid;
                        $('#compare').attr('href',link);
                  }
                  
                  
                  function compare(){
                      
                     //var ourArray = new Array();
                      $('.highlight').each(function() {
                            $(this).each(function(){ alert($(this).attr('tag'));});
                        });

                      //alert(ourArray[0]);
                      
                      
                      
                  }
                  
                  
              </script>
      <style>
      .highlight { background:yellow; }
      </style>
       
</body>
</html>