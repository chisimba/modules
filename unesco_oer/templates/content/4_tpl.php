<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');

if ($adaptationstring == null)
    $adaptationstring = "relation is not null";
$institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
$institutionGUI->getInstitution($institutionId);
?>

<div class="mainContentHolder">
    <div class="subNavigation"></div>
    
    
     <div class="leftColumnDiv">

        <?php
        $filtering = $this->getobject('filterdisplay', 'unesco_oer');
        echo $filtering->SideFilter('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
        ?>

        <br/><br/>
        <div class="blueBackground rightAlign">
            <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
            <a href="#" class="resetLink"> 
                <?php
                $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));

                $button->onclick = "javascript:ajaxFunction23('$adaptationstring');ajaxFunction($i)";
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
    
    <!-- Left Wide column DIv -->
    <div class="LeftWideColumnDiv">
        <div class="breadCrumb">
            <a href="#" class="orangeListingHeading">Product adaptation</a> |
            <a class="greyTextTwelveSize noUnderline"><?php echo $institutionGUI->showInstitutionName() ?></a>
        </div>
        <div class="adaptationsBackgroundColor">
            <div class="innerLeftContent">
                <div class="tenPixelLeftPadding twentyPixelRightPadding">
                    <h2 class="adaptationListingLink"><?php echo $institutionGUI->showInstitutionName() ?>
                    </h2>
                    <a>
                        <?php
                        echo $institutionGUI->showEditInstitutionLinkThumbnail($institutionId);
                        ?>
                    </a>
                    <a>
                        <?php
                        echo $institutionGUI->showEditInstitutionLink($institutionId);
                        ?>
                    </a>
                    <a>
                        <?php
                        echo ' | ';
                        echo $institutionGUI->showNewInstitutionLinkThumbnail();
                        ?>
                    </a>
                    <a>
                        <?php
                        echo $institutionGUI->showNewInstitutionLink();
                        ?>
                    </a>
                    <br>
                    <br />
                    <div class="leftImageHolder rightTwent">
                        <img src="<?php
                        echo $institutionGUI->showInstitutionThumbnail();
                        ?>" width="121" height="156"><br />
                    </div>
                    <div class="institutionFullDescription">
                        <?php
                        echo $institutionGUI->showInstitutionDescription($institutionId);
                        ?>
                    </div>

                    <br><br>
                    <div class="adaptationInnerPageHeding"><h3 class="pinkText">Adaptations</h3></div>
                    <br>

                    
                    </div>
                </div>

                <div class="innerRightColumn4">

                </div>
            </div>
        </div>
        <div class="innerRightContent">
            <div class="rightColumn4RightPadding">
                <div class="printEmailDownloadIcons">
                    <a href="#"><img src="skins/unesco_oer/images/icon-content-top-print.png" width="19" height="15"></a>
                    <a href="#"><img src="skins/unesco_oer/images/icon-content-top-email.png" width="19" height="15"></a>
                </div>
                <br><br>
                <span class="greyText fontBold">Type of institution:</span> <a class="greyText fontBold"> <?php echo $institutionGUI->showInstitutionType(); ?></a>
                <br><br>
                <span class="greyText fontBold">Country:</span> <a class="greyText fontBold"> <?php echo $institutionGUI->showInstitutionCountry(); ?> </a>
                <br><br>
                <span class="greyText fontBold">Address:</span><?php
                        $address = $institutionGUI->showInstitutionAddress();
                        echo $address['address1'];

                        if (!empty($address['address2'])) {
                            echo ', ' . $address['address2'];
                        }

                        if (!empty($address['address3'])) {
                            echo ', ' . $address['address3'];
                        }
                    ?>
                        <br><br>
                        <span class="greyText fontBold">Institution website:</span> <a href="#" class="greyTextLink"><?php echo $institutionGUI->showInstitutionWebsiteLink(); ?></a>
                        <br><br>
                        <span class="greyText fontBold">Keywords:</span> <a>
                <?php
                        $keywords = $institutionGUI->showInstitutionKeywords();
                        echo $keywords['keyword1'];

                        if (!empty($keywords['keyword2'])) {
                            echo '</a> <a>| ' . $keywords['keyword2'];
                        } ?>
                    </a>
                    <br><br>

                </div>
            </div>

        </div>
        </div>
        <!-- Right column DIv -->
        <div class="rightColumnDiv">
            <div class="rightColumnDiv">
                <div class="featuredHeader pinkText">FEATURED ADAPTATION</div>
                <div class="rightColumnBorderedDiv">
                    <div class="rightColumnContentPadding">
                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
                        <div class="featuredAdaptationRightContentDiv">
                            <span class="greyListingHeading">Manual for Investigative Journalists</span>
                            <br><br>
                            <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">See all adaptations (15)</a></div>


                        </div>
                        <div class="featuredAdaptedBy">Adapted By</div>
                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                        <span class="greyListingHeading">Polytechnic of Namibia</span>
                    </div>
                </div>
                <div class="spaceBetweenRightBorderedDivs">
                    <div class="featuredHeader pinkText">BROWSE ADAPTATIONS BY MAP</div>
                </div>
                <div class="rightColumnBorderedDiv">
                    <div class="rightColumnContentPadding">


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
                            $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2b_tpl.php', "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));

                            echo $form->show();
                        ?>
                    </body>
                </html>

            </div>
        </div>

    </div>
</div>
</div>