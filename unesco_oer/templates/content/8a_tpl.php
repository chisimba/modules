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
                        <img src="images/icon-logout.png" alt="logout" class="imgFloatLeft">
                    </div>
                    <div class="profileBookmarkGroupsMessengerDiv">

                        <a href="#"><img src="images/icon-my-profile.png" alt="My Profile" width="20" height="20" class="userIcons" title="My Profile"></a><div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-my-bookmarks.png" alt="My Bookmarks" width="20" height="20" class="userIcons" title="My Bookmarks"></a><div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-my-administration-tools.png" alt="Administration Tools" width="20" height="20" class="userIcons" title="Administration Tools"></a><div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-my-groups.png" alt="My Groups" width="20" height="20" class="userIcons" title="My Groups"></a><div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-my-messenger.png" alt="My Messages" width="20" height="20" class="userIcons" title="My Messages"></a><div class="numberNextToUserIcons"></div><div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-dashboard.png" alt="Dashboard" width="20" height="20" class="userIcons" title="Dashboard"></a><div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-settings.png" alt="Settings" width="20" height="20" class="userIcons" title="Settings"></a>
                    </div>
                </div>

                <div class="logoutSearchDivRight">
                	<div class="searctInputTextDiv">
                    	<div class="searchGoButton"><a href=""><img src="images/button-search.png" class="searchGoImage"></a>
                        <a href="" class="searchGoLink">GO</a></div>
                        <div class="searchInputBoxDiv">
                        	<input type="text" name="" id="" class="searchInput" value="Type search term here...">
                            <select name="" id="" class="searchDropDown">
                            	<option value="">All</option>

                            </select>
                        </div>
                        <div class="textNextToRightFloatedImage">Search</div>
                        <img src="images/icon-search.png" alt="Search" class="imgFloatLeft">
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
            	<img src="images/logo-unesco.gif" class="logoFloatLeft" alt="logo">
                 <div class="logoText">
                <span class="greyTitleText">Unesco&rsquo;s Open Educational Resources Platform</span><br>
                <h1 class="darkBlueText">African Journalism School Network</h1>
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
                    <img src="images/icon-languages.png" class="languagesMainIcon">
    			</div>
        		<div class="mainNavigation">
                    <ul id="sddm">
                         <li><a href="#">UNESCO OER PRODUCTS</a></li>
                         <li class="mainNavPipe">&nbsp;</li>
                         <li><a href="#">PRODUCT ADAPTATIONS</a></li>

						 <li class="mainNavPipe">&nbsp;</li>
                         <li class="onStateGroup"><a href="#">GROUPS</a></li>
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
            <div class="breadCrumb tenPixelLeftPadding">
                <a href="#" class="groupsBreadCrumbColor">Groups</a> |
                <span class="groupsBreadCrumbColor noUnderline">Department of Media Studies, University of Namibia, Namibia</span>

            </div>
           <div class="greenBackgroundColor">
          <div class="wideLeftFloatDiv">
        	<!-- Left Colum -->
                <div class="groupsleftColumnDiv  tenPixelTopPadding">
                	<div class="tenPixelPaddingLeft tenPixelPaddingRight">
                    	<img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid"><h5 class="greenText">Department of Media Studies, University of Namibia, Namibia</h5>
                        <div class="groupSubLinks">

                        <div class="greyDivider"></div>
                        <div class="groupSubLinksList">
                           <img src="images/small-icon-rss-feed.png" alt="Feed" width="18" height="18" class="smallLisitngIcons">
                          <div class="linksTextNextToSubIcons"><a href="#" class="greyTextBoldLink">Subscribe to feed</a></div>
                        </div>
                        <div class="groupSubLinksList">
                           <img src="images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                          <div class="linksTextNextToSubIcons"><a href="#" class="greyTextBoldLink">Bookmark this</a></div>

                        </div>
                        <div class="greyDivider"></div>
                        <br>
                       	<div class="groupSubLinksList">
                           <img src="images/icon-group-leave-group.png" alt="Leaave Group" width="18" height="18" class="smallLisitngIcons">
                           <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Leave group</a></div>
                        </div>
                        <div class="groupSubLinksList">

                           <img src="images/icon-group-discussion.png" alt="Group discussion" width="18" height="18" class="smallLisitngIcons">
                          <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Group discussion</a></div>
                        </div>
                        <div class="groupSubLinksList">
                           <img src="images/icon-group-subgroups.png" alt="Sub Groups" width="18" height="18" class="smallLisitngIcons">
                          <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Subgroups</a></div>
                        </div>
                        <div class="groupSubLinksList">

                           <img src="images/icon-group-new-sub-group.png" alt="New Group" width="18" height="18" class="smallLisitngIcons">
                          <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Create a new subgroup</a></div>
                        </div>
                        <div class="groupSubLinksList">
                           <img src="images/icon-group-email-alerts.png" alt="Email Alerts" width="18" height="18" class="smallLisitngIcons">
                          <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Email alerts</a></div>
                        </div>
                        <div class="groupSubLinksList">

                           <img src="images/icon-group-calendar.png" alt="Group Calendar" width="18" height="18" class="smallLisitngIcons">
                          <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Group calendar</a></div>
                        </div>
                        <div class="groupSubLinksList">
                           <img src="images/icon-group-files.png" alt="Group Files" width="18" height="18" class="smallLisitngIcons">
                          <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Group files</a></div>
                        </div>
                        <div class="greyDivider"></div>

                        <div class="groupSubLinksList">
                          <a href="#" class="greenTextBoldLink">Manage OER Resources</a><br>
                        </div>
                        <div class="groupSubLinksList">
                          <a href="#" class="greenTextBoldLink">Add OER Resources</a>
                        </div>
                        </div>
                    </div>

                </div>

                <div class="groupsRightColumn tenPixelTopPadding">
                    	<div class="groupOwnerImage">
                        	<img src="images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                            <br>
                            <span class="greenText fontBold">Owner:</span> <br>Igor Nuk<br><br>
                            <span class="greenText fontBold">Administrators: <br></span>2<br><br>

                            <span class="greenText fontBold">Group members: <br></span>16
                         </div>
                         <div class="groupDescription">
                         	<div class="tenPixelPaddingLeft">
                            	 <h2 class="greenText">Department of Media Studies, University of Namibia, Namibia</h2><br>
                            	<span class="greenText fontBold">Description</span>
                                <li class="noPaddingList">Advertising</li>

                                <li class="noPaddingList">Online Journalism</li>
                                <li class="noPaddingList">Photjournalism</li>
                                <li class="noPaddingList">Print writing and Design</li>
                                <br>
                                <span class="greenText fontBold">Brief description: </span>Nam vestibulum vehicula tincidunt. Sed non velit risus, in tristique elit. In et magna dolor. Nulla condimentum gravida blandit. Aliquam id turpis vitae justo molestie pharetra quis non libero. Maecenas auctor, ligula at malesuada gravida, tortor turpis porttitor lacus, sit amet aliquet lectus turpis in sapien.Phasellus pulvinar, lacus et imperdiet sagittis, quam turpis egestas nulla, sit amet commodo felis est id mauris. Phasellus sit amet venenatis est. Phasellus in quam eget quam tristique tristique. Praesent est ligula, elementum nec lobortis id.
                                <br><br>
                                <span class="greenText fontBold">Interest: </span>

                                <br><br>
                                <span class="greenText fontBold">Website: </span>www.unam.na
                                <br><br>
                                <span class="greenText fontBold">Region: </span>Africa
                                <br><br>
                                <span class="greenText fontBold">Country: </span>Namibia
                                <br><br>
                                <span class="greenText fontBold">School database revcord URL: </span>http://www.unesco-ci.org
                            </div>

                         </div>

                    <div class="discussionListingDiv">
                    	<div class="discussionListHeadingDiv">Latest discussion</div>
                        <div class="discussionListDiv">
                        	<div class="discusionPostDiv">
                            	<img src="images/user.jpg" width="40" height="40" class="discussionImage">
                            	<a href="" class="greenTextBoldLink">Discussion post title</a>
                                <br>

                                Posts: 3
                            </div>

                            <div class="discusionPostDiv">
                            	<img src="images/user.jpg" width="40" height="40" class="discussionImage">
                            	<a href="" class="greenTextBoldLink">Discussion post title</a>
                                <br>
                                Posts: 8
                                <div class="discusionReplyDiv">
                                	<img src="images/user.jpg" width="40" height="40" class="discussionImage">

                            		<a href="" class="greenTextBoldLink">Re:Discussion post title</a>
                                	<br>
                                	Posts: 1
                                </div>
                            </div>

                            <div class="discusionPostDiv">
                            	<img src="images/user.jpg" width="40" height="40" class="discussionImage">
                            	<a href="" class="greenTextBoldLink">Discussion post title</a>

                                <br>
                                Posts: 23
                            </div>

                            <div class="showAlldiscussions">
                            	<a href="" class="greenTextBoldLink">All discussions</a> |
                                <a href="" class="greenTextBoldLink">add a discussion</a>
                            </div>
                        </div>


                    </div>


                    <!--- OER Resources -->
                    <div class="resourcesListingDiv">
                    	<div class="resourcesListHeadingDiv">OER Resources</div>
                        <div class="resourcesListDiv">
                        	<div class="resourcesPostDiv">
                            	<img src="images/adapted-product-grid-group-logo-placeholder.jpg" width="45" height="49" class="resourcesImage">
                            	<h2>Model Cirrcula for Journalism Education</h2>

                                <br>
                                Adapted in : <a href="" class="greyTextLink">English</a>
                            </div>

                            <div class="resourcesPostDiv">
                            	<img src="images/adapted-product-grid-group-logo-placeholder.jpg" width="45" height="49" class="resourcesImage">
                            	<h2>Manual for Investigative Journalism</h2>
                                <br>

                                Adapted in : <a href="" class="greyTextLink">English</a>
                            </div>
                            <div class="showAlldiscussions">
                            	<a href="" class="greyTextBoldLink">All OER resources</a> |
                                <a href="" class="greyTextBoldLink">add resource</a>
                            </div>
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