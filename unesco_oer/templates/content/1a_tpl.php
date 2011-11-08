
<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$adaptationstring = 'parent_id is null and deleted = 0';

if ($finalstring == null) {
    $finalstring = 'parent_id is null and deleted = 0';
    $TotalEntries = 'parent_id is null and deleted = 0';
}


$js = '<script language="JavaScript" src="' . $this->getResourceUri('filterproducts.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);

$js = '<script language="JavaScript" src="' . $this->getResourceUri('jquery.bubblepopup.v2.3.1.min.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);

$js = '<script language="JavaScript" src="' . $this->getResourceUri('addProduct.js') . '" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);


?>
<div class="mainContentHolder">

    <div class="subNavigation">
        <div style="clear:both;"></div> 
        <div class="breadCrumb module"> 
            <div id='breadcrumb'>
                <ul><li class="first"><?php echo $this->objLanguage->languageText('mod_unesco_oer_add_data_homeBtn', 'unesco_oer') ?></li>

                </ul>
            </div>

        </div>


    </div>
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
    <!-- Center column DIv -->
    <div class="centerColumnDiv">
        <div class="GridListViewDiv">
            <div class="sortBy">
                </select>
                <?php
                $sort = $this->getobject('filterdisplay', 'unesco_oer');
                echo $sort->SortDisp('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
                ?>
                </select>
            </div>
            <div class="viewGrid">
                <div class="viewAsDiv">View as: </div>


                <?php
                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
                $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
                echo $abLink->show();
                ?>

                <div class="gridListDivView">
                    <?php
                    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
                    $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_grid', 'unesco_oer');
                    echo $abLink->show();
                    ?>
                </div>

                <div class="gridListPipe">|</div>

                <?php
                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1b_tpl.php')));
                $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                echo $abLink->show();
                ?>

                <div class="gridListDivView">

                    <?php
                    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1b_tpl.php')));
                    $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_List', 'unesco_oer');
                    echo $abLink->show();
                    ?>
                </div>

                <?php
                $divOpen = '<div class="gridListPipe">|</div>
                                            <div class="gridListDivView">';
                $divClose = '</div>';

                $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
                $groupId = $objGroups->getId("ProductCreators");
                $objGroupOps = $this->getObject("groupops", "groupadmin");
                $userId = $this->objUser->userId();
                if ($this->objUser->isLoggedIn() && $this->objUser->isAdmin()){// $objGroupOps->isGroupMember($groupId, $userId)) {

                    $uri = $this->uri(array("action" => "newProduct", 'nextAction' => 'ViewProduct', 'cancelAction' => 'home', 'add_product_submit' => 'newproduct'));
                    $abLink = new link($uri);
                    $abLink->link = '<img src="skins/unesco_oer/images/icon-new-product.png" alt="New Product" width="20" height="20" class="imgFloatRight">';

                    $newProductLink = new link($uri);
                    $newProductLink->link = "New product";

                    echo $divOpen;
                    echo $abLink->show();
                    echo $newProductLink->show();
                    echo $divClose;
                }
                ?>


            </div>
        </div>
        <div id='filterDiv' title ="1a" >
            <?php
            $objTable = $this->getObject('htmltable', 'htmlelements');
            $objTable->cssClass = "gridListingTable";
            $objTable->width = NULL;


            $products = $this->objDbProducts->getFilteredProducts($finalstring);

            $newRow = true;
            $count = 0;
            $noOfAdaptations = 0;

            foreach ($products as $product) {               //populates table
                if ($product['parent_id'] == null) {
                    $count++;
                    $product['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($product['id']);

                    $languages = $this->objDbAvailableProductLanguages->getProductLanguage($product['id']);
                    $theProduct = $product + $languages;

                    if ($newRow) {
                        $objTable->startRow();
                        $objTable->addCell($this->objProductUtil->populateGridView($theProduct, $noOfAdaptations));
                        $newRow = false;
                    } else {
                        $objTable->addCell($this->objProductUtil->populateGridView($theProduct, $noOfAdaptations));
                    }
                }

                if ($count == 3) {
                    $newRow = true;
                    $objTable->endRow();
                    $count = 0;
                }
            }
            echo $objTable->show();
            ?>
        </div>  



        <?php
        $products = $this->objDbProducts->getFilteredProducts($finalstring);
        $bookmark = $this->objbookmarkmanager->populateGridView($products);
        echo $bookmark;
        ?>



        <!-- Pagination-->
        <div class="paginationDiv">


            <?php
//                $Pagination = $this->getobject('filterdisplay', 'unesco_oer');
//                $Pagination->Pagination('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum, $pageinfo)
            ?>


        </div>
    </div>

    <!-- Right column DIv -->

    <div class="rightColumnDiv">
        <div class="featuredHeader"><?php echo $this->objLanguage->languageText('mod_unesco_oer_featured', 'unesco_oer') ?></div>
        <div class="rightColumnBorderedDiv">
            <div class="rightColumnContentPadding">
                <?php
                $featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
                $featuredProduct = $this->objDbProducts->getProductByID($featuredProductID);

                echo $this->objFeaturedProducUtil->featuredProductView($featuredProduct);
                ?>
                <!--                <div class="listingAdaptationLinkDiv">
                
                                    <a href="#" class="adaptationLinks">
                <?php
                //The reason it does not display the number of adaptations is because this uses puid as the id and the function getNoOfAdaptations uses id as the id
                //   $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($featuredProduct['id']);
                //   echo"See all adaptations ($NOofAdaptation)"; // This must be a link;
                ?>
                                    </a>
                                </div>-->
            </div>
        </div>


        <div class="spaceBetweenRightBorderedDivs">
            <div class="featuredHeader innerPadding"><?php echo $this->objLanguage->languageText('mod_unesco_oer_most', 'unesco_oer') ?></div>
        </div>

        <div class="rightColumnContentPadding">
            <?php
            $objTabs = $this->newObject('tabcontent', 'htmlelements');
            $objTabs->setWidth(180);

            $mostAdapted = $this->objProductUtil->displayMostAdapted($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $displayAllMostAdaptedProducts);
            $mostCommented = $this->objProductUtil->displayMostCommented($this->objDbProducts, $this->objDbComments);
            $mostRated = $this->objProductUtil->displayMostRated($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $this->objDbProductRatings);
            $objTabs->addTab($this->objLanguage->languageText('mod_unesco_oer_adapted', 'unesco_oer'), $mostAdapted);
            $objTabs->addTab($this->objLanguage->languageText('mod_unesco_oer_rated', 'unesco_oer'), $mostRated);
            $objTabs->addTab($this->objLanguage->languageText('mod_unesco_oer_Comments', 'unesco_oer'), $mostCommented);
            echo $objTabs->show()
            ?>
        </div>
    </div>
</div>
