<?php
$this->loadClass('commentmanager', 'unesco_oer');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dbcomments', 'unesco_oer');
$this->loadClass('treemenu', 'tree');
$this->loadClass('treenode', 'tree');
$product = $this->getObject('product', 'unesco_oer');
$product->loadProduct($productID);

$js = '<script language="JavaScript" src="' . $this->getResourceUri('filterproducts.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);

//load java script
$js = '<script language="JavaScript" src="' . $this->getResourceUri('ratingsys.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);
?>
<div class="breadCrumb">
    <ul>
        <li>
    <?php
    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
    $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_products', 'unesco_oer');
    $abLink->cssClass = "blueText noUnderline";
    echo $abLink->show();
    ?>
    <!--    <a href="#" class="blueText noUnderline">UNESCO OER Products</a> -->
        </li>
        <li>
<!--    <a >-->
    <!--                        Model Curriculum for Journalism Education-->
    <?php
    echo $product->getTitle();
    ?>
        </li>
<!--    </a>-->
    </ul>
</div>

<div class="productsBackgroundColor">
    <div class="innerLeftContent">
        <div class="tenPixelPaddingLeft">
            <h2 class="blueText">
                <?php
                echo $product->getTitle();
                ?>
            </h2><br>

            <div class="leftImageHolder">
                <?php
                $thumbnailPath = $product->getThumbnailPath();
                $imageTag = "<img src='$thumbnailPath' alt='Placeholder' width='121' height='156'<br>";
                echo $imageTag;
                ?>
<!--                    	<img src="skins/unesco_oer/images/3a-placeholder.jpg" alt="Placeholder" width="121" height="156"><br>-->
                <span id="rateStatus"></span>
                <!--                        <div id="rateMe" title="">
                                            <a id="_1" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
                                            <a id="_2" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
                                            <a id="_3" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
                                            <a id="_4" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
                                            <a id="_5" title="" onmouseover="rating(this)" onmouseout="off(this)" onclick="rateIt(this)"></a>
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
                    $form = new form('addProductRating_ui', $this->uri(array('action' => 'submitProductRating', 'id' => $productID, 'rateSubmit' => '', 'prevAction' => 'ViewProduct')));
                    $form->addToForm($content);
                    echo $form->show();
                }
                ?>
                <div class="commentsLinkUnderRatingStarsDiv">
                    <img src="skins/unesco_oer/images/icon-comment-post.png" alt="Comments" width="18" height="18"class="smallLisitngIcons">
                    <div class="textNextToTheListingIconDiv"><!--<a href="#" class="bookmarkLinks">-->
                        <div class ="bookmarkLinks">
                            <?php
                            echo $this->objDbComments->getTotalcomments($productID) . " Comments";
                            ?>
                        </div>


                        <!--</a>--></div>
                </div>
            </div>
            <?php
            echo $product->getDescription();
            ?>
            <!--                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis congue aliquam orci, a vehicula quam scelerisque in. Donec sed quam enim, sit amet tincidunt magna. Quisque vel pharetra justo. Nulla facilisi. Cras mauris ipsum, varius quis suscipit vitae, sagittis nec nisl. Phasellus auctor venenatis vulputate. Nunc volutpat risus eget ante mollis et semper nisi porttitor. Nulla vitae mi nisi, vel rhoncus eros. Vivamus rutrum quam ut tortor egestas volutpat.
            <br><br>
            Integer venenatis, augue vel iaculis commodo, ante nisi bibendum odio, ac tristique arcu nibh at augue. Nunc congue, nisl a aliquet lacinia, ipsum enim feugiat purus, a lobortis orci nisl bibendum nunc.
            <br><br>
            Suspendisse sodales magna ut turpis venenatis pellentesque. Maecenas ut metus nisl, eu consectetur nibh. Aliquam aliquet, nibh in tempus bibendum, arcu diam accumsan est, vitae tempor mauris ligula ullamcorper lacus.
            <br><br>
            Donec id orci ut justo aliquam pulvinar. Aliquam molestie, risus sed consequat suscipit, enim tellus tincidunt dolor, vel aliquet arcu nisi vitae nisl.<br>-->
            <br>
            <!--<img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">-->
            <!--<div class="listingAdaptationLinkDivWide">-->
            <!--    <a href="#" class="adaptationLinks">Make a new adaptation using this UNESCO Product</a>-->
            <?php
            $adaptationDivStart = '<div class="listingAdaptationLinkDivWide">';
            $adaptationDivEnd = '</div>';
            $adaptationImg = '<img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">';

            if ($this->objUser->isLoggedIn()) {
                $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $productID , 'nextAction' => 'ViewProduct', 'cancelAction' => 'ViewProduct', 'cancelParams'=> "id=$productID"));
                $adaptLink = new link($uri);
                $adaptLink->cssClass = "adaptationLinks";
                $linkText = $this->objLanguage->languageText('mod_unesco_oer_product_new_adaptation', 'unesco_oer');
                $adaptLink->link = $linkText;

                echo $adaptationImg;
                echo $adaptationDivStart;
                echo $adaptLink->show();
                echo $adaptationDivEnd;
            }
            ?>
            <!--</div>-->
            <br><br>
            <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
            <div class="listingAdaptationLinkDivWide"><a href="#" class="adaptationLinks">
                <?php
                
                                $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $productID)));
                                $CommentLink->cssClass = 'adaptationLinks';
                                $CommentLink->link = ' See existing Adaptaions ('. $this->objDbProducts->getNoOfAdaptations($productID) . ')';
                                echo $CommentLink->show();
                                
                                   
                
                ?>

                
                </a></div>


            <div class="sectionsHead">
                <h3 class="floaLeft greyText">Sections:</h3>
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
                                        </ul>-->


                <?php
            
           
              $content = $product->getContentManager();
              echo  $content->getContentTree(FALSE,FALSE);
           
                ?>
                <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
               

<!--                <ul class="ulPlusPublish">
                    <li class="grey"><a href="">Folder 1</a></li>
                </ul>
                <ul class="ulMinusPublish">
                    <li class="grey"><a href="">Folder 2</a>
                        <ul class="ulDocument">
                            <li class="grey"><a href="">Section 1</a></li>
                            <li class="grey"><a href="">Section 2</a></li>
                            <li class="grey"><a href="">Sectiongsgerg 3</a></li>
                            <ul class="ulMinusPublish">
                                <li class="grey"><a href="">Sub-folder 1</a>
                                    <ul class="ulDocument">
                                        <li class="grey"><a href="">Section 4</a></li>
                                        <li class="grey"><a href="">Section 5</a></li>
                                        <li class="grey"><a href="">Section 6</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </ul>
                    </li>
                </ul>-->
            </div>

            </div>

            </div>

            <div class="innerRightContent">
                <div class="twentyPixelPaddingLeft">
                    <div class="printEmailDownloadIcons">
<?php
if ($this->objUser->isLoggedIn()) {
    $uri = $this->uri(array('action' => 'saveProductMetaData', 'productID' => $productID, 'nextAction' => 'ViewProduct', 'cancelAction'=>'ViewProduct', 'cancelParams'=>"id=$productID" ));
    $editLink = new link($uri);
    $editLink->title = $this->objLanguage->languageText('mod_unesco_oer_products_edit_metadata', 'unesco_oer');;
    $linkText = '<img src="skins/unesco_oer/images/icon-edit-section.png" alt="Print" width="19" height="15">';
    $editLink->link = $linkText;
    echo $editLink->show();

    $uri = $this->uri(array('action' => 'deleteProduct', 'productID' => $productID, 'prevAction' => 'home'));
    $deleteLink = new link($uri);
    $deleteLink->title = $this->objLanguage->languageText('mod_unesco_oer_products_delete', 'unesco_oer');;
    $deleteLink->cssId = "deleteProduct";
    $linkText = '<img src="skins/unesco_oer/images/icon-delete.png" alt="Print" width="19" height="15">';
    $deleteLink->link = $linkText;
    echo $deleteLink->show();

    $hiddenInput = new hiddeninput('hasAdaptations');
    $hiddenInput->value = $product->hasAdaptation();
    $hiddenInput->extra = "id='hasAdaptations'";
    echo $hiddenInput->show();

    $uri = $this->uri(array('action' => "createFeaturedProduct", 'id' => $productID));
    $editLink = new link($uri);
    $editLink->title = $this->objLanguage->languageText('mod_unesco_oer_products_make_featured', 'unesco_oer');;
    $linkText = '<img src="skins/unesco_oer/images/icon-content-top-email.png" alt="Print" width="19" height="15">';
    $editLink->link = $linkText;
    echo $editLink->show();
    
}

$products = $this->objDbProducts->getProductByID($productID);
echo $this->objProductUtil->populatebookmark($products);
?>

<!--                <a href="#"><img src="skins/unesco_oer/images/icon-content-top-print.png" alt="Email" width="19" height="15"></a>-->
              <A HREF="javascript:window.print()"><img src="skins/unesco_oer/images/icon-content-top-print.png" alt="Email" width="19" height="15"></A>

<!--                <a href="#"><img src="skins/unesco_oer/images/icon-content-top-download.png" alt="Download" width="19" height="15"></a>-->

                    </div>
                    <br><br>
                    <span class="greyText fontBold">
                        <?php
                        echo $this->objLanguage->languageText('mod_unesco_oer_products_author', 'unesco_oer')
                        ?>
                        </span> 
<?php
echo $product->getAuthors();
?>
                    <br><br><br>
                    <span class="greyText fontBold">
                        
                         <?php
                        echo $this->objLanguage->languageText('mod_unesco_oer_products_contacts', 'unesco_oer')
                        ?>
                    </span>
                    <!--                    Harra Padhy | Abel Caine | Igor Nuk-->
                    <?php
                    echo $product->getContacts();
                    ?>
                    <br><br><br>
                    <span class="greyText fontBold">
                         <?php
                        echo $this->objLanguage->languageText('mod_unesco_oer_products_published', 'unesco_oer')
                        ?>
                    </span> 
                    <!--                    UNESCO-->
                    <?php
                    echo $product->getPublisher();
                    ?>
                    <br><br><br>
                    <span class="greyText fontBold">
                         <?php
                        echo $this->objLanguage->languageText('mod_unesco_oer_products_category', 'unesco_oer')
                        ?>
                    </span> 
                    <!--                    <a href="#" class="greyTextLink">Journalism Education</a>-->
                    <?php
                    $themes = $product->getThemeNames();
                    foreach ($themes as $theme) {
                        $themeTag = "<a href='#' class='greyTextLink'>$theme</a>";
                        echo $themeTag . " ";
                    }
                    ?>
                    <br><br><br>
                    <span class="greyText fontBold">
                         <?php
                        echo $this->objLanguage->languageText('mod_unesco_oer_products_keywords', 'unesco_oer')
                        ?>
                    </span> 
                    <!--                    <a href="#" class="greyTextLink">Journalism</a> | <a href="#" class="greyTextLink">Education</a>-->
                    <?php
                    $keywords = $product->getKeyWords();
                    $keywordsSize = count($keywords);
                    for ($index = 0; $index < $keywordsSize; $index++) {
                        $keywordText = $keywords[$index]['keyword'];
                        $keywordTag = "<a href='#' class='greyTextLink'>$keywordText</a>";
                        echo $keywordTag;
                        if ($index < $keywordsSize - 1)
                            echo " | ";
                    }
                    ?>
                    <br><br><br>
                    <span class="greyText fontBold">
                        <?php
                        echo $this->objLanguage->languageText('mod_unesco_oer_products_language_versions', 'unesco_oer')
                        ?>
                    </span>
                    <ul>
                    <?php
                    $translations = $product->getTranslationsList();
                    foreach ($translations as $translation) {
                        $prodLanguage = $this->objDbProductLanguages->getLanguageNameByID($translation['language']);
                        $selected = ($product->getIdentifier() == $translation['id']) ? '(current)' : '';
                        $languageTag = "<li><a href='{$this->uri(array('action'=>'ViewProduct', 'id'=>$translation['id']))}' class='liStyleLink'>$prodLanguage $selected</a></li>";
                        echo $languageTag;
                    }

//                    $languageTag = "<li><a href='#' class='liStyleLink'>$prodLanguage</a></li>";
//                    echo $languageTag;
                    ?>
                        <!--                    	<li><a href="#" class="liStyleLink">English</a></li>
                                                <li><a href="#" class="liStyleLink">Français</a></li>
                                                <li><a href="#" class="liStyleLink">Español</a></li>
                                                <li><a href="#" class="liStyleLink">Русский</a></li>
                        
                                                <li><a href="#" class="liStyleLink">لعربية</a></li>
                                                <li><a href="#" class="liStyleLink">中文</a></li>-->
                    </ul>
                    <span class="greyText fontBold">Related news:</span>
                    <br><br>
                    Integer venenatis, augue vel iaculis commodo, ante nisi bibendum odio, ac tristique arcu nibh at augue.
                    <div class="viewAllnewsBlueDiv"><a href="#" class="greyTextLink">See all related news</a></div>
                    <span class="greyText fontBold">Related events:</span>
                    <br><br>
                    Integer venenatis, augue vel iaculis commodo, ante nisi bibendum odio, ac tristique arcu nibh at augue.
                    <div class="viewAllnewsBlueDiv"><a href="#" class="greyTextLink">See all related events</a></div>
<?php
if (($this->objDbComments->getTotalcomments($productID) >= 2)) {
    ?>
                        <span class="greyText fontBold">User comment:</span>
                        <br><br>

                        <div class="listCommunityRelatedInfoDiv">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png"></div>
                            <div class="communityRelatedInfoText">
                                <a href="#" class="greyTextLink">

    <?php
    $Comment = $this->getobject('commentmanager', 'unesco_oer');
    $comments = $Comment->recentcomment($productID);
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
    $comments = $Comment->recentcomment($productID);
    echo $comments[1];
    ?>
                                </a>
                            </div>
                        </div>
                                <?php
                                if (($this->objDbComments->getTotalcomments($productID) > 2)) {
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
        $content = '';
        $totalcomments = $this->objDbComments->getTotalcomments($productID);


        for ($i = 0; $i < $totalcomments - 2; $i++) {


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

                                <a href="javascript:void(0)" class="greyTextLink">
                                
                                <?php
                                echo $this->objLanguage->languageText('mod_unesco_oer_comments_hide', 'unesco_oer');
                                
                                ?>
                                </a></div>


    <?php } ?>

    <?php
} else if (($this->objDbComments->getTotalcomments($productID) == 1)) {


    echo '
                        <span class="greyText fontBold"><div id="usercomments"><h1>User comments:</h1></div></span>
                    <br><br>
                    <div class="commentsDiv">
                        <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png" alt="Comments"></div>
                        <div class="communityRelatedInfoText">';

    $Comment = $this->getobject('commentmanager', 'unesco_oer');
    $comments = $Comment->recentcomment($productID);
    echo $comments[1];

    echo'  </div>
                                  </div>';
}

$Comment = $this->getobject('commentmanager', 'unesco_oer');
echo $Comment->commentbox($productID);
?>
                    <!--                <div class="commentSubmit">
                                           <div class="submiText"><a href="" class="searchGoLink">SUBMIT</a></div> 
                                           <a href=""><img src="skins/unesco_oer/images/button-search.png" alt="Submit" width="17" height="17" class="submitCommentImage"></a>
                                        </div>-->

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