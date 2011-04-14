<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
if ($adaptationstring == null)
$adaptationstring = 'parent_id is not null';
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
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is null', "page" => '1a_tpl.php')));
                                            $abLink->link = 'UNESCO OER PRODUCTS';
                                            echo $abLink->show();
                            ?>

                                        </div>
                                    </div>
                                    <div class="mainNavPipe">&nbsp;</div>
                                    <div class="navitemOnstate">
                                        <div class="navitemInnerOnstate">
                            <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is not null', "page" => '2a_tpl.php')));
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
                                    <div class="moduleHeader">
                                         <?php
                                         echo  $this->objLanguage->languageText('mod_unesco_oer_product_description', 'unesco_oer')


                                        ?>

                                        </div>
                                    <div class="blueNumberBackground">
                                        <div class="iconOnBlueBackground"><img src="skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                                        <div class="numberOffilteredProducts">
                            <?php
                                            echo $TotalRecords = $this->objDbProducts->getTotalEntries($TotalEntries);
                            ?>
                                        </div>
                                    </div>
                                    <div class="moduleSubHeader">Product matches filter criteria</div>
                                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-type.png" alt="Type of product" class="modulesImages">
                                         <?php
                                         echo  $this->objLanguage->languageText('mod_unesco_oer_product_type', 'unesco_oer')


                                        ?>

                                        </div>

                                    <div class="blueBackground blueBackgroundCheckBoxText">

                        <?php
                                            $products = $this->objDbProducts->getProducts(0, 10);




                                            $form = new form('ProductType', $this->uri(array('action' => "FilterProducts", "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter)));




                                            $button = new button('Search',$this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));
                                        $button->setToSubmit();

                                        $checkbox = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_model', 'unesco_oer'));
                                        $checkbox2 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_handbook', 'unesco_oer'));
                                        $checkbox3 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_guide', 'unesco_oer'));
                                        $checkbox4 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_manual', 'unesco_oer'));
                                        $checkbox5 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_bestoractile', 'unesco_oer'));



                                            if ($Model == 'on')
                                                $checkbox->ischecked = true;

                                            if ($Handbook == 'on')
                                                $checkbox2->ischecked = true;

                                            if ($Guide == 'on')
                                                $checkbox3->ischecked = true;

                                            if ($Manual == 'on')
                                                $checkbox4->ischecked = true;

                                            if ($Besoractile == 'on')
                                                $checkbox5->ischecked = true;


                                            $form->addToForm($checkbox->show());
                                            $form->addToForm('Model<br>');
                                            $form->addToForm($checkbox2->show());
                                            $form->addToForm('handbook<br>');
                                            $form->addToForm($checkbox3->show());
                                            $form->addToForm('Guide<br>');
                                            $form->addToForm($checkbox4->show());
                                            $form->addToForm('Manual<br>');
                                            $form->addToForm($checkbox5->show());
                                            $form->addToForm('Besoractile<br>');
                                            $form->addToForm($button->show());


                                            echo $form->show();
                                            echo $Modelback
                        ?>




                                            <!--
                                                                    <input type="checkbox"> Model<br>
                                                                    <input type="checkbox"> Guide<br>
                                                                    <input type="checkbox"> Handbook<br>
                                                                    <input type="checkbox"> Manual<br>
                                                                    <input type="checkbox"> Bestoractile<br>-->

                                        </div>


                                        <br>
                                        <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-theme.png" alt="Theme" class="modulesImages">


                                             <?php
                                         echo  $this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer')


                                        ?>
                                        </div>
                                        <div class="blueBackground">
                        <?php
                                            $products = $this->objDbProducts->getProducts(0, 10);
                                            $filterTheme = new dropdown('ThemeFilter');
                                            $filterTheme->addoption('All');
                                            foreach ($products as $product) {
                                                
                                                $filterTheme->addOption($product['theme']);
                                            }
                                            $filterTheme->setSelected($ThemeFilter);

                                            $uri = $this->uri(array('action' => 'ThemeFilter'));
                                            $form = new form('ThemeFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));


                                            $uri = $this->uri(array('action' => 'FilterProducts'));
                                            $filterTheme->addOnChange('javascript: sendThemeFilterform()');



                                            $form->addtoform($filterTheme->show());

                                            echo $form->show();
                        ?>
                                            <!--

                                                                  <select name="theme" id="theme" class="leftColumnSelectDropdown">
                                                                        <option value="">All</option>
                                                                    </select>

                                            <!-- Pagination-->
                                        </div>
                                        <br>



                                        <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-languages.png" alt="Language" class="modulesImages">
                                             <?php
                                         echo  $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer')


                                        ?>

                                            </div>
                                        <div class="blueBackground">
                        <?php
                                            $products = $this->objDbProducts->getProducts(0, 10);
                                            $filterLang = new dropdown('LanguageFilter');
                                             $filterLang->addoption('All');
                                            foreach ($products as $product) {
                                               
                                                $filterLang->addOption($product['language']);
                                            }

                                            $filterLang->setSelected($LangFilter);
                                            $form = new form('LanguageFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));


                                            $uri = $this->uri(array('action' => 'LanguageFilter'));
                                            $filterLang->addOnChange('javascript: sendLanguageFilterform()');


                                            $form->addtoform($filterLang->show());


                                            echo $form->show();
                        ?>




                                                                                 <!--   <select name="language" id="language" class="leftColumnSelectDropdown">
                                                                                        <option value="">All</option>
                                                                                  /select>

                                            -->
                                        </div>
                                        <br>
                                        <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesImages">
                                             <?php
                                         echo  $this->objLanguage->languageText('mod_unesco_oer_author', 'unesco_oer')


                                        ?>

                                            </div>
                                        <div class="blueBackground">

                        <?php
                                            $products = $this->objDbProducts->getProducts(0, 10);
                                            $filterAuth = new dropdown('AuthorFilter');
                                              $filterAuth->addoption('All');
                                            foreach ($products as $product) {
                                              
                                                $filterAuth->addOption($product['creator']);
                                            }

                                            $filterAuth->setSelected($AuthFilter);
                                            $form = new form('AuthorFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));


                                            $uri = $this->uri(array('action' => 'AuthorFilter'));
                                            $filterAuth->addOnChange('javascript: sendAuthorFilterform()');


                                            $form->addtoform($filterAuth->show());


                                            echo $form->show();
                        ?>


                                                                                    <!--<select name="author" id="author" class="leftColumnSelectDropdown">
                                                                                        <option value="">All</option>
                                                                                    </select>

                                            -->
                                        </div>
                                        <br>
                                        <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages">
                                             <?php
                                         echo  $this->objLanguage->languageText('mod_unesco_oer_items_per_page', 'unesco_oer')


                                        ?>
                                            </div>
                                        <div class="blueBackground">


                        <?php
                                            $products = $this->objDbProducts->getProducts(0, 10);
                                            $filterNum = new dropdown('NumFilter');


                                            $filterNum->addoption('All');
                                            $filterNum->addOption('1');
                                            $filterNum->addOption('2');
                                            $filterNum->addOption('3');



                                            $filterNum->setSelected($NumFilter);
                                            $form = new form('NumFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));


                                            $uri = $this->uri(array('action' => 'NumFilter'));
                                            $filterNum->addOnChange('javascript: sendNumFilterform()');


                                            $form->addtoform($filterNum->show());


                                            echo $form->show();
                        ?>



                                            <!--                        <select name="items_per_page" id="items_per_page" class="leftColumnSelectDropdown">
                                                                        <option value="">All</option>

                                                                    </select>-->
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

                            <?php
                                            $products = $this->objDbProducts->getProducts(0, 10);
                                            $filterLang = new dropdown('SortFilter');

                                            $filterLang->addoption('None');
                                            $filterLang->addoption('Date');
                                            $filterLang->addOption('Alphabetical');


                                            $filterLang->setSelected($SortFilter);
                                            $form = new form('SortFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));


                                            $uri = $this->uri(array('action' => 'SortFilter'));
                                            $filterLang->addOnChange('javascript: sendSortFilterform()');



                                            $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_sort_by', 'unesco_oer'));
                                            $form->addtoform($filterLang->show());
                                            echo $form->show();
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
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php')));
                                            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
                                            echo $abLink->show();
                            ?>

                                            <div class="gridListDivView">
                                <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php')));
                                            $abLink->link = 'GRID';
                                            echo $abLink->show();
                                ?>
                                        </div>

                                        <div class="gridListPipe">|</div>

                            <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2b_tpl.php')));
                                            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                                            echo $abLink->show();
                            ?>

                                            <div class="gridListDivView">

                                <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2b_tpl.php')));
                                            $abLink->link = 'LIST';
                                            echo $abLink->show();
                                ?>

                                        </div>
                                    </div>
                                </div>

                    <?php
                                            $objTable = $this->getObject('htmltable', 'htmlelements');





                                            $products = $this->objDbProducts->getFilteredProducts($finalstring);
                                            $newRow = true;
                                            $count = 0;

                                            foreach ($products as $product) {
                                                $count++;                       //populates table
                                                //Check if the creator is a group or an institution
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

                                                if ($newRow) {
                                                    $objTable->startRow();
                                                    $objTable->addCell($this->objProductUtil->populateAdaptedGridView($product));
                                                    $newRow = false;
                                                } else {
                                                    $objTable->addCell($this->objProductUtil->populateAdaptedGridView($product));
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
<!--                                                <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>-->




                        <?php
//
                                            $TotalRecords = $this->objDbProducts->getTotalEntries($TotalEntries);

                                            $TotalPages = ceil($TotalRecords / $NumFilter);


                                        if ($TotalPages > 0) {




                                            echo '<div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>';



                                            for ($i = 1; $i <= $TotalPages; $i++) {

                                                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));
                                                $abLink->link = $i;
                                                echo $abLink->show();
                                            }

                                        }
//
//
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
                                                if ($product['parent_id'] != NULL) {
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
                                    <?php
//                                $im = imagecreatefromjpeg("skins/unesco_oer/images/earth_310.jpg");
//
//                              $Institution = $this->objDbInstitution->getAllInstitution();
//                               for ($i = 0; $i < count($Institution); $i++) {
//                                   $InstitutionName = $this->objDbInstitution->getInstitutionName( $Institution[$i]['id']);
//                                   if ($this->objDbProducts->hasAnAdaptation($InstitutionName)) {
//                                       $lat = $this->objDbInstitution->getInstitutionLatitude($Institution[$i]['id']);
//                                       $long = $this->objDbInstitution->getInstitutionLongitude($Institution[$i]['id']);
//                                       $this->objDbInstitution->MapHandler($im, $lat, $long);
//                                       }
//                               }
//
//                               $Groups =$this->objDbGroups->getAllGroups();
//                               for ($i = 0; $i < count($Groups); $i++) {
//                                   $GroupName = $this->objDbGroups->getGroupName($Groups[$i]['id']);
//                                   if ($this->objDbProducts->hasAnAdaptation($GroupName)) {
//                                       $lat = $this->objDbGroups->getGroupLatitude($Groups[$i]['id']);
//                                       $long = $this->objDbGroups->getGroupLongitude($Groups[$i]['id']);
//                                       $this->objDbGroups->MapHandler($im, $lat, $long);
//                                       }
//                               }
//                               //imagepng($im, "usrfiles/unesco_oer/map/Downloads.jpg");
//                               imagepng($im, "map_images/Downloads.jpg");
                                            //<img src="map_images/Downloads.jpg" width="200" height="100">
                                    ?>


                                    <?php
                                            $im = imagecreatefromjpeg("skins/unesco_oer/images/earth_310.jpg");
                                            $products = $this->objDbProducts->getFilteredProducts($finalstring);
                                            for ($i = 0; $i < count($products); $i++) {
                                                $creator = $products[$i]['creator'];
                                                if ($this->objDbInstitution->isInstitution($creator) || $this->objDbGroups->isGroup($creator)) {
                                                    $lat = $this->objDbInstitution->getInstitutionLatitude($creator);
                                                    $long = $this->objDbInstitution->getInstitutionLongitude($creator);
                                                    $this->objDbInstitution->MapHandler($im, $lat, $long);
                                                    $lat = $this->objDbGroups->getGroupLatitude($creator);
                                                    $long = $this->objDbGroups->getGroupLongitude($creator);
                                                    $this->objDbGroups->MapHandler($im, $lat, $long);
                                                }
                                            }
                                            imagepng($im, "skins/unesco_oer/images/earthMap.jpg");
                                    ?>
                                    <img src="skins/unesco_oer/images/earthMap.jpg" width="200" height="100">

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
