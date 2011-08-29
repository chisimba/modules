<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$origional = "parent_id is null and deleted = 0";
$adaptation = "parent_id is not null and deleted = 0";
$page = $this->getParam('page');
$group = $this->getParam('group');
?>

<div class="blueHorizontalStrip"></div>
<div class="mainWrapper">

    <div class="topContent">
        <table width="100%">

            <tr>
                <td align="right" colspan="3">
                    <div class="logOutSearchDiv">
                        <div class="logoutSearchDivLeft">
                            <?php
                            if ($this->objUser->isLoggedIn()) {
                                echo ' <div class="nameDiv">Logged in as ' . $this->objUser->fullname() . '</div>';
                            }else{
                                echo ' <div class="nameDiv">Welcome ' . $this->objUser->fullname() . '</div>';
                            }
                            ?>
                            <div class="logoutDiv">
                                <?php
                                if ($this->objUser->isLoggedIn()) {
                                    echo '<div class="textNextToRightFloatedImage"><a href="?module=security&action=logoff" class="prifileLinks">Log out</a></div>';
                                } else {
                                    echo '<div class="textNextToRightFloatedImage"><a href="?module=unesco_oer&action=login" class="prifileLinks">Log in</a></div>';
                                }
                                ?>
                                <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">
                            </div>
                            <div class="profileBookmarkGroupsMessengerDiv">


                                <a href="?module=userdetails"><img src="skins/unesco_oer/images/icon-my-profile.png" alt="My Profile" width="20" height="20" class="userIcons" title="My Profile"></a>
                                <?php
                                $booklink = new link($this->uri(array("action" => "Bookmarks")));
                                $img2 = '<img src= "skins/unesco_oer/images/icon-my-bookmarks.png" alt="My Bookmarks" width="20" height="20" class="userIcons" title="My Bookmarks">';
                                $booklink->link = $img2;
                                echo $booklink->show();





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

                                <script type="text/javascript">
                                    var addthis_config = {"data_track_clickback":true};
                                </script>
                                <!-- AddThis Button BEGIN -->
                                <div class="shareDiv">
                                    <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=xa-4e09cecf254052c9">
                                        <img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Share">
                                    </a>
                                    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e09cecf254052c9"></script>
                                </div>
                            </div>
                        </div>
                    </div>


                </td>
            </tr>
            <tr>
                <td align="left">
                    <img src="skins/unesco_oer/images/logo-unesco.gif" class="logoFloatLeft" alt="logo">
                </td>
                <td align="left">
                    <span class="greyTitleText">Unesco&rsquo;s Open Educational Resources Platform</span><br>

                    <h1>
                        <?php
                        switch ($page) {
                            case '2a_tpl.php':
                            case '2b_tpl.php':
                                echo 'PRODUCT ADAPTATIONS';
                                break;
                            case'10a_tpl.php':
                                echo 'GROUPS';
                                break;


                            default:
                                echo 'UNESCO OER PRODUCTS';
                                break;
                        }
                        ?>
                    </h1>
                </td>

                <td align="right">
                    <div class="clanguagesDiv">
                        <div class="xlanguages">
                            <a href="" class="languagesLinksActive">English</a> |
                            <a href="" class="languagesLinks">Français</a> |
                            <a href="" class="languagesLinks">Español</a> |
                            <a href="" class="languagesLinks">Русский</a> |
                            <a href="" class="languagesLinks">لعربية</a> |
                            <a href="" class="languagesLinks">中文</a>
                        </div>
                        <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages" class="languagesMainIcon">
                    </div>
                </td>

            </tr>

        </table>
        <div class="mainNavigation">
            <ul id="sddm">
                <li <?php if (empty($page) || (strcmp($page, '1a_tpl.php') == 0))
                            echo 'class="onStateProducts"'; ?> >
                        <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
                        $abLink->link = 'UNESCO OER PRODUCTS';
                        echo $abLink->show();
                        ?>

                </li>
                <li class="mainNavPipe">&nbsp;</li>

                <li
                <?php
//                                if (strcmp($page,'2a_tpl.php') == 0) echo 'class="onStateProducts"';
                switch ($page) {
                    case '2a_tpl.php':
                    case '2b_tpl.php':
                        echo 'class="onStateProducts"';
                        break;

                    default:
                        break;
                }
                ?>
                    >
                        <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '2a_tpl.php')));
                        $abLink->link = 'Product Adaptations';
                        echo $abLink->show();
                        ?>


                </li>
                <li class="mainNavPipe">&nbsp;</li>
                <li <?php if (strcmp($page, '10a_tpl.php') == 0)
                            echo 'class="onStateProducts"'; ?> >
                        <?php
                        $abLink = new link($this->uri(array("action" => '10', "page" => '10a_tpl.php')));
                        $abLink->link = 'GROUPS';
                        echo $abLink->show();
                        ?>
                </li>
                <li class="mainNavPipe">&nbsp;</li>
                <li><a href="#">REPORTING</a></li>
                <li class="mainNavPipe">&nbsp;</li>
                <li><a href="#">ABOUT</a></li>
                <li class="mainNavPipe">&nbsp;</li>
                <li><a href="#">CONTACT</a></li>
            </ul>
        </div>
    </div>
<!--    <div class="mainContentHolder">-->
        <?php
        echo $this->getContent();
        ?>
<!--    </div>-->
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
