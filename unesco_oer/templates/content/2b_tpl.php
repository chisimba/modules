<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');
if ($adaptationstring == null)
$adaptationstring = "relation is not null";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>UNESCO</title>
        <link href="style.css" rel="stylesheet" type="text/css">
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
                        <div class="nameDiv"><?php echo "Hello"." ".$this->objUser->fullname(); ?></div>
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
                                            $abLink = new link($this->uri(array("action" => "addData")));
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
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <div class="logoutSearchDivRight">
                        <div class="searctInputTextDiv">
                            <div class="searchGoButton"><a href="">

<!--                                    <img src="skins/unesco_oer/images/button-search.png" class="searchGoImage" alt="Search"></a>-->
                                <a href="" class="searchGoLink"></a></div>
                            <div class="searchInputBoxDiv">
<?php
    $Search = $this->getobject('filterdisplay', 'unesco_oer');
    echo $Search->Search('2b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
?>


                               
<!--
<!--                                <input type="text" name="" class="searchInput" value="Type search term here...">
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
                    <div class="navitem">
                        <div class="navitemInner">
                            <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is null', "page" => '1a_tpl.php')));
                                            $abLink->link = 'UNESCO OER PRODUCTS';
                                            echo $abLink->show();
                            ?>

                                        </div>
                                    </div>
                                    <div class="mainNavPipe">&nbsp;</div>
                                    <div class="navitemOnstate">
                                        <div class="navitemInnerOnstate">
                            <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is not null', "page" => '2a_tpl.php')));
                                            $abLink->link = 'PRODUCT ADAPTATIONS';
                                            echo $abLink->show();
                            ?>

                                        </div>
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
                           echo $filtering->SideFilter('2b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
?>

                                    </div>
                                    <!-- Center column DIv -->
                                    <div class="centerColumnDiv">
                                        <div class="GridListViewDiv">
                                            <div class="sortBy">
<?php
                           $search = $this->getobject('filterdisplay', 'unesco_oer');
                          echo $search->SortDisp('2b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);

                    ?>



                                                <!--                            Sort By:
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
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is not null', "page" => '2a_tpl.php','MapEntries' => $MapEntries)));
                                            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
                                            echo $abLink->show();
                            ?>

                                            <div class="gridListDivView">
                            <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is not null', "page" => '2a_tpl.php','MapEntries' => $MapEntries)));
                                            $abLink->link = 'GRID';
                                            echo $abLink->show();
                            ?>
                                            </div>

                                            <div class="gridListPipe">|</div>

<?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation not is null', "page" => '2b_tpl.php')));
                                            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                                            echo $abLink->show();
?>

                                            <div class="gridListDivView">

<?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is not null', "page" => '2b_tpl.php')));
                                            $abLink->link = 'LIST';
                                            echo $abLink->show();
?>

                                            </div>
                                        </div>
                                    </div>

                            <?php
                                            //$objTable = $this->getObject('htmltable', 'htmlelements');

                                            $newRow = true;
                                            $count = 0;

                                            $products = $this->objDbProducts->getFilteredProducts($finalstring);


                                            foreach ($products as $product) {
                                                if ($product['relation'] != '') {
                                                    $product['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($product['id']);

                                                    //Get The adapters details
                                                    if ($this->objDbGroups->isGroup($product['creator'])) {
                                                        $thumbnail = $this->objDbGroups->getGroupThumbnail($product['creator']);
                                                        $product['group_thumbnail'] = $thumbnail['thumbnail'];
                                                        $product['institution_thumbnail'] = NULL;
                                                        $product['country'] = 'Not Available';
                                                        $product['type'] = 'Not Available';
                                                    } else {
                                                        $thumbnail = $this->objDbInstitution->getInstitutionThumbnail($product['creator']);
                                                        $product['group_thumbnail'] = NULL;
                                                        $product['country'] = 'Not Available';
                                                        $product['type'] = 'Not Available';
                                                        $product['institution_thumbnail'] = $thumbnail['thumbnail'];
                                                    }
                                                    echo $this->objProductUtil->populateAdaptedListView($product);
                                                }
                                            }
                            ?>

                                <!--
                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                                        Adapted by
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="darkGreyColour">Polytechnic of Namibia</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-managed-by.png" alt="Mamged by" width="24" height="24"><br>
                                        <span class="greenColor">Managed by</span>
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="greenColor">Politechnic of Namibia, journalism department</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>



                                </div>


                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                                        Adapted by
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="darkGreyColour">Polytechnic of Namibia</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>



                                </div>


                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-managed-by.png" alt="Adapted by" width="24" height="24"><br>
                                        <span class="greenColor">Managed by</span>
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="greenColor">Politechnic of Namibia, journalism department</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>



                                </div>


                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                                        Adapted by
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="darkGreyColour">Polytechnic of Namibia</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>
                                </div>

                                -->




                                <!-- Pagination-->
                                <div class="paginationDiv">
<!--                                    <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>-->

<?php
                            $Pagination = $this->getobject('filterdisplay', 'unesco_oer');
                              $Pagination->Pagination('2b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum, $pageinfo)
?>
                                                <!--                        <div class="paginationLinkDiv">
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
                                                <div class="featuredHeader">FEATURED ADAPTATION</div>
                                                <div class="rightColumnBorderedDiv">
                                                    
                        <?php
                                            $featuredProducts = $this->objDbFeaturedProduct->getCurrentFeaturedAdaptedProduct();
                                            
                                            foreach ($featuredProducts as $featuredProduct) {
                                                //Check if it's an adapted product
                                                $product = $this->objDbProducts->getProductByID($featuredProduct['product_id']);

                                                //If the product is an adaptation
                                                if ($product['relation'] != NULL) {
                                                    $featuredAdaptedProduct = $product;
                                                }
                                            }

                                           if ($this->objDbGroups->isGroup($featuredAdaptedProduct['creator'])) {
                                                    $thumbnail = $this->objDbGroups->getGroupThumbnail($featuredAdaptedProduct['creator']);
                                                    $featuredAdaptedProduct['group_thumbnail'] = $thumbnail['thumbnail'];
                                                    $featuredAdaptedProduct['institution_thumbnail'] = NULL;
                                                } else {
                                                    $thumbnail = $this->objDbInstitution->getInstitutionThumbnail($featuredAdaptedProduct['creator']);
                                                    $featuredAdaptedProduct['group_thumbnail'] = NULL;
                                                    $featuredAdaptedProduct['institution_thumbnail'] = $thumbnail['thumbnail'];
                                                }
                                                //Get the number of adaptations
                                                $featuredAdaptedProduct['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($featuredAdaptedProduct['id']);

                                            echo $this->objFeaturedProducUtil->displayFeaturedAdaptedProduct($featuredAdaptedProduct);
                        ?>
                                        <div class="spaceBetweenRightBorderedDivs">
                                            <div class="featuredHeader">BROWSER ADAPTATION BY MAP</div>
                                        </div>
                                        <div class="rightColumnBorderedDiv">
                                            <div class="rightColumnContentPadding">

                                    <!DOCTYPE html>
                                    <html>
                                        <head>
                                            <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
                                            <style type="text/css">
                                                html { height: 100% }
                                                body { height: 100%; margin: 0px; padding: 0px }
                                                #map_canvas { height: 100% }
                                            </style>
                                            <script type="text/javascript"
                                                    src="http://maps.google.com/maps/api/js?sensor=true">
                                            </script>
 <script type="text/javascript">

                                                var marker = new Array();


                                                function initialize() {

                                                    myLatlng = [

                                            <?php

                                            $coords = $this->objDbProducts->getAdaptedProducts();

                                            foreach ($coords as $coord) {
                                                ?>

                                                            new google.maps.LatLng(<?php echo $coord['loclat'] . ',' . $coord['loclong']; ?>),


                                            <?php } ?>

                                                    ];


                                                    title = [

                                            <?php

                                            $title = $this->objDbProducts->getAdaptedProducts();

                                            foreach ($title as $titles) {
                                                ?>
                                                      "<?php           echo $titles['name']            ?>",



                                            <?php } ?>

                                                    ];





                                                    var myOptions = {
                                                        zoom: 0,
                                                        center: myLatlng[0],
                                                        mapTypeId: google.maps.MapTypeId.ROADMAP
                                                    }
                                                    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

                                                    var oldAction = document.forms["maps"].action;

                                                    for(i=0;i<myLatlng.length;i++)
                                                    {
                                                        marker[i] = new google.maps.Marker(
                                                        { position: myLatlng[i],
                                                            title: title[i]

                                                        } );

                                                        var pos = marker[i].getPosition();





                                                        google.maps.event.addListener(marker[i], 'click',
                                                        (function(pos)
                                                        { return function()
                                                            {
                                                                //alert(i);
                                                                document.forms["maps"].action = oldAction + "&lat=" + pos.lat() + "&Lng=" + pos.lng();
                                                                document.forms["maps"].submit();
                                                            };
                                                        }
                                                    )(pos)
                                                    );

                                                        marker[i].setMap(map);

                                                    }


                                                }

                                            </script>
                                        </head>
                                        <body onload="initialize()">
                                            <div id="map_canvas" style="width:100%; height:20%"></div>
                                            <?php
                                            $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2b_tpl.php',  "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));

                                            echo $form->show();
                                          
                                            ?>
                                        </body>
                                    </html>



                            </div>
                        </div>
                    </div>

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
