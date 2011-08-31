<?php
/*
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation; either version 2 of the License, or 
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the 
 * Free Software Foundation, Inc., 
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

$product = $this->getObject('product');
$product->loadProduct($productID);
$content = $product->getContentManager();
$existingContent = $content->getContentByContentID($path);

$groupInfo = $product->getGroupInfo();
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
                        Section View
                    </a>
                </a>
            </div>

        </div>
    </div>
    <!-- Left Wide column DIv -->
    <div class="adaptationsBackgroundColor">

        <div class="adaptationListViewTop">
            <div class="tenPixelLeftPadding tenPixelTopPadding">
                <div class="productAdaptationViewLeftColumnTop">
                    <div class="leftTopImage">
<?php
$thumbnailPath = $product->getThumbnailPath();
$imageTag = "<img src='$thumbnailPath' alt='Placeholder' width='45' height='49'<br>";
echo $imageTag;
?>
                    </div>
                    <div class="leftFloatDiv">
                        <h3><a href="#" class="adaptationListingLink">   
<?php
echo $product->getTitle();
?> 
                            </a></h3><br>
                        <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>
                    </div>
                </div>
<?php
$institutionID = $product->getInstitutionID();
if (!empty($institutionID)) {
    ?>
                    <div class="middleAdaptedByIcon">
                        <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                        <span class="pinkText">Adapted By</span>
                    </div>


                    <div class="productAdaptationViewLeftColumnTop">
                        <div class="leftTopImage">
    <?php
    $objInstitutionManager = $this->getObject('institutionmanager', 'unesco_oer');
    $objInstitutionManager->getInstitution($institutionID);
    $thumbnailPath = $objInstitutionManager->getInstitutionThumbnail();
    $imageTag = "<img src='$thumbnailPath' alt='Placeholder' width='45' height='49'<br>";
    echo $imageTag;
    ?>


                        </div>
                        <div class="leftFloatDiv">
                            <h3 class="darkGreyColour"> 
    <?php
    echo $objInstitutionManager->getInstitutionName();
    ?>

                            </h3><br>
                            <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>
                        </div>
                    </div>

    <?php
} else {
    ?>
                    <div class="middleAdaptedByIcon">
                        <img src="skins/unesco_oer/images/icon-managed-by.png" alt="Adapted by" width="24" height="24"><br>
                        <span class="greenText">Managed By</span>
                    </div>

                    <div class="productAdaptationViewLeftColumnTop">
                        <div class="leftFloatDiv">
                            <h3 class="greenText">
    <?php
    echo $groupInfo['name'];
    ?>


                            </h3><br>
                            <div class="textNextToTheListingIconDiv"><a href="#" class="greenTextLink">View group</a></div>
                        </div>
                    </div>

    <?php
}
?>
            </div>
        </div>

        <div class="hunderedPercentGreyHorizontalLine"></div>

        <div class="LeftWiderColumnDiv">

            <div class="pageBreadCrumb">
                <a href="#" class="greyText Underline">Barchelor</a> | 
                <a href="#" class="greyText Underline">Year 1</a> | 
                <a href="#" class="greyText Underline">Term 1</a> | 
                <span class="greyText">Foundation of Journalism Writing</span>
                <br><br>
            </div>

            <div class="headingHolder">
<?php
echo '<div class="heading2"><h2 class="greyText">' . $existingContent->getTitle() . ' </div> <br>';
?>
                <div class="icons2">
                    <a href="#"><img src="skins/unesco_oer/images/icon-edit-section.png" alt="Edit" width="18" height="18"></a>
<!--                        <a href="#"><img src="skins/unesco_oer/images/icon-delete.png" width="18" height="18" alt="Delete"></a>-->
                <?php
                $deleteContentLink = new link('deleteContent');
                $deleteContentLink->link = '<img src="skins/unesco_oer/images/icon-delete.png" width="18" height="18" alt="Delete">';
                $deleteContentLink->href = $this->uri(array(
                    'action' => 'deleteContent',
                    'productID' => $product->getIdentifier(),
                    'contentID' => $existingContent->getID(),
                    'nextAction' => 'viewProduct',
                    'params' => "id={$product->getIdentifier()}"));

                echo $deleteContentLink->show();
                ?>
                    <a href="#"><img src="skins/unesco_oer/images/icon-add-to-adaptation.png" width="18" height="18" alt="Add to adaptation"></a>
                    <a href="#"><img src="skins/unesco_oer/images/icon-content-top-print.png" width="19" height="15" align="Print"></a>
                    <a href="#"><img src="skins/unesco_oer/images/icon-download.png" alt="download" width="18" height="18"></a> 
                </div>
            </div>

            <div class="contentDivThreeWider">
                    <?php
                    echo $existingContent->showReadOnlyInput();
                    ?>
            </div>
        </div>


        <div class="rightColumnDivWide rightColumnPadding">

                <?php
                $content = $product->getContentManager();
                echo $content->getContentTree(FALSE);
                ?>


            <div class="spaceBetweenRightBorderedDivs"></div>
            <div class="rightColumnBorderedDivKeywordsWider">
                <div class="rightColumnContentPadding">

                    <h3 class="greyText">Section keywords</h3><br>
                    <a href="#" class="greyTextLink">equipment</a> | <a href="#" class="greyTextLink">computer</a>
                </div>
            </div>

        </div>
    </div>
</div>