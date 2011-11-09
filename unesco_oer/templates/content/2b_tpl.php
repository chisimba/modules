<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');
if ($adaptationstring == null)
$adaptationstring = "parent_id is not null and deleted = 0";

$js = '<script language="JavaScript" src="'.$this->getResourceUri('filterproducts.js').'" type="text/javascript"></script>';
    $this->appendArrayVar('headerParams', $js);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  

                            <div class="mainContentHolder">
                                <div class="subNavigation"></div>
                                <!-- Left Colum -->
                                 <div class="leftColumnDiv">
                              <?php
                           
                             if ($browsecheck){
                                  
                                  $adaptationstring = $finalstring;
                              }
                           $filtering = $this->getobject('filterdisplay', 'unesco_oer');
                           echo $filtering->SideFilter('2b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
                           
                           if ($browsecheck){
                                  
                                  $adaptationstring = "parent_id is not null and deleted = 0";
                              }
?>

                                
                                
                            
                            
                    <br><br>
                    <div class="blueBackground rightAlign">
                        <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                        <a href="#" class="resetLink"> 
 <?php
        $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));
        
         if ($browsecheck){
           if ($i == null){
               $i = 1;
           }
    
           $button->onclick = "javascript:ajaxFunction23('$adaptationstring','$ProdID',$browsecheck);ajaxFunction($i,'$ProdID',$browsecheck)";
          
       }
       else  $button->onclick = "javascript:ajaxFunction23('$adaptationstring');ajaxFunction($i)";
       
      
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
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php','MapEntries' => $MapEntries)));
                                            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
                                            echo $abLink->show();
                            ?>

                                            <div class="gridListDivView">
                            <?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php','MapEntries' => $MapEntries)));
                                            $abLink->link = 'GRID';
                                            echo $abLink->show();
                            ?>
                                            </div>

                                            <div class="gridListPipe">|</div>

<?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" =>$adaptationstring, "page" => '2b_tpl.php')));
                                            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                                            echo $abLink->show();
?>

                                            <div class="gridListDivView">

<?php
                                            $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2b_tpl.php')));
                                            $abLink->link = 'LIST';
                                            echo $abLink->show();
?>

                                            </div>
                                        </div>
                                    </div>
                           <div id='filterDiv' title = "2b" >
                            <?php
                                            //$objTable = $this->getObject('htmltable', 'htmlelements');

                                            $newRow = true;
                                            $count = 0;

                                            if ($browsecheck){
                             
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
                                <!--
                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                                        Adapted by
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="darkGreyColour">Polytechnic of Namibia</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-managed-by.png" alt="Mamged by" width="24" height="24"><br>
                                        <span class="greenColor">Managed by</span>
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="greenColor">Politechnic of Namibia, journalism department</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>



                                </div>


                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                                        Adapted by
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="darkGreyColour">Polytechnic of Namibia</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>



                                </div>


                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-managed-by.png" alt="Adapted by" width="24" height="24"><br>
                                        <span class="greenColor">Managed by</span>
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="greenColor">Politechnic of Namibia, journalism department</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>



                                </div>


                                <div class="adaptationListView">
                                    <div class="productAdaptationListViewLeftColumn">
                                        <h2><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h2><br>
                                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (11)</a></div>
                                    </div>
                                    <div class="productAdaptationListViewMiddleColumn">
                                        <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                                        Adapted by
                                    </div>
                                    <div class="productAdaptationListViewRightColumn">
                                        <h2 class="darkGreyColour">Polytechnic of Namibia</h2>
                                        <br>
                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv">
                                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                                            </div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                        </div>

                                        <div class="productAdaptationViewDiv">
                                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                            <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                        </div>
                                    </div>
                                </div>

                                -->




                                <!-- Pagination-->
                                <div class="paginationDiv">
<!--                                    <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>-->

<?php
                            $Pagination = $this->getobject('filterdisplay', 'unesco_oer');
                              $Pagination->Pagination('2b_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum, $pageinfo)
?>
                                                <!--                        <div class="paginationLinkDiv">
                                                                            <a href="#" class="pagination">Prev</a>
                                                                            <a href="#" class="pagination">1</a>
                                                                            <a href="#" class="pagination">2</a>
                                                                            <a href="#" class="pagination">3</a>
                                                                            <a href="#" class="pagination">4</a>
                                                                            <a href="#" class="pagination">5</a>
                                                                            <a href="#" class="pagination">6</a>
                                                                            <a href="#" class="pagination">7</a>
                                                                            <a href="#" class="pagination">8</a>
                                                                            <a href="#" class="pagination">9</a>
                                                                            <a href="#" class="pagination">10</a>
                                                                            <a href="#" class="pagination">Next</a>
                                                                        </div>-->
                                            </div>
                                        </div>

                                        <!-- Right column DIv -->
                                        <div class="rightColumnDiv">
                                            <div class="rightColumnDiv">
                                                <div class="featuredHeader">FEATURED ADAPTATION</div>
                                                <div class="rightColumnBorderedDiv">
                                                    
                        <?php    $featuredProducts = $this->objDbFeaturedProduct->getCurrentFeaturedAdaptedProduct();
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
                                    <div class="featuredHeader"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_browse_map', 'unesco_oer') ?></div>
                                </div>
                                <div class="rightColumnBorderedmap">
                                    <div >

                      
                                      <!DOCTYPE html>
                                
                                            <head>
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

                                              $coords = $this->objDbGroups->getAllgroups();
//                                            $objDbGroups = $this->getObject('dbgroups','unesco_oer');
//                                            $array_of_AdaptedProduct_COordinates=array();
//                                            $adaptedproduct;//Todo get an array of adapted product in the page
//                                            foreach($adaptedProduct as $product){
//                                                $productid; //TODO get product id of each adapted product
//                                               array_push($array_of_AdaptedProduct_COordinates,$objDbGroups->getAdaptedProductLat($productid));
//                                            }
//
//
//                                               $coords=$this->$array_of_AdaptedProduct_COordinates;


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
                                                      "<?php           echo $titles['name']            ?>",



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
                                        </head>
                         
                                            <div id="map_canvas" style="width:210; height:110"></div>
<?php
                                                $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', "page" => '2a_tpl.php',  'MapEntries' => $MapEntries)));

                                                echo $form->show();
                                                
                                                
                                                
                                                
                                            ?>
                                        </body>
                                    </html>



                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Footer-->
    
</html>

