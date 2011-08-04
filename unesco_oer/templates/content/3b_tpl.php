<?php
$this->loadClass('commentmanager', 'unesco_oer');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dbcomments', 'unesco_oer');
$this->loadClass('treemenu', 'tree');
$this->loadClass('treenode', 'tree');
$product = $this->getObject('product', 'unesco_oer');
$product->loadProduct($productID);

$js = '<script language="JavaScript" src="' . $this->getResourceUri('filterproducts.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);

//load java script
$js = '<script language="JavaScript" src="' . $this->getResourceUri('ratingsys.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);
?> 


  


<div class="mainContentHolder">
        	<div class="subNavigation"></div>
            <div class="breadCrumb tenPixelLeftPadding">
                <a href="#" class="productBreadCrumbColor">UNESCO OER Products</a> | 
                <a href="#" class="productBreadCrumbColor">Model Curriculum for Journalism Education</a> |
                Adaptations
            </div>
            <div class="productsBackgroundColor">
            <div class="TopImageAndHeading tenPixelTopPadding">
              <img src=' <?php     echo $product->getThumbnailPath(); ?>' width="45" height="49" class="leftTopImage">
              <h2 class="blueText">
                  
                           <?php
                echo $product->getTitle();
             
                ?>
              </h2>
        	</div>
          <div class="wideLeftFloatDiv">
        	<!-- Left Colum -->
        	<div class="leftColumnDiv">
            	<div class="moduleHeader blueText">FILTER PRODUCTS</div>
                <div class="blueNumberBackground">
                	<div class="iconOnBlueBackground"><img src="skins/unesco_oer/skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                    <div class="numberOffilteredProducts">56</div>
                </div>
<div class="moduleSubHeader">Product matches filter criteria</div>
                <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/skins/unesco_oer/images/icon-filter-type.png" alt="Type of product" class="modulesskins/unesco_oer/skins/unesco_oer/images">Type of product</div>
                <div class="blueBackground blueBackgroundCheckBoxText">
                	<input type="checkbox"> Model<br>
                    <input type="checkbox"> Guide<br>
                    <input type="checkbox"> Handbook<br>
                    <input type="checkbox"> Manual<br>
                    <input type="checkbox"> Bestoractile<br>
                </div>
                <br>
                <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-theme.png" alt="Theme" class="modulesskins/unesco_oer/images">Theme</div>
                <div class="blueBackground">
                	<select name="theme" id="theme" class="leftColumnSelectDropdown">
                    	<option value="">All</option>
                    </select>
                </div>
                <br>
                <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-languages.png" alt="Language" class="modulesskins/unesco_oer/images">Language</div>
                <div class="blueBackground">
                	<select name="language" id="language" class="leftColumnSelectDropdown">
                    	<option value="">All</option>
                    </select>
                </div>
                <br>
                <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesskins/unesco_oer/images">Author</div>
                <div class="blueBackground">
                	<select name="author" id="author" class="leftColumnSelectDropdown">
                    	<option value="">All</option>
                    </select>
                </div>
                <br>
                <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesskins/unesco_oer/images">Items per page</div>
                <div class="blueBackground">
                	<select name="items_per_page" id="items_per_page" class="leftColumnSelectDropdown">
                    	<option value="">All</option>
                    </select>
                </div>
                <br><br>
                <div class="blueBackground rightAlign">
                	<img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                    <a href="#" class="resetLink">RESET</a> 
                </div>
            </div>
                
                
                
        	<!-- Center column DIv -->
            <div class="rightWideColumnDiv">
            <div class=""><input type="checkbox"> Toggle
             <img src="skins/unesco_oer/images/icon-compare-adaptations.png" class="toogleImagePadding">
<!--              <a href="#" class="pinkTextLink">Compare
              selected adaptations</a></div>-->
  <?php
            $abLink = new link('#');
             $abLink->cssClass = 'pinkTextLink';
             $abLink->cssId = 'compareproduct';
             $abLink->link = 'Compare';
             echo $abLink->show();
            
            
            ?>
            </div>
            <table class="threeAListingTable" cellspacing="0" cellpadding="0">
               	  <tr>
                      <?php
                           $form = new form("compareprods", $this->uri(array('action' => 'CompareProducts')));
                         $products = $this->objDbProducts->getadapted($productID);
                foreach ($products as $product){
                    
                    $groupid = $this->objDbProducts->getAdaptationDataByProductID($product['id']);
                    $grouptitle =  $this->objDbGroups-> getGroupName($groupid['group_id']);
                   $thumbnail = $this->objDbGroups->getThumbnail($groupid['group_id']);
                   
                $checkbox = new checkbox('selectedusers[]', $product['id']);
                $checkbox->value = $product['id'];
                $checkbox->cssId = 'user_' . $product['id'];
               
           
                 
                  $form->addToForm('<td>
                            
                            <div class="adaptedByDiv3a">Adapted by:</div>
                            <div class="gridSmallImageAdaptation">
                            	<img src="' . $thumbnail .'" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                <span class="greyListingHeading">
                            
                                    
                                    
                                </span>
                  			</div>
                            <div class="gridAdaptationLinksDiv">' .
                    
                         $grouptitle
                       .'
                    
                    
                    
                            	
                            </div>
                            <div class="">
                            	<div class="product3aViewDiv">
                                    <div class="imgFloatRight">
                                      <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18">
                                    </div>
                                    <div class="listingAdaptationLinkDiv">
                                        <a href="#" class="adaptationLinks">Make a new adaptation using this adaptation</a>
                                    </div>
                           	  </div>
                                
                       		  <div class="product3aViewDiv">
                                    <div class="imgFloatRight">
                                    	<img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18">
                                </div>
                                    <div class="listingAdaptationLinkDiv">
                                    <a href="#" class="bookmarkLinks">bookmark</a>
                                 	</div>
                                </div>
                                 <div class="product3aViewDiv">
                                    <div class="imgFloatRight">');
                          
                          
                          $form->addToForm($checkbox->show());
                  
                            $form->addToForm('
                                    
                    
                                   <div class="listingAdaptationLinkDiv">
                                    <a href="#" class="bookmarkLinks">Compare</a>
                                 	</div>
                                </div>
                                
                                
                            </div>
                </td>
                
                ');
                    
                }
                
                echo $form->show();      
                    	
                ?>
                            
                            
                            
                            
                      
                    </tr>
              </table>
                
                <script src="http://code.jquery.com/jquery-latest.js"></script>
     <script>
               $(document).ready($('#compareproduct').click(function(){
                     document.forms['compareprods'].submit();
                  
                 
                  

                  }));

</script>
