<?php
    $this->loadClass('commentmanager','unesco_oer');
    $this->loadClass('textarea', 'htmlelements');
    $this->loadClass('link', 'htmlelements');
    $this->loadClass('form','htmlelements');
    $this->loadClass('button','htmlelements');
    $this->objDbComments = $this->getobject('dbcomments', 'unesco_oer');
     //load java script
    $js = '<script language="JavaScript" src="'.$this->getResourceUri('ratingsys.js').'" type="text/javascript"></script>';
    $this->appendArrayVar('headerParams', $js);
?>
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
<!--                        <div id="rateMe" title="">
                            <a id="_1" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_2" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_3" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_4" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                            <a id="_5" title="" onmouseover="rating(this)" onmouseout="off(this)"></a>
                        </div>-->
                        <?php
                        if ($this->objUser->isLoggedIn()) {
                            $content = '<div id="rateMe" title="">
                            <a id="_1" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
                            <a id="_2" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
                            <a id="_3" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
                            <a id="_4" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
                            <a id="_5" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
                            </div>';
                            //TODO prev action parameter must point to the correct page.
                            $form = new form('addProductRating_ui',$this->uri(array('action'=>'submitProductRating', 'productID' => $productID, 'rateSubmit' => '', 'prevAction' => 'home')));
                            $form->addToForm($content);
                            echo $form->show();
                        }

                        ?>
                        <div class="commentsLinkUnderRatingStarsDiv">
                        <img src="skins/unesco_oer/images/icon-comment-post.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">
                                <?php

                              echo  $this->objDbComments->getTotalcomments($productID) . " Comments";


                                ?>


                            </a></div>
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
                            if ($this->objUser->isLoggedIn()) {
                                //TODO send prevAction to 2a when that link is no longer broken
                                $uri = $this->uri(array('action' => 'editProduct', 'id' => $productID , 'prevAction' => 'home'));
                                $editLink = new link($uri);
                                $editLink->cssClass = "searchGoLink";
                                $linkText = "edit product";
                                $editLink->link = $linkText;
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

                        <?php
                    if (($this->objDbComments->getTotalcomments($productID) >= 2))
                    {

                    ?>
                    <span class="greyText fontBold">User comments:</span>
                    <br /><br />
                    

                    <div class="listCommunityRelatedInfoDiv">
                    	<div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png"></div>
                        <div class="communityRelatedInfoText">
                        	<a href="#" class="greyTextLink">

                               <?php


                         $Comment = $this->getobject('commentmanager', 'unesco_oer');
                             $comments =  $Comment->recentcomment($productID);
                             echo $comments[2];

                            ?>

                      
                            </a>
                        </div>
                    </div>
                    <div class="listCommunityRelatedInfoDiv">
                    	<div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png"></div>
                        <div class="communityRelatedInfoText">
                        	<a href="#" class="greyTextLink">
                            <?php
                    

                        $Comment = $this->getobject('commentmanager', 'unesco_oer');
                             $comments =  $Comment->recentcomment($productID);
                             echo $comments[1];

                            ?>
                            </a>
                        </div>
                    </div>
                   
                    <div class="viewAllnewsBlueDiv"><a href="#" class="greyTextLink">

                                  
                           view all comments
                      


                        </a></div>

                      <button>Show it</button>
                         <script src="http://code.jquery.com/jquery-latest.js"></script>
                            <script>
                            $("button").click(function () {
                            $("div").show("slow");
                            });
                            </script>



                            <div style="display: none">
                                <?php
                              $comments = $this->objDbComments->getComment($productID);
                        $content ='';

                        foreach ($comments as $comment) {
                    $content .= '        <div class="listCommunityRelatedInfoDiv">
                    	<div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png"></div>
                        <div class="communityRelatedInfoText">
                        	<a href="#" class="greyTextLink">' . $comment['product_comment'] .

                             


                       

                         
                      
                           ' </a>
                        </div>
                    </div>';


                         

                        }

                    echo $content

                                ?>  </div>





<!--                    <textarea class="commentTextBox">Leave comment</textarea>
                    <div class="commentSubmit">
                        <div class="submiText"><a href="" class="searchGoLink">SUBMIT</a></div>
                        <a href=""><img src="skins/unesco_oer/images/button-search.png" width="17" height="17" class="submitCommentImage"></a>
                    </div>-->
                    <?php
                    }

                    else  if (($this->objDbComments->getTotalcomments($productID) == 1)) {


                   echo ' <span class="greyText fontBold">User comments:</span>
                    <br /><br />
                    <div class="listCommunityRelatedInfoDiv">
                    	<div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png"></div>
                        <div class="communityRelatedInfoText">
                        	<a href="#" class="greyTextLink">';



                            $Comment = $this->getobject('commentmanager', 'unesco_oer');
                             $comments =  $Comment->recentcomment($productID);
                             echo $comments[1];



                         echo'   </a>
                        </div>
                    </div>';



                    }


                    $Comments = $this->getobject('commentmanager', 'unesco_oer');
                          echo   $Comments->commentbox($productID);






		        ?>
                    </div>
                </div>
                </div>
           