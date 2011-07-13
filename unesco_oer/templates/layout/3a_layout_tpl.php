<?php
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

$this->loadClass('commentmanager', 'unesco_oer');
$this->loadClass('link', 'htmlelements');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>UNESCO</title>
        <!-- TODO chasimbanize the java script where appropriate -->
        <!--<script type="text/javascript" src="ratingsys.js"></script>-->
        <link href="style.css" rel="stylesheet" type="text/css">
        <link href="rate_stars.css" rel="stylesheet" type="text/css">
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
                            <div class="nameDiv"><?php echo 'Logged in as ' . $this->objUser->fullname(); ?></div>
                            <div class="logoutDiv">
                                <div class="textNextToRightFloatedImage"><a href="?module=security&action=logoff" class="prifileLinks">Log out</a></div>
                                <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">
                            </div>
                            <div class="profileBookmarkGroupsMessengerDiv">
                                <a href="?module=userdetails"><img src="skins/unesco_oer/images/icon-my-profile.png" alt="My Profile" width="20" height="20" class="userIcons" title="My Profile"></a>
                                <a href="#"><img src="skins/unesco_oer/images/icon-my-bookmarks.png" alt="My Bookmarks" width="20" height="20" class="userIcons" title="My Bookmarks"></a>



                            <!--                                <a href="#"><img src="skins/unesco_oer/images/icon-my-administration-tools.png" alt="Administration Tools" width="20" height="20" class="userIcons" title="Administration Tools"></a>-->
                            <?php
                            $abLink = new link($this->uri(array("action" => "controlpanel")));
                            $img = '<img src="skins/unesco_oer/images/icon-my-administration-tools.png" alt="Administration Tools" width="20" height="20" class="userIcons" title="Administration Tools">';
                            $abLink->link = $img;
                            echo $abLink->show();
                            ?>
                            <a href="#"><img src="skins/unesco_oer/images/icon-my-groups.png" alt="My Groups" width="20" height="20" class="userIcons" title="My Groups"></a>
                            <a href="#"><img src="skins/unesco_oer/images/icon-my-messenger.png" alt="My Messenger" width="20" height="20" class="userIcons" title="My Messenger"></a>
                            <a href="?module=forum"><img src="skins/_common/icons/modules/forum.gif" alt="My Forums" width="20" height="20" class="userIcons" title=
                                <?php
                                $myForumsCaption = $this->objLanguage->languageText('mod_unesco_oer_my_forums_caption', 'unesco_oer');
                                echo $myForumsCaption;
                                ?>
                                                         ></a>
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
                                <img src="skins/unesco_oer/images/logo-unesco.gif" class="logoFloatLeft" alt="logo">
                                <div class="logoText">
                                    <span class="greyTitleText">Unesco&rsquo;s Open Educational Resources Platform</span><br>
                                    <h1>UNESCO OER PRODUCTS</h1>
                                </div>
                            </div>
                            <div class="languagesDiv">
                                <div class="languages">
                                    <a href="" class="languagesLinksActive">English</a> |
                                    <a href="" class="languagesLinks">Français</a> |
                                    <a href="" class="languagesLinks">Español</a> |
                                    <a href="" class="languagesLinks">Русский</a> |
                                    <a href="" class="languagesLinks">لعربية</a> |
                                    <a href="" class="languagesLinks">中文</a>
                                </div>
                                <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages" class="languagesMainIcon">
                            </div>
                            <div class="mainNavigation">
                                <ul id="sddm">
                                    <li class="onStateProducts">
                            <?php
                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is null and deleted = 0', "page" => '1a_tpl.php')));
                            $abLink->link = 'UNESCO OER PRODUCTS';
                            echo $abLink->show();
                            ?>




                            <!--<a href="#">UNESCO OER PRODUCTS</a>      -->
                        </li>
                        <li class="mainNavPipe">&nbsp;</li>





                        <li>
                            <?php
                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is not null and deleted = 0', "page" => '2a_tpl.php')));
                            $abLink->link = 'Product Adaptations';
                            echo $abLink->show();
                            ?>


                        </li>
                        <li class="mainNavPipe">&nbsp;</li>
                        <li><a href="#">GROUPS</a></li>
                        <li class="mainNavPipe">&nbsp;</li>
                        <li><a href="#">REPORTING</a></li>
                        <li class="mainNavPipe">&nbsp;</li>
                        <li><a href="#">ABOUT</a></li>
                        <li class="mainNavPipe">&nbsp;</li>
                        <li><a href="#">CONTACT</a></li>
                    </ul>
                </div>
            </div>

            <div class="mainContentHolder">
                <div class="subNavigation"></div>
                <!-- Left Wide column DIv -->
                <div class="LeftWideColumnDiv">
                    <?php
                            //echo the content
                            echo $this->getContent();
                    ?>
                        </div>
                        <!-- Right column DIv -->
                        <div class="rightColumnDiv">
                            <div class="featuredHeader blueText">FEATURED UNESCO PRODUCTS</div>
                            <div class="rightColumnBorderedDiv">
                                <div class="rightColumnContentPadding">
                                    <div class="rightColumnContentPadding">
                                <?php
                                $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
                                $featuredProduct = $this->objDbProducts->getProductByID($featuredProductID);

                                echo $this->objFeaturedProducUtil->featuredProductView($featuredProduct);
                                ?>

                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">

                                        <?php
                                        //The reason it does not display the number of adaptations is because this uses puid as the id and the function getNoOfAdaptations uses id as the id


                                        $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($featuredProduct['id']);
                                        echo"See all adaptations ($NOofAdaptation)"; // This must be a link;
                                        ?>

                                    </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="spaceBetweenRightBorderedDivs">
                        <div class="featuredHeader innerPadding blueText">MOST...</div>
                    </div>
                    <!--tabs -->
                    <!--                	<div class="tabsOffState">ADAPTED</div>
                                        <div class="tabsOnState">RATED</div>
                                        <div class="tabsOffState">COMMENTED</div>-->

                    <div class="rightColumnBorderedDiv">


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





                        <!--




                        <div class="rightColumnContentPadding">
                    	<div class="leftImageTabsList"><img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="placeholder" width="45" height="49"></div>
                                                <div class="rightTextTabsList">
                        	Model Curricula for Journalism Education
                                                    <div class="listingAdaptationsLinkAndIcon">
                                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                                        <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                    </div>
                                                </div>
                                                <div class="tabsListingSpace"></div>
                                                <div class="leftImageTabsList"><img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="placeholder" width="45" height="49"></div>
                                                <div class="rightTextTabsList">
                        	Model Curricula for Journalism Education
                                                    <div class="listingAdaptationsLinkAndIcon">
                                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                                        <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                    </div>
                                                </div>
                                                <div class="tabsListingSpace"></div>
                                                <div class="leftImageTabsList"><img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="placeholder" width="45" height="49"></div>
                                                <div class="rightTextTabsList">
                        	Model Curricula for Journalism Education
                                                    <div class="listingAdaptationsLinkAndIcon">
                                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                                        <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                                    </div>
                                                </div>
                                            </div>-->
                    </div>
                    <br>
                </div>
            </div>
            <!-- Start of Footer -->
            <div class="footerDiv">

                <h3>United Nations Educational, Scientific and Cultural ORganization</h3><br />
                UNESCO Headquarters is established in Paris. Offices are located in two places in the same area:

                7, place de Fontenoy 75352 Paris 07 SP France
                1, rue Miollis 75732 Paris Cedex 15 France


                General phone: 
                +33 (0)1 45 68 10 00



            </div>
            <!-- End of Footer -->
        </div>
    </body>
</html>
