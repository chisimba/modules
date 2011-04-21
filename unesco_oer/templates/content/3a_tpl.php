<?php
    $this->loadClass('commentmanager','unesco_oer');
    $this->loadClass('textarea', 'htmlelements');
    $this->loadClass('link', 'htmlelements');
    $this->loadClass('form','htmlelements');
    $this->loadClass('button','htmlelements');
?>
            	<div class="breadCrumb">
                	<a href="#" class="blueText noUnderline">UNESCO OER Products</a> |
                    <a href="#" class="blueText noUnderline">Model Curriculum for Journalism Education</a>
                </div>

                <div class="productsBackgroundColor">
                	<div class="innerLeftContent">
               	  			<div class="tenPixelPaddingLeft">
                        	<h2 class="blueText">Model Curricula for Journalism Education</h2><br>

                            <div class="leftImageHolder">
                    	<img src="skins/unesco_oer/images/3a-placeholder.jpg" alt="Placeholder" width="121" height="156"><br>
                    	<span id="rateStatus"></span>
                        <div id="rateMe" title="">
                            <a id="_1" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_2" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_3" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_4" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_5" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                        </div>
                        <div class="commentsLinkUnderRatingStarsDiv">
                        <img src="skins/unesco_oer/images/icon-comment-post.png" alt="Comments" width="18" height="18"class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">25 comments</a></div>
                        </div>
                  </div>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis congue aliquam orci, a vehicula quam scelerisque in. Donec sed quam enim, sit amet tincidunt magna. Quisque vel pharetra justo. Nulla facilisi. Cras mauris ipsum, varius quis suscipit vitae, sagittis nec nisl. Phasellus auctor venenatis vulputate. Nunc volutpat risus eget ante mollis et semper nisi porttitor. Nulla vitae mi nisi, vel rhoncus eros. Vivamus rutrum quam ut tortor egestas volutpat.
<br><br>
Integer venenatis, augue vel iaculis commodo, ante nisi bibendum odio, ac tristique arcu nibh at augue. Nunc congue, nisl a aliquet lacinia, ipsum enim feugiat purus, a lobortis orci nisl bibendum nunc.
<br><br>
Suspendisse sodales magna ut turpis venenatis pellentesque. Maecenas ut metus nisl, eu consectetur nibh. Aliquam aliquet, nibh in tempus bibendum, arcu diam accumsan est, vitae tempor mauris ligula ullamcorper lacus.
<br><br>
Donec id orci ut justo aliquam pulvinar. Aliquam molestie, risus sed consequat suscipit, enim tellus tincidunt dolor, vel aliquet arcu nisi vitae nisl.<br><br>
<img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
<div class="listingAdaptationLinkDivWide"><a href="#" class="adaptationLinks">Make a new adaptation using this UNESCO Product</a></div>
<br><br>
<img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
<div class="listingAdaptationLinkDivWide"><a href="#" class="adaptationLinks">See existing adaptations of this UNESCO Product (15)</a></div>


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
                    	<div class="twentyPixelPaddingLeft">
                        	<div class="printEmailDownloadIcons">
                        <?php
                        if ($this->objUser->isLoggedIn()) {
                            $uri = $this->uri(array('action' => 'editProduct', 'id' => $productID , 'prevAction' => 'home'));
                            $editLink = new link($uri);
                            $editLink->cssClass = "searchGoLink";
                            $linkText = "edit product";
                            $editLink->link = $linkText;
                            echo $editLink->show();
                        }
                        ?>
                    	<a href="#"><img src="skins/unesco_oer/images/icon-content-top-print.png" alt="Print" width="19" height="15"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-content-top-email.png" alt="Email" width="19" height="15"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-content-top-download.png" alt="Download" width="19" height="15"></a>
                    </div>
                    <br><br>
                    <span class="greyText fontBold">Author(s):</span> John Doe
					<br><br><br>
                    <span class="greyText fontBold">UNESCO contacts:</span> Harra Padhy | Abel Caine | Igor Nuk
                    <br><br><br>
                    <span class="greyText fontBold">Published by:</span> UNESCO
                    <br><br><br>
                    <span class="greyText fontBold">Category:</span> <a href="#" class="greyTextLink">Journalism Education</a>
                    <br><br><br>
                    <span class="greyText fontBold">Keywords:</span> <a href="#" class="greyTextLink">Journalism</a> | <a href="#" class="greyTextLink">Education</a>
                    <br><br><br>
                    <span class="greyText fontBold">See language versions of this product:</span>
                    <ul>
                    	<li><a href="#" class="liStyleLink">English</a></li>
                        <li><a href="#" class="liStyleLink">Français</a></li>
                        <li><a href="#" class="liStyleLink">Español</a></li>
                        <li><a href="#" class="liStyleLink">Русский</a></li>

                        <li><a href="#" class="liStyleLink">لعربية</a></li>
                        <li><a href="#" class="liStyleLink">中文</a></li>
                    </ul>
                    <span class="greyText fontBold">Related news:</span>
                    <br><br>
                    Integer venenatis, augue vel iaculis commodo, ante nisi bibendum odio, ac tristique arcu nibh at augue.
                    <div class="viewAllnewsBlueDiv"><a href="#" class="greyTextLink">See all related news</a></div>
                    <span class="greyText fontBold">Related events:</span>
                    <br><br>
                    Integer venenatis, augue vel iaculis commodo, ante nisi bibendum odio, ac tristique arcu nibh at augue.
                    <div class="viewAllnewsBlueDiv"><a href="#" class="greyTextLink">See all related events</a></div>
                    <span class="greyText fontBold">User comments:</span>
                    <br><br>
                    <div class="commentsDiv">
                        <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png" alt="Comments"></div>
                        <div class="comments">Integer venenatis, augue vel iaculis commodo, ante nisi bibendum.</div>
                    </div>
                    <div class="commentsDiv">
                        <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png" alt="Comments"></div>
                        <div class="comments">Integer venenatis, augue vel iaculis commodo, ante nisi bibendum.</div>
                    </div>
                	<div class="viewAllnewsBlueDiv"><a href="#" class="greyTextLink">See all comments</a></div>
<!--                    <textarea class="commentTextBox">Leave comment</textarea> -->
			<?php
		            $commentText = new textarea('newComment');
		            $commentText->setCssClass("commentTextBox");

                            //TODO make parameter pagename dynamic
                            $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID , 'pageName' => 'home'));
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
<!--                <div class="commentSubmit">
                       <div class="submiText"><a href="" class="searchGoLink">SUBMIT</a></div> 
                       <a href=""><img src="skins/unesco_oer/images/button-search.png" alt="Submit" width="17" height="17" class="submitCommentImage"></a>
                    </div>-->

                        </div>
                    </div>


                </div>