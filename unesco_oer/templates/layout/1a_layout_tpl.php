<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$adaptationstring = "parent_id is null";
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
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <div class="logoutSearchDivRight">
                        <div class="searctInputTextDiv">
                            <div class="searchGoButton"><a href=""><img src="skins/unesco_oer/images/button-search.png" width="17" height="17" class="searchGoImage"></a>
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
                                <a class="addthis_button" href="#"><img src="#" width="125" height="16" alt="Bookmark and Share"></a><script type="text/javascript" src="#"></script>

                                <!-- AddThis Button END -->
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
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is null', "page" => '1a_tpl.php')));
                                        $abLink->link = 'UNESCO OER PRODUCTS';
                                        echo $abLink->show();
                            ?>
                                    </div>
                                </div>
                                <div class="mainNavPipe">&nbsp;</div>
                                <div class="navitem">
                                    <div class="navitemInner">
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
                                        echo $this->objLanguage->languageText('mod_unesco_oer_product_description', 'unesco_oer')
                        ?>

                                    </div>
                                    <div class="blueNumberBackground">
                                        <div class="iconOnBlueBackground"><img src="skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                                        <div class="numberOffilteredProducts">

                            <?php
                                        if ($finalstring == null) {                       //Initialization check
                                            $finalstring = 'parent_id is null';
                                            $TotalEntries = 'parent_id is null';
                                        }

                                        echo $TotalRecords = $this->objDbProducts->getTotalEntries($TotalEntries);
                            ?>




                                    </div>
                                </div>
                                <div class="moduleSubHeader">Product matches filter criteria</div>
                                <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-type.png" alt="Type of product" class="modulesImages">

                        <?php
                                        echo $this->objLanguage->languageText('mod_unesco_oer_product_type', 'unesco_oer')
                        ?>

                                    </div>
                                    <div class="blueBackground blueBackgroundCheckBoxText">

                        <?php
                                        $products = $this->objDbProducts->getProducts(0, 10);




                                        $form = new form('ProductType', $this->uri(array('action' => "FilterProducts", "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter)));




                                        $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));
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
                        ?>

                                                                <!--                                    <input type="checkbox"> Model<br>
                                                                                                    <input type="checkbox"> Guide<br>
                                                                                                    <input type="checkbox"> Handbook<br>
                                                                                                    <input type="checkbox"> Manual<br>
                                                                                                    <input type="checkbox"> Bestoractile<br>-->
                                    </div>
                                    <br>
                                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-theme.png" alt="Theme" class="modulesImages">
                        <?php
                                        echo $this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer')
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
                                        $form = new form('ThemeFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));


                                        $uri = $this->uri(array('action' => 'FilterProducts'));
                                        $filterTheme->addOnChange('javascript: sendThemeFilterform()');



                                        $form->addtoform($filterTheme->show());

                                        echo $form->show();
                        ?>

                                                                                                                                                                                                                    <!--                        <select name="theme" id="theme" class="leftColumnSelectDropdown">
                                                                                                                                                                                                                                                <option value="">All</option>
                                                                                                                                                                                                                                            </select>-->
                                    </div>
                                    <br>
                                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-languages.png" alt="Language" class="modulesImages">
                        <?php
                                        echo $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer')
                        ?>


                                    </div>
                                    <div class="blueBackground">


                        <?php
                                        $products = $this->objDbProducts->getProducts(0, 10);
                                        $filterLang = new dropdown('LanguageFilter');
                                        $filterLang->addOption('All');
                                        foreach ($products as $product) {

                                            $filterLang->addOption($product['language']);
                                        }

                                        $filterLang->setSelected($LangFilter);
                                        $form = new form('LanguageFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));


                                        $uri = $this->uri(array('action' => 'LanguageFilter'));
                                        $filterLang->addOnChange('javascript: sendLanguageFilterform()');


                                        $form->addtoform($filterLang->show());


                                        echo $form->show();
                        ?>

                                                                                                                                                                                                                    <!--                        <select name="language" id="language" class="leftColumnSelectDropdown">
                                                                                                                                                                                                                                                <option value="">All</option>
                                                                                                                                                                                                                                                <option value="">English</option>
                                                                                                                                                                                                                                                <option value="">Français</option>
                                                                                                                                                                                                                                                <option value="">Español</option>
                                                                                                                                                                                                                                                <option value="">Русский</option>
                                                                                                                                                                                                                                                <option value="">لعربية</option>
                                                                                                                                                                                                                                                <option value="">中文</option>
                                                                                                                                                                                                                                            </select>-->
                                    </div>
                                    <br>
                                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesImages">
                        <?php
                                        echo $this->objLanguage->languageText('mod_unesco_oer_author', 'unesco_oer')
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
                                        $form = new form('AuthorFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));


                                        $uri = $this->uri(array('action' => 'AuthorFilter'));
                                        $filterAuth->addOnChange('javascript: sendAuthorFilterform()');


                                        $form->addtoform($filterAuth->show());


                                        echo $form->show();
                        ?>




                                                                                                                                                                                                                    <!--                        <select name="author" id="author" class="leftColumnSelectDropdown">
                                                                                                                                                                                                                                                <option value="">All</option>
                                                                                                                                                                                                                                            </select>-->
                                    </div>
                                    <br>
                                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages">
                        <?php
                                        echo $this->objLanguage->languageText('mod_unesco_oer_items_per_page', 'unesco_oer')
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
                                        $form = new form('NumFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));


                                        $uri = $this->uri(array('action' => 'NumFilter'));
                                        $filterNum->addOnChange('javascript: sendNumFilterform()');


                                        $form->addtoform($filterNum->show());


                                        echo $form->show();
                        ?>









                                                                <!--                                        <select name="items_per_page" id="items_per_page" class="leftColumnSelectDropdown">
                                                                                                            <option value="">All</option>
                                                                                                        </select>-->
                                    </div>
                                    <br><br>
                                    <div class="blueBackground rightAlign">
                                        <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                                        <a href="#" class="resetLink">RESET</a>
                                    </div>
                                    <div class="rssFeed">
                                        <img src="skins/unesco_oer/images/small-icon-rss-feed.png" alt="RSS Feed" width="18" height="18" class="imgFloatRight">
                                        <div class="feedLinkDiv"><a href="#" class="rssFeedLink">RSS Feed</a></div>
                                    </div>
                                </div>
                            <!-- Center column DIv -->
                            <div class="centerColumnDiv">
                                <?php
                                //echo the content
                                echo $this->getContent();
                                ?>
                            </div>
                                <!-- Right column DIv -->
                                <div class="rightColumnDiv">
                                    <div class="rightColumnDiv">
                                        <div class="featuredHeader">FEATURED UNESCO PRODUCTS</div>
                                        <div class="rightColumnBorderedDiv">
                                            <div class="rightColumnContentPadding">
                                <?php
                                        $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
                                        $featuredProduct = $this->objDbProducts->getAll("where puid = '$featuredProductID'");
                                        if (sizeof($featuredProduct) > 0) {
                                            //TODO error handling
                                        }
                                        echo $this->objFeaturedProducUtil->featuredProductView($featuredProduct[0]);
                                ?>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">

                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">
                                        <?php
                                        //The reason it does not display the number of adaptations is because this uses puid as the id and the function getNoOfAdaptations uses id as the id
                                        $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
                                        $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($featuredProductID);
                                        echo"See all adaptations ($NOofAdaptation)"// This must be a link;
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

                        <div class="rightColumnBorderedDiv">
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