<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$adaptationstring = "relation is null";
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
                            <div class="nameDiv"><?php echo 'Logged in as '. $this->objUser->fullname(); ?></div>
                            <div class="logoutDiv">
                                <div class="textNextToRightFloatedImage"><a href="?module=security&action=logoff" class="prifileLinks">Log out</a></div>
                                <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">
                            </div>
                            <div class="profileBookmarkGroupsMessengerDiv">
                                <a href="#"><img src="skins/unesco_oer/images/icon-my-profile.png" alt="My Profile" width="20" height="20" class="userIcons" title="My Profile"></a>
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
                                    <a class="addthis_button" href="#"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share"></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=jabulane"></script>

                                    <!-- AddThis Button END -->
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
                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is null', "page" => '1a_tpl.php')));
                            $abLink->link = 'UNESCO OER PRODUCTS';
                            echo $abLink->show();
                            ?>




                            <!--<a href="#">UNESCO OER PRODUCTS</a>      -->
                        </li>
                        <li class="mainNavPipe">&nbsp;</li>





                        <li>
                            <?php
                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is not null', "page" => '2a_tpl.php')));
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
                <?php
                            echo $this->getContent();
                ?>
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
                    <img src="images/icon-footer.png" alt="CC" width="80" height="15" class="imageFooterPad">
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

