<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">


<html>

        
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
                     <li>
                       <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '12a_tpl.php')));
                        $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reporting_general', 'unesco_oer');
                        echo $abLink->show();
                       ?>
                     </li>

                     <li>
                      <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '12b_tpl.php')));
                        $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reporting_multiple_criteria', 'unesco_oer');
                        echo $abLink->show();
                       ?>

                     </li>

                     <li class="onState"><a href="#"><?php echo $this->objLanguage->languageText('mod_unesco_oer_reporting_charts', 'unesco_oer')?></a></li>
                </ul>
                </div>
                <div class="tenPixelPaddingLeft">
                <div class="topReportingDiv">
                	<div class="paddingContentTopLeftRightBottom">
                            <div id="noflash"></div>

            <script type="text/javascript" src="packages/unesco_oer/resources/js/json/json2.js"></script>
            <script type="text/javascript" src="packages/unesco_oer/resources/js/swfobject.js"></script>
            <?php
            
                $resource = $this->getResourceUri('open-flash-chart.swf');
                $expressInst = $this->getResourceUri('expressInstall.swf');
              
            ?>
            <script type="text/javascript">
                
            function outputStatus(e) {
		if (!e.success){
                    var noflashdiv = document.getElementById('noflash');
                    noflashdiv.innerHTML = '<a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a>';
                }  
		}

            
            
            
            swfobject.embedSWF(
                "<?php echo $resource ?>", "pie_chart",
                "300", "300", "9.0.0", "<?php echo $expressInst ?>",
                {"get-data":"get_pie"},outputStatus );

            swfobject.embedSWF(
                "<?php echo $resource ?>", "bar_chart",
                "300", "300", "9.0.0", "<?php echo $expressInst ?>",
                {"get-data":"get_bar"} );

            swfobject.embedSWF(
                "<?php echo $resource ?>", "bar1_chart",
                "300", "300", "9.0.0", "<?php echo $expressInst ?>",
                {"get-data":"get_bar1"} );

            swfobject.embedSWF(
                "<?php echo $resource ?>", "bar2_chart",
                "300", "300", "9.0.0", "<?php echo $expressInst ?>",
                {"get-data":"get_bar2"} );

            swfobject.embedSWF(
                "<?php echo $resource ?>", "bar3_chart",
                "300", "300", "9.0.0", "<?php echo $expressInst ?>",
                {"get-data":"get_bar3"} );

             swfobject.embedSWF(
                "<?php echo $resource ?>", "bar4_chart",
                "300", "300", "9.0.0", "<?php echo $expressInst ?>",
                {"get-data":"get_bar4"} );

             swfobject.embedSWF(
                "<?php echo $resource ?>", "bar5_chart",
                "300", "300", "9.0.0", "<?php echo $expressInst ?>",
                {"get-data":"get_bar5"} );
                
             swfobject.embedSWF(
                "<?php echo $resource ?>", "line_chart",
                "300", "300", "9.0.0", "<?php echo $expressInst ?>",
                {"get-data":"get_line"} );

            function get_pie()
                {
                   <?php
                    $ProdOriginals = intval($this->objDbreporting->getProdOriginal());
                    $ProdAdaptations = intval($this->objDbreporting->getProdAdapted());
                    $chart = $this->objchartgenerator->drawPieChart($ProdOriginals, $ProdAdaptations);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            function get_bar()
                {
                   <?php
                    $data = $this->objDbreporting->getLanguageBreakdownAdaptations();
                    $title = $this->objLanguage->languageText('mod_unesco_oer_lang_adapt', 'unesco_oer');
                    $filter = "language";
                    $chart = $this->objchartgenerator->drawVerticleBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            function get_bar1()
                {
                   <?php
                    $data = $this->objDbreporting->getLanguageBreakdownOriginals();
                    $title = $this->objLanguage->languageText('mod_unesco_oer_lang_breakdown', 'unesco_oer');
                    $filter = "language";
                    $chart = $this->objchartgenerator->drawVerticleBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            function get_bar2()
                {
                   <?php
                    $data = $this->objDbreporting->getBreakdownTypeAdaptation();
                    $title = $this->objLanguage->languageText('mod_unesco_oer_type_adapt1', 'unesco_oer');
                    $filter = "description";
                    $chart = $this->objchartgenerator->drawVerticleBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            function get_bar3()
                {
                   <?php
                    $data = $this->objDbreporting->getBreakdownTypeOriginal();
                    $title = $this->objLanguage->languageText('mod_unesco_oer_type_ori', 'unesco_oer');
                    $filter = "description";
                    $chart = $this->objchartgenerator->drawVerticleBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            function get_bar4()
                {
                   <?php
                    $data = $this->objDbreporting->getBreakdownCountryAdaptations();
                    $title = $this->objLanguage->languageText('mod_unesco_oer_type_adapt4', 'unesco_oer');
                    $filter = "country";
                    $chart = $this->objchartgenerator->drawHorizontalBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }
             function get_bar5()
                {
                   <?php
                    $data = $this->objDbreporting->getRegionBreakdownAdaptation();
                    $title = $this->objLanguage->languageText('mod_unesco_oer_type_adapt3', 'unesco_oer');
                    $filter = "region";
                    $chart = $this->objchartgenerator->drawHorizontalBarChart1($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }
             
             function get_line()
                {
                   <?php
                    $data2 = $this->objDbreporting->getEvolutionByAdaptation();
                    $data1 = $this->objDbreporting->getEvolutionByOriginal();
                    $title = $this->objLanguage->languageText('mod_unesco_ori_adapt1', 'unesco_oer');

                    $chart = $this->objchartgenerator->drawLineChart($title,$data1, $data2);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            </script>

               <div id="pie_chart"></div>
               <div id="line_chart"></div>               
               <div id="bar_chart"></div>
               <div id="bar1_chart"></div>
               <div id="bar2_chart"></div>
               <div id="bar3_chart"></div>
               <div id="bar5_chart"></div>
               <div id="bar4_chart"></div>







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
                                
                                <div id="browseByMap">


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
                                        <body onload="initialize()">
                                            <div id="map_canvas" style="width:100%; height:20%"></div>
<?php
                                                $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', "page" => '2a_tpl.php',  'MapEntries' => $MapEntries)));

                                                echo $form->show();
                                                
                                                
                                                
                                                
                                                
                                     
                                                
                                                
                                                
                                                
?>    </div>
        </div>
                                    
        </div>
                                </div>
        <!-- Footer-->


</body>
</html>