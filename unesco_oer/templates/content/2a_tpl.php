<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$filterSelect = false;
?>
<script src="http://code.jquery.com/jquery-1.5.js"></script>

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
                <div class="logOutSearchDiv">
                    <div class="logoutSearchDivLeft">
                        <div class="nameDiv">Hello Igor Nuk</div>
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
                                    <td><a href="#" class="prifileLinks">Administration Tools</a></td>
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
                            <div class="searchGoButton"><a href=""><img src="skins/unesco_oer/images/button-search.png" class="searchGoImage"></a>
                                <a href="" class="searchGoLink">GO</a></div>
                            <div class="searchInputBoxDiv">
                                <input type="text" name="" id="" class="searchInput" value="Type search term here...">
                                <select name="" id="" class="searchDropDown">
                                    <option value="">All</option>
                                </select>
                            </div>
                            <div class="textNextToRightFloatedImage">Search</div>
                            <img src="skins/unesco_oer/images/icon-search.png" alt="Search" class="imgFloatLeft">
                        </div>
                        <div class="facebookShareDiv">

                            <!-- AddThis Button BEGIN -->
                            <div class="shareDiv">
                                <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=jabulane"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share"></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=jabulane"></script>

                                <!-- AddThis Button END -->
                            </div>

                            <div class="likeDiv">
                                <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fexample.com%2Fpage%2Fto%2Flike&amp;layout=button_count&amp;show_faces=true&amp;width=50&amp;action=like&amp;font=tahoma&amp;colorscheme=light&amp;"></iframe>

                            </div>


                        </div>
                    </div>
                </div>
                <div class="logoAndHeading">
                    <img src="skins/unesco_oer/images/logo-unesco.gif" class="imgFloatRight" alt="logo">
                    <div class="logoText">
                        <span class="greyTitleText">Unesco&rsquo;s Open Educational Resources Platform</span><br>
                        <h1>UNESCO OER PRODUCTS</h1>
                    </div>
                </div>
                <div class="languagesDiv">
                    <a href="" class="languagesLinksActive">English</a> |
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
$abLink = new link($this->uri(array("action" => "home")));
$abLink->link = 'UNESCO OER PRODUCTS';
echo $abLink->show();
?>

                        </div>
                    </div>
                    <div class="mainNavPipe">&nbsp;</div>
                    <div class="navitemOnstate">
                        <div class="navitemInnerOnstate">
<?php
$abLink = new link($this->uri(array("action" => "2a")));
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
                    <div class="navitem"><div class="navitemInner"><a href="#">CONTACT</a></div></div>
                </div>
            </div>

            <div class="mainContentHolder">
                <div class="subNavigation"></div>
                <!-- Left Colum -->
                <div class="leftColumnDiv">
                    <div class="moduleHeader">FILTER PRODUCTS</div>
                    <div class="blueNumberBackground">
                        <div class="iconOnBlueBackground"><img src="skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                        <div class="numberOffilteredProducts">56</div>
                    </div>
                    <div class="moduleSubHeader">Product matches filter criteria</div>
                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-type.png" alt="Type of product" class="modulesImages">Type of product</div>
                    <div class="blueBackground blueBackgroundCheckBoxText">
                        <input type="checkbox"> Model<br>
                        <input type="checkbox"> Guide<br>
                        <input type="checkbox"> Handbook<br>
                        <input type="checkbox"> Manual<br>
                        <input type="checkbox"> Bestoractile<br>
                    </div>
                    <br>
                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-theme.png" alt="Theme" class="modulesImages">Theme</div>
                    <div class="blueBackground">
                    <?php
                    $products = $this->objDbProducts->getProducts(0, 10);
                    $filter = new dropdown('filterTheme');
                    foreach ($products as $product) {

                        $filter->addOption($product['theme']);
                    }
                    $uri = $this->uri(array('action' => 'filterTheme'));
                    $form = new form('filterTheme', $this->uri(array('action' => 'FilterProducts')));
                    //         $filterbutton = new button('FilterProducts', "FilterProducts");
                    //   $filterbutton->settosubmit();

                    $uri = $this->uri(array('action' => 'FilterProducts'));
                    $filter->addOnChange('javascript: sendFilterform()');
                    //                         $form -> addtoform($filterbutton->show());
                    // $filter->setselected();

                    $form->addtoform($filter->show());
                    //    $form->addtoform($filterbutton->show());
                    echo $form->show();
                    echo $testfilter;
                    ?>
                        <!--

                                              <select name="theme" id="theme" class="leftColumnSelectDropdown">
                                                    <option value="">All</option>
                                                </select>

                        <!-- Pagination-->
                    </div>
                    <br>



                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-languages.png" alt="Language" class="modulesImages">Language</div>
                    <div class="blueBackground">
<?php ?>




                                     <!--   <select name="language" id="language" class="leftColumnSelectDropdown">
                                            <option value="">All</option>
                                      /select>

                        -->
                    </div>
                    <br>
                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesImages">Author</div>
                    <div class="blueBackground">

                        <?php
                        $products = $this->objDbProducts->getProducts(0, 10);
                        $filter = new dropdown('filter');
                        foreach ($products as $product) {

                            $filter->addOption($product['creator']);
                        }
                        $uri = $this->uri(array('action' => 'filter'));
                        $form = new form('filter', $this->uri(array('action' => 'FilterProducts')));
                        $filterbutton = new button('FilterProducts', "FilterProducts");
                        $filterbutton->settosubmit('filter');

                        $uri = $this->uri(array('action' => 'FilterProducts'));
                        $filter->addOnChange();
                        //                         $form -> addtoform($filterbutton->show());
                        // $filter->setselected();

                        $form->addtoform($filter->show());
                        $form->addtoform($filterbutton->show());

                        echo $form->show();
                        echo $testfilter
                        ?>


                                        <!--<select name="author" id="author" class="leftColumnSelectDropdown">
                                            <option value="">All</option>
                                        </select>

                        -->
                    </div>
                    <br>
                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages">Items per page</div>
                    <div class="blueBackground">
                        <select name="items_per_page" id="items_per_page" class="leftColumnSelectDropdown">
                            <option value="">All</option>
                            <option value="1">Peter Griffin</option>
                        </select>
                    </div>
                    <br><br>
                    <div class="blueBackground rightAlign">
                        <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                        <a href="#" class="resetLink">RESET</a>
                    </div>
                    <div class="filterheader">


<?php ?>

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
                            Sort By:
                            <select name="" class="contentDropDown">
                                <option value="">Date Added</option>
                            </select>
                            <select name="" class="contentDropDown">
                                <option value="">DESC</option>
                            </select>
                        </div>
                        <div class="viewGrid">
                            <div class="viewAsDiv">View as: </div>


                                    <?php
                                    $abLink = new link($this->uri(array("action" => "2a")));
                                    $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
                                    echo $abLink->show();
                                    ?>

                            <div class="gridListDivView">
                                                <?php
                                                $abLink = new link($this->uri(array("action" => "2a")));
                                                $abLink->link = 'GRID';
                                                echo $abLink->show();
                                                ?>
                            </div>

                            <div class="gridListPipe">|</div>

                            <?php
                            $abLink = new link($this->uri(array("action" => "2b")));
                            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                            echo $abLink->show();
                            ?>

                            <div class="gridListDivView">

<?php
                            $abLink = new link($this->uri(array("action" => "2b")));
                            $abLink->link = 'LIST';
                            echo $abLink->show();
?>

                            </div>
                        </div>
                    </div>

<?php
                            $objTable = $this->getObject('htmltable', 'htmlelements');
                            if ($filterSelect == false) {
                                $teststring .="parent_id is not null";
                            }
                            else
                                $teststring .= " and parent_id is not null" . $teststring;

                            $filterselect = true;
                            echo "$teststring";


                            $products = $this->objDbProducts->getFilteredProducts($teststring);
                            $newRow = true;
                            $count = 0;

                            foreach ($products as $product) {               //populates table
                                if ($product['parent_id'] != null) {
                                    $count++;
                                    
                                    if ($newRow) {
                                        $objTable->startRow();
                                        $objTable->addCell($this->objProductUtil->populateAdaptedGridView($product));
                                        $newRow = false;
                                    } else {
                                        $objTable->addCell($this->objProductUtil->populateAdaptedGridView($product));
                                    }
                                }

                                //Display 3 products per row
                                if ($count == 3) {
                                    $newRow = true;
                                    $count = 0;
                                    $objTable->endRow();
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
                                                        <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101" alt="Placeholder">
                                                        <div class="imageBotomFlag"></div>
                                                    </div>
                                                    <br>
                                                    <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                    <div class="adaptedByDiv">Adapted by:</div>
                                                    <div class="gridSmallImageAdaptation">
                                                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                        <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                    </div>
                                                    <div class="gridAdaptationLinksDiv">
                                                        <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                        <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                        <a href="#" class="productAdaptationGridViewLinks">English</a>
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
                                                    <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                    <div class="adaptedByDiv greenColor">Managed by:</div>
                                                    <div class="gridSmallImageAdaptation">
                                                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                        <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                    </div>
                                                    <div class="gridAdaptationLinksDiv">
                                                        <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                        <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                        <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                    <div class="imageGridListing">
                                                        <div class="imageTopFlag"></div>
                                                        <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101" alt="Placeholder">
                                                        <div class="imageBotomFlag"></div>
                                                    </div>
                                                    <br>
                                                    <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                    <div class="adaptedByDiv">Adapted by:</div>
                                                    <div class="gridSmallImageAdaptation">
                                                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                        <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                    </div>
                                                    <div class="gridAdaptationLinksDiv">
                                                        <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                        <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                        <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                    <div class="imageGridListing">
                                                        <div class="imageTopFlag"></div>
                                                        <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                        <div class="imageBotomFlag"></div>
                                                    </div>
                                                    <br>
                                                    <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                    <div class="adaptedByDiv">Adapted by:</div>
                                                    <div class="gridSmallImageAdaptation">
                                                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                        <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                    </div>
                                                    <div class="gridAdaptationLinksDiv">
                                                        <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                        <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                        <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                    <div class="imageGridListing">
                                                        <div class="imageTopFlag"></div>
                                                        <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101" alt="Placeholder">
                                                        <div class="imageBotomFlag"></div>
                                                    </div>
                                                    <br>
                                                    <div class="orangeListingHeading">The Net for Journalists Curricula</div>
                                                    <div class="adaptedByDiv">Adapted by:</div>
                                                    <div class="gridSmallImageAdaptation">
                                                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                        <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                    </div>
                                                    <div class="gridAdaptationLinksDiv">
                                                        <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                        <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                        <a href="#" class="productAdaptationGridViewLinks">English</a>
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
                                                    <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                    <div class="adaptedByDiv">Adapted by:</div>
                                                    <div class="gridSmallImageAdaptation">
                                                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                        <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                    </div>
                                                    <div class="gridAdaptationLinksDiv">
                                                        <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                        <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                        <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>

                    -->
                    <!-- Pagination-->
                    <div class="paginationDiv">
                        <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>
                        <div class="paginationLinkDiv">
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
                        </div>
                    </div>
                </div>

                <!-- Right column DIv -->
                <div class="rightColumnDiv">
                    <div class="rightColumnDiv">
                        <div class="featuredHeader">FEATURED ADAPTATION</div>
                        <div class="rightColumnBorderedDiv">
                            <div class="rightColumnContentPadding">
                                <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
                                <div class="featuredAdaptationRightContentDiv">
                                    <span class="greyListingHeading">Manual for Investigative Journalists</span>
                                    <br><br>
                                    <a href="#" class="adaptationLinks">See all adaptations (15)</a>
                                    <br>
                                    <a href="#" class="adaptationLinks">See UNSECO orginals</a>

                                </div>
                                <div class="featuredAdaptedBy">Adapted By</div>
                                <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                <span class="greyListingHeading">Polytechnic of Namibia</span>
                            </div>
                        </div>
                        <div class="spaceBetweenRightBorderedDivs">
                            <div class="featuredHeader">BROWSER ADAPTATION BY MAP</div>
                        </div>
                        <div class="rightColumnBorderedDiv">
                            <div class="rightColumnContentPadding">



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

