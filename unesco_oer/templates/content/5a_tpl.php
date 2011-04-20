<?php
    $this->loadClass('commentmanager','unesco_oer');
    $this->loadClass('textarea', 'htmlelements');
    $this->loadClass('link', 'htmlelements');
    $this->loadClass('form','htmlelements');
    $this->loadClass('button','htmlelements');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>UNESCO</title>
<script type="text/javascript" language="javascript" src="ratingsys.js"></script> 
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
        	<div class="logOutSearchDiv">
            	<div class="logoutSearchDivLeft">
                	<div class="nameDiv">Hello Igor Nuk</div>
                    <div class="logoutDiv">
                    	<div class="textNextToRightFloatedImage"><a href="#" class="prifileLinks">Log out</a></div>
                        <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">
                    </div>
                    <div class="profileBookmarkGroupsMessengerDiv">
                        <a href="#"><img src="skins/unesco_oer/images/icon-my-profile.png" alt="My Profile" width="20" height="20" class="userIcons" title="My Profile"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-my-bookmarks.png" alt="My Bookmarks" width="20" height="20" class="userIcons" title="My Bookmarks"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-my-administration-tools.png" alt="Administration Tools" width="20" height="20" class="userIcons" title="Administration Tools"></a>
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
                         <li>
                                      <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is null', "page" => '1a_tpl.php')));
                                            $abLink->link = 'UNESCO OER PRODUCTS';
                                            echo $abLink->show();
                            ?>


                         </li>
                         <li class="mainNavPipe">&nbsp;</li>
                         <li class="onStateAdaptations">
                             
                                                          <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is not null', "page" => '2a_tpl.php')));
                                            $abLink->link = 'Product Adaptations';
                                            echo $abLink->show();
                            ?>
                             
<!--                             <a href="#">PRODUCT ADAPTATIONS</a></li>-->
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
                    <a href="#" class="greyTextTwelveSize">Politechnic of Namibia</a> |
                    <span class="greyText">GIE English</span>
                </div>
                <div class="adaptationsBackgroundColor">
                <div class="innerLeftContent">
                	<div class="tenPixelPaddingLeft">
               	  <h2 class="adaptationListingLink">Model Curricula for Journalism Education</h2>
                  <a href="#"><img src="skins/unesco_oer/images/icon-edit-section.png" class="Farright"></a>
                  <a href="#" class="greyTextLink">Edit metadata</a><br><br>
                    <div class="leftImageHolder">
                    	<img src="skins/unesco_oer/images/3a-placeholder.jpg" width="121" height="156"><br />
                    	<span id="rateStatus"></span>
                        <div id="rateMe" title="">
                            <a id="_1" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_2" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_3" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_4" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_5" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                        </div>
                        <div class="commentsLinkUnderRatingStarsDiv">
                        <img src="skins/unesco_oer/images/icon-comment-post.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">25 comments</a></div>
                        </div>
                  	</div>
                  	<div class="rightFixedContent">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis congue aliquam orci, a vehicula quam scelerisque in. Donec sed quam enim, sit amet tincidunt magna. Quisque vel pharetra justo. Nulla facilisi. Cras mauris ipsum, varius quis suscipit vitae, sagittis nec nisl. Phasellus auctor venenatis vulputate. Nunc volutpat risus eget ante mollis et semper nisi porttitor. Nulla vitae mi nisi, vel rhoncus eros. Vivamus rutrum quam ut tortor egestas volutpat.
<br><br>
Donec id orci ut justo aliquam pulvinar. Aliquam molestie, risus sed consequat suscipit, enim tellus tincidunt dolor, vel aliquet arcu nisi vitae nisl.<br><br>
	<div class="listingAdaptationsLinkAndIcon ExtraWidthDiv">
        <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
        <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>
	</div>
	<div class="listingAdaptationsLinkAndIcon ExtraWidthDiv">
        <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="smallLisitngIcons">
        <div class="textNextToTheListingIconDiv wideTextNextTiListingIconDiv"><a href="#" class="adaptationLinks">Make a new adaptation using this UNESCO Product</a></div>
	</div>
    <div class="listingAdaptationsLinkAndIcon ExtraWidthDiv">
        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
        <div class="textNextToTheListingIconDiv wideTextNextTiListingIconDiv"><a href="#" class="adaptationLinks">See existing adaptations of this UNESCO Product (15)</a></div>
     </div>
                    </div>
                    <div class="sectionsHead">
                        <h3 class="floaLeft greyText">Sections:</h3>
                        <div class="addNewMode">
                            <img src="skins/unesco_oer/images/icon-product-add-node.png" alt="New mode" width="18" height="18"class="smallLisitngIcons">
                            <div class="addNewModeDiv"><a href="#" class="addNewModeLink">add new mode</a></div>
                        </div>
                    </div>
            
                    <div class="unOrderedListDiv">
                    	<ul class="ulMinusPublish">
                            <li><a href="">Foundation of Journalism: Writing</a>
                            	<ul class="ulDocument">
                                	<li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                    <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                    <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="ulMinusPublish">
                            <li class="grey"><a href="">Foundation of Journalism: Writing</a>
                            	<ul class="ulDocument">
                                	<li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                    <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                    <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="ulPlusPublish">
                            <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                        </ul>
                        <ul class="ulMinusPublish">
                            <li class="grey"><a href="">Foundation of Journalism: Writing</a>
                            	<ul class="ulDocument">
                                	<li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                    <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                    <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                    <ul class="ulMinusPublish">
                                        <li class="grey"><a href="">Foundation of Journalism: Writing</a>
                                        	<ul class="ulDocument">
                                                <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                                <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                                <li class="grey"><a href="">Foundation of Journalism: Writing</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </ul>
                            </li>
                        </ul>
                    </div>
					</div>
                </div>
                <div class="innerRightContent">
                	<div class="printEmailDownloadIcons">
                            <?php
                            //TODO send prevAction to 2a when that link is no longer broken
                            $uri = $this->uri(array('action' => 'editProduct', 'id' => $productID , 'prevAction' => 'home'));
                            $editLink = new link($uri);
                            $editLink->cssClass = "searchGoLink";
                            $linkText = "edit product";
                            $editLink->link = $linkText;
                            if ($this->objUser->isLoggedIn()) {
                                echo $editLink->show();
                            }
                            ?>
                    	<a href="#"><img src="skins/unesco_oer/images/icon-content-top-print.png" width="19" height="15"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-content-top-email.png" width="19" height="15"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-content-top-download.png" width="19" height="15"></a>
                    </div>
                    <br><br>
                    
                    <div class="adaptedByDivIcon">
                    	<img src="skins/unesco_oer/images/icon-adapted-by.png" class="adadtedByInnerIcon">
                        <div class="paddingAdaptedImageHeading pinkText">Adapted By</div> 
                    </div>
                    
                    <div class="moveContentLeft">
                    
                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                        <h2 class="pinkText">Polytechnic of Namibia</h2>
                        
                        <div class="textFloatLeftDivInnerSmallColumn">
                          <span class="greyText fontBold">Type of institution:</span> <a href="#" class="greyTextLink">School</a>
                            <br><br><br>
                            <span class="greyText fontBold">Country:</span> <a href="#" class="greyTextLink">Namibia</a>
                            <br><br><br>
                            <span class="greyText fontBold">Adopted in:</span> English | <a href="#" class="greyTextLink">German</a>
                            <br><br>
                        </div>
                        
                        <div class="textFloatLeftDivInnerSmallColumn">
                        <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv">
                        <a href="#" class="bookmarkLinks">Full information on this institution</a>
                        </div>
                        </div>
                    </div>
                    <div class="managedByDivWidth">
                    	<img src="skins/unesco_oer/images/icon-managed-by.png" width="24" height="24" class="floaLeft">
                        <div class="managedByTextPadding">
                        	<span class="greenColor">Managed By<br><br>
                            Polithecnich of Namibia journalism department
                            </span>
                            <br /><br />
                            <a href="#" class="greenTextLink">View group</a>
                        </div> 
                    </div>
                    
                    <div class="textFloatLeftDivInnterColumn">
                    <span class="greyText fontBold">User comments:</span>
                    <br /><br />
                    <div class="listCommunityRelatedInfoDiv">
                    	<div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png"></div>
                        <div class="communityRelatedInfoText">
                        	<a href="#" class="greyTextLink">
                            	Phasellus nec eleifend magna. Morbi vel nibh quis massa venenatis fermentum in at massa. Fusce dignissim eros
                            </a>
                        </div>
                    </div>
                    <div class="listCommunityRelatedInfoDiv">
                    	<div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png"></div>
                        <div class="communityRelatedInfoText">
                        	<a href="#" class="greyTextLink">
                            	Phasellus nec eleifend magna. Morbi vel nibh quis massa venenatis fermentum in at massa. Fusce dignissim eros
                            </a>
                        </div>
                    </div>
                    <div class="viewAllnewsBlueDiv"><a href="#" class="greyTextLink">See all comments</a></div>
<!--                    <textarea class="commentTextBox">Leave comment</textarea>
                    <div class="commentSubmit">
                        <div class="submiText"><a href="" class="searchGoLink">SUBMIT</a></div>
                        <a href=""><img src="skins/unesco_oer/images/button-search.png" width="17" height="17" class="submitCommentImage"></a>
                    </div>-->
                    <?php
		            $commentText = new textarea('newComment');
		            $commentText->setCssClass("commentTextBox");

                            //TODO make parameter pagename dynamic
                            $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID , 'pageName' => '2a'));
                            $commentLink = new link($uri);
                            $commentLink->cssClass = "searchGoLink";
                            $linkText = $this->objLanguage->
                                    languageText('mod_unesco_oer_submit','unesco_oer');
                            $commentLink->link = $linkText;

                            $commentSubmitDiv = '<div class="commentSubmit">';
                            $submiTextDiv = '<div class="submiText">';
                            $submitCommentImage = '<img src="skins/unesco_oer/images/button-search.png" alt="Submit" width="17" height="17" class="submitCommentImage">';
                            $closeDiv = '</div>';

                            $button = new button('submitComment', $linkText);
                            $button->setToSubmit();

                            $form = new form('3a_comments_ui', $uri);
                            $form->addToForm($commentText->show());
                            $form->addToForm($commentSubmitDiv);
                            $form->addToForm($submiTextDiv);
                            //$form->addToForm($commentLink->show());
                            $form->addToForm($button->show()); //TODO use text link instead of button
                            $form->addToForm($closeDiv);
                            $form->addToForm($submitCommentImage);
                            $form->addToForm($closeDiv);
                            echo $form->show();
		        ?>
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