<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
?>
<?php $this->setLayoutTemplate('maincontent_layout_tpl.php'); ?>

        	<div class="subNavigation"></div>
        	<!-- Left Colum -->
        	<div class="leftColumnDiv">

            	<div class="moduleHeader greenText">GROUPS TOOLS</div>
                <div class="moduleHeader darkBlueText">
<!--                <img src="images/icon-group-new-sub-group.png" alt="Group" width="18" height="18" class="smallLisitngIcons">-->
                <div class="linkTextNextToCreateGroupIcons"><a href="#" class="greenTextBoldLink">

                            <?php
                            $link = new link($this->uri(array("action" => 'groupRegistationForm')));
                            $link->link = '<img src="skins/unesco_oer/images/icon-group-new-sub-group.png" alt="Group" width="18" height="18" class="smallLisitngIcons">
                            Create Group';
                            echo '&nbsp;' . $link->show();
                            ?>


                        </a></div>
                </div>
                <br><br>

<?php
echo $this->objGroupUtil->groupPerPage();
 //$this->objGroupUtil->populateListView();
?>


<!--                <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages">Groups per page</div>


                <div class="blueBackground">
                	<select name="items_per_page" id="items_per_page" class="leftColumnSelectDropdown">
                    	<option value=""> 15</option>
                    </select>
                </div>-->

            </div>
        	<!-- Center column DIv -->
            <div class="centerColumnDiv">


            	<div class="GridListViewDiv">
                	<div class="sortBy">
                    Sort By:
                    <select name="" class="contentDropDown">
                    	<option value="">Date Added</option>
                    </select>
                    <select name="" class="contentDropDown">
                    	<option value="">DESC</option>

                    </select>
                    </div>
                    <div class="viewGrid">
                    	<div class="viewAsDiv">View as: </div>


                               <div class="gridListDivView">

                        <?php
                            $abLink = new link($this->uri(array("action" => 'groupGrid',"page"=>'10a_tpl.php')));
                            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
                            echo $abLink->show();
                        ?>

<?php
                            $abLink = new link($this->uri(array("action" => 'groupGrid',"page"=>'10a_tpl.php')));
                            $abLink->link = 'GRID';
                            echo $abLink->show();
?>
                                </div>

                                <div class="gridListPipe">|</div>

                            <?php
                            $abLink = new link($this->uri(array("action" =>'groupList',"page"=>'10a_tpl.php')));
                            $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                            echo $abLink->show();
                            ?>

                            <div class="gridListDivView">

                                <?php
                                $abLink =  new link($this->uri(array("action" =>'groupList',"page"=>'10a_tpl.php')));
                                $abLink->link = 'LIST';
                                echo $abLink->show();
                                ?>

                            </div>
                    </div>

                </div>


                   //<?php
//    $this->loadClass('htmlheading', 'htmlelements');
//    $this->loadClass('link', 'htmlelements');
//    $objIcon = $this->newObject('geticon', 'htmlelements');
//    $this->objLanguagecode=$this->getObject('languagecode', 'language');
//    $objTable = $this->getObject('htmltable', 'htmlelements');
////    $objTable->width = '90%';
////     $objTable->border = '0';
////    $objTable->cellspacing = '0';
////     $objTable->cellpadding = '0';
//
//    echo '<div id="institutionheading">';
//            '<br><br />';
//
//    echo '</div>';
//
//
//
//    $Institution = $this->objDbGroups->getAllGroups();
//    if (count($Institution) > 0) {
//        $newRow = true;
//        $count = 0;
//
//        foreach ($Institution as $Institutions) {
//            $count=$count+1;
//            $institutionLink = new link($this->uri(array("action" => '11a','id' =>$Institutions['id'])));
//            $institutionLink->cssClass = 'darkGreyColour';
//            $institutionLink->link = '<img align="top"  width="25" height="25" src="' .$Institutions['thumbnail']. '" />';
//            $content.='
//            <div id="institutions"> ' . $institutionLink->show(). '
//          ' .$Institutions['name'] . ' |' .$Institutions['email'] . '          <a class="greyListingHeading">' . $Institutions['website'] . '</a> |
//          <a class="greyListingHeading">' . $this->objLanguagecode->getName($Institutions['country']). '</a> |
//          <a class="greyListingHeading">' . $Institutions['city'] . '</a>
//
//<br/>
//           </div> ';
//            if ($newRow) {
//                $objTable->startRow();
//                $objTable->addCell($content);
//                 $newRow = false;
//                   } else {
//                        $objTable->addCell($content);
//                    }
//                    if ($count ==2 ) {
//                       $newRow = true;
//                        $objTable->endRow();
//                        $count = 0;
//                    }
//
//        }
//    }
//
//
//   $fieldset1 = $this->newObject('fieldset', 'htmlelements');
//    $institutionsFsLegend = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
//    $fieldset1->setLegend();
//    $fieldset1->addContent($objTable->show());
//    echo $fieldset1->show();
//
//    $aabLink =  new link($this->uri(array("action" =>'groupList',"page"=>'10a_tpl.php')));
//    $aabLink->link = 'LIST';
//    echo $aabLink->show();
//


    ?>




                       <div class="gridViewGroupBackgroundColor">
                    <div class="paddingGroubGridListingTable">
                        <table class="groupListingTable" cellspacing="0" cellpadding="0">
                            <td>







                <?php
                $objTable = $this->getObject('htmltable', 'htmlelements');
                $objTable->cssClass = "gridListingTable";
                $objTable->width = NULL;

                $groups = $this->objDbGroups->getAllGroups();
                $newRow = true;
                $count = 0;
                foreach ($groups as $group) {
                    $count++;
                    if ($newRow) {
                        $objTable->startRow();
                        $objTable->addCell($this->objGroupUtil->content($group));
                        $newRow = false;
                   } else {
                        $objTable->addCell($this->objGroupUtil->content($group));
                    }
                    if ($count == 3) {
                       $newRow = true;
                        $objTable->endRow();
                        $count = 0;
                    }
                }

//                $fieldset1 = $this->newObject('fieldset', 'htmlelements');
//                $fieldset1->setLegend();
//                $fieldset1->addContent($objTable->show());
//                echo $fieldset1->show();
               echo $objTable->show();
                ?>

                                             </td>
                            </table>
                      </div>
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

                                $objProduct = $this->getObject('product');
                                $objProduct->loadProduct($featuredAdaptedProduct['id']);

                                echo $this->objFeaturedProducUtil->displayFeaturedAdaptedProduct($objProduct);
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





                                                echo $temp;




?>
                                        </body>
                                    </html>



















                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Footer-->







