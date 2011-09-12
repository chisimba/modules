
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
                     <li class="onState"><a href="#">GENERAL</a></li>
                     <li>
                      <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '12b_tpl.php')));
                        $abLink->link = 'ADAPTATIONS BY MULTIPLE CRITERIA';
                        echo $abLink->show();
                       ?>
                     </li>

                     <li>
                       <?php
                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptation, "page" => '12c_tpl.php')));
                        $abLink->link = 'CHARTS';
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

                                    <div class="generalResourcesTableHeading">General:</div>
                                    <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th>Propery</th>
                                            <th>value</th>
                                        </tr>
                                        <tr>

                                        	<td>Number of UNESCO Originals</td>
                                            <td>
                                                <?php

                                                $temp =$this->objDbreporting->getProdOriginal();
                                                echo$temp;

                                                ?>                                             
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td>Number of adaptations</td>
                                            <td>
                                                <?php

                                                $temp =$this->objDbreporting->getProdAdapted();
                                                echo$temp;

                                                ?>

                                            </td>
                                        </tr>

                                        <tr>
                                        	<td>Number of languages in UNESCO Originals</td>
                                            <td>
                                                <?php

                                                $temp = $this->objDbreporting->getNoLanguagesOriginals();
                                                echo$temp;
                                                
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td>Number of languages in adaptations</td>
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
                                    <div class="generalResourcesTableHeading">Language breakdown - originals:</div>

                                    <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th>Language</th>
                                            <th>total</th>
                                        </tr>
                                        <td>
                                            <?php

                                                $temp = $this->objDbreporting->getLanguageBreakdownOriginals();
                                                $ArrayCount = sizeof($temp);
                                                

                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["language"].'<br>';
                                                    }                                               

                                            ?>

                                        </td>
                                        <td>
                                            <?php

                                                $temp = $this->objDbreporting->getLanguageBreakdownOriginals();
                                                $ArrayCount = sizeof($temp);

                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["count"].'<br>';
                                                    }
                                            ?>
                                        </td>
                                    </table>
                                </div>
                                <div class="rightLegendContentHolder">
                                	<div class="generalResourcesTableHeading">Breakdown by type - originals:</div>
                                   <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th>Type of original</th>
                                            <th>total</th>

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
                                    <div class="generalResourcesTableHeading">Language breakdown - adaptations:</div>
                                    <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th>Language</th>

                                            <th>total</th>
                                        </tr>
                                    <td>
                                            <?php

                                                $temp = $this->objDbreporting->getLanguageBreakdownAdaptations();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["language"].'<br>';
                                                    }

                                            ?>
                                    </td>
                                    <td>
                                            <?php
                                                $temp = $this->objDbreporting->getLanguageBreakdownAdaptations();
                                                $ArrayCount = sizeof($temp);


                                                for ($i=0; $i < $ArrayCount; $i++)
                                                    {
                                                        echo $temp[$i]["count"].'<br>';
                                                    }

                                             ?>                                   

                                    </td>

                                    </table>
                       
                                </div>
                                <div class="rightLegendContentHolder">
                                	<div class="generalResourcesTableHeading">Breakdown by type - adaptations:</div>
                                   <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th>Type of adaptation</th>
                                            <th>total</th>

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
                                    <div class="generalResourcesTableHeading">Breakdown by type - institutions:</div>
                                    <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th>Type of adaptation</th>

                                            <th>total</th>
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
                                	<div class="generalResourcesTableHeading">Breakdown by region - adaptations:</div>
                                   <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th>Type of adaptation</th>
                                            <th>total</th>

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
                                	<div class="generalResourcesTableHeading">Breakdown by country - adaptations:</div>

                                   <table class="generalReportingTable" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<th>Country of adaptation</th>
                                            <th>total</th>
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
</div>
        <!-- Footer-->

      
        </div>
</body>
</html>