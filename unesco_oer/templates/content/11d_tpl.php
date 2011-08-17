<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
 $this->objLanguagecode=$this->getObject('languagecode', 'language');
?>

<?php $this->setLayoutTemplate('maincontent_layout_tpl.php'); ?>

  
            <div class="subNavigation"></div>
        	<!-- Left column DIv -->
            <div class="groupWideLeftColumn">

            	<div class="tenPixelLeftPadding tenPixelBottomPadding">
                	<a href="#" class="groupsBreadCrumbColor">                                                         <?php
                             $GroupLink=new link($this->uri(array("action" =>'10',"page"=>'10a_tpl.php')));
                             $GroupLink->link="Groups";
                             echo $GroupLink->show();
                             ?></a> |
                <span class="groupsBreadCrumbColor noUnderline">  <?php echo $this->objDbGroups->getGroupName($this->getParam('id')) . ", " .$this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id')));?></span>
                </div>
            	<div class="tenPixelPaddingLeft">
                <div class="topGroupDiv">
                	<div class="paddingContentTopLeftRightBottom">
                     <div class="memberList">
                        <?php echo $this->objGroupUtil->topcontent($this->getParam('id'))?>

<!--                     <img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid" height="49" width="45">
                      <div class="textNextToGroupIcon">
                      	<h2 class="greenText">Polytechnic of Namibia, journalism department</h2>
                       	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet elit vitae neque consequat congue sed ac nunc. Phasellus mattis rhoncus commodo. Fusce non metus ut nunc dapibus cursus et sit amet diam. Nunc non nibh sit amet leo bibendum sagittis. Vestibulum posuere tincidunt tincidunt. Aenean euismod vulputate volutpat.
                       </div>-->
                      </div>
                      <div class="memberList rightAlign">
                      <div class="saveCancelButtonHolder">
                            <div class="textNextoSubmitButton"><a href="#" class="greenTextBoldLink">Link to institution</a></div>

                        </div>
                        <div class="saveCancelButtonHolder">
                            <div class="buttonSubmit"><a href=""><img src="images/icon-join-group.png" alt="Join Group" height="18" width="18"></a></div>
                            <div class="textNextoSubmitButton"><a href="#" class="greenTextBoldLink">Join Group</a>
                            <span class="greenText">|&nbsp;</span></div>
                        </div>
                      </div>


                    </div>

                </div>
                </div>
                <div class="innerMenuTabsDiv">
                <ul id="innerMenuTabs">
                     <li><a href="#">
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
                             ?>
                         </a></li>
                     <li><a href="#">
                                                                            <?php
                             $discussionLink=new link($this->uri(array("action" =>'11b','id'=>$this->getParam('id'),"page"=>'10a_tpl.php')));
                             $No_Of_discussion=count($this->objDbGroups->getGroupProductadaptation($this->getParam('id')));
                             $discussionLink->link=" DISCUSSIONS(1)";// DISCUSSIONS(".$No_Of_discussion.")";
                             echo $discussionLink->show();
                             ?></a></li>
                     <li class="onState"><a href="#">
                               <?php
                             $InstitutionLink=new link($this->uri(array("action" =>'11d','id'=>$this->getParam('id'),"page"=>'10a_tpl.php')));
                             $No_Of_instutions=$this->objDbGroups->getNoOfInstitutions($this->getParam('id'));
                             $InstitutionLink->link="INSTITUTIONS(".$No_Of_instutions.")";
                             echo $InstitutionLink->show();
                             ?>
                         </a></li>

                </ul>
                </div>
                <div class="tenPixelPaddingLeft">
                      <div class="topGroupDiv">
                          	<div class="paddingContentTopLeftRightBottom">
                       
                    <?php
                     $this->objGroupUtil->groupInstitution($this->getParam('id'));
                            


                    ?>

<!--                <div class="topGroupDiv">
                	<div class="paddingContentTopLeftRightBottom">
                        <div class="discussionList">
                            <img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid" height="49" width="45">
                            <div class="textNextToGroupIcon">
                                <h2>Politechnic of Namibia</h2>

                                <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                            </div>
                        </div>

                    </div>
                </div>
                <br><br><br>-->
               
             </div>
              </div>
                <br><br><br>
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

  


