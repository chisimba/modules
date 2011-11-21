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
            <a href="#" class="orangeListingHeading"><?php echo $this->objLanguage->languageText('mod_unesco_oer_productadaptationstitle', 'unesco_oer'); ?></a> |
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
                    <div class="adaptationInnerPageHeding"><h3 class="pinkText">Adaptations</h3></div>
                    <br>
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
//                                    $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));
//
//                                    $button->onclick = "javascript:ajaxFunction23('$adaptationstring');ajaxFunction($i)";
//                                    echo $button->show();

                                    echo "<a onclick='javascript:ajaxFunction23(" . '"' . $adaptationstring . '"' . ");ajaxFunction($i)' class='resetLink' >{$this->objLanguage->languageText('mod_unesco_oer_search_2', 'unesco_oer')}</a>";
                                    echo $imgButton = "<input name='Go' onclick='javascript:ajaxFunction23(" . '"' . $adaptationstring . '"' . ");ajaxFunction($i)' type='image' src='skins/unesco_oer/images/button-search.png' value='Find'> </input>";

                                    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
                                    $abLink->cssClass = "resetLink";
                                    $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset_2', 'unesco_oer');
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
<!--                    <div class="leftColumnDiv">

                    </div>-->
                    <div class="innerRightColumn4">


                        
                            <?php
                            $productsByID = $institutionDB->getProductIdbyInstid($institutionId);

                            foreach ($productsByID as $productsByInst) {
                                $product =  $this->getObject('product','unesco_oer');
                                $products = $product->loadProduct($productsByInst['product_id']);
                                
                                $productID = $productsByInst['product_id'];

                                $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $productID, 'nextAction' => 'ViewProduct', 'cancelAction' => 'ViewProduct', 'cancelParams' => "id=$productID"));
                                $adaptLink = new link($uri);
                                $adaptLink->cssClass = "adaptationLinks";
                                $linkText = $this->objLanguage->languageText('mod_unesco_oer_product_new_adaptation', 'unesco_oer');
                                $adaptLink->link = $linkText;
                                
                                $bookmarkText = $this->objLanguage->languageText('mod_unesco_oer_bookmark', 'unesco_oer');
                                $adaptedText = $this->objLanguage->languageText('mod_unesco_oer_adapted_in', 'unesco_oer');
                                
                                $bookmark =  $this->objProductUtil->populatebookmark($productID,'smallLisitngIcons');

                            
                                $productData = '
                                <div class="listAdaptations">
                                <div class="floaLeftDiv">
                                    <img width="45" height="49" alt="Adaptation placeholder" src="'.$product->getThumbnailPath().'">
                                </div>
                                <div class="rightColumInnerDiv">
                                <div class="blueListingHeading">'.$product->getTitle().'</div>
                                '.$adaptedText.' <a href="#" class="productAdaptationGridViewLinks">English</a>
                                <br>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">'.$adaptLink->show().'</a></div>
                                </div>

                                <div class="listingAdaptationsLinkAndIcon">
                                    '.$bookmark.'
                                <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">'.$bookmarkText.'</a></div>
                                </div>
                                </div>
                                </div>';
                            
                             echo $productData;
                            }
                            
                            ?>
                        
                        
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
                    <span class="greyText fontBold"><b><?php echo $this->objLanguage->languageText('mod_unesco_oer_institution_adapted', 'unesco_oer'); ?></b></span> <a class="greyText fontBold"> <?php echo $institutionGUI->showInstitutionType(); ?></a>
                    <br><br>
                    <span class="greyText fontBold"><b><?php echo $this->objLanguage->languageText('mod_unesco_oer_institution_adapted1', 'unesco_oer'); ?></b></span> <a class="greyText fontBold"> <?php echo $institutionGUI->showInstitutionCountry(); ?> </a>
                    <br><br>
                    <span class="greyText fontBold"><b><?php echo $this->objLanguage->languageText('mod_unesco_oer_institution_adapted2', 'unesco_oer'); ?></b></span><?php
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
                    <span class="greyText fontBold"><b><?php echo $this->objLanguage->languageText('mod_unesco_oer_institution_adapted3', 'unesco_oer'); ?></b></span> <a href="#" class="greyTextLink"><?php echo $institutionGUI->showInstitutionWebsiteLink(); ?></a>
                    <br><br>
                    <span class="greyText fontBold"><b><?php echo $this->objLanguage->languageText('mod_unesco_oer_institution_adapted4', 'unesco_oer'); ?></b></span> <a>
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
                $coords = $this->objDbGroups->getAllgroups();
                $title = $this->objDbGroups->getAllgroups();
                ?>

            </div>
        </div>


        <div class="spaceBetweenRightBorderedDivs">
            <div class="featuredHeader"><?php echo $this->objLanguage->languageText('mod_unesco_oer_browse_map', 'unesco_oer') ?></div>


            <script type="text/javascript">
                var marker = new Array();
                $(document).ready(function(){ 
                    myLatlng = [

<?php
foreach ($coords as $coord) {
    ?>
                    new google.maps.LatLng(
    <?php echo $coord['loclat'] . ',' . $coord['loclong']; ?>
                ),

<?php } ?>

        ];


        title = [

<?php
foreach ($title as $titles) {
    ?>
                    "<?php echo $titles['name'] ?>",

<?php } ?>

        ];

        var myOptions = {
            zoom: 0,
            center: new google.maps.LatLng(0, 0),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

        var oldAction = document.forms["maps"].action;

        for(i=0;i<myLatlng.length;i++)
        {
            marker[i] = new google.maps.Marker(
            { position: myLatlng[i],
                title: title[i]

            } );

            var pos = marker[i].getPosition();
            google.maps.event.addListener(marker[i], 'click',
            (function(pos)
            { return function()
                {
                    //alert(i);
                    document.forms["maps"].action = oldAction + "&lat=" + pos.lat() + "&Lng=" + pos.lng();
                    document.forms["maps"].submit();
                };
            }
        )(pos)
        );

            marker[i].setMap(map);

        }


    });

            </script>



            <div id="map_canvas" style="width:190; height:110"></div>
            <?php
            $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', 'MapEntries' => $MapEntries)));

            echo $form->show();
            ?>

        </div>
    </div>


</div>