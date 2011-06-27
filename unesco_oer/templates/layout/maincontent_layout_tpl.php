<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$origional = "parent_id is null and deleted = 0";
$adaptation = "parent_id is not null and deleted = 0"
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
                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
                            $abLink->link = 'UNESCO OER PRODUCTS';
                            echo $abLink->show();
                            ?>




                            <!--<a href="#">UNESCO OER PRODUCTS</a>      -->
                        </li>
                        <li class="mainNavPipe">&nbsp;</li>





                        <li>
                            <?php
                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '2a_tpl.php')));
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
