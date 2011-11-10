

<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');
$adaptationstring = "parent_id is null and deleted = 0";

$js = '<script language="JavaScript" src="'.$this->getResourceUri('filterproducts.js').'" type="text/javascript"></script>';
    $this->appendArrayVar('headerParams', $js);

$js = '<script language="JavaScript" src="' . $this->getResourceUri('addProduct.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);
?>
      <div class="mainWrapper">
           

                        <div class="mainContentHolder">
                            <div class="subNavigation"></div>
                             <div class="leftColumnDiv">
                            <!-- Left Colum -->
                         <?php
                         
                         
                          $filtering = $this->getobject('filterdisplay', 'unesco_oer');
                        echo $filtering->SideFilter('1b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
?>


                            
                           
                    <br><br>
                    <div class="blueBackground rightAlign">
                        <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                    
 <?php
        $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));
       
        $button->onclick = "javascript:ajaxFunction23('$adaptationstring');ajaxFunction($i)";
       //echo $button->show();
echo $imgButton = "<input name='Go' class='searchGoLink' onclick='javascript:ajaxFunction23(".'"'.$adaptationstring.'"'.");ajaxFunction($i)' type='image' src='skins/unesco_oer/images/button-search.png' value='Find'> </input>";
        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
        $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset', 'unesco_oer');
       echo $abLink->show();
        
           ?>
      
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

                    <?php
                           $search = $this->getobject('filterdisplay', 'unesco_oer');
                          echo $search->SortDisp('1b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);

                    ?>






                                        <!--

                                        Sort By:
                                                                    <select name="" class="contentDropDown">
                                                                        <option value="">Date Added</option>
                                                                    </select>
                                                                    <select name="" class="contentDropDown">
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
                                </div>
                            </div>
                            <!--Display the products in a list view-->
                              <div id='filterDiv' title = "1b" >
                                  
                                  

                    <?php
                                        $objTable = $this->getObject('htmltable', 'htmlelements');
                                        $products = $this->objDbProducts->getFilteredProducts($finalstring);

                                        //Loop through the products and display each in it's own line
                                          $TotalRecords = $this->objDbProducts->getTotalEntries($adaptationstring);
                                        
                                        
                                    //    foreach ($products as $product) {
                                            //Get number of adaptations
//                                            $product['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($product['id']);
//                                            $languages = $this->objDbAvailableProductLanguages->getProductLanguage($product['id']);
//                                            $theProduct = $product + $languages;

                            //               echo $this->objProductUtil->populateListViewtemp($products);
                                     //   }
                                       echo $this->objProductUtil->populateListView('0',$TotalRecords ,$products);
                    ?>
                              </div>          <!--</div>-->



                                        <!--                          <div class="productsListView">
                                                                    <h2>The Net for Journalists</h2><br>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv">new</div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv">
                                                                            <select name="" class="listingsLanguageDropDown">
                                                                                <option value="">Languages</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="productsListView">
                                                                    <h2>The Entrepreneur’s Guide to Computer Recycling</h2><br>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv">
                                                                            <select name="" class="listingsLanguageDropDown">
                                                                                <option value="">Languages</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="productsListView">
                                                                    <h2>Manual for Investigative Journalists</h2><br>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv">
                                                                            <select name="" class="listingsLanguageDropDown">
                                                                                <option value="">Languages</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="productsListView">
                                                                    <h2>Marovo Lagoon Encyclopaedia</h2><br>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv">
                                                                            <select name="" class="listingsLanguageDropDown">
                                                                                <option value="">Languages</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="productsListView">
                                                                    <h2>Nulla venenatis ligula a nulla lobortis convallis. Aenean vel arcu nunc, vel vestibulum lacus. Praesent quis lorem velit, vitae aliquet nisi. Suspendisse potenti.</h2><br>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv">new</div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                                                    </div>
                                                                    <div class="productlistViewLeftFloat">
                                                                        <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                                                        <div class="listingAdaptationLinkDiv">
                                                                            <select name="" class="listingsLanguageDropDown">
                                                                                <option value="">Languages</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                        -->

                                        <!-- Pagination-->
                                        <div class="paginationDiv">
                    <!--                                    <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>-->

                        <?php
                            $Pagination = $this->getobject('filterdisplay', 'unesco_oer');
                              $Pagination->Pagination('1b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum, $pageinfo)
?>
















                                        <!--                                    <div class="paginationLinkDiv">
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
                                        <div class="featuredHeader"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_featured', 'unesco_oer') ?></div>
                                        <div class="rightColumnBorderedDiv">
                                            <div class="rightColumnContentPadding">
                                <?php
//                                        $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
//                                        $featuredProduct = $this->objDbProducts->getAll("where puid = '$featuredProductID'");
//                                        if (sizeof($featuredProduct) > 0) {
//                                            //TODO error handling
//                                        }
//                                        echo $this->objFeaturedProducUtil->featuredProductView($featuredProduct[0]);
                                        $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
                                        $featuredProduct = $this->objDbProducts->getProductByID($featuredProductID);

                                        echo $this->objFeaturedProducUtil->featuredProductView($featuredProduct);
                                ?>

<!--                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">
                                        <?php
//                                        $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
//                                        $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($featuredProductID);
//                                        echo"See all adaptations ($NOofAdaptation)"// This must be a link;
                                        $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($featuredProduct['id']);
                                        echo"See all adaptations ($NOofAdaptation)"; // This must be a link;
                                        ?>
                                    </a></div>-->
                            </div>
                        </div>
                        <div class="spaceBetweenRightBorderedDivs">
                            <div class="featuredHeader innerPadding"> <?php   echo $this->objLanguage->languageText('mod_unesco_oer_most', 'unesco_oer') ?></div>
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
                                        $mostAdapted = $this->objProductUtil->displayMostAdapted($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution);
                                        $mostCommented = $this->objProductUtil->displayMostCommented($this->objDbProducts, $this->objDbComments);
                                        $mostRated = $this->objProductUtil->displayMostRated($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $this->objDbProductRatings);
                                        $objTabs->addTab($this->objLanguage->languageText('mod_unesco_oer_adapted', 'unesco_oer'), $mostAdapted);
                                        $objTabs->addTab($this->objLanguage->languageText('mod_unesco_oer_rated', 'unesco_oer'), $mostRated);
                                        $objTabs->addTab($this->objLanguage->languageText('mod_unesco_oer_Comments', 'unesco_oer'), $mostCommented);
                                        echo $objTabs->show();
                                ?>

<!--                            </div>-->
                        </div>
                        <br>
                    </div>
                </div>
            </div>
            <!-- Footer-->
            
        </div>
    </body>
</html>


