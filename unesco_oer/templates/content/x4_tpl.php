<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');
if ($adaptationstring == null)
    $adaptationstring = "relation is not null";
$institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
$institutionGUI->getInstitution($institutionId);
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
                        <div class="nameDiv"><?php echo $this->objUser->fullname(); ?></div>>
                        <div class="logoutDiv">
                            <div class="textNextToRightFloatedImage"><a href="#" class="prifileLinks">Log out</a></div>
                            <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">
                        </div>
                        <div class="profileBookmarkGroupsMessengerDiv">
                            <a href="#"><img src="skins/unesco_oer/images/icon-my-profile.png" alt="My Profile" width="20" height="20" class="userIcons" title="My Profile"></a>
                            <div class="spacingBetweenUserIcons">&nbsp;</div>
                            <a href="#"><img src="skins/unesco_oer/images/icon-my-bookmarks.png" alt="My Bookmarks" width="20" height="20" class="userIcons" title="My Bookmarks"></a>
                            <div class="spacingBetweenUserIcons">&nbsp;</div>
                            <a href="#"><img src="skins/unesco_oer/images/icon-my-administration-tools.png" alt="Administration Tools" width="20" height="20" class="userIcons" title="Administration Tools"></a>
                            <div class="spacingBetweenUserIcons">&nbsp;</div>
                            <a href="#"><img src="skins/unesco_oer/images/icon-my-groups.png" alt="My Groups" width="20" height="20" class="userIcons" title="My Groups"></a><div class="numberNextToUserIcons"></div>
                            <div class="spacingBetweenUserIcons">&nbsp;</div>
                            <a href="#"><img src="skins/unesco_oer/images/icon-my-messenger.png" alt="My Messages" width="20" height="20" class="userIcons" title="My Messages"></a><div class="numberNextToUserIcons"></div>
                        </div>
                    </div>
                    <div class="logoutSearchDivRight">
                        <div class="searctInputTextDiv">
                            <div class="searchGoButton"><a href=""><img src="skins/unesco_oer/images/button-search.png" width="17" height="17" class="searchGoImage"></a>
                                <a href="" class="searchGoLink">GO</a></div>
                            <div class="searchInputBoxDiv">
                                <?php
                                $Search = $this->getobject('filterdisplay', 'unesco_oer');
                                echo $Search->Search('4_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
                                ?>
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
                    <img src="skins/unesco_oer/images/icon-languages.png" class="languagesMainIcon">
                </div>
                <div class="mainNavigation">
                    <ul id="sddm">
                        <li><a href="#">UNESCO OER PRODUCTS</a></li>
                        <li class="mainNavPipe">&nbsp;</li>
                        <li class="onStateAdaptations"><a href="#">PRODUCT ADAPTATIONS</a></li>
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
                    <div class="breadCrumb">
                        <a href="#" class="orangeListingHeading">Product adaptation</a> |
                        <a href="#" class="orangeListingHeading"> <?php
                                    echo $institutionGUI->showInstitutionName();
                                    ?></a>
                    </div>
                    <div class="adaptationsBackgroundColor">
                        <div class="innerLeftContent">
                            <div class="tenPixelLeftPadding twentyPixelRightPadding">
                                <h2 class="adaptationListingLink">
                                    <?php
                                    echo $institutionGUI->showInstitutionName();
                                    ?>
                                </h2>
                                <a href="#"><img src="skins/unesco_oer/images/icon-edit-section.png" class="Farright"></a>
                                <?php echo $institutionGUI->showEditInstitutionLink($institutionId) ?> |
    <!--                                <a href="#"><img src="" width="18" height="18" class="Farright"></a>-->
                                    <a href="#"><img src="skins/unesco_oer/images/new-institution.png" width="18" height="18" class="Farright"></a>

                                <?php 
                                    echo $institutionGUI->showNewInstitutionLink() ?> <br>
                                    <br />
                                    <div class="leftImageHolder rightTwent">
                                        '<img src= "
                                    <?php
                                    echo $institutionGUI->showInstitutionThumbnail();
                                    
                                    ?> " width="70" height="105"><br />
                                </div>
                                <?php
                                    echo $institutionGUI->showInstitutionDescription();
                                ?>
                                    <br><br>
                                    <div class="adaptationInnerPageHeding"><h3 class="pinkText">Adaptations</h3></div>
                                    <br>

                                    
                                <?php
                                    $filtering = $this->getobject('filterdisplay', 'unesco_oer');
                                    echo $filtering->SideFilter('4_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
                                ?>



                                </div>
                                </div>

                                <div class="innerRightColumn4">
                                    <div class="listAdaptations">
                                        <div class="floaLeftDiv">
                                            <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder">
                                        </div>
                                        <div class="rightColumInnerDiv">
                                            <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                                            Adapted in <a href="#" class="productAdaptationGridViewLinks">English</a>
                                            <br>
                                            <div class="listingAdaptationsLinkAndIcon">
                                                <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                            </div>

                                            <div class="listingAdaptationsLinkAndIcon">
                                                <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="listAdaptations">
                                        <div class="floaLeftDiv">
                                            <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder">
                                        </div>
                                        <div class="rightColumInnerDiv">
                                            <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                                            Adapted in <a href="#" class="productAdaptationGridViewLinks">English</a>
                                            <br>
                                            <div class="listingAdaptationsLinkAndIcon">
                                                <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                            </div>

                                            <div class="listingAdaptationsLinkAndIcon">
                                                <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="listAdaptations">
                                        <div class="floaLeftDiv">
                                            <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder">
                                        </div>
                                        <div class="rightColumInnerDiv">
                                            <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                                            Adapted in <a href="#" class="productAdaptationGridViewLinks">English</a>
                                            <br>
                                            <div class="listingAdaptationsLinkAndIcon">
                                                <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                            </div>

                                            <div class="listingAdaptationsLinkAndIcon">
                                                <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="listAdaptations">
                                        <div class="floaLeftDiv">
                                            <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder">
                                        </div>
                                        <div class="rightColumInnerDiv">
                                            <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                                            Adapted in <a href="#" class="productAdaptationGridViewLinks">English</a>
                                            <br>
                                            <div class="listingAdaptationsLinkAndIcon">
                                                <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                            </div>

                                            <div class="listingAdaptationsLinkAndIcon">
                                                <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="innerRightContent">
                            <div class="rightColumn4RightPadding">
                                <div class="printEmailDownloadIcons">
                                    <a href="#"><img src="skins/unesco_oer/images/icon-content-top-print.png" width="19" height="15"></a>
                                    <a href="#"><img src="skins/unesco_oer/images/icon-content-top-email.png" width="19" height="15"></a>
                                </div>
                                <br><br>
                                <span class="greyText fontBold">Type of institution:</span> <?php echo $institutionGUI->showInstitutionType(); ?>
                                <br><br>
                                <span class="greyText fontBold">Country:</span>  <?php echo $institutionGUI->showInstitutionCountry(); ?>
                                <br><br>
                                <span class="greyText fontBold">Address:</span><?php
                                    $address = $institutionGUI->showInstitutionAddress();

                                    echo $address['address1'];

                                    if (isset($address['address2'])) {
                                        echo ', ' . $address['address2'];
                                    }

                                    if (isset($address['address3'])) {
                                        echo ', ' . $address['address3'];
                                    }
                                ?>
                                    <br><br>
                                    <span class="greyText fontBold">Institution website:</span> <a href="#" class="greyTextLink"><?php echo $institutionGUI->showInstitutionWebsiteLink(); ?></a>
                                    <br><br>
                                    <span class="greyText fontBold">Keywords:</span> <?php
                                    $keywords = $institutionGUI->showInstitutionKeywords();
                                    echo $keywords['keyword1'];

                                    if (isset($keywords['keyword2'])) {
                                        echo ' | ' . $keywords['keyword2'];
                                    }
                                ?>
                            <br><br>
                            <span class="greenText fontBold">Linked groups:</span>
                            <br>
                            <div class="linkedGroups">
                                <a href="#" class="greenTextLink">Group1</a><br>
                                <a href="#" class="greenTextLink">Group2</a>
                            </div>
                            <br><br>
                            <span class="greyText fontBold">Community related information:</span>
                            <div class="listCommunityRelatedInfoDiv">
                                <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png" width="18" height="18"></div>
                                <div class="communityRelatedInfoText">Ignor uploaded a file (7 hours ago)</div>
                            </div>
                            <div class="listCommunityRelatedInfoDiv">
                                <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-friend.png" width="18" height="18"></div>
                                <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                            </div>
                            <div class="listCommunityRelatedInfoDiv">
                                <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                                <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                            </div>
                            <div class="listCommunityRelatedInfoDiv">
                                <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-friend.png" width="18" height="18"></div>
                                <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                            </div>
                            <div class="listCommunityRelatedInfoDiv">
                                <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png" width="18" height="18"></div>
                                <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                            </div>
                            <div class="listCommunityRelatedInfoDiv">
                                <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                                <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Right column DIv -->
            <div class="rightColumnDiv">
                <div class="rightColumnDiv">
                    <div class="featuredHeader pinkText">FEATURED ADAPTATION</div>
                    <div class="rightColumnBorderedDiv">
                        <div class="rightColumnContentPadding">
                            <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
                            <div class="featuredAdaptationRightContentDiv">
                                <span class="greyListingHeading">Manual for Investigative Journalists</span>
                                <br><br>
                                <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">See all adaptations (15)</a></div>


                            </div>
                            <div class="featuredAdaptedBy">Adapted By</div>
                            <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                            <span class="greyListingHeading">Polytechnic of Namibia</span>
                        </div>
                    </div>
                    <div class="spaceBetweenRightBorderedDivs">
                        <div class="featuredHeader pinkText"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_browse_map', 'unesco_oer') ?></div>
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
                <a href="" class="footerLink">Sitemap</a>
            </div>
        </div>
    </div>
</body>
</html>
