<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">


<html>

        <div class="mainContentHolder">
        	<div class="subNavigation"></div>
        	<!-- Left Wide column DIv -->
            <div class="groupWideLeftColumn">

            	<div class="tenPixelPaddingLeft">
                <div class="topReportingDiv">
                	<div class="paddingContentTopLeftRightBottom">
                     <div class="memberList">
                     <img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                      <div class="textNextToGroupIcon">
                      	<h2 class="greyText">Reporting Tool</h2>
                       	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet elit vitae neque consequat congue sed ac nunc. Phasellus mattis rhoncus commodo. Fusce non metus ut nunc dapibus cursus et sit amet diam. Nunc non nibh sit amet leo bibendum sagittis. Vestibulum posuere tincidunt tincidunt. Aenean euismod vulputate volutpat.
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

                     <li>
                      <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '12b_tpl.php')));
                        $abLink->link = 'ADAPTATIONS BY MULTIPLE CRITERIA';
                        echo $abLink->show();
                       ?>

                     </li>

                     <li class="onState"><a href="#">CHARTS</a></li>
                </ul>
                </div>
                <div class="tenPixelPaddingLeft">
                <div class="topReportingDiv">
                	<div class="paddingContentTopLeftRightBottom">
                                   
            <script type="text/javascript" src="packages/unesco_oer/resources/js/json/json2.js"></script>
            <script type="text/javascript" src="packages/unesco_oer/resources/js/swfobject.js"></script>
            <script type="text/javascript">

            swfobject.embedSWF(
                "packages/unesco_oer/resources/open-flash-chart.swf", "pie_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_pie"} );

            swfobject.embedSWF(
                "packages/unesco_oer/resources/open-flash-chart.swf", "bar_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar"} );

            swfobject.embedSWF(
                "packages/unesco_oer/resources/open-flash-chart.swf", "bar1_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar1"} );

            swfobject.embedSWF(
                "packages/unesco_oer/resources/open-flash-chart.swf", "bar2_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar2"} );

            swfobject.embedSWF(
                "packages/unesco_oer/resources/open-flash-chart.swf", "bar3_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar3"} );

             swfobject.embedSWF(
                "packages/unesco_oer/resources/open-flash-chart.swf", "bar4_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar4"} );

             swfobject.embedSWF(
                "packages/unesco_oer/resources/open-flash-chart.swf", "bar5_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar5"} );

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
                    $title = "Language Breakdown By Adaptations";
                    $filter = "language";
                    $chart = $this->objchartgenerator->drawVerticleBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            function get_bar1()
                {
                   <?php
                    $data = $this->objDbreporting->getLanguageBreakdownOriginals();
                    $title = "Language Breakdown By Originals";
                    $filter = "language";
                    $chart = $this->objchartgenerator->drawVerticleBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            function get_bar2()
                {
                   <?php
                    $data = $this->objDbreporting->getBreakdownTypeAdaptation();
                    $title = "Breakdown By Type - Adaptations";
                    $filter = "description";
                    $chart = $this->objchartgenerator->drawVerticleBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            function get_bar3()
                {
                   <?php
                    $data = $this->objDbreporting->getBreakdownTypeOriginal();
                    $title = "Breakdown By Type - Originals";
                    $filter = "description";
                    $chart = $this->objchartgenerator->drawVerticleBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            function get_bar4()
                {
                   <?php
                    $data = $this->objDbreporting->getBreakdownCountryAdaptations();
                    $title = "Breakdown By Country - Adaptations";
                    $filter = "country";
                    $chart = $this->objchartgenerator->drawHorizontalBarChart($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }
             function get_bar5()
                {
                   <?php
                    $data = $this->objDbreporting->getRegionBreakdownAdaptation();
                    $title = "Breakdown By Region - Adaptations";
                    $filter = "region";
                    $chart = $this->objchartgenerator->drawHorizontalBarChart1($title, $data,$filter);
                    echo "return JSON.stringify($chart);";
                    ?>
                }

            </script>

               <div id="pie_chart"></div>
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
                                </div>
                                <div class="rightColumnBorderedDiv">
                                    <div class="rightColumnContentPadding">

                            <?php
                            ?>

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
                                                $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', "page" => '2a_tpl.php',  'MapEntries' => $MapEntries)));

                                                echo $form->show();
                                                
                                                
                                                
                                                
                                                
                                     
                                                
                                                
                                                
                                                
?>    </div>
        </div>
        </div>
        <!-- Footer-->


</body>
</html>