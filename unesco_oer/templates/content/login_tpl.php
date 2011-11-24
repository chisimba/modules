
<div class="mainContentHolder">

    <div class="subNavigation">
        <div style="clear:both;"></div> 
        <div class="breadCrumb module"> 
            <div id='breadcrumb'>
                <ul><li class="first">Home</li>

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

                $button = new button('Reset', $this->objLanguage->languageText('mod_unesco_oer_reset', 'unesco_oer'));

                $button->onclick = "javascript:window.location='{$this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php'))}';";
                echo $button->show();

//                echo "<a onclick='javascript:ajaxFunction23(".'"'.$adaptationstring.'"'.");ajaxFunction($i)' class='resetLink' >{$this->objLanguage->languageText('mod_unesco_oer_search_2', 'unesco_oer')}</a>";
//                echo $imgButton = "<input name='Go' onclick='javascript:ajaxFunction23(".'"'.$adaptationstring.'"'.");ajaxFunction($i)' type='image' src='skins/unesco_oer/images/button-search.png' value='Find'> </input>";
//
//                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
//                $abLink->cssClass = "resetLink";
//                $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset_2', 'unesco_oer');
//                echo $abLink->show();
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
        <?php
        $loginInterface = $this->newObject('logininterface', 'security');
        $objUser = $this->getObject('user', 'security');
        $objAltConfig = $this->getObject('altconfig', 'config');
        if ($objUser->isLoggedIn()) {
            header('Location: ' . $objAltConfig->getsiteRoot());
        } else {
            echo $loginInterface->renderLoginBox('unesco_oer');
        }
        ?>
    </div>

    <!-- Right column DIv -->

    <div class="rightColumnDiv">
        <div class="featuredHeader">FEATURED UNESCO PRODUCTS</div>
        <div class="rightColumnBorderedDiv">
            <div class="rightColumnContentPadding">
<?php
$featuredProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();
$featuredProduct = $this->objDbProducts->getProductByID($featuredProductID);

echo $this->objFeaturedProducUtil->featuredProductView($featuredProduct);
?>

            </div>
        </div>


        <div class="spaceBetweenRightBorderedDivs">
            <div class="featuredHeader innerPadding">MOST...</div>
        </div>

        <div class="rightColumnContentPadding">
<?php
$objTabs = $this->newObject('tabcontent', 'htmlelements');
$objTabs->setWidth(180);

$mostAdapted = $this->objProductUtil->displayMostAdapted($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $displayAllMostAdaptedProducts);
$mostCommented = $this->objProductUtil->displayMostCommented($this->objDbProducts, $this->objDbComments);
$mostRated = $this->objProductUtil->displayMostRated($this->objDbProducts, $this->objDbGroups, $this->objDbInstitution, $this->objDbProductRatings);
$objTabs->addTab('Adapted', $mostAdapted);
$objTabs->addTab('Rated', $mostRated);
$objTabs->addTab('Comments', $mostCommented);
echo $objTabs->show()
?>
        </div>
    </div>
</div>

?>
