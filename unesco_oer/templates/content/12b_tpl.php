<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<!--        <div class="mainContentHolder">-->
        	<div class="subNavigation"></div>
        	<!-- Left Wide column DIv -->
            <div class="groupWideLeftColumn">

            	<div class="tenPixelPaddingLeft">
                <div class="topReportingDiv">
                	<div class="paddingContentTopLeftRightBottom">
                     <div class="memberList">
                     
                      <div class="textNextToGroupIcon">
                      	<h2 class="greyText">Reporting Tool</h2>
                       	
                       </div>

                      </div>
                    </div>
                </div>
                </div>
                <div class="innerMenuTabsDiv">
                <ul id="innerMenuTabsReporting">
                     <li>
                       <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '12a_tpl.php')));
                        $abLink->link = 'GENERAL';
                        echo $abLink->show();
                       ?>

                     </li>
                     <li class="onState"><a href="#">ADAPTATIONS BY MULTIPLE CRITERIA</a></li>

                     <li>
                       <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '12c_tpl.php')));
                        $abLink->link = 'CHARTS';
                        echo $abLink->show();
                       ?>                            
                     </li>
                </ul>
                </div>
                                    <?php                                    
                                   $displayReport = $this->objdisplayreportgenerator->displayForm();
                                   echo $displayReport;
                                    ?>
            </div>
                <!-- Right column DIv -->
            <div class="rightColumnDiv">

                                    <div class="featuredHeader" ><?php   echo $this->objLanguage->languageText('mod_unesco_oer_featured', 'unesco_oer') ?></div>
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
                                    <div class="featuredHeader"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_browse_map', 'unesco_oer') ?></div>
                                </div>
                                <div class="rightColumnBorderedDiv">
                                    <div class="rightColumnContentPadding">

                                        <!DOCTYPE html>

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
                                $coords = $this->objDbGroups->getAllgroups();

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


                                                }

                                            </script>
                                        </head>

                                            <div id="map_canvas" style="width:100%; height:20%"></div>
<?php
                                                $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', "page" => '2a_tpl.php',  'MapEntries' => $MapEntries)));

                                                echo $form->show();






?>
                                    </div>
                                </div>
        </div>
<!--        </div>-->
                
<!--        </div>-->