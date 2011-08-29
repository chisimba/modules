<?php
    $this->loadClass('commentmanager','unesco_oer');
    $this->loadClass('textarea', 'htmlelements');
    $this->loadClass('link', 'htmlelements');
    $this->loadClass('form','htmlelements');
    $this->loadClass('button','htmlelements');
    $this->objDbComments = $this->getobject('dbcomments', 'unesco_oer');
    $product = $this->getObject('product','unesco_oer');
    $product->loadProduct($productID);
     //load java script
    $js = '<script language="JavaScript" src="'.$this->getResourceUri('ratingsys.js').'" type="text/javascript"></script>';
    $this->appendArrayVar('headerParams', $js);
?>
            	<div class="breadCrumb">
                    <ul>
                        <li>
                    <?php
                    $adaptation = "parent_id is not null and deleted = 0";
                    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '2a_tpl.php')));
                    $abLink->cssClass = "orangeListingHeading";
                    $abLink->link = 'Product Adaptations';
                    echo $abLink->show();
                    ?>
                        </li>
<!--                	<a href="#" class="orangeListingHeading">Product adaptation</a> | -->
<!--                    <a href="#" class="greyTextTwelveSize">Politechnic of Namibia</a>-->
                        <li>
                    <?php
                        $groupInfo = $product->getGroupInfo();
                        $groupLink= new link($this->uri(array("action" => '11a','id'=>$groupInfo['id'],"page"=>'10a_tpl.php')));
                        $groupLink->link = $groupInfo['name'];
                        $groupLink->cssClass = "greyTextTwelveSize";
                        echo $groupLink->show();
                    ?>
                        </li>
                        <li>
                    <span class="greyText">
<!--                        GIE English-->
                    <?php
                        echo $product->getTitle();
                    ?>
                    </span>
                        </li>
                    </ul>
                </div>
                <div class="adaptationsBackgroundColor">
                <div class="innerLeftContent">
                	<div class="tenPixelPaddingLeft">
               	  <h2 class="adaptationListingLink">
<!--                      Model Curricula for Journalism Education-->
                    <?php
                        echo $product->getTitle();
                    ?>
                  </h2>
<!--                  <a href="#"><img src="skins/unesco_oer/images/icon-edit-section.png" class="Farright"></a>
                  <a href="#" class="greyTextLink">Edit metadata</a><br>--><br>
                    <div class="leftImageHolder">
<!--                    	<img src="skins/unesco_oer/images/3a-placeholder.jpg" width="121" height="156"><br />-->
                        <?php
                        $thumbnailPath = $product->getThumbnailPath();
                        $imageTag = "<img src='$thumbnailPath' alt='Placeholder' width='121' height='156'<br>";
                        echo $imageTag;
                        ?>
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
<!--                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis congue aliquam orci, a vehicula quam scelerisque in. Donec sed quam enim, sit amet tincidunt magna. Quisque vel pharetra justo. Nulla facilisi. Cras mauris ipsum, varius quis suscipit vitae, sagittis nec nisl. Phasellus auctor venenatis vulputate. Nunc volutpat risus eget ante mollis et semper nisi porttitor. Nulla vitae mi nisi, vel rhoncus eros. Vivamus rutrum quam ut tortor egestas volutpat.
<br><br>
Donec id orci ut justo aliquam pulvinar. Aliquam molestie, risus sed consequat suscipit, enim tellus tincidunt dolor, vel aliquet arcu nisi vitae nisl.-->
                        <?php
                            echo $product->getDescription();
                        ?>
<br><br>
	<div class="listingAdaptationsLinkAndIcon ExtraWidthDiv">
        <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
        <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>
	</div>
<!--	<div class="listingAdaptationsLinkAndIcon ExtraWidthDiv">
        <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="smallLisitngIcons">
        <div class="textNextToTheListingIconDiv wideTextNextTiListingIconDiv"><a href="#" class="adaptationLinks">Make a new adaptation using this UNESCO Product</a></div>
	</div>-->
        <?php
            $adaptationDivStart = '<div class="listingAdaptationsLinkAndIcon ExtraWidthDiv">';
            $adaptationDivEnd = '</div>';
            $adaptationImg = '<img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="smallLisitngIcons">';

            $adaptationLinkDiv = '<div class="textNextToTheListingIconDiv wideTextNextTiListingIconDiv">';

            if ($this->objUser->isLoggedIn()) {
                $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $productID, 'nextAction' => 'ViewProduct' , 'cancelAction' => 'ViewProduct', 'cancelParams'=> "id=$productID"));
                $adaptLink = new link($uri);
                $adaptLink->cssClass = "adaptationLinks";
                $linkText = 'Make a new adaptation using this UNESCO Product';
                $adaptLink->link = $linkText;

                echo $adaptationDivStart;
                echo $adaptationImg;
                echo $adaptationLinkDiv;
                echo $adaptLink->show();
                echo $adaptationDivEnd;
                echo $adaptationDivEnd;
            }
        ?>

    <div class="listingAdaptationsLinkAndIcon ExtraWidthDiv">
        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
        <div class="textNextToTheListingIconDiv wideTextNextTiListingIconDiv"><a href="#" class="adaptationLinks">See existing adaptations of this UNESCO Product (15)</a></div>
     </div>
                    </div>
                    <div class="sectionsHead">
                        <h3 class="floaLeft greyText">Sections:</h3>
<!--                        <div class="addNewMode">
                            <img src="skins/unesco_oer/images/icon-product-add-node.png" alt="New mode" width="18" height="18"class="smallLisitngIcons">
                            <div class="addNewModeDiv"><a href="#" class="addNewModeLink">add new mode</a></div>
                        </div>-->
                <div class="addNewMode">
                    <?php if ($this->objUser->isLoggedIn()) { ?>
                    <img src="skins/unesco_oer/images/icon-product-add-node.png" alt="New mode" width="18" height="18"class="smallLisitngIcons">
                    <div class="addNewModeDiv"><a href="#" class="addNewModeLink">
                        <?php

                            $abLink = new link($this->uri(array("action" => 'saveContent', 'productID' => $productID)));
                            $abLink->link =   $this->objLanguage->languageText('mod_unesco_oer_products_new_node', 'unesco_oer');

                            $abLink->cssClass = "blueText noUnderline";
                            echo $abLink->show();


                        ?>


                        </a></div>
                     <?php } ?>
                </div>
                    </div>
            
                    <div class="unOrderedListDiv">
<!--                    	<ul class="ulMinusPublish">
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
                        </ul>-->
                    
               <?php
            
              $content = $product->getContentManager();
              echo  $content->getContentTree(FALSE);
           
               ?>                   
                                                
                                                
                    </div>
					</div>
                </div>
                <div class="innerRightContent">
                	<div class="printEmailDownloadIcons">
                            <?php
                            if ($this->objUser->isLoggedIn()) {
                                $uri = $this->uri(array('action' => 'saveProductMetaData', 'productID' => $productID, 'nextAction' => 'ViewProduct', 'cancelAction'=>'ViewProduct', 'cancelParams'=>"id=$productID" ));
                                $editLink = new link($uri);
                                $editLink->title = "Edit Metadata";
                                $linkText = '<img src="skins/unesco_oer/images/icon-edit-section.png" alt="Print" width="19" height="15">';
                                $editLink->link = $linkText;
                                echo $editLink->show();

                                $uri = $this->uri(array('action' => 'deleteProduct', 'productID' => $productID, 'prevAction' => 'home'));
                                $deleteLink = new link($uri);
                                $deleteLink->title = "Delete Product";
                                $deleteLink->cssId = "deleteProduct";
                                $linkText = '<img src="skins/unesco_oer/images/icon-delete.png" alt="Print" width="19" height="15">';
                                $deleteLink->link = $linkText;
                                echo $deleteLink->show();

                                $hiddenInput = new hiddeninput('hasAdaptations');
                                $hiddenInput->value = $product->hasAdaptation();
                                $hiddenInput->extra = "id='hasAdaptations'";
                                echo $hiddenInput->show();

                                $uri = $this->uri(array('action' => "createFeaturedAdaptation", 'id' => $productID));
                                $editLink = new link($uri);
                                $editLink->title = "Make Featured Product";
                                $linkText = '<img src="skins/unesco_oer/images/icon-content-top-print.png" alt="Print" width="19" height="15">';
                                $editLink->link = $linkText;
                                echo $editLink->show();
                            }
                            ?>
<!--                    	<a href="#"><img src="skins/unesco_oer/images/icon-content-top-print.png" width="19" height="15"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-content-top-email.png" width="19" height="15"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-content-top-download.png" width="19" height="15"></a>-->
                        
                        <?php
                        $products = $this->objDbProducts->getProductByID($productID);
                        echo  $this->objProductUtil->populatebookmark($products);
                        ?>
                    </div>
                    <br><br>
                    <?php
                    $institutionID = $product->getInstitutionID();
                    if (!empty ($institutionID)) {
                    ?>
                    <div class="adaptedByDivIcon">
                    	<img src="skins/unesco_oer/images/icon-adapted-by.png" class="adadtedByInnerIcon">
                        <div class="paddingAdaptedImageHeading pinkText">Adapted By</div> 
                    </div>
                    
                    <div class="moveContentLeft">
                    
                        <img width="45" height="50" src="
                        <?php
                        $objInstitutionManager = $this->getObject('institutionmanager', 'unesco_oer');
                        $objInstitutionManager->getInstitution($institutionID);
                        echo $objInstitutionManager->getInstitutionThumbnail();
                        ?>
                             " alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                        <h2 class="pinkText">
<!--                            Polytechnic of Namibia-->
                        <?php
                        echo $objInstitutionManager->getInstitutionName();
                        ?>
                        </h2>
                        
                        <div class="textFloatLeftDivInnerSmallColumn">
                          <span class="greyText fontBold">Type of institution:</span> <a href="#" class="greyTextLink">
<!--                              School-->
                          <?php
                          echo $objInstitutionManager->getInstitutionType();
                          ?>
                          </a>
                            <br><br><br>
                            <span class="greyText fontBold">Country:</span> <a href="#" class="greyTextLink">
<!--                                Namibia-->
                            <?php
                            echo $objInstitutionManager->getInstitutionCountry();
                            ?>
                            </a>

                            <br><br><br>
                            <span class="greyText fontBold">Adapted in:</span> 
<!--                            English | <a href="#" class="greyTextLink">German</a>-->
                            <?php
                            $translations = $product->getTranslationsList();
                            foreach ($translations as $translation) {
                                $prodLanguage = $this->objDbProductLanguages->getLanguageNameByID($translation['language']);
                                if ($product->getIdentifier() == $translation['id']){
                                    echo " $prodLanguage ";
                                } else {
                                    echo " <a href='{$this->uri(array('action'=>'ViewProduct', 'id'=>$translation['id']))}' class='greyTextLink'>$prodLanguage</a> ";
                                }
                            }
                            ?>
                            <br><br>
                        </div>
                        
                        <div class="textFloatLeftDivInnerSmallColumn">
                        <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv">
<!--                        <a href="#" class="bookmarkLinks">Full information on this institution</a>-->
                        <?php
                        $uri = $this->uri(array('action' => '4', 'institutionId' =>$institutionID));
                        $InstitutionLink =  new link($uri);
                        $InstitutionLink->cssClass = 'bookmarkLinks';
                        $InstitutionLink->link = 'Full information on this institution';
                        echo $InstitutionLink->show();
                        ?>
                        </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="managedByDivWidth">
                    	<img src="skins/unesco_oer/images/icon-managed-by.png" width="24" height="24" class="floaLeft">
                        <div class="managedByTextPadding">
                        	<span class="greenColor">Managed By<br><br>
<!--                            Polithecnich of Namibia journalism department-->
                            <?php
                            echo $groupInfo['name'];
                            ?>
                            </span>
                            <br /><br />
<!--                            <a href="#" class="greenTextLink">View group</a>-->
                            <?php
                            
                            ?>
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
                
   <?php
        
                      if (($this->objDbComments->getTotalcomments($productID) > 2)){
                          
                       
               ?>
                         <script src="http://code.jquery.com/jquery-latest.js"></script>
                            <script>
                           $(document).ready(function(){

 
        $(".slidingDiv").hide();

        $(".greyTextLink").show();

 

    $('.greyTextLink').click(function(){

    $(".slidingDiv").slideToggle();

    });


});

                            </script>



                           <a href="javascript:void(0)" class="greyTextLink">Show all comments</a>

                            <div class="slidingDiv">

    
                         <?php
                        $comments = $this->objDbComments->getComment($productID);
                        $content ='';
                        $totalcomments = $this->objDbComments->getTotalcomments($productID);
                        

                        for ($i=0; $i<$totalcomments-2; $i++) {
                            
                            
                    $content .= '        <div class="listCommunityRelatedInfoDiv">
                    	<div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png"></div>
                        <div class="communityRelatedInfoText">
                        	<a href="#" class="greyTextLink">' . $comments[$i]['product_comment'] .

                             


                       

                         
                      
                           ' </a>
                        </div>
                    </div>';


                         

                        }

                    echo $content

                                ?> 
    
    
    
    
    
    
    
    
    
    <a href="javascript:void(0)" class="greyTextLink">hide comments</a></div>
                            
<?php }?>




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
<script type="text/javascript">

    jQuery(document).ready(function(){
//
                            jQuery("a[id=deleteProduct]").click(function(){
                                if(jQuery("#hasAdaptations").val()==true){
                                    alert('This product has adaptations, you may not delete it.');
                                } else {
                                    var r=confirm( "Are you sure you want to delete this product?");
                                    if(r== true){
                                        window.location=this.href;
                                    }
                                }
                                    return false;
                                }

                            );
                        }
//
                    );
</script>