<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$adaptationstring = "relation is null";
if ($finalstring == null)

   {
            $finalstring = 'relation is null';
             $TotalEntries = 'relation is null';
    }

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
                            <div class="nameDiv"><?php echo $this->objUser->fullname(); ?></div>
                            <div class="logoutDiv">
                                <div class="textNextToRightFloatedImage"><a href="?module=security&action=logoff" class="prifileLinks">Log out</a></div>
                                <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">
                            </div>
                            <div class="profileBookmarkGroupsMessengerDiv">
                                <table class="profileBookmarkGroupsMessengerTable" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><img src="skins/unesco_oer/images/icon-my-profile.png"></td>
                                        <td><a href="#" class="prifileLinks">My Profile</a></td>
                                        <td><img src="skins/unesco_oer/images/icon-my-bookmarks.png"></td>
                                        <td><a href="#" class="prifileLinks">My Bookmarks</a></td>
                                        <td><img src="skins/unesco_oer/images/icon-my-administration-tools.png"></td>
                                        <td><a href="#" class="prifileLinks">
                                            <?php
                                            $abLink = new link($this->uri(array("action" => "addData")));
                                            $abLink->link = 'Administration Tools';
                                            echo $abLink->show();
                                            ?>
                                        </a></td>
                                </tr>
                                <tr>
                                    <td><img src="skins/unesco_oer/images/icon-my-groups.png"></td>
                                    <td><a href="#" class="prifileLinks">My Groups</a></td>
                                    <td><img src="skins/unesco_oer/images/icon-my-messenger.png"></td>
                                    <td><a href="#" class="prifileLinks">My Messenger</a></td>
                                    <td><img src="skins/unesco_oer/images/icon-product-add-node.png"></td>

                                    <td><a href="#" class="prifileLinks">
                                            <?php
                                            $abLink = new link($this->uri(array("action" => "userListingForm")));
                                            $abLink->link = 'User';
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
                            <div class="searchGoButton"> <!-- <a href="">
<!--                                    <img src="skins/unesco_oer/images/button-search.png" width="17" height="17" class="searchGoImage"></a>-->



                                    <a href="" class="searchGoLink">




                                    </a></div>
                            <div class="searchInputBoxDiv">

<!--                              <input type="text" name="" id="" class="searchInput" value="Type search term here...">-->

                                <?php

  $Search = $this->getobject('filterdisplay', 'unesco_oer');
    echo $Search->Search($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
                                ?>




                                                            <!--                                     <select name="" id="" class="searchDropDown">
                                                                                                <option value="">All</o    <input type="text" name="" id="" class="searchInput" value="Type search term here...">
                                                                                            <select name="" id="" class="searchDropDown">ption>
                                                                                            </select>-->
                                        </div>
                                        <div class="textNextToRightFloatedImage">Search</div><!--
                                        <img src="skins/unesco_oer/images/icon-search.png" alt="Search" class="imgFloatLeft">-->
                                    </div>
                                    <div class="facebookShareDiv">

                                        <!-- AddThis Button BEGIN -->
                                        <div class="shareDiv">
            <!--                                            <a class="addthis_button" href="#"><img src="#" width="125" height="16" alt="Bookmark and Share"></a><script type="text/javascript" src="#"></script>

                                             AddThis Button END -->
                                        </div>

                                        <div class="likeDiv">


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
                                            <a href="?module=unesco_oer&action=changelanguege&langugae=EN" class="languagesLinksActive">English</a> |
                                            <a href="?module=unesco_oer&action=changelanguege&langugae=FR" class="languagesLinks">Français</a> |
                                            <a href="?module=unesco_oer&action=changelanguege&langugae=SP" class="languagesLinks">Español</a> |
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
                                <div class="navitem"><div class="navitemInner"><a href="#">CONTACT</a></div></div>
                            </div>
                        </div>

                        <div class="mainContentHolder">
                            <div class="subNavigation"></div>
                            <!-- Left Colum -->
                           <?php
                           $filtering = $this->getobject('filterdisplay', 'unesco_oer');
                           echo $filtering->SideFilter('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
?>
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
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is null', "page" => '1a_tpl.php')));
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
                                            
                                <?php
                                $divOpen = '<div class="gridListPipe">|</div>
                                            <div class="gridListDivView">';
                                $divClose = '</div>';
                                        if ($this->objUser->isLoggedIn()) {
                                            $abLink = new link($this->uri(array("action" => "createProduct", 'prevAction' => 'home')));
                                            $abLink->link = '<img src="skins/unesco_oer/images/icon-new-product.png" alt="New Product" width="20" height="20" class="imgFloatRight">';
                                            
                                            $newProductLink = new link($this->uri(array("action" => "savetest", 'prevAction' => 'home')));
                                            $newProductLink->link = "New product";

                                            echo $divOpen;
                                            echo  $abLink->show();
                                            echo $newProductLink->show();
                                            echo $divClose;
                                        }
                                ?>

                                    
                                </div>
                            </div>
                    <?php
                                        //Creates chisimba table
                                        $objTable = $this->getObject('htmltable', 'htmlelements');
                                        $objTable->cssClass = "gridListingTable";
                                        $objTable->width = NULL;


                                        $products = $this->objDbProducts->getFilteredProducts($finalstring);
echo $finalstring;
                                        $newRow = true;
                                        $count = 0;
                                        $noOfAdaptations = 0;

                                        foreach ($products as $product) {               //populates table
                                            if ($product['relation'] == null) {
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
                            $Pagination = $this->getobject('filterdisplay', 'unesco_oer');
                              $Pagination->Pagination('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum, $pageinfo)
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
//                                        $objTabs->cssClass = "tabsOnState";
                                        $mostAdapted = $this->objProductUtil->displayMostAdapted($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $displayAllMostAdaptedProducts);
                                        $mostCommented = $this->objProductUtil->displayMostCommented($this->objDbProducts, $this->objDbComments);
                                        $mostRated = $this->objProductUtil->displayMostRated($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $this->objDbProductRatings);
                                        $objTabs->addTab('Adapted', $mostAdapted);
                                        $objTabs->addTab('Rated', $mostRated);
                                        $objTabs->addTab('Commented', $mostCommented);
                                        echo $objTabs->show();
                            ?>

                            <!--                            </div>-->
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
<script>

    function sendThemeFilterform()
    {
    document.forms["ThemeFilter"].submit();
    }

    function sendLanguageFilterform()
    {
    document.forms["LanguageFilter"].submit();

    }function sendAuthorFilterform()
    {
    document.forms["AuthorFilter"].submit();
    }


    function sendSortFilterform()
    {
    document.forms["SortFilter"].submit();
    }

    function sendNumFilterform()
    {
    document.forms["NumFilter"].submit();
    }

</script>
