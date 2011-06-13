<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');

//Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'off');



if ($adaptationstring == null)
    $adaptationstring = 'relation is not null';
 $js = '<script language="JavaScript" src="'.$this->getResourceUri('filterproducts.js').'" type="text/javascript"></script>';
    $this->appendArrayVar('headerParams', $js);


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
                        <div class="nameDiv"><?php echo "Hello" . " " . $this->objUser->fullname(); ?></div>
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
    $abLink = new link($this->uri(array("action" => "controlpanel")));
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
                                    <td><img src="skins/unesco_oer/images/icon-product-add-node.png"></td>
                                    <td><a href="#" class="prifileLinks">
<?php
    $abLink = new link($this->uri(array("action" => "userRegistrationForm")));
    $abLink->link = 'Add User';
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
                            <div class="searchGoButton"><a href="">

        <!--                                    <img src="skins/unesco_oer/images/button-search.png" class="searchGoImage"></a>-->
                                    <a href="" class="searchGoLink"></a></div>
<?php
    $Search = $this->getobject('filterdisplay', 'unesco_oer');
    echo $Search->Search($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
?>
                            <div class="textNextToRightFloatedImage">Search</div>
<!--                           <img src="skins/unesco_oer/images/icon-search.png" alt="Search" class="imgFloatLeft">-->
                        </div>
                        <div class="facebookShareDiv">

                            <!-- AddThis Button BEGIN -->
                            <div class="shareDiv">
<!--                              <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=jabulane"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share"></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=jabulane"></script>-->

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
                        <h1>UNESCO OER Configuration Platform</h1>
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
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is null', "page" => '1a_tpl.php')));
                        $abLink->link = 'Home';
                        echo $abLink->show();
                        ?>

                        </div>
                    </div>
                    <div class="mainNavPipe">&nbsp;</div>
                    <div class="navitemOnstate">
                        <div class="navitemInnerOnstate">
                            <?php
                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'relation is not null', "page" => 'controlpanel_tpl.php')));
                            $abLink->link = 'Control Panel';
                            echo $abLink->show();
                            ?>
                        </div>
                    </div>
                   
                </div>
            </div>

      
        <div class="mainContentHolder">
        	<!-- Left Wide column DIv -->
            <div class="LeftWiderColumnDiv">
                <div class="pageBreadCrumb"></div>
                <div class="contentDivThreeWider"><br>
                  <br><br>
                  <table width="617" height="424" cellpadding="0" cellspacing="0" class="groupListingTable">
                    <tr>
                      <td width="152"><div class="whiteBackgroundBox"> <img src="skins/unesco_oer/images/icon-original-product.png" alt="Adaptation placeholder" width="109" height="116" class="smallAdaptationImageGrid">
                        <div class="groupMemberAndJoinLinkDiv">
                          <div align="center">
                             <?php
                            $abLink = new link($this->uri(array("action" => 'adddata')));
                             $abLink->cssClass = 'groupMemberAndJoinLinkDiv';
                            $abLink->link = 'Products';
                           
                               echo $abLink->show();
?> 
                              
                              
                             <br>
                            <br>
                          </div>
                        </div>
                      </div></td>
                      <td width="152"><div class="whiteBackgroundBox"> <img src="skins/unesco_oer/images/icon-member.png" alt="Adaptation placeholder" width="98" height="114" class="smallAdaptationImageGrid">
                        <div class="groupMemberAndJoinLinkDiv">
                            
                             <?php
                            $abLink = new link($this->uri(array("action" =>'userListingForm')));
                             $abLink->cssClass = 'groupMemberAndJoinLinkDiv';
                            $abLink->link = 'Users';
                           
                               echo $abLink->show();
?> 
                              
                            
                            <br>
                            <br>
                        </div>
                      </div></td>
                      <td width="269"><div class="whiteBackgroundBox"> <img src="skins/unesco_oer/images/icon-managed-by.png" alt="Adaptation placeholder" width="91" height="113" class="smallAdaptationImageGrid">
                        <div class="groupMemberAndJoinLinkDiv">  

 <?php
                            $abLink = new link($this->uri(array("action" => 'editgroups')));
                             $abLink->cssClass = 'groupMemberAndJoinLinkDiv';
                            $abLink->link = 'Groups';
                           
                               echo $abLink->show();
?> 
                              
                            <br>
                            <br>
                        </div>
                      </div></td>
                    </tr>
                    <tr>
                      <td><div class="whiteBackgroundBox"> <img src="skins/unesco_oer/images/icon-filter-institution-type.png" alt="Adaptation placeholder" width="88" height="105" class="smallAdaptationImageGrid">
                        <div class="groupMemberAndJoinLinkDiv">
                            
                            <?php
                            $abLink = new link($this->uri(array("action" => 'viewInstitutions')));
                             $abLink->cssClass = 'groupMemberAndJoinLinkDiv';
                            $abLink->link = 'Institution';
                           
                               echo $abLink->show();
?> 
                              <br>
                            <br>
                        </div>
                      </div></td>
                      <td><div class="whiteBackgroundBox"> <img src="skins/unesco_oer/images/link.png" alt="Adaptation placeholder" width="84" height="74" class="smallAdaptationImageGrid">
                            <div class="groupMemberAndJoinLinkDiv">Link Groups and Institutions<br>
                                <br>
                            </div>
                      </div></td>
                      <td><div class="whiteBackgroundBox"> <img src="skins/unesco_oer/images/icon-group-product.png" alt="Adaptation placeholder" width="98" height="100" class="smallAdaptationImageGrid">
                            <div class="groupMemberAndJoinLinkDiv">Create Static Page<br>
                                <br>
                            </div>
                      </div></td>
                    </tr>
                  </table>
                  <br>
                <br><br><br>
              </div>
            </div>
            
            <div class="rightColumnDivWide rightColumnPadding"></div>
        <!-- Right column DIv -->
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