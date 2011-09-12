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
                "open-flash-chart.swf", "pie_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_pie"} );

            swfobject.embedSWF(
                "open-flash-chart.swf", "bar_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar"} );

            swfobject.embedSWF(
                "open-flash-chart.swf", "bar1_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar1"} );

            swfobject.embedSWF(
                "open-flash-chart.swf", "bar2_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar2"} );

            swfobject.embedSWF(
                "open-flash-chart.swf", "bar3_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar3"} );

             swfobject.embedSWF(
                "open-flash-chart.swf", "bar4_chart",
                "300", "300", "9.0.0", "expressInstall.swf",
                {"get-data":"get_bar4"} );

             swfobject.embedSWF(
                "open-flash-chart.swf", "bar5_chart",
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
            	<div class="featuredHeader pinkText">FEATURED ADAPTATION</div>

                <div class="rightColumnBorderedDiv">
                	<div class="rightColumnContentPadding">
                	  <img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
               	  <div class="featuredAdaptationRightContentDiv">
                        	<span class="greyListingHeading">Manual for Investigative Journalists</span>
                            <br><br>
                            <img src="images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">See all adaptations (15)</a></div>



                        </div>
                        <div class="featuredAdaptedBy">Adapted By</div>
                        <img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                        <span class="greyListingHeading">Polytechnic of Namibia</span>
                        </div>
                </div>
                <div class="spaceBetweenRightBorderedDivs">
                	<div class="featuredHeader pinkText">BROWSER ADAPTATION BY MAP</div>

                </div>
                <div class="rightColumnBorderedDiv">
                	<div class="rightColumnContentPadding">



                     </div>
                </div>

            </div>
        </div>
        </div>
        <!-- Footer-->


</body>
</html>