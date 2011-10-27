<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->objLanguagecode = $this->getObject('languagecode', 'language');
?>



<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul><li class="first">Home</li>
            <li><a href='?module=unesco_oer&action=10' alt='Groups' title='Groups'>Groups</a></li>
            <li><a href='?module=unesco_oer&action=groupGrid' alt='groupview' title='groupGridView'>Groups Grid</a></li>
            <!--           <li>Edit Group</li>-->
            <!--<li><a href='/newsroom/2430/newsitems.html' alt='Click here to view NewsItems' title='Click here to view NewsItems'>NewsItems</a></li>
            <li><a href='#' alt='Click here to view 2011-07' title='Click here to view 2011-07'>2011-07</a></li>
            <li>witsjunction</li>
            -->
        </ul>
    </div>

</div>






<div class="subNavigation"></div>
<!-- Left Colum -->
<div class="leftColumnDiv">

    <div class="moduleHeader greenText"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_group_tools', 'unesco_oer') ?></div>
    <div class="moduleHeader darkBlueText">
<!--                <img src="images/icon-group-new-sub-group.png" alt="Group" width="18" height="18" class="smallLisitngIcons">-->
        <div class="linkTextNextToCreateGroupIcons"><a href="#" class="greenTextBoldLink">

                <?php
                
                if ($onestepid != null){
                $link = new link('#');
                $link->link = '<img src="skins/unesco_oer/images/icon-group-new-sub-group.png" alt="Group" width="18" height="18" class="smallLisitngIcons">
                            Create Group';
                $link->cssId = 'creategroup';
                $link->cssClass="greenTextBoldLink";
              //  if($this->objUser->isLoggedIn() && $this->objUser->isAdmin()){
                
                }else{
                      $link = new link($this->uri(array("action" => 'groupRegistationForm', "page" => '10a_tpl.php', 'onestepid' => $onestepid)));
                $link->link = '<img src="skins/unesco_oer/images/icon-group-new-sub-group.png" alt="Group" width="18" height="18" class="smallLisitngIcons">
                            Create Group';             
                $link->cssClass="greenTextBoldLink";
                   
                }
               if ($this->objUser->isAdmin()) {
                   echo '&nbsp;' . $link->show();
               }
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
                $abLink = new link($this->uri(array("action" => 'groupGrid', "page" => '10a_tpl.php')));
                $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png" alt="Grid" width="19" height="15" class="imgFloatRight">';
                echo $abLink->show();
                ?>

                <?php
                $abLink = new link($this->uri(array("action" => 'groupGrid', "page" => '10a_tpl.php')));
                $abLink->link = 'GRID';
                echo $abLink->show();
                ?>
            </div>

            <div class="gridListPipe">|</div>

            <?php
                $abLink = new link($this->uri(array("action" => 'groupList', "page" => '10a_tpl.php')));
                $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                echo $abLink->show();
            ?>

                <div class="gridListDivView">

                <?php
                $abLink = new link($this->uri(array("action" => 'groupList', "page" => '10a_tpl.php')));
                $abLink->link = 'LIST';
                echo $abLink->show();
                ?>

            </div>








            <!--                        <div class="gridListDivView"><a href="#" class="gridListViewLinks">LIST</a></div>-->
        </div>

    </div>
    <div class="gridViewGroupBackgroundColor">
        <div class="paddingGroubGridListingTable">
            <table class="groupListingTable" cellspacing="0" cellpadding="0">
                <td>

                    <?php
                   
                    $objTable = $this->getObject('htmltable', 'htmlelements');
                    //$objTable->cssClass = "gridListingTable";
                    //$objTable->width = NULL;

                    $groups = $this->objDbGroups->getAllGroups();
                    $newRow = true;
                    $count = 0;
                    foreach ($groups as $group) {
                        $count++;
                        if ($newRow) {
                            $objTable->startRow();
                            $objTable->addCell($this->objGroupUtil->content($group,$onestepid));
                            $newRow = false;
                        } else {
                            $objTable->addCell($this->objGroupUtil->content($group,$onestepid));
                        }
                        if ($count == 3) {
                            $newRow = true;
                            $objTable->endRow();
                            $count = 0;
                        }
                    }
                
                  
                    echo $objTable->show();

//                $fieldset1 = $this->newObject('fieldset', 'htmlelements');
//                $fieldset1->setLegend();
//                $fieldset1->addContent($objTable->show());
//                echo $fieldset1->show();
                    // echo $objTable->show();
                    ?>
                </td>
            </table>
        </div>
    </div>
    <!-- <div class="gridViewGroupBackgroundColor">
              <div class="paddingGroubGridListingTable">
              <table class="groupListingTable" cellspacing="0" cellpadding="0">
              <tr>


              <td>
                      <div class="whiteBackgroundBox">
                  <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                  <div class="groupGridViewHeading greenText">

                      Polytechnic of Namibia, journalism department
                  </div>
                  <div class="groupMemberAndJoinLinkDiv">
                      <span class="greenText">Members :</span> 12<br><br>
                      <a href="#"><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18" class="smallLisitngIcons"></a>
                                      <div class="linkTextNextToJoinGroupIcons"><a href="#" class="greenTextBoldLink">Join</a></div>
                  </div>

                  </div>
              </td>
              <td>
                      <div class="whiteBackgroundBox">
                 <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                      <div class="groupGridViewHeading greenText">
                      Journalism section group of university of namibia
                      </div>
                  <div class="groupMemberAndJoinLinkDiv">

                      <span class="greenText">Members :</span> 32<br><br>
                      <a href="#"><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18" class="smallLisitngIcons"></a>
                                      <div class="linkTextNextToJoinGroupIcons"><a href="#" class="greenTextBoldLink">Join</a></div>
                  </div>
                  </div>
              </td>
              <td>

                      <div class="whiteBackgroundBox">
                       <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                      <div class="groupGridViewHeading greenText">
                      Rhodes university journalism department
                      </div>
                  <div class="groupMemberAndJoinLinkDiv">
                      <span class="greenText">Members :</span> 9<br><br>
                      <a href="#"><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18" class="smallLisitngIcons"></a>

                                      <div class="linkTextNextToJoinGroupIcons"><a href="#" class="greenTextBoldLink">Join</a></div>
                  </div>
                  </div>
              </td>
          </tr>
          <tr>
              <td>
                      <div class="whiteBackgroundBox">

                      <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                      <div class="groupGridViewHeading greenText">
                              Laggon of Cambera community
                      </div>
                      <div class="groupMemberAndJoinLinkDiv">
                        <span class="greenText">Members :</span> 7<br><br>
                          <a href="#"><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18" class="smallLisitngIcons"></a>
                                      <div class="linkTextNextToJoinGroupIcons"><a href="#" class="greenTextBoldLink">Join</a></div>

                      </div>
                  </div>
              </td>
              <td>

              </td>
              <td>

              </td>
          </tr>
      </table>


          </div>
      </div>-->
</div>
<!-- Right column DIv -->
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
                        <div class="featuredHeader">
                            
                       <?php   echo $this->objLanguage->languageText('mod_unesco_oer_browse_map', 'unesco_oer') ?>  </div>
                    </div>
                    <div class="rightColumnBorderedmap">
                        <div >

                    <?php ?>

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
                         
                                            <div id="map_canvas" style="width:210; height:110"></div>
<?php
                                                $form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', "page" => '2a_tpl.php',  'MapEntries' => $MapEntries)));

                                                echo $form->show();
                                                
                                           
?>
                        </body>
                    </html>

                </div>
            </div>
        </div>
    </div>
    
    <?php
    if ($onestepid != null){
        
        echo " <script>
     
            
         alert('You are not a member of any group. Please select a group or create one.');
       

        </script>";
        
    }
 
    ?>
     <script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
    <script>
        
   $(document).ready(function(){   
       
       <?php
       foreach ($groups as $group){
        echo "  $('#".$group['id']."').click(function(){
         
        if(confirm('Do you want to create or link this group to an institution?'))
{

    
   var link = 'index.php?module=unesco_oer&action=11a&id=". $group['id']."&onestepid=".$onestepid."' ;
    location = link;
  
  
  
}
else
{
    
     var link = 'index.php?module=unesco_oer&action=onestepjoingroup&groupid=".$group['id']."&productID=".$onestepid."&userid=".$this->objUser->userId()."' ;
    location = link;
   
}
        
        
     });"  ; 
     
           
           
           }

   ?>     
    
 
  
     $('#creategroup').click(function(){
         
        if(confirm("Do you want to create or link this group to an institution?"))
{
//$this->uri(array("action" => 'groupRegistationForm', "page" => '10a_tpl.php', 'onestepid' => $onestepid))
    
   var link = 'index.php?module=unesco_oer&action=institutionEditor&onestepid=' + '<?php echo $onestepid?> ' ;
    location = link;
////  
// 
  
}
else
{
//    
     var link = 'index.php?module=unesco_oer&action=groupRegistationForm&onestepid='  +  '<?php echo $onestepid?>'  ;
    location = link;
   
}
        
        
     });
        
      
        
    } );     
        
        </script>