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
            </div>
          <div class="wideLeftFloatDiv">
        	<!-- Left Colum -->
        	 <div class="leftColumnDiv">    

        <?php
        $filtering = $this->getobject('filterdisplay', 'unesco_oer');
        $products = $this->objDbProducts->getadapted($productID);
        echo $filtering->SideFilter('3b_tpl.php', $SortFilter, $TotalPages, $products, $browsemapstring, $NumFilter, $PageNum);
        
        
        ?>
                <div class="blueBackground rightAlign">
        <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
        <a href="#" class="resetLink"> 
            <?php
            $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));
            $i = 1;

            $button->onclick = "javascript:ajaxFunction23('$adaptationstring','$productID');ajaxFunction($i,'$productID')";
            echo $button->show();

            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
            $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset', 'unesco_oer');
            echo $abLink->show();
            ?>

        </a>
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

            
        
                    <div class="sortBy">
<?php
                          $search = $this->getobject('filterdisplay', 'unesco_oer');
                         echo $search->SortDisp('3b_tpl.php', $SortFilter, $TotalPages,  $NumFilter, $PageNum);

                    ?>



                                                <!--                            Sort By:
                                                                            <select name="" class="contentDropDown">
                                                                                <option value="">Date Added</option>
                                                                            </select>
                                                                            <select name="" class="contentDropDown">
                                                                                <option value="">DESC</option>
                                                                           </select>-->
                    </div>
         

            
 
                                          
                <div  id='filterDiv' title = "3b" float ="left">
           
                      <?php
                      
                      
                           $form = new form("compareprods", $this->uri(array('action' => 'CompareProducts')));
                           
                           $objTable = $this->getObject('htmltable', 'htmlelements');
                            $objTable->cssClass = "threeAListingTable";
                            $objTable->width = NULL;
                         
                            $newRow = true;
                            $count = 0;

//                           
//                           $form->addtoform(' <table class="threeAListingTable" cellspacing="0" cellpadding="0" ALIGN="LEFT">
//               	  <tr> ');
                         $products = $this->objDbProducts->getadapted($productID);
              
                         
            foreach ($products as $product){
                       
                       
                $count++;
                $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $productID , 'nextAction' => 'ViewProduct', 'cancelAction' => 'ViewProduct', 'cancelParams'=> "id=$productID"));
                $adaptLink = new link($uri);
                $adaptLink->cssClass = "adaptationLinks";
                $linkText = $this->objLanguage->languageText('mod_unesco_oer_product_new_adaptation', 'unesco_oer');
                $adaptLink->link = $linkText; 
                
               

                $groupid = $this->objDbProducts->getAdaptationDataByProductID($product['id']);
                $grouptitle =  $this->objDbGroups-> getGroupName($groupid['group_id']);
                $thumbnail = $this->objDbGroups->getThumbnail($groupid['group_id']);
                   
                    
                 $abLink= new link($this->uri(array("action" => '11a','id'=>$groupid['group_id'],"page"=>'10a_tpl.php')));
                 $abLink->link =  "<img src='" . $thumbnail . "' width='45' height='49' >";
                 $abLink->cssClass = "smallAdaptationImageGrid";
                   
                 $checkbox = new checkbox('selectedusers[]', $product['id']);
                 $checkbox->value = $product['id'];
                 $checkbox->cssId = 'user_' . $product['id'];
                 
                   $bookLink = new link('#');
                   $bookLink->cssClass = "bookmarkLinks";
                   $bookLink->cssId = $product['id'];
                   $linkText = '<img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"> Bookmark';
                   $bookLink->link = $linkText;
                   
               
               
                       $content = '
                            
                            <div class="adaptedByDiv3a">Adapted by:</div>
                            <div class="gridSmallImageAdaptation" >
                            	' . $abLink->show() .' 
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
                                        <a href="#" class="adaptationLinks">';
                       
                       if ($this->objUser->isLoggedIn()) {
                           $content .=  $adaptLink->show();
                                   
                           };
                       
                       
                       $content.= '</a>
                                    </div>
                           	  </div>
                                
                       		  <div class="product3aViewDiv">
                                    <div class="imgFloatRight">
                                   '. $bookLink->show().'
                                </div>
                                    
                                  
                                </div>
                                 <div class="product3aViewDiv">
                                    <div class="imgFloatRight">'.
                 
                         $checkbox->show().'
                  
                                   <div class="listingAdaptationLinkDiv">
                                    <a href="#" class="bookmarkLinks">Compare</a>
                                 	</div>';
                    
                
          if ($newRow) {
            $objTable->startRow();
            $objTable->addCell($content);
            $newRow = false;
        } else {
            $objTable->addCell($content);
        }
                

    if ($count == 3) {
        $newRow = true;
        $objTable->endRow();
        $count = 0;
    }
                
               
               } 
                
          $form->addToForm($objTable->show());
        echo $form->show();      
                
                
      
                
                
                    	
                ?>
                            
                  
                </div>
             
         
        <div id ="bookmarks">
                        <?php
                          $bookmark = $this->objbookmarkmanager->populateGridView($products);
        echo $bookmark;   
                        
                        ?>
                            
                </div>  
                
  </div>
            </div>
          
 
                <script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
                <script src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
                
     <script>
               $(document).ready($('#compareproduct').click(function(){
                     document.forms['compareprods'].submit();
                  
                 
                  

                  }));

</script>
