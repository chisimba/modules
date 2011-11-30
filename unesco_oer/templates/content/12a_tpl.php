
        
        	<div class="subNavigation"></div>
        	<!-- Left Wide column DIv -->
            <div class="groupWideLeftColumn">

            	<div class="tenPixelPaddingLeft">
                <div class="topReportingDiv">
                	<div class="paddingContentTopLeftRightBottom">
                     <div class="memberList">
                     
                      <div class="textNextToGroupIcon">
                      	<h2 class="greyText"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_reporting_tool', 'unesco_oer') ?></h2>
                       	
                       </div>

                      </div>
                    </div>
                </div>
                </div>
                <div class="innerMenuTabsDiv">
                <ul id="innerMenuTabsReporting">
                     <li class="onState"><a href="#"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_reporting_tool', 'unesco_oer') ?></a></li>
                     <li>
                      <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '12b_tpl.php')));
                        $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reporting_multiple_criteria', 'unesco_oer');
                        echo $abLink->show();
                       ?>
                     </li>

                     <li>
                       <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '12c_tpl.php')));
                        $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reporting_charts', 'unesco_oer');
                        echo $abLink->show();
                       ?>

                     </li>
                </ul>
                </div>
                <div class="tenPixelPaddingLeft">
                <div class="topReportingDiv">
                	<div class="paddingContentTopLeftRightBottom">

                            <div class="legendContent">
                                <div class="leftLegendContentHolder">

                                    <div class="generalResourcesTableHeading"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_general', 'unesco_oer') ?></div>
                                    <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_property', 'unesco_oer') ?></th>
                                            <th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_value', 'unesco_oer') ?></th>
                                        </tr>
                                        <tr>

                                        	<td><?php   echo $this->objLanguage->languageText('mod_unesco_oer_unesco_ori', 'unesco_oer') ?></td>
                                            <td>
                                                <?php

                                                $temp =$this->objDbreporting->getProdOriginal();
                                                echo$temp;

                                                ?>                                             
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td><?php   echo $this->objLanguage->languageText('mod_unesco_oer_unesco_adapt', 'unesco_oer') ?></td>
                                            <td>
                                                <?php

                                                $temp =$this->objDbreporting->getProdAdapted();
                                                echo$temp;

                                                ?>

                                            </td>
                                        </tr>

                                        <tr>
                                        	<td><?php   echo $this->objLanguage->languageText('mod_unesco_oer_unesco_ori1', 'unesco_oer') ?></td>
                                            <td>
                                                <?php

                                                $temp = $this->objDbreporting->getNoLanguagesOriginals();
                                                echo$temp;
                                                
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td><?php   echo $this->objLanguage->languageText('mod_unesco_oer_unesco_adapt1', 'unesco_oer') ?></td>
                                            <td>
                                                <?php
                                                
                                                $temp = $this->objDbreporting->getNoLanguagesAdaptations();
                                                echo$temp;                                                
                                                
                                                ?>
                                            </td>

                                        </tr>
                                    </table>
                                </div>
                                <div class="rightLegendContentHolder">

                                </div>
                            </div>


                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                    <div class="generalResourcesTableHeading"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_lang_breakdown', 'unesco_oer') ?></div>

                                    <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer') ?></th>
                                            <th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_total', 'unesco_oer') ?></th>
                                        </tr>
                                        <td>
                                            <?php

                                                $temp = $this->objDbreporting->getLanguageBreakdownOriginals();
                                                $ArrayCount = sizeof($temp);
                                                
                                                echo "English".'<br>';
                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["language"].'<br>';
                                                    }                                               

                                            ?>

                                        </td>
                                        <td>
                                            <?php

                                                $temp = $this->objDbreporting->getLanguageBreakdownOriginals();
                                                $temp1 = $this->objDbreporting->getEnglishLangCountOriginals();
                                                $ArrayCount = sizeof($temp);
                                                
                                                echo $temp1[0]["count"].'<br>';     
                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["count"].'<br>';
                                                    }
                                            ?>
                                        </td>
                                    </table>
                                </div>
                                <div class="rightLegendContentHolder">
                                	<div class="generalResourcesTableHeading"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_type_ori', 'unesco_oer') ?></div>
                                   <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_type_ori', 'unesco_oer') ?></th>
                                            <th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_total', 'unesco_oer') ?></th>

                                        </tr>
                                        <td>
                                            <?php
                                                $temp = $this->objDbreporting->getBreakdownTypeOriginal();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["description"].'<br>';
                                                    }

                                             ?>
                                        </td>
                                        <td>
                                            <?php
                                                $temp = $this->objDbreporting->getBreakdownTypeOriginal();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["count"].'<br>';
                                                    }

                                             ?>
                                        </td>
                                    </table>

                                </div>
                            </div>



                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                    <div class="generalResourcesTableHeading"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_lang_adapt', 'unesco_oer') ?></div>
                                    <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer') ?></th>

                                            <th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_total', 'unesco_oer') ?></th>
                                        </tr>
                                    <td>
                                            <?php

                                                $temp = $this->objDbreporting->getLanguageBreakdownAdaptations();
                                                $ArrayCount = sizeof($temp);

                                                echo "English".'<br>';    
                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["language"].'<br>';
                                                    }

                                            ?>
                                    </td>
                                    <td>
                                            <?php
                                                $temp = $this->objDbreporting->getLanguageBreakdownAdaptations();
                                                $temp1 = $this->objDbreporting->getEnglishLangCountAdaptations();
                                                $ArrayCount = sizeof($temp);
                                                
                                                echo $temp1[0]["count"].'<br>'; 

                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["count"].'<br>';
                                                    }

                                             ?>                                   

                                    </td>

                                    </table>
                       
                                </div>
                                <div class="rightLegendContentHolder">
                                	<div class="generalResourcesTableHeading"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_type_adapt1', 'unesco_oer') ?></div>
                                   <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_type_adapt', 'unesco_oer') ?></th>
                                            <th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_total', 'unesco_oer') ?></th>

                                        </tr>
                                        <td>
                                            <?php
                                                $temp = $this->objDbreporting->getBreakdownTypeAdaptation();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["description"].'<br>';
                                                    }

                                             ?>
                                        </td>
                                        <td>
                                            <?php
                                                $temp = $this->objDbreporting->getBreakdownTypeAdaptation();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["count"].'<br>';
                                                    }

                                             ?>
                                        </td>

                                    </table>

                                </div>

                            </div>

                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                    <div class="generalResourcesTableHeading"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_type_adapt2', 'unesco_oer') ?></div>
                                    <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_type_adapt', 'unesco_oer') ?></th>

                                            <th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_total', 'unesco_oer') ?></th>
                                        </tr>
                                    <td>
                                            <?php
                                                $temp = $this->objDbreporting->getInstitutionTypeBreakdownAdaptation();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                       $type =  $temp[$i]["type"];
                                                       echo $type.'<br>';
                                                    }
    

                                            ?>
                                    </td>
                                    <td>
                                            <?php
                                                $temp = $this->objDbreporting->getInstitutionTypeBreakdownAdaptation();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                       $count =  $temp[$i]["count"];
                                                       echo $count.'<br>';
                                                    }

                                             ?>

                                    </td>

                                    </table>

                                </div>
                                <div class="rightLegendContentHolder">
                                	<div class="generalResourcesTableHeading"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_type_adapt3', 'unesco_oer') ?></div>
                                   <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_type_adapt', 'unesco_oer') ?></th>
                                            <th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_total', 'unesco_oer') ?></th>

                                        </tr>
                                        <td>
                                            <?php
                                                $temp = $this->objDbreporting->getRegionBreakdownAdaptation();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                       $region =  $temp[$i]["region"];
                                                       echo $region.'<br>';
                                                    }
                                             ?>
                                        </td>
                                        <td>
                                            <?php
                                                $temp = $this->objDbreporting->getRegionBreakdownAdaptation();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                       $region =  $temp[$i]["count"];
                                                       echo $region.'<br>';
                                                    }
                                             ?>
                                        </td>

                                    </table>

                                </div>

                            </div>

                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                    &nbsp;
                                </div>
                                <div class="rightLegendContentHolder">
                                	<div class="generalResourcesTableHeading"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_type_adapt4', 'unesco_oer') ?></div>

                                   <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_county_adapt', 'unesco_oer') ?></th>
                                            <th><?php   echo $this->objLanguage->languageText('mod_unesco_oer_total', 'unesco_oer') ?></th>
                                        </tr>

                                        <td>
                                            <?php
                                                $temp = $this->objDbreporting->getBreakdownCountryAdaptations();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                       $countryCode =  $temp[$i]["country"];
                                                       $countryName = $this->objDbreporting->getCountryName($countryCode);
                                                       echo $countryName.'<br>';
                                                    }
                                             ?>
                                        </td>
                                        <td>
                                            <?php
                                                $temp = $this->objDbreporting->getBreakdownCountryAdaptations();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["count"].'<br>';
                                                    }

                                             ?>

                                        </td>

                                    </table>
                                </div>
                            </div>


                    </div>
                </div>

                <br><br><br>
                </div>

            </div>
            <!-- Right column DIv -->
            <div class="rightColumnDiv">
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
                                
                                
<!--                                    <div id="browseByMap">-->



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
                                                     <script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
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
                         
                                         <div id="map_canvas" style="width:178; height:130"></div>
<?php
                                                $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', "page" => '2a_tpl.php',  'MapEntries' => $MapEntries)));

                                                echo $form->show();
                                                
                                           
?>
                                            </div>
                                    </div>
                                
                                    </div>
                                </div>
                                        
        <!-- Footer-->

      
        </div>
</body>
</html>