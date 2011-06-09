

<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');
$adaptationstring = "relation is null";

$js = '<script language="JavaScript" src="'.$this->getResourceUri('filterproducts.js').'" type="text/javascript"></script>';
    $this->appendArrayVar('headerParams', $js);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>UNESCO</title>
        <!--[if IE]>
             <style type="text/css" media="screen">
             body {
    	behavior: url(csshover.htc);
             }
             </style>
         <![endif]-->
    </head>

    <body>
        <div class="blueHorizontalStrip"></div>
        <div class="mainWrapper">
            <div class="topContent">
                <?php
                if ($this->objUser->isLoggedIn()) {
                ?>
                    <div class="logOutSearchDiv">
                        <div class="logoutSearchDivLeft">
                            <div class="nameDiv"><?php echo "Hello" . " " . $this->objUser->fullname(); ?></div>
                            <div class="logoutDiv">
                                <div class="textNextToRightFloatedImage"><a href="#" class="prifileLinks">Log out</a></div>
                                <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">
                            </div>
                            <div class="profileBookmarkGroupsMessengerDiv">
                                <table class="profileBookmarkGroupsMessengerTable" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><img src="skins/unesco_oer/images/icon-my-profile.png" alt="My profile"></td>
                                        <td><a href="#" class="prifileLinks">My Profile</a></td>
                                        <td><img src="skins/unesco_oer/images/icon-my-bookmarks.png" alt="Bookmarks"></td>
                                        <td><a href="#" class="prifileLinks">My Bookmarks</a></td>
                                        <td><img src="skins/unesco_oer/images/icon-my-administration-tools.png" alt="Administration Tools"></td>
                                        <td><a href="#" class="prifileLinks">
                                            <?php
                                            $abLink = new link($this->uri(array("action" => "controlpanel")));
                                            $abLink->link = 'Administration Tools';
                                            echo $abLink->show();
                                            ?>
                                        </a></td>
                                </tr>
                                <tr>
                                    <td><img src="skins/unesco_oer/images/icon-my-groups.png" alt="My Groups"></td>
                                    <td><a href="#" class="prifileLinks">My Groups</a></td>
                                    <td><img src="skins/unesco_oer/images/icon-my-messenger.png" alt="My Messenger"></td>
                                    <td><a href="#" class="prifileLinks">My Messenger</a></td>
                                     <td><img src="skins/unesco_oer/images/icon-product-add-node.png"></td>
                                     <td><a href="#" class="prifileLinks">
                                            <?php
                                            $abLink = new link($this->uri(array("action" => "userRegistrationForm")));
                                            $abLink->link = 'Add User';
                                            echo $abLink->show();
                                            ?>
                                            </a></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <div class="logoutSearchDivRight">
                        <div class="searctInputTextDiv">
                            <div class="searchGoButton"><a href="">

<!--         <img src="skins/unesco_oer/images/button-search.png" class="searchGoImage" alt="Search"></a>-->
                                    <a href="" class="searchGoLink"></a></div>
                            <div class="searchInputBoxDiv">

                               <?php
    $Search = $this->getobject('filterdisplay', 'unesco_oer');
    echo $Search->Search('1b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
?>
                                            <!--
                                                                            <input type="text" name="" class="searchInput" value="Type search term here...">
                                                                            <select name="" class="searchDropDown">
                                                                                <option value="">All</option>
                                                                            </select>-->
                                        </div>
                                        <div class="textNextToRightFloatedImage">Search</div>
            <!--                            <img src="skins/unesco_oer/images/icon-search.png" alt="Search" class="imgFloatLeft">-->
                                    </div>
                                    <div class="facebookShareDiv">


                                        <!-- AddThis Button BEGIN -->
                                        <div class="shareDiv">
            <!--                                <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=jabulane"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share"></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=jabulane"></script>-->

                                            <!-- AddThis Button END -->
                                        </div>

                                        <div class="likeDiv">
            <!--                                <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fexample.com%2Fpage%2Fto%2Flike&amp;layout=button_count&amp;show_faces=true&amp;width=50&amp;action=like&amp;font=tahoma&amp;colorscheme=light&amp;"></iframe>-->

                                        </div>


                                    </div>
                                </div>
                            </div>
                <?php
                                        } else {
                ?>
                                            <div id="loginDiv">
                                                <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">  <a href="?module=security&action=login" >Log in</a>
                                            </div>
                <?php
                                        }
                ?>
                                        <div class="logoAndHeading">
                                            <img src="skins/unesco_oer/images/logo-unesco.gif" class="imgFloatRight" alt="logo">
                                            <div class="logoText">
                                                <span class="greyTitleText">Unesco&rsquo;s Open Educational Resources Platform</span><br>
                                                <h1>UNESCO OER PRODUCTS</h1>
                                            </div>
                                        </div>
                                        <div class="languagesDiv">
                                            <a href="" class="languagesLinks">English</a> |
                                            <a href="" class="languagesLinks">Français</a> |
                                            <a href="" class="languagesLinks">Español</a> |
                                            <a href="" class="languagesLinks">Русский</a> |
                                            <a href="" class="languagesLinks">لعربية</a> |
                                            <a href="" class="languagesLinks">中文</a>
                                        </div>
                                        <div class="mainNavigation">
                                            <div class="navitemOnstate">
                                                <div class="navitemInnerOnstate">
                            <?php
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is null', "page" => '1a_tpl.php')));
                                        $abLink->link = 'UNESCO OER PRODUCTS';
                                        echo $abLink->show();
                            ?>
                                    </div>
                                </div>
                                <div class="mainNavPipe">&nbsp;</div>
                                <div class="navitem">
                                    <div class="navitemInner">
                            <?php
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is not null', "page" => '2a_tpl.php')));
                                        $abLink->link = 'PRODUCT ADAPTATIONS';
                                        echo $abLink->show();
                            ?>
                                    </div>
                                </div>
                                <div class="mainNavPipe"></div>
                                 <div class="navitem">
                                    <div class="navitemInner"><a href="#">GROUPS</a></div>
                                </div>
                                <div class="mainNavPipe"></div>
                                <div class="navitem">
                                    <div class="navitemInner"><a href="#">REPORTING</a></div>
                                </div>
                                <div class="mainNavPipe"></div>
                                <div class="navitem">
                                    <div class="navitemInner"><a href="#">ABOUT</a></div>
                                </div>
                                <div class="mainNavPipe"></div>
                                <div class="navitem">
                                    <div class="navitemInner"><a href="#">CONTACT</a></div>
                                </div>
                            </div>
                        </div>

                        <div class="mainContentHolder">
                            <div class="subNavigation"></div>
                            <!-- Left Colum -->
                         <?php
                         
                         
                          $filtering = $this->getobject('filterdisplay', 'unesco_oer');
                        echo $filtering->SideFilter('1b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
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

        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is null', "page" => '1a_tpl.php')));
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
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation', "page" => '1a_tpl.php')));
                                        $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
                                        echo $abLink->show();
                            ?>

                                        <div class="gridListDivView">
                                <?php
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is null', "page" => '1a_tpl.php')));
                                        $abLink->link = 'GRID';
                                        echo $abLink->show();
                                ?>
                                    </div>

                                    <div class="gridListPipe">|</div>

                            <?php
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is null', "page" => '1b_tpl.php')));
                                        $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                                        echo $abLink->show();
                            ?>

                                        <div class="gridListDivView">

                                <?php
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is null', "page" => '1b_tpl.php')));
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
                                        foreach ($products as $product) {
                                            //Get number of adaptations
                                            $product['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($product['id']);
                                            $languages = $this->objDbAvailableProductLanguages->getProductLanguage($product['id']);
                                            $theProduct = $product + $languages;

                                            echo $this->objProductUtil->populateListView($theProduct);
                                        }
                                        //echo $this->objProductUtil->populateListView();
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
                                        <div class="featuredHeader">FEATURED UNESCO PRODUCTS</div>
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

                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">
                                        <?php
//                                        $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
//                                        $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($featuredProductID);
//                                        echo"See all adaptations ($NOofAdaptation)"// This must be a link;
                                        $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($featuredProduct['id']);
                                        echo"See all adaptations ($NOofAdaptation)"; // This must be a link;
                                        ?>
                                    </a></div>
                            </div>
                        </div>
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
                                        $mostAdapted = $this->objProductUtil->displayMostAdapted($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution);
                                        $mostCommented = $this->objProductUtil->displayMostCommented($this->objDbProducts, $this->objDbComments);
                                        $mostRated = $this->objProductUtil->displayMostRated($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $this->objDbProductRatings);
                                        $objTabs->addTab('Adapted', $mostAdapted);
                                        $objTabs->addTab('Rated', $mostRated);
                                        $objTabs->addTab('Commented', $mostCommented);
                                        echo $objTabs->show();
                                ?>

<!--                            </div>-->
                        </div>
                        <br>
                    </div>
                </div>
            </div>
            <!-- Footer-->
            <div class="footerDiv">
                <div class="footerLinksLists">
                    <div class="footerLinksHeadings">Links Set One</div>
                    <a href="" class="footerLink">Link 1</a><br>
                    <a href="" class="footerLink">Link 2</a><br>
                    <a href="" class="footerLink">Link 3</a>
                </div>
                <div class="footerLinksLists">
                    <div class="footerLinksHeadings">Links Set Two</div>
                    <a href="" class="footerLink">Link 4</a><br>
                    <a href="" class="footerLink">Link 5</a><br>
                    <a href="" class="footerLink">Link 6</a>
                </div>
                <div class="footerLinksLists">
                    <div class="footerLinksHeadings">Links Set Three</div>
                    <a href="" class="footerLink">Link 7</a><br>
                    <a href="" class="footerLink">Link 8</a><br>
                    <a href="" class="footerLink">Link 9</a>
                </div>
                <div class="footerLinksLists">
                    <div class="footerLinksHeadings">Links Set Four</div>
                    <a href="" class="footerLink">Link 10</a><br>
                    <a href="" class="footerLink">Link 11</a><br>
                    <a href="" class="footerLink">Link 12</a>
                </div>
                <div class="footerBottomText">
                    <img src="skins/unesco_oer/images/icon-footer.png" alt="CC" width="80" height="15" class="imageFooterPad">
                    <a href="" class="footerLink">UNESCO</a> |
                    <a href="" class="footerLink">Communication and Information</a> |
                    <a href="" class="footerLink">About OER Platform</a> |
                    <a href="" class="footerLink">F.A.Q.</a> |
                    <a href="" class="footerLink">Glossary</a> |
                    <a href="" class="footerLink">Terms of use</a> |
                    <a href="" class="footerLink">Contact</a> |
                    <a href="" class="footerLink">Sitemap</a> | &copy; UNESCO 1995-2011
                </div>
            </div>
        </div>
    </body>
</html>


