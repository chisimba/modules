

<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

<script type="text/javascript"
        src="http://maps.google.com/maps/api/js?sensor=true">
</script>
<script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAA-O3c-Om9OcvXMOJXreXHAxQGj0PqsCtxKvarsoS-iqLdqZSKfxS27kJqGZajBjvuzOBLizi931BUow"></script>

<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');

if ($adaptationstring == null)
    $adaptationstring = "parent_id is not null and deleted = 0";
$js = '<script language="JavaScript" src="' . $this->getResourceUri('filterproducts.js') . '" type="text/javascript"></script>';
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
    <!-- Left Colum -->
    <div class="leftColumnDiv">

        <?php
        if ($browsecheck) {

            $adaptationstring = $finalstring;
        }
        $filtering = $this->getobject('filterdisplay', 'unesco_oer');
        echo $filtering->SideFilter('2a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);

        if ($browsecheck) {

            $adaptationstring = "parent_id is not null and deleted = 0";
        }
        ?>


        <div class="blueBackground rightAlign">
            <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
            <a href="#" class="resetLink"> 
                <?php
                $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));

                if ($browsecheck) {
                    if ($i == null) {
                        $i = 1;
                    }
                    echo "<a onclick='javascript:ajaxFunction23(" . '"' . $adaptationstring . '"' . "," . '"' . "$ProdID" . '"' . ",$browsecheck);ajaxFunction($i," . '"' . "$ProdID" . '"' . ",$browsecheck)' class='resetLink' >{$this->objLanguage->languageText('mod_unesco_oer_search_2', 'unesco_oer')}</a>";
                    echo $imgButton = "<input name='Go' onclick='javascript:ajaxFunction23(" . '"' . $adaptationstring . '"' . "," . '"' . "$ProdID" . '"' . ",$browsecheck);ajaxFunction($i," . '"' . "$ProdID" . '"' . ",$browsecheck)' type='image' src='skins/unesco_oer/images/button-search.png' value='Find'> </input>";
                } else {
                    echo "<a onclick='javascript:ajaxFunction23(" . '"' . $adaptationstring . '"' . ");ajaxFunction($i)' class='resetLink' >{$this->objLanguage->languageText('mod_unesco_oer_search_2', 'unesco_oer')}</a>";
                    echo $imgButton = "<input name='Go' onclick='javascript:ajaxFunction23(" . '"' . $adaptationstring . '"' . ");ajaxFunction($i)' type='image' src='skins/unesco_oer/images/button-search.png' value='Find'> </input>";
                }

                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
                $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset_2', 'unesco_oer');
                $abLink->cssClass = "resetLink";
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


            <?php
            $search = $this->getobject('filterdisplay', 'unesco_oer');
            echo $search->SortDisp('2a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
            ?>

            <div class="viewGrid">
                <div class="viewAsDiv">View as: </div>


                <?php
                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php')));
                $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
                echo $abLink->show();
                ?>

                <div class="gridListDivView">
                    <?php
                    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php')));
                    $abLink->link = 'GRID';
                    echo $abLink->show();
                    ?>
                </div>

                <div class="gridListPipe">|</div>

                <?php
                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2b_tpl.php', 'MapEntries' => $MapEntries)));
                $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                echo $abLink->show();
                ?>

                <div class="gridListDivView">

                    <?php
                    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2b_tpl.php', 'MapEntries' => $MapEntries)));
                    $abLink->link = 'LIST';
                    echo $abLink->show();
                    ?>

                </div>
            </div>
        </div>
        <div id='filterDiv'  >
            <div id='searchpage' title ="2a"> <p></p></div>
            <?php
            $objTable = $this->getObject('htmltable', 'htmlelements');
            $objTable->cssClass = "gridListingTable";
            $objTable->width = NULL;

            if ($browsecheck) {

                $products = $finalstring;
            }
            else
                $products = $this->objDbProducts->getFilteredProducts($finalstring);


            $newRow = true;
            $count = 0;



            foreach ($products as $product) {
                $count++;                       //populates table
                //Check if the creator is a group or an institution

                $objProduct = $this->getObject('product');
                if ($browsecheck) {
                    $objProduct->loadProduct($product['product_id']);
                }
                else
                    $objProduct->loadProduct($product);

                if ($newRow) {
                    $objTable->startRow();
                    $objTable->addCell($this->objProductUtil->populateAdaptedGridView($objProduct));
                    $newRow = false;
                } else {
                    $objTable->addCell($this->objProductUtil->populateAdaptedGridView($objProduct));
                }


                //Display 3 products per row
                if ($count == 3) {
                    $newRow = true;
                    $count = 0;
                    $objTable->endRow();
                }
            }

            echo $objTable->show();
            ?>
        </div>
        <?php
        $bookmark = $this->objbookmarkmanager->populateGridView($products);
        echo $bookmark;
        ?>             

        <?php
        $Pagination = $this->getobject('filterdisplay', 'unesco_oer');
        $Pagination->Pagination('2a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum, $pageinfo)
        ?>



    </div>

    <!-- Right column DIv -->
    <div class="rightColumnDiv">

        <div class="featuredHeader" ><?php echo $this->objLanguage->languageText('mod_unesco_oer_featured', 'unesco_oer') ?></div>
        <div class="rightColumnBorderedDiv">
            <?php
            $featuredProducts = $this->objDbFeaturedProduct->getCurrentFeaturedAdaptedProduct();
            foreach ($featuredProducts as $featuredProduct) {

                //Check if it's an adapted product
                $product = $this->objDbProducts->getProductByID($featuredProduct['product_id']);

                //If the product is an adaptation
                if ($product['parent_id'] != NULL) {
                    $featuredAdaptedProduct = $product;
                }
            }

            $objProduct = $this->getObject('product');
            $objProduct->loadProduct($featuredAdaptedProduct['id']);

            echo $this->objFeaturedProducUtil->displayFeaturedAdaptedProduct($objProduct);
            $coords = $this->objDbGroups->getAllgroups();
            $title = $this->objDbGroups->getAllgroups();
            ?>
            <div class="spaceBetweenRightBorderedDivs">
                <div class="featuredHeader"><?php echo $this->objLanguage->languageText('mod_unesco_oer_browse_map', 'unesco_oer') ?></div>
            </div>
            <div id="browseByMap">
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

                <br/>
                <div id="map_canvas" style="width:190; height:110"></div>
                <?php
                $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', 'MapEntries' => $MapEntries)));

                echo $form->show();
                ?>

            </div>
        </div>

    </div>
</div>





