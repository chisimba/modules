<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');


$this->_institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
$this->objLanguage = $this->getObject("language", "language");
$this->objDbProducts = $this->getObject("dbproducts", "unesco_oer");
$this->objDbAvailableProductLanguages = $this->getObject("dbavailableproductlanguages", "unesco_oer");
$this->objUser = $this->getObject("user", "security");

$productID = $this->getParam('productid');
$product = $this->getObject('product');
$product->loadProduct($productID);
$content = $product->getContentManager();
$page = $this->getParam('page');
?>

<?php
$base = '<script language="JavaScript" src="' . $this->getResourceUri('ckeditor/ckeditor.js', 'ckeditor') . '" type="text/javascript"></script>';
$baseajax = '<script language="JavaScript" src="' . $this->getResourceUri('ckeditor/_source/core/ajax.js', 'ckeditor') . '" type="text/javascript"></script>';
echo $base;
echo $baseajax;
?>

<div class="mainContentHolder">
    <div class="subNavigation"></div>
    <div class="wideTopContentHolderDiv">

        <div class="topHeadingDiv">
            <div class="breadCrumb">
                <?php
                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
                $abLink->link = 'UNESCO OER Products';
                $abLink->cssClass = "blueText noUnderline";
                echo $abLink->show();
                ?>
                <!--    <a href="#" class="blueText noUnderline">UNESCO OER Products</a> -->
                |
                <a href="#" class="blueText noUnderline">
                    <!--                        Model Curriculum for Journalism Education-->
                    <?php
                    $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $productID)));
                    $abLink->link = $product->getTitle();
                    $abLink->cssClass = "blueText noUnderline";


                    echo $abLink->show();
                    ?>
                    | <a class="blueText noUnderline">
                        Comments
                    </a>
                </a>
            </div>
        </div>
    </div>
    <!-- Left Wide column DIv -->
    <div class="productsBackgroundColor">

        <div class="leftTopImage tenPixelLeftPadding tenPixelTopPadding">
            <?php
            $thumbnailPath = $product->getThumbnailPath();
            $imageTag = "<img src='$thumbnailPath' alt='Placeholder' width='90' height='110'<br>";
            echo $imageTag;
            ?>

        </div>

        <div class="rightTopContentAndLinks tenPixelLeftPadding tenPixelTopPadding">
            <h2 class="blueText">
                <?php
                echo $product->getTitle();
                ?> 

            </h2>
            <div class="listTopLinks">
                <div class="productLinksViewDiv">


                    <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
<!--                    <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>-->
                    
                    <?php 
                    
                       $CommentLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $productID)));
                       $CommentLink->cssClass = 'productsLink';
                       $CommentLink->link = $this->objLanguage->languageText('mod_unesco_oer_view_product', 'unesco_oer') ;
                       echo $CommentLink->show();
                    
                    
                    ?>
                </div>
            </div>

            <div class="listTopLinks">
                <div class="productLinksViewDiv">

                    <div class="textNextToTheListingIconDiv wideradaptationLink">
                        <table width="100%">
                            <tr>

                                <?php
                                if ($this->objUser->isLoggedIn()) {

                                    $adaptationImg = '<img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight"> <div class="textNextToTheListingIconDiv wideradaptationLink">';
                                    $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $productID, 'nextAction' => 'ViewProduct', 'cancelAction' => 'ViewProduct', 'cancelParams' => "id=$productID"));
                                    $adaptLink = new link($uri);
                                    $adaptLink->cssClass = "adaptationLinks";
                                    $linkText = $this->objLanguage->languageText('mod_unesco_oer_product_new_adaptation', 'unesco_oer');
                                    $adaptLink->link = $linkText;
                                    ?>
                                    <td width="50%">
                                        <div id="makeadpt">

                                            <?php
                                            echo $adaptationImg;
                                            echo $adaptLink->show();
                                            ?>
                                        </div>
                                    </td>
                                    <?php
                                }
                                ?>      

                                <td width="50%">
                                    <div class="productLinksViewDiv">
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                                        <div class="textNextToTheListingIconDiv wideradaptationLink">


                                            <?php
                                            $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $productID)));
                                            $CommentLink->cssClass = 'adaptationLinks';
                                            $CommentLink->link = $this->objLanguage->languageText('mod_unesco_oer_products_see_existing', 'unesco_oer') . ' (' . $this->objDbProducts->getNoOfAdaptations($productID) . ')';
                                            echo $CommentLink->show();
                                            ?>
                                        </div>
                                    </div>
                                </td></tr></table>
                    </div>

                </div>      
                <div class="hunderedPercentGreyHorizontalLine"></div>        
            </div>

            <!-- Center column DIv -->
            <div class="centerColumnDiv">

                <div id="filterDiv" title ="1a" > <br>
                    <?php
//Creates chisimba table

                    $comments = $this->objDbComments->getCommentarray($productID);

                    foreach ($comments as $comment) {

                        echo '<div class = "blueListingHeading"> ' .
                        $comment['user_id'] . '<span class = "greyListingHeading">  - ' . $comment['created_on'] . '<br><br></div></span>';
                        echo $comment['product_comment'] . '<br>';
                        echo '<div class="greyDivider" > </div>';
                    }




                    $editor = $this->newObject('htmlarea', 'htmlelements');

                    $editor->name = 'commentbox';
                    $editor->height = '150px';
//$editor->width = '70%';
                    $editor->setBasicToolBar();


                    $button = new button('submitComment', $this->objLanguage->languageText('mod_unesco_oer_add_data_newcommentBtn', 'unesco_oer'));
                    $button->setToSubmit();

                    $button2 = new button('Cancel', $this->objLanguage->languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer'));
                    $button2->setToSubmit();

                    $userid = $this->objUser->userId();

                    $form = new form('3a_comments_ui', $this->uri(array("action" => 'savecomment', 'productid' => $productID, 'user_id' => $userid)));

                    $Cancelbutton = new button('submitform', $this->objLanguage->languageText('mod_unesco_oer_group_cancel_button', 'unesco_oer'));
                    $uri = $this->uri(array("action" => 'ViewProduct', "id" => $productID));
                    $Cancelbutton->setOnClick("javascript: window.location='$uri'");
                  
                    
                    
               

                    $form->addToForm("<br>Add your Comment <br><br> ");
                    $form->addToForm($editor->show());
                    $form->addToForm("<br>");
                    $form->addToForm($button->show());

                    $form->addToForm($Cancelbutton->show());
                    if ($this->objUser->isLoggedIn()) {
                        echo $form->show();
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>



