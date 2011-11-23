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


<div class="subNavigation"></div>
<!-- Left Colum -->
<div class="leftColumnDiv">
    <?php
    if ($browsecheck) {

        $adaptationstring = $finalstring;
    }
    $filtering = $this->getobject('filterdisplay', 'unesco_oer');
    echo $filtering->SideFilter('2b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);

    if ($browsecheck) {

        $adaptationstring = "parent_id is not null and deleted = 0";
    }
    ?>





    <br><br>
    <div class="blueBackground rightAlign">

     
            <?php


            if ($browsecheck) {
                if ($i == null) {
                    $i = 1;
                }

//                $button->onclick = "javascript:ajaxFunction23('$adaptationstring','$ProdID',$browsecheck);ajaxFunction($i,'$ProdID',$browsecheck)";
              //  echo "<a onclick='javascript:ajaxFunction23(" . '"' . $adaptationstring . '"' . "," . '"' . "$ProdID" . '"' . ",$browsecheck);ajaxFunction($i," . '"' . "$ProdID" . '"' . ",$browsecheck)' class='resetLink' >{$this->objLanguage->languageText('mod_unesco_oer_search_2', 'unesco_oer')}</a>";
                    echo $imgButton = "<a  href='#' onclick='javascript:ajaxFunction23(" . '"' . $adaptationstring . '"' . "," . '"' . "$ProdID" . '"' . ",$browsecheck);ajaxFunction($i," . '"' . "$ProdID" . '"' . ",$browsecheck)' ".  $this->objLanguage->languageText('mod_unesco_oer_search_2', 'unesco_oer')." </a>";
                } else {
                 //   echo "<a onclick='javascript:ajaxFunction23(" . '"' . $adaptationstring . '"' . ");ajaxFunction($i)' class='resetLink' >{$this->objLanguage->languageText('mod_unesco_oer_search_2', 'unesco_oer')}</a>";
                    echo $imgButton = "<a  href='#' onclick='javascript:ajaxFunction23(".'"'.$adaptationstring.'"'.");ajaxFunction($i)' >" .  $this->objLanguage->languageText('mod_unesco_oer_search_2', 'unesco_oer')." </a>";
                }


//            echo $button->show();

            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2b_tpl.php')));
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
<!-- Center column DIv -->
<div class="centerColumnDiv">
    <div class="GridListViewDiv">
        <div class="sortBy">
            <?php
            $search = $this->getobject('filterdisplay', 'unesco_oer');
            echo $search->SortDisp('2b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
            ?>



            <!--                            Sort By:
                                        <select name="" class="contentDropDown">
                                            <option value="">Date Added</option>
                                        </select>
                                        <select name="" class="contentDropDown">
                                            <option value="">DESC</option>
                                        </select>-->
        </div>
        <div class="viewGrid">
            <div class="viewAsDiv">View as: </div>


            <?php
            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', 'MapEntries' => $MapEntries)));
            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
            echo $abLink->show();
            ?>

            <div class="gridListDivView">
                <?php
                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', 'MapEntries' => $MapEntries)));
                $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_grid', 'unesco_oer');
                echo $abLink->show();
                ?>
            </div>

            <div class="gridListPipe">|</div>

            <?php
            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2b_tpl.php')));
            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
            echo $abLink->show();
            ?>

            <div class="gridListDivView">

                <?php
                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2b_tpl.php')));
                $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_List', 'unesco_oer');
                echo $abLink->show();
                ?>

            </div>
        </div>
    </div>
      <div id='searchpage' title ="2b"> <p></p></div>
    <div id='filterDiv' >
         
        <?php
//$objTable = $this->getObject('htmltable', 'htmlelements');

        $newRow = true;
        $count = 0;

        if ($browsecheck) {

            $products = $finalstring;
        }
        else
            $products = $this->objDbProducts->getFilteredProducts($finalstring);


        foreach ($products as $product) {
            $objProduct = $this->getObject('product');
            if ($browsecheck) {
                $objProduct->loadProduct($product['product_id']);
            }
            else
                $objProduct->loadProduct($product);

            echo $this->objProductUtil->populateAdaptedListView($objProduct);
        }
        ?>
    </div>

    <?php
    $bookmark = $this->objbookmarkmanager->populateGridView($products);
    echo $bookmark;
    ?>  


    <!-- Pagination-->
<!--    <div class="paginationDiv">-->
<!--                                    <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>-->

    <?php
    $Pagination = $this->getobject('filterdisplay', 'unesco_oer');
    $Pagination->Pagination('2b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum, $pageinfo)
    ?>

<!--    </div>-->
</div>

<!-- Right column DIv -->
<div class="rightColumnDiv">
    <div class="rightColumnDiv">
        <div class="featuredHeader">FEATURED ADAPTATION</div>
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
?>
            <div class="spaceBetweenRightBorderedDivs">
                <div class="featuredHeader"><?php echo $this->objLanguage->languageText('mod_unesco_oer_browse_map', 'unesco_oer') ?></div>
            </div>
            <div class="rightColumnBorderedmap">

            <?php
            $coords = $this->objDbGroups->getAllgroups();
            ?>

                <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
                <style type="text/css">
                    html { height: 100% }
                    body { height: 100%; margin: 0px; padding: 0px }
                    #map_canvas { height: 100% }
                </style>
                <script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
                <script type="text/javascript"
                        src="http://maps.google.com/maps/api/js?sensor=true">
                </script>
                <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAA-O3c-Om9OcvXMOJXreXHAxQGj0PqsCtxKvarsoS-iqLdqZSKfxS27kJqGZajBjvuzOBLizi931BUow"></script>
                <script type="text/javascript">

                    var marker = new Array();
                    $(document).ready(function(){ 
                        myLatlng = [

                          <?php
                          foreach ($coords as $coord) {
                           ?>
                         new google.maps.LatLng(<?php echo $coord['loclat'] . ',' . $coord['loclong']; ?>),


                        <?php } ?>

                            ];

                            title = [

                              <?php
                        $title = $this->objDbGroups->getAllgroups();

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
$form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', "page" => '2a_tpl.php', 'MapEntries' => $MapEntries)));

echo $form->show();
?>

            </div>
        </div>
    </div>

</div>


