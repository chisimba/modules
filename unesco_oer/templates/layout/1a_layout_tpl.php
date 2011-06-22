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
        	<div class="logOutSearchDiv">
            	<div class="logoutSearchDivLeft">
                	<div class="nameDiv">Hello Igor Nuk</div>
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
                    	<div class="searchGoButton"><a href=""><img src="images/button-search.png" alt="Search" width="17" height="17" class="searchGoImage"></a>
                      <a href="" class="searchGoLink">GO</a></div>
                        <div class="searchInputBoxDiv">
                        	<input type="text" name="" class="searchInput" value="Type search term here...">
                            <select name="" class="searchDropDown">
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
          	<div class="logoAndHeading">
            	<img src="images/logo-unesco.gif" class="logoFloatLeft" alt="logo">
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
                    <img src="images/icon-languages.png" alt="Languages" class="languagesMainIcon">
    			</div>
        		<div class="mainNavigation">
                    <ul id="sddm">
                         <li class="onStateProducts"><a href="#">UNESCO OER PRODUCTS</a></li>
                         <li class="mainNavPipe">&nbsp;</li>
                         <li><a href="#">PRODUCT ADAPTATIONS</a></li>
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
        	<!-- Left Colum -->
        	<div class="leftColumnDiv">
            	<div class="moduleHeader blueText">FILTER PRODUCTS</div>
                <div class="blueNumberBackground">
                	<div class="iconOnBlueBackground"><img src="images/icon-filter.png" alt="filter"></div>
                    <div class="numberOffilteredProducts">56</div>
                </div>
<div class="moduleSubHeader">Product matches filter criteria</div>
                <div class="moduleHeader darkBlueText"><img src="images/icon-filter-type.png" alt="Type of product" class="modulesImages">Type of product</div>
                <div class="blueBackground blueBackgroundCheckBoxText">
                	<input type="checkbox"> Model<br>
                    <input type="checkbox"> Guide<br>
                    <input type="checkbox"> Handbook<br>
                    <input type="checkbox"> Manual<br>
                    <input type="checkbox"> Bestoractile<br>
                </div>
                <br>
                <div class="moduleHeader darkBlueText"><img src="images/icon-filter-theme.png" alt="Theme" class="modulesImages">Theme</div>
                <div class="blueBackground">
                	<select name="theme" id="theme" class="leftColumnSelectDropdown">
                    	<option value="">All</option>
                    </select>
                </div>
                <br>
                <div class="moduleHeader darkBlueText"><img src="images/icon-filter-languages.png" alt="Language" class="modulesImages">Language</div>
                <div class="blueBackground">
                	<select name="language" id="language" class="leftColumnSelectDropdown">
                    	<option value="">All</option>
                    </select>
                </div>
                <br>
                <div class="moduleHeader darkBlueText"><img src="images/icon-filter-author.png" alt="Author" class="modulesImages">Author</div>
                <div class="blueBackground">
                	<select name="author" id="author" class="leftColumnSelectDropdown">
                    	<option value="">All</option>
                    </select>
                </div>
                <br>
                <div class="moduleHeader darkBlueText"><img src="images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages">Items per page</div>
                <div class="blueBackground">
                	<select name="items_per_page" id="items_per_page" class="leftColumnSelectDropdown">
                    	<option value="">All</option>
                    </select>
                </div>
                <br><br>
                <div class="blueBackground rightAlign">
                	<img src="images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                    <a href="#" class="resetLink">RESET</a>
                </div>
                <div class="rssFeed">
                	<img src="images/small-icon-rss-feed.png" alt="RSS Feed" width="18" height="18" class="imgFloatRight">
                    <div class="feedLinkDiv"><a href="#" class="rssFeedLink">RSS Feed</a></div>
                </div>
            </div>
        	<!-- Center column DIv -->
            <div class="centerColumnDiv">
            	<div class="GridListViewDiv">
                	<div class="sortBy">
                    Sort By:
                    <select name="" class="contentDropDown">
                    	<option value="">Date Added</option>
                    </select>
                    <select name="" class="contentDropDown">
                    	<option value="">DESC</option>
                    </select>
                    </div>
                    <div class="viewGrid">
                    	<div class="viewAsDiv">View as: </div>
                        <a href="1a.html" class="gridListViewLinks"><img src="images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight"></a><div class="gridListDivView"><a href="1a.html" class="gridListViewLinks">GRID</a></div> <div class="gridListPipe">|</div>
                        <a href="1b.html" class="gridListViewLinks"><img src="images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight"></a><div class="gridListDivView"><a href="1b.html" class="gridListViewLinks">LIST</a></div>
                    </div>
                </div>
                <table class="gridListingTable" cellspacing="0" cellpadding="0">
                	<tr>
                    	<td>
                          <div class="newImageIcon"><img src="images/icon-new.png" alt="New" width="18" height="18"></div>
                            <div class="imageGridListing">
                            	<div class="imageTopFlag"></div>
                                <img src="images/product-grid-product-cover-placeholder.jpg" alt="Grid placeholder" width="79" height="101">
                                <div class="imageBotomFlag"></div>
                            </div>
           	    		  	<br>
                            <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                            <div class="listingLanguageLinkAndIcon">
                            <img src="images/small-icon-languages.png" alt="Languages search" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv">
                          <select name="" class="listingsLanguageDropDown">
                            	<option value="">Languages</option>
                            </select>
                            </div>
                  			</div>
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                            </div>
                        </td>
                        <td>
                        	<div class="newImageIcon"></div>
                            <div class="imageGridListing">
                            	<div class="imageTopFlag"></div>
                                <img src="images/product-grid-product-cover-placeholder.jpg" alt="Grid placeholder" width="79" height="101">
                                <div class="imageBotomFlag"></div>
                            </div>
           	    		  	<br>
                            <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                            <div class="listingLanguageLinkAndIcon">
                            <img src="images/small-icon-languages.png" alt="Languages search" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv">
                          	<select name="" class="listingsLanguageDropDown">
                            	<option value="">Languages</option>
                            </select>
                            </div>
                  			</div>
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                            </div>
                        </td>
                        <td>
                        	<div class="newImageIcon"></div>
                            <div class="imageGridListing">
                            	<div class="imageTopFlag"></div>
                                <img src="images/product-grid-product-cover-placeholder.jpg" alt="Grid placeholder" width="79" height="101">
                                <div class="imageBotomFlag"></div>
                            </div>
           	    		  	<br>
                            <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                            <div class="listingLanguageLinkAndIcon">
                            <img src="images/small-icon-languages.png" alt="Languages search" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv">
                          <select name="" class="listingsLanguageDropDown">
                            	<option value="">Languages</option>
                            </select>
                            </div>
                  			</div>
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<div class="newImageIcon"></div>
                            <div class="imageGridListing">
                            	<div class="imageTopFlag"></div>
                                <img src="images/product-grid-product-cover-placeholder.jpg" alt="Grid placeholder" width="79" height="101">
                                <div class="imageBotomFlag"></div>
                            </div>
           	    		  	<br>
                            <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                            <div class="listingLanguageLinkAndIcon">
                            <img src="images/small-icon-languages.png" alt="Languages search" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv">
                          	<select name="" class="listingsLanguageDropDown">
                            	<option value="">Languages</option>
                            </select>
                            </div>
                  			</div>
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                            </div>
                        </td>
                        <td>
                        	<div class="newImageIcon"><img src="images/icon-new.png" alt="New" width="18" height="18"></div>
                            <div class="imageGridListing">
                            	<div class="imageTopFlag"></div>
                                <img src="images/product-grid-product-cover-placeholder.jpg" alt="Grid placeholder" width="79" height="101">
                                <div class="imageBotomFlag"></div>
                            </div>
           	    		  	<br>
                            <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                            <div class="listingLanguageLinkAndIcon">
                            <img src="images/small-icon-languages.png" alt="Languages search" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv">
                          <select name="" class="listingsLanguageDropDown">
                            	<option value="">Languages</option>
                            </select>
                            </div>
                  			</div>
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                            </div>
                        </td>
                        <td>
                        	<div class="newImageIcon"><img src="images/icon-new.png" alt="New" width="18" height="18"></div>
                            <div class="imageGridListing">
                            	<div class="imageTopFlag"></div>
                                <img src="images/product-grid-product-cover-placeholder.jpg" alt="Grid placeholder" width="79" height="101">
                                <div class="imageBotomFlag"></div>
                            </div>
           	    		  	<br>
                            <div class="blueListingHeading">Marovo Lagoon Encyclopaedia</div>
                            <div class="listingLanguageLinkAndIcon">
                            <img src="images/small-icon-languages.png" alt="Languages search" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv">
                          	<select name="" class="listingsLanguageDropDown">
                            	<option value="">Languages</option>
                            </select>
                            </div>
                  			</div>
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                            </div>
                        </td>
                    </tr>
                </table>
                <!-- Pagination-->
                <div class="paginationDiv">
                	<div class="paginationImage"><img src="images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>
                	<div class="paginationLinkDiv">
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
                    </div>
                </div>
            </div>

            <!-- Right column DIv -->
            <div class="rightColumnDiv">
            	<div class="rightColumnDiv">
            	<div class="featuredHeader blueText">FEATURED UNESCO PRODUCTS</div>
                <div class="rightColumnBorderedDiv">
                	<div class="rightColumnContentPadding">
                    	<img src="images/feature-img-holder.gif" alt="Featured" width="136" height="176"><br>
                        <div class="greyListingHeading">Manual for Investigative Journalists</div>
                        <br>
                        <img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">See all adaptations (15)</a></div>
                     </div>
                </div>
                <div class="spaceBetweenRightBorderedDivs">
                	<div class="featuredHeader innerPadding blueText">MOST...</div>
                </div>
                <!--tabs -->
                	<div class="tabsOffState">ADAPTED</div>
                    <div class="tabsOnState">RATED</div>
                    <div class="tabsOffState">COMMENTED</div>

                <div class="rightColumnBorderedDiv">
                	<div class="rightColumnContentPadding">
                    	<div class="leftImageTabsList"><img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="placeholder" width="45" height="49"></div>
                        <div class="rightTextTabsList">
                        	Model Curricula for Journalism Education<br>

                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                            </div>

                        </div>
                        <div class="tabsListingSpace"></div>
                        <div class="leftImageTabsList"><img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="placeholder" width="45" height="49"></div>
                        <div class="rightTextTabsList">
                        	Model Curricula for Journalism Education
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                            </div>

                        </div>
                        <div class="tabsListingSpace"></div>
                        <div class="leftImageTabsList"><img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="placeholder" width="45" height="49"></div>
                        <div class="rightTextTabsList">
                        	Model Curricula for Journalism Education
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                            </div>

                        </div>
                    </div>
                </div>
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
