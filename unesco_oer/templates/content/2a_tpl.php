<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');

//Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'Off');



if ($adaptationstring == null)
    $adaptationstring = 'parent_id is not null';
 $js = '<script language="JavaScript" src="'.$this->getResourceUri('filterproducts.js').'" type="text/javascript"></script>';
    $this->appendArrayVar('headerParams', $js);


?>
        <div class="mainWrapper">

            <div class="mainContentHolder">
                <div class="subNavigation"></div>
                <!-- Left Colum -->
                

<?php
                           $filtering = $this->getobject('filterdisplay', 'unesco_oer');
                           echo $filtering->SideFilter('2a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
?>
                
                
                
                
                            
                             </div>
                    <br><br>
                    <div class="blueBackground rightAlign">
                        <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                        <a href="#" class="resetLink"> 
 <?php
        $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));
       
        $button->onclick = "javascript:ajaxFunction23('$adaptationstring');ajaxFunction($i)";
       echo $button->show();

        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is null', "page" => '1a_tpl.php')));
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
                <div id='filterDiv' title = "2a" >
                            <?php
                                $objTable = $this->getObject('htmltable', 'htmlelements');





                                $products = $this->objDbProducts->getFilteredProducts($finalstring);
                                echo $finalstring;
                                $newRow = true;
                                $count = 0;

                                foreach ($products as $product) {
                                    $count++;                       //populates table
                                    //Check if the creator is a group or an institution

                                    if ($this->objDbGroups->isGroup($product['creator'])) {
                                        $thumbnail = $this->objDbGroups->getGroupThumbnail($product['creator']);
                                        $product['group_thumbnail'] = $thumbnail['thumbnail'];
                                        $product['institution_thumbnail'] = NULL;
                                        //$product['country'] = 'Not Available';
                                        $product['country'] = $this->objDbGroups->getGroupCountry($product['creator']);
                                        $product['type'] = 'Not Available';
                                    } else {
                                        $thumbnail = $this->objDbInstitution->getInstitutionThumbnail($product['creator']);
                                        $product['group_thumbnail'] = NULL;
                                        //$product['country'] = 'Not Available';


                                        $product['country'] = $this->objDbInstitution->getInstitutionCountry($product['creator']);
                                        //$product['type'] = 'Not Available';

                                        $institutionTypeID = $this->objDbInstitution->findInstitutionTypeID($product['creator']);
                                        //   $product['type'] = $this->objDbInstitutionTypes->getTypeName($institutionTypeID);

                                        $product['institution_thumbnail'] = $thumbnail['thumbnail'];
                                    }

                                    if ($newRow) {
                                        $objTable->startRow();
                                        $objTable->addCell($this->objProductUtil->populateAdaptedGridView($product));
                                        $newRow = false;
                                    } else {
                                        $objTable->addCell($this->objProductUtil->populateAdaptedGridView($product));
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
                                <!--
                                                    <table class="gridListingTable" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td>
                                                                <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                                <div class="imageGridListing">
                                                                    <div class="imageTopFlag"></div>
                                                                    <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101" alt="Placeholder">
                                                                    <div class="imageBotomFlag"></div>
                                                                </div>
                                                                <br>
                                                                <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                                <div class="adaptedByDiv">Adapted by:</div>
                                                                <div class="gridSmallImageAdaptation">
                                                                    <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                                    <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                                </div>
                                                                <div class="gridAdaptationLinksDiv">
                                                                    <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                                    <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                                    <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                                </div>

                                                            </td>
                                                            <td>
                                                                <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                                <div class="imageGridListing">
                                                                    <div class="imageTopFlag"></div>
                                                                    <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                                    <div class="imageBotomFlag"></div>
                                                                </div>
                                                                <br>
                                                                <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                                <div class="adaptedByDiv greenColor">Managed by:</div>
                                                                <div class="gridSmallImageAdaptation">
                                                                    <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                                    <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                                </div>
                                                                <div class="gridAdaptationLinksDiv">
                                                                    <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                                    <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                                    <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                                <div class="imageGridListing">
                                                                    <div class="imageTopFlag"></div>
                                                                    <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101" alt="Placeholder">
                                                                    <div class="imageBotomFlag"></div>
                                                                </div>
                                                                <br>
                                                                <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                                <div class="adaptedByDiv">Adapted by:</div>
                                                                <div class="gridSmallImageAdaptation">
                                                                    <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                                    <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                                </div>
                                                                <div class="gridAdaptationLinksDiv">
                                                                    <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                                    <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                                    <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                                <div class="imageGridListing">
                                                                    <div class="imageTopFlag"></div>
                                                                    <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                                    <div class="imageBotomFlag"></div>
                                                                </div>
                                                                <br>
                                                                <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                                <div class="adaptedByDiv">Adapted by:</div>
                                                                <div class="gridSmallImageAdaptation">
                                                                    <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                                    <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                                </div>
                                                                <div class="gridAdaptationLinksDiv">
                                                                    <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                                    <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                                    <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                                <div class="imageGridListing">
                                                                    <div class="imageTopFlag"></div>
                                                                    <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101" alt="Placeholder">
                                                                    <div class="imageBotomFlag"></div>
                                                                </div>
                                                                <br>
                                                                <div class="orangeListingHeading">The Net for Journalists Curricula</div>
                                                                <div class="adaptedByDiv">Adapted by:</div>
                                                                <div class="gridSmallImageAdaptation">
                                                                    <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                                    <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                                </div>
                                                                <div class="gridAdaptationLinksDiv">
                                                                    <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                                    <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                                    <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                                                                <div class="imageGridListing">
                                                                    <div class="imageTopFlag"></div>
                                                                    <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                                                    <div class="imageBotomFlag"></div>
                                                                </div>
                                                                <br>
                                                                <div class="orangeListingHeading">Model Curricula for Journalism Education</div>
                                                                <div class="adaptedByDiv">Adapted by:</div>
                                                                <div class="gridSmallImageAdaptation">
                                                                    <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                                                                    <span class="greyListingHeading">Polytechnic of Namibia</span>
                                                                </div>
                                                                <div class="gridAdaptationLinksDiv">
                                                                    <a href="#" class="productAdaptationGridViewLinks">School</a> |
                                                                    <a href="#" class="productAdaptationGridViewLinks">Namibia</a> <br>
                                                                    <a href="#" class="productAdaptationGridViewLinks">English</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                -->
                                <!-- Pagination-->
                              
                               
                                <div class="paginationDiv">
                                    

<?php
                            $Pagination = $this->getobject('filterdisplay', 'unesco_oer');
                              $Pagination->Pagination('2a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum, $pageinfo)
?>
                                   
                                </div>

                            </div>

                            <!-- Right column DIv -->
                            <div class="rightColumnDiv">
                                <div class="rightColumnDiv">
                                    <div class="featuredHeader" >FEATURED ADAPTATION</div>
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

                                if ($this->objDbGroups->isGroup($featuredAdaptedProduct['creator'])) {
                                    $thumbnail = $this->objDbGroups->getGroupThumbnail($featuredAdaptedProduct['creator']);
                                    $featuredAdaptedProduct['group_thumbnail'] = $thumbnail['thumbnail'];
                                    $featuredAdaptedProduct['institution_thumbnail'] = NULL;
                                } else {
                                    $thumbnail = $this->objDbInstitution->getInstitutionThumbnail($featuredAdaptedProduct['creator']);
                                    $featuredAdaptedProduct['group_thumbnail'] = NULL;
                                    $featuredAdaptedProduct['institution_thumbnail'] = $thumbnail['thumbnail'];
                                }
                                //Get the number of adaptations
                                $featuredAdaptedProduct['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($featuredAdaptedProduct['id']);

                                echo $this->objFeaturedProducUtil->displayFeaturedAdaptedProduct($featuredAdaptedProduct);
?>
                                <div class="spaceBetweenRightBorderedDivs">
                                    <div class="featuredHeader">BROWSER ADAPTATION BY MAP</div>
                                </div>
                                <div class="rightColumnBorderedDiv">
                                    <div class="rightColumnContentPadding">

                            <?php
                            ?>

                                        <!DOCTYPE html>
                                        <html>
                                            <head>
                                                <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
                                                <style type="text/css">
                                                    html { height: 100% }
                                                    body { height: 100%; margin: 0px; padding: 0px }
                                                    #map_canvas { height: 100% }
                                                </style>
                                                <script type="text/javascript"
                                                        src="http://maps.google.com/maps/api/js?sensor=true">
                                                </script>
                                                <script type="text/javascript">

                                                    var marker = new Array();


                                                    function initialize() {

                                                    myLatlng = [

<?php
                                $coords = $this->objDbProducts->getAdaptedProducts();

                                foreach ($coords as $coord) {
?>

                                                new google.maps.LatLng(<?php echo $coord['loclat'] . ',' . $coord['loclong']; ?>),


<?php } ?>

                                                ];



                                                title = [

                                                <?php
                                                $title = $this->objDbProducts->getAdaptedProducts();

                                                foreach ($title as $titles) {
                                                ?>
                                                    "<?php echo $titles['name'] ?>",



<?php } ?>

                                                ];





                                                var myOptions = {
                                                zoom: 0,
                                                center: myLatlng[0],
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


                                                }

                                            </script>
                                        </head>
                                        <body onload="initialize()">
                                            <div id="map_canvas" style="width:100%; height:20%"></div>
<?php
                                                $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));

                                                echo $form->show();
                                                
                                                
                                                
                                                
                                                
                                                echo $temp;
                                                
                                                
                                                
                                                
?>
                                        </body>
                                    </html>



















                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer-->
                
            </div>
    </body>
</html>





