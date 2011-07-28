<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$adaptationstring = 'parent_id is null and deleted = 0';

if ($finalstring == null) {
    $finalstring = 'parent_id is null and deleted = 0';
    $TotalEntries = 'parent_id is null and deleted = 0';
}
$this->setLayoutTemplate('maincontent_layout_tpl.php');

	
$js = '<script language="JavaScript" src="' . $this->getResourceUri('filterproducts.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);

$js = '<script language="JavaScript" src="' . $this->getResourceUri('jquery.bubblepopup.v2.3.1.min.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);
?>
	

<div class="mainWrapper">
    
   

    <div class="mainContentHolder">
      
        <div class="subNavigation"></div>
        <!-- Left Colum -->



        <?php
        $filtering = $this->getobject('filterdisplay', 'unesco_oer');
       
        echo $filtering->SideFilter('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
        ?>




    </div>
    <br><br>
    <div class="blueBackground rightAlign">
        <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
        <a href="#" class="resetLink"> 
            <?php
            $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));

            $button->onclick = "javascript:ajaxFunction23('$adaptationstring');ajaxFunction($i)";
            echo $button->show();

            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
            $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset', 'unesco_oer');
            echo $abLink->show();
            ?>

        </a>
    </div>
    <div class="filterheader">




    </div>
    <div class="rssFeed">
        <img src="skins/unesco_oer/images/small-icon-rss-feed.png" alt="RSS Feed" width="18" height="18" class="imgFloatRight">
        <div class="feedLinkDiv"><a href="#" class="rssFeedLink">RSS Feed</a></div>
    </div>




</div>
<!-- Center column DIv -->
<div class="centerColumnDiv">
    <div class="GridListViewDiv">
        <div class="sortBy">
            
                                                                                                                                                                                                                                            <!--                                                <select name="" class="contentDropDown">
                                                                                                                                                                                                                                                 <option value="">Date Added</option>
                                                                                                                                                                                                                                             </select>-->
            <?php
            
         
            
            $sort = $this->getobject('filterdisplay', 'unesco_oer');
            echo $sort->SortDisp('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
            ?>


                                                                                                                                                                                                    <!--                                                <select name="" class="contentDropDown">
                                                                                                                                                                                                        <option value="">DESC</option>
                                                                                                                                                                                                    </select>-->
        </div>
        <div class="viewGrid">
            <div class="viewAsDiv">View as: </div>


            <?php
            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
            echo $abLink->show();
            ?>

            <div class="gridListDivView">
                <?php
                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
                $abLink->link = 'GRID';
                echo $abLink->show();
                ?>
            </div>

            <div class="gridListPipe">|</div>

            <?php
            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1b_tpl.php')));
            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
            echo $abLink->show();
            ?>

            <div class="gridListDivView">

                <?php
                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1b_tpl.php')));
                $abLink->link = 'LIST';
                echo $abLink->show();
                ?>
            </div>

            <?php
            $divOpen = '<div class="gridListPipe">|</div>
                                            <div class="gridListDivView">';
            $divClose = '</div>';
            if ($this->objUser->isLoggedIn() && $this->objUser->isAdmin()) {
                $abLink = new link($this->uri(array("action" => "createProduct", 'prevAction' => 'home')));
                $abLink->link = '<img src="skins/unesco_oer/images/icon-new-product.png" alt="New Product" width="20" height="20" class="imgFloatRight">';

                $newProductLink = new link($this->uri(array("action" => "saveProductMetaData", 'prevAction' => 'home')));
                $newProductLink->link = "New product";

                echo $divOpen;
                echo $abLink->show();
                echo $newProductLink->show();
                echo $divClose;
            }
            ?>


        </div>
    </div>
    <div id='filterDiv' title ="1a" >
        <?php
        //Creates chisimba table
        
        
        
        $objTable = $this->getObject('htmltable', 'htmlelements');
        $objTable->cssClass = "gridListingTable";
        $objTable->width = NULL;


        $products = $this->objDbProducts->getFilteredProducts($finalstring);

        $newRow = true;
        $count = 0;
        $noOfAdaptations = 0;

        foreach ($products as $product) {               //populates table
            if ($product['parent_id'] == null) {
                $count++;
                $product['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($product['id']);

                $languages = $this->objDbAvailableProductLanguages->getProductLanguage($product['id']);
                $theProduct = $product + $languages;

                if ($newRow) {
                    $objTable->startRow();
                    $objTable->addCell($this->objProductUtil->populateGridView($theProduct, $noOfAdaptations));
                    $newRow = false;
                } else {
                    $objTable->addCell($this->objProductUtil->populateGridView($theProduct, $noOfAdaptations));
                }
            }

            if ($count == 3) {
                $newRow = true;
                $objTable->endRow();
                $count = 0;
            }
        }
        echo $objTable->show();
        ?>
    </div>  
    

   
      <?php
        
       $products = $this->objDbProducts->getFilteredProducts($finalstring);
       $bookmark = $this->objbookmarkmanager->populateGridView($products);
       echo $bookmark;
    
      
      
      
          ?>
                              
                               <!--
                                          <table class="gridListingTable" cellspacing="0" cellpadding="0">
                                              <tr>
                                                  <td>
                                                      <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                      <div class="imageGridListing">
                                                          <div class="imageTopFlag"></div>
                                                          <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                          <div class="imageBotomFlag"></div>
                                                      </div>
                                                      <br>
                                                      <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                                                      <div class="listingLanguageLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                          <div class="listingLanuagesDropdownDiv">
                                                              <select name="" class="listingsLanguageDropDown">
                                                                  <option value="">Languages</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="listingAdaptationsLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                          <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                      </div>
                                                  </td>
                                                  <td>
                                                      <div class="newImageIcon"></div>
                                                      <div class="imageGridListing">
                                                          <div class="imageTopFlag"></div>
                                                          <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                          <div class="imageBotomFlag"></div>
                                                      </div>
                                                      <br>
                                                      <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                                                      <div class="listingLanguageLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                          <div class="listingLanuagesDropdownDiv">
                                                              <select name="" class="listingsLanguageDropDown">
                                                                  <option value="">Languages</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="listingAdaptationsLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                          <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                      </div>
                                                  </td>
                                                  <td>
                                                      <div class="newImageIcon"></div>
                                                      <div class="imageGridListing">
                                                          <div class="imageTopFlag"></div>
                                                          <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                          <div class="imageBotomFlag"></div>
                                                      </div>
                                                      <br>
                                                      <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                                                      <div class="listingLanguageLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                          <div class="listingLanuagesDropdownDiv">
                                                              <select name="" class="listingsLanguageDropDown">
                                                                  <option value="">Languages</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="listingAdaptationsLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                          <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                      </div>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td>
                                                      <div class="newImageIcon"></div>
                                                      <div class="imageGridListing">
                                                          <div class="imageTopFlag"></div>
                                                          <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                          <div class="imageBotomFlag"></div>
                                                      </div>
                                                      <br>
                                                      <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                                                      <div class="listingLanguageLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                          <div class="listingLanuagesDropdownDiv">
                                                              <select name="" class="listingsLanguageDropDown">
                                                                  <option value="">Languages</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="listingAdaptationsLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                          <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                      </div>
                                                  </td>
                                                  <td>
                                                      <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                      <div class="imageGridListing">
                                                          <div class="imageTopFlag"></div>
                                                          <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                          <div class="imageBotomFlag"></div>
                                                      </div>
                                                      <br>
                                                      <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                                                      <div class="listingLanguageLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                          <div class="listingLanuagesDropdownDiv">
                                                              <select name="" class="listingsLanguageDropDown">
                                                                  <option value="">Languages</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="listingAdaptationsLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                          <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                      </div>
                                                  </td>
                                                  <td>
                                                      <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                      <div class="imageGridListing">
                                                          <div class="imageTopFlag"></div>
                                                          <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                          <div class="imageBotomFlag"></div>
                                                      </div>
                                                      <br>
                                                      <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                                                      <div class="listingLanguageLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                          <div class="listingLanuagesDropdownDiv">
                                                              <select name="" class="listingsLanguageDropDown">
                                                                  <option value="">Languages</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="listingAdaptationsLinkAndIcon">
                                                          <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                          <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                      </div>
                                                  </td>
                                              </tr>
                                          </table> -->

    <!-- Pagination-->
    <div class="paginationDiv">


        <?php
//                $Pagination = $this->getobject('filterdisplay', 'unesco_oer');
//                $Pagination->Pagination('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum, $pageinfo)
        ?>




        <!--                                            <div class="paginationLinkDiv">
                                                        <a href="#" class="pagination">Prev</a>
                                                        <a href="#" class="pagination">1</a>
                                                        <a href="#" class="pagination">2</a>
                                                        <a href="#" class="pagination">3</a>
                                                        <a href="#" class="pagination">4</a>
                                                        <a href="#" class="pagination">5</a>
                                                        <a href="#" class="pagination">6</a>
                                                        <a href="#" class="pagination">7</a>
                                                        <a href="#" class="pagination">8</a>
                                                        <a href="#" class="pagination">9</a>
                                                        <a href="#" class="pagination">10</a>
                                                        <a href="#" class="pagination">Next</a>
                                                    </div>-->
    </div>
</div>

<!-- Right column DIv -->
<div class="rightColumnDiv">
    <div class="rightColumnDiv">
        <div class="featuredHeader">FEATURED UNESCO PRODUCTS</div>
        <div class="rightColumnBorderedDiv">
            <div class="rightColumnContentPadding">
                <?php
                $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
                $featuredProduct = $this->objDbProducts->getProductByID($featuredProductID);

                echo $this->objFeaturedProducUtil->featuredProductView($featuredProduct);
                ?>
                <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">
                        <?php
                        //The reason it does not display the number of adaptations is because this uses puid as the id and the function getNoOfAdaptations uses id as the id


                        $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($featuredProduct['id']);
                        echo"See all adaptations ($NOofAdaptation)"; // This must be a link;
                        ?>
                    </a></div>
            </div>
        </div>
        <div id="stats  "> 
            <div class="spaceBetweenRightBorderedDivs">
                <div class="featuredHeader innerPadding">MOST...</div>
            </div>
            <!-- tabs
            <div class="tabsOnState">ADOPTED</div>
            <div class="tabsOffState">RATED</div>
            <div class="tabsOffState">COMMENTED</div>
            -->

            <!--                        <div class="rightColumnBorderedDiv">-->
            <div class="rightColumnContentPadding">


                <?php
                $objTabs = $this->newObject('tabcontent', 'htmlelements');
                $objTabs->setWidth(180);



                $mostAdapted = $this->objProductUtil->displayMostAdapted($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $displayAllMostAdaptedProducts);
                $mostCommented = $this->objProductUtil->displayMostCommented($this->objDbProducts, $this->objDbComments);
                $mostRated = $this->objProductUtil->displayMostRated($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $this->objDbProductRatings);
                $objTabs->addTab('Adapted', $mostAdapted);
                $objTabs->addTab('Rated', $mostRated);
                $objTabs->addTab('Comments', $mostCommented);
                echo $objTabs->show()
              
                        
                        
                        
                        
                ?>

                <!--                            </div>-->
            </div>
        </div>
        <!--
        <div class="rightColumnBorderedDiv">
            <div class="rightColumnContentPadding">
                <div class="leftImageTabsList"><img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="placeholder" width="45" height="49"></div>
                <div class="rightTextTabsList">
                        	Model Curricula for Journalism Education<br><a href="#" class="adaptationLinks">11 adaptations</a>
                </div>
                <div class="tabsListingSpace"></div>
                <div class="leftImageTabsList"><img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="placeholder" width="45" height="49"></div>
                <div class="rightTextTabsList">
                        	Model Curricula for Journalism Education<br><a href="#" class="adaptationLinks">11 adaptations</a>
                </div>
                <div class="tabsListingSpace"></div>
                <div class="leftImageTabsList"><img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="placeholder" width="45" height="49"></div>
                <div class="rightTextTabsList">
                        	Model Curricula for Journalism Education<br><a href="#" class="adaptationLinks">11 adaptations</a>
                </div>
            </div>
        </div>
        -->
        <br>
           
    </div>
</div>


  