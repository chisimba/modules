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
                <div class="tenPixelPaddingLeft">
                <div class="topReportingDiv">
                	<div class="paddingContentTopLeftRightBottom">
                        <fieldset>
                            <legend>Country and region</legend>

                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                    Select country/region preset<br>
                                    <select name="theme" id="theme" class="countryRegionSelectBox">
                                    <option value="">--</option>
                                    </select>
                                </div>
                                <div class="rightLegendContentHolder">

                                    Select country<br>
                                    <select name="theme" size="1" multiple class="countrySelectBox" id="country">
                                    	<option value="">Algeria</option>
                                        <option value="">Angola</option>
                                        <option value="">Benin</option>
                                        <option value="">Botswana</option>

                                    </select>
                                	<br>
                                    Use CTRL button to select more than one country
                                    <br><br>
                                    Select region<br>
                                    <select name="theme" size="1" multiple class="countrySelectBox" id="region">
                                    	<option value="">Africa</option>
                                        <option value="">Arab States</option>

                                        <option value="">Asian and the Pacific</option>
                                        <option value="">Europe and North America</option>
                                    </select>
                                	<br>
                                    Use CTRL button to select more than one region
                                </div>
                            </div>
                        </fieldset>

                        <br>
                        <fieldset>
                            <legend>Theme/keywords</legend>
                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                	UNESCO theme:<br>
                                    <select name="theme" size="1" multiple class="countrySelectBox" id="region">
                                    	<option value="">Access to information</option>

                                        <option value="">Africa</option>
                                        <option value="">Anti-Doping</option>
                                        <option value="">Archives</option>
                                    </select>
                                	<br>
                                    Use CTRL button to select more than one country
                                </div>
                                <div class="rightLegendContentHolder">

                                	Keyword:<br>
                                    <input type="text" name="" class="keywordBox">
                                    <br><br>
                                    Search for keywords in:<br>
                                    <input type="radio" name="radio" id=""> Product<br>
                                    <input type="radio" name="radio" id=""> Sections<br>

                                    <input type="radio" name="radio" id=""> Products and sections<br>
                                </div>
                            </div>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>Adaptation type</legend>

                            <div class="legendContent">
                                <div class="legendWideContentHolder">
                                	<input type="checkbox" name=""> Model
                                    <input type="checkbox" name=""> Guide
                                    <input type="checkbox" name=""> Handbook
                                    <input type="checkbox" name=""> Manual
                                    <input type="checkbox" name=""> Best practice
                                </div>
                            </div>

                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>Adapted by institution type</legend>
                            <div class="legendContent">
                                 <div class="legendWideContentHolder">
                                	<input type="checkbox" name=""> School
                                    <input type="checkbox" name=""> NGO
                                    <input type="checkbox" name=""> IGO
                                    <input type="checkbox" name=""> Private Sector
                                </div>

                            </div>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>Languages</legend>
                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                	<select name="theme" size="1" multiple class="countrySelectBox" id="region">

                                    	<option value="">English</option>
                                        <option value="">French</option>
                                        <option value="">Spanish</option>
                                    </select>
                                    <br>
                                    Use CTRL button to select more than one language
                                </div>
                                <div class="rightLegendContentHolder">&nbsp;</div>

                            </div>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>Output</legend>
                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                	Report format<br>

                                    <input type="radio" name="radio" id=""> PDF<br>
                                    <input type="radio" name="radio" id=""> CSV<br>
                                    <input type="radio" name="radio" id=""> html<br>
                                </div>
                                <div class="rightLegendContentHolder">
                                	Number of results per page (HTML only):<br>

                                    <select name="theme" id="theme" class="countryRegionSelectBox">
                                    <option value="">15</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <div class="legendContent tenPixelTopPadding">
                            <div class="saveCancelButtonHolder">

                                <div class="buttonSubmit"><a href=""><img src="images/button-search.png" alt="Search"></a></div>
                                <div class="textNextoSubmitButton"><a href="" class="searchGoLink">RESET</a></div>
                            </div>
                            <div class="saveCancelButtonHolder">
                                <div class="buttonSubmit"><a href=""><img src="images/button-search.png" alt="Search"></a></div>
                                <div class="textNextoSubmitButton"><a href="" class="searchGoLink">GENERATE REPORT</a></div>
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
                                                
                                                
                                                
                                                
                                                
                                     
                                                
                                                
                                                
                                                
?>
            </div>
        </div>
        </div>
        <!-- Footer-->

        
</body>
</html>