<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');

if ($adaptationstring == null)
    $adaptationstring = "relation is not null";
$institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
$institutionDB = $this->getObject('dbinstitution', 'unesco_oer');
$productsDB = $this->getObject('dbproducts', 'unesco_oer');
$institutionGUI->getInstitution($institutionId);
?>

<div class="mainContentHolder">
    <div class="subNavigation"></div>


    <!-- Left Wide column DIv -->
    <div class="LeftWideColumnDiv">
        <div class="breadCrumb">
            <a href="#" class="orangeListingHeading">Product adaptation</a> |
            <a class="greyTextTwelveSize noUnderline"><?php echo $institutionGUI->showInstitutionName() ?></a>
        </div>
        <div class="adaptationsBackgroundColor">
            <div class="innerLeftContent">
                <div class="tenPixelLeftPadding twentyPixelRightPadding">
                    <h2 class="adaptationListingLink">
                        <?php echo $institutionGUI->showInstitutionName() ?>
                    </h2>
                    <a>
                        <?php
                        //echo $institutionGUI->showEditInstitutionLinkThumbnail($institutionId);
                        echo $institutionGUI->showEditInstitutionLink($institutionId);
                        ?>
                    </a>
                    <a>
                        <?php
                        // echo $institutionGUI->showEditInstitutionLink($institutionId);
                        ?>
                    </a>
                    <a>
                        <?php
                        //  echo ' | ';
                        //echo $institutionGUI->showNewInstitutionLinkThumbnail();
                        ?>
                    </a>
                    <a>
                        <?php
                        // echo $institutionGUI->showNewInstitutionLink();
                        ?>
                    </a>
                    <br>
                    <br />
                    <div class="leftImageHolder rightTwent">
                        <img src="<?php
                        echo $institutionGUI->showInstitutionThumbnail();
                        ?>" width="121" height="156"><br />
                    </div>
                    <div class="institutionFullDescription">
                        <?php
                        echo $institutionGUI->showInstitutionDescription($institutionId);
                        ?>
                    </div>

                    <br><br>
                    <div class="adaptationInnerPageHeding"><h3 class="pinkText">Adaptations</h3>

                        <div class="leftColumnDiv">

                            <?php
                            $filtering = $this->getobject('filterdisplay', 'unesco_oer');
                            echo $filtering->SideFilter('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
                            ?>

                            <br/><br/>
                            <div class="blueBackground rightAlign">
                                <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                                <a href="#" class="resetLink"> 
                                    <?php
                                    $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));

                                    $button->onclick = "javascript:ajaxFunction23('$adaptationstring');ajaxFunction($i)";
                                    echo $button->show();

                                    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
                                    $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset', 'unesco_oer');
                                    echo $abLink->show();
                                    ?>

                                </a>
                            </div>
                            <div class="filterheader">

                            </div>
                            <div class="rssFeed">
                                <img src="skins/unesco_oer/images/small-icon-rss-feed.png" alt="RSS Feed" width="18" height="18" class="imgFloatRight">
                                <div class="feedLinkDiv"><a href="#" class="rssFeedLink">RSS Feed</a></div>
                            </div>

                        </div>

                    </div>
                    <div class="leftColumnDiv">

                    </div>
                    <div class="innerRightColumn4">


                        <div class="rightColumInnerDiv">
                            <?php
                            $productsByID = $institutionDB->getProductIdbyInstid($institutionId);
                            foreach ($productsByID as $productsByInst) {
                                $products[] = $productsDB->getOERbyProductID($productsByInst["product_id"]);
                            }
                            ?>
                            <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                            Adapted in <a href="#" class="productAdaptationGridViewLinks">English</a>
                            <br>
                            <div class="listingAdaptationsLinkAndIcon">
                                <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                            </div>

                            <div class="listingAdaptationsLinkAndIcon">
                                <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                            </div>
                        </div>
                    </div>
                    <br>


                </div>
            </div>

            <div class="innerRightContent">
                <div class="rightColumn4RightPadding">
                    <div class="printEmailDownloadIcons">
                        <a href="#"><img src="skins/unesco_oer/images/icon-content-top-print.png" width="19" height="15"></a>
                        <a href="#"><img src="skins/unesco_oer/images/icon-content-top-email.png" width="19" height="15"></a>
                    </div>
                    <br><br>
                    <span class="greyText fontBold"><b>Type of institution:</b></span> <a class="greyText fontBold"> <?php echo $institutionGUI->showInstitutionType(); ?></a>
                    <br><br>
                    <span class="greyText fontBold"><b>Country:</b></span> <a class="greyText fontBold"> <?php echo $institutionGUI->showInstitutionCountry(); ?> </a>
                    <br><br>
                    <span class="greyText fontBold"><b>Address:</b></span><?php
                            $address = $institutionGUI->showInstitutionAddress();
                            echo $address['address1'];

                            if (!empty($address['address2'])) {
                                echo ', ' . $address['address2'];
                            }

                            if (!empty($address['address3'])) {
                                echo ', ' . $address['address3'];
                            }
                            ?>
                    <br><br>
                    <span class="greyText fontBold"><b>Institution website:</b></span> <a href="#" class="greyTextLink"><?php echo $institutionGUI->showInstitutionWebsiteLink(); ?></a>
                    <br><br>
                    <span class="greyText fontBold"><b>Keywords:</b></span> <a>
                        <?php
                        $keywords = $institutionGUI->showInstitutionKeywords();
                        echo $keywords['keyword1'];

                        if (!empty($keywords['keyword2'])) {
                            echo '</a> <a>| ' . $keywords['keyword2'];
                        }
                        ?>
                    </a>
                    <br><br>

                </div>
            </div>


        </div>
    </div>

    <!--
    <div class="rightColumnDiv">
        <div class="rightColumnDiv">
            <div class="featuredHeader"><?php echo $this->objLanguage->languageText('mod_unesco_oer_featured', 'unesco_oer') ?></div>
            <div class="rightColumnBorderedDiv">
                <div class="rightColumnContentPadding">
                    <?php
                    $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
                    $featuredProduct = $this->objDbProducts->getProductByID($featuredProductID);

                    echo $this->objFeaturedProducUtil->featuredProductView($featuredProduct);
                    ?>
                    <?php
                    $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($featuredProduct['id']);
                    echo"See all adaptations ($NOofAdaptation)"; // This must be a link;
                    ?>

                </div>
            </div>
           
            
            <br>
        </div>
    </div>
    -->


</div>