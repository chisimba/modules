<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
?>
<?php $this->setLayoutTemplate('maincontent_layout_tpl.php'); ?>
            <div class="subNavigation"></div>
        	 Left column DIv 
            <div class="groupWideLeftColumn">

            	<div class="tenPixelLeftPadding tenPixelBottomPadding">
                	<a href="#" class="groupsBreadCrumbColor">Groups</a> |
                <span class="groupsBreadCrumbColor noUnderline">
                    <?php echo $this->objDbGroups->getGroupName($this->getParam('id')) . ", " .$this->objDbGroups->getGroupCountry($this->getParam('id'));?>
                    </span>
                </div>
            	<div class="tenPixelPaddingLeft">
                <div class="topGroupDiv">
                	<div class="paddingContentTopLeftRightBottom">
                     <div class="memberList">

                     <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                      <div class="textNextToGroupIcon">
                      	<h2 class="greenText">
                            <?php echo $this->objDbGroups->getGroupName($this->getParam('id'));?>
                            </h2><?php echo $this->objDbGroups->getGroupDescription($this->getParam('id'));?>
                       
                       </div>
                      </div>
                      <div class="memberList rightAlign">
                      <div class="saveCancelButtonHolder">
                            <div class="textNextoSubmitButton"><a href="#" class="greenTextBoldLink">Link to institution</a></div>

                        </div>
                        <div class="saveCancelButtonHolder">
                            <div class="buttonSubmit"><a href=""><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18"></a></div>
                            <div class="textNextoSubmitButton"><a href="#" class="greenTextBoldLink">Join Group</a>
                            <span class="greenText">|&nbsp;</span></div>
                        </div>
                      </div>
                    </div>

                </div>
                </div>
                <div class="innerMenuTabsDiv">
                <ul id="innerMenuTabs">
                     <li class="onState"><a href="#">
                             <?php
                             $memberLink=new link($this->uri(array("action" =>'groupMembersForm','id'=>$this->getParam('id'),"page"=>'10a_tpl.php')));
                             $No_Of_Members=$this->ObjDbUserGroups->groupMembers($this->getParam('id'));
                             $memberLink->link="Members(".$No_Of_Members.")";
                             echo $memberLink->show();
                             ?>

                            </a></li>
                     <li><a href="#">
                             <?php
                             $groupadaptationLink=new link($this->uri(array("action" =>'11c','id'=>$this->getParam('id'),"page"=>'10a_tpl.php')));
                             $No_Of_adaptation=count($this->objDbGroups->getGroupProductadaptation($this->getParam('id')));
                             $groupadaptationLink->link=" ADAPTATIONS(".$No_Of_adaptation.")";
                             echo $groupadaptationLink->show();
                             ?></a></li>
                     <li><a href="#">DISCUSSIONS (1)</a></li>
                     <li><a href="#">INSTITUTIONS (1)</a></li>

                </ul>
                </div>
                <div class="tenPixelPaddingLeft">
                <div class="topGroupDiv">
                            <?php
                            $this->objGroupUtil->groupMembers($this->getParam('id'));

                            ?>

<!--                	<div class="paddingContentTopLeftRightBottom">
                        <div class="memberList">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                            <div class="memberIconText">Ignor Inuk (<span class="greenText fontBold">Group Administrator</span>)</div>

                        </div>

                        <div class="memberList">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                            <div class="memberIconText">Lemi Cook</div>
                        </div>

                        <div class="memberList">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                            <div class="memberIconText">John Smith</div>

                        </div>

                        <div class="memberList">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                            <div class="memberIconText">Abel Cicina</div>
                        </div>

                        <div class="memberList">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                            <div class="memberIconText">Davide Stroni</div>

                        </div>
                    </div>-->
                </div>
                <br><br><br>
                </div>

            </div>

             Right column DIv 
            <div class="rightColumnDiv">
            	<div class="rightColumnDiv">

            	<div class="featuredHeader pinkText">FEATURED ADAPTATION</div>
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
   





