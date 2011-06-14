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
                	<div class="nameDiv"><?php echo $this->objUser->fullname(); ?></div>
                    <div class="logoutDiv">
                    	<div class="textNextToRightFloatedImage"><a href="#" class="prifileLinks">Log out</a></div>
                        <img src="images/icon-logout.png" alt="logout" class="imgFloatLeft">
                    </div>
                    <div class="profileBookmarkGroupsMessengerDiv">

                        <a href="#"><img src="images/icon-my-profile.png" alt="My Profile" width="20" height="20" class="userIcons" title="My Profile"></a>
                        <div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-my-bookmarks.png" alt="My Bookmarks" width="20" height="20" class="userIcons" title="My Bookmarks"></a>
                        <div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-my-administration-tools.png" alt="Administration Tools" width="20" height="20" class="userIcons" title="Administration Tools"></a>
                        <div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-my-groups.png" alt="My Groups" width="20" height="20" class="userIcons" title="My Groups"></a><div class="numberNextToUserIcons"></div>
                        <div class="spacingBetweenUserIcons">&nbsp;</div>
                        <a href="#"><img src="images/icon-my-messenger.png" alt="My Messages" width="20" height="20" class="userIcons" title="My Messages"></a><div class="numberNextToUserIcons"></div>

                    </div>
                </div>
                <div class="logoutSearchDivRight">
                	<div class="searctInputTextDiv">
                    	<div class="searchGoButton"><a href=""><img src="images/button-search.png" width="17" height="17" class="searchGoImage"></a>
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
        	<!-- Left column DIv -->
            <div class="groupWideLeftColumn">

            	<div class="tenPixelLeftPadding tenPixelBottomPadding">
                	<a href="#" class="groupsBreadCrumbColor">Groups</a> |
                <span class="groupsBreadCrumbColor noUnderline">Department of Media Studies, University of Namibia, Namibia</span>
                </div>
            	<div class="tenPixelPaddingLeft">
                <div class="topGroupDiv">
                	<div class="paddingContentTopLeftRightBottom">
                     <div class="memberList">

                     <img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                      <div class="textNextToGroupIcon">
                      	<h2 class="greenText">Polytechnic of Namibia, journalism department</h2>
                       	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet elit vitae neque consequat congue sed ac nunc. Phasellus mattis rhoncus commodo. Fusce non metus ut nunc dapibus cursus et sit amet diam. Nunc non nibh sit amet leo bibendum sagittis. Vestibulum posuere tincidunt tincidunt. Aenean euismod vulputate volutpat.
                       </div>
                      </div>
                      <div class="memberList rightAlign">
                      <div class="saveCancelButtonHolder">
                            <div class="textNextoSubmitButton"><a href="#" class="greenTextBoldLink">Link to institution</a></div>

                        </div>
                        <div class="saveCancelButtonHolder">
                            <div class="buttonSubmit"><a href=""><img src="images/icon-join-group.png" alt="Join Group" width="18" height="18"></a></div>
                            <div class="textNextoSubmitButton"><a href="#" class="greenTextBoldLink">Join Group</a>
                            <span class="greenText">|&nbsp;</span></div>
                        </div>
                      </div>


                    </div>

                </div>
                </div>
                <div class="innerMenuTabsDiv">
                <ul id="innerMenuTabs">
                     <li><a href="#">MEMBERS (5)</a></li>
                     <li><a href="#">ADAPTATIONS (2)</a></li>
                     <li class="onState"><a href="#">DISCUSSIONS (1)</a></li>
                     <li><a href="#">INSTITUTIONS (1)</a></li>

                </ul>
                </div>
                <div class="tenPixelPaddingLeft">
                <div class="topGroupDiv">
                	<div class="paddingContentTopLeftRightBottom">
                        <div class="discussionList">
                            <h3 class="fontBold">What are the implications of using the section name foundation?</h3>
                            Posts: 12<br>

                            Last posted: 12 May 2010
                        </div>


                    </div>
                </div>
                <br><br><br>
                </div>

            </div>

            <!-- Right column DIv -->
            <div class="rightColumnDiv">

            	<div class="rightColumnDiv">
            	<div class="featuredHeader pinkText">FEATURED ADAPTATION</div>
                <div class="rightColumnBorderedDiv">
                	<div class="rightColumnContentPadding">
                	  <img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
               	  <div class="featuredAdaptationRightContentDiv">
                        	<span class="greyListingHeading">Manual for Investigative Journalists</span>
                            <br><br>


                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">See all adaptations (15)</a></div>
                            </div>
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">See UNSECO orginals</a></div>
                            </div>



                        </div>
                        <div class="featuredAdaptedBy">Adapted By</div>
                        <img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                <span class="greyListingHeading">Polytechnic of Namibia</span>
                     </div>
                </div>
                <div class="spaceBetweenRightBorderedDivs">
                	<div class="featuredHeader pinkText">BROWSER ADAPTATION BY MAP</div>

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