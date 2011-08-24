<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
 $this->objLanguagecode=$this->getObject('languagecode', 'language');
?>

<?php $this->setLayoutTemplate('maincontent_layout_tpl.php'); ?>


<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul><li class="first">Home</li>
            <li><a href='?module=unesco_oer&action=10' alt='Groups' title='Groups'>Groups</a></li>
            <li><a href='?module=unesco_oer&action=groupList' alt='groupview' title='groupListView'>Groups List</a></li>
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
//echo $this->objGroupUtil->groupPerPage();
// //$this->objGroupUtil->populateListView();
//?>


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






                 

<!--                        <div class="gridListDivView"><a href="#" class="gridListViewLinks">LIST</a></div>-->
                    </div>

                </div>





                <div class="gridViewGroupBackgroundColor">
                    <div class="paddingGroubGridListingTable">
                        <table class="groupListingTable" cellspacing="0" cellpadding="0">
                            <td>



                <?php
    $this->loadClass('htmlheading', 'htmlelements');
    $this->loadClass('link', 'htmlelements');
    $objIcon = $this->newObject('geticon', 'htmlelements');
     $this->objLanguagecode=$this->getObject('languagecode', 'language');

// setup and show heading
    $header = new htmlheading();
    $header->type = 1;
    $header->str = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
    echo '<div id="institutionheading">';
  
            '<br><br />';

  

    echo '</div>';

    $content = '';
    $Institution = $this->objDbGroups->getAllGroups();
    if (count($Institution) > 0) {
        foreach ($Institution as $Institutions) {
            //$institutionGUI->getInstitution($Institutions['id']);
            

            $institutionLink = new link($this->uri(array("action" => '11a','id' =>$Institutions['id'])));
            $institutionLink->cssClass = 'darkGreyColour';
            $institutionLink->link = '<img align="top"  width="45" height="49" src="' .$Institutions['thumbnail']. '" />';
           $content.='
            <div id="institutions"> ' . $institutionLink->show() . '&nbsp;&nbsp;' .$Institutions['description'] . '<br/>
          ' .$Institutions['name'] . ' |
          ' .$Institutions['email'] . '&nbsp;&nbsp;

          <a class="greyListingHeading">' . $Institutions['website'] . '</a> |
          <a class="greyListingHeading">' . $this->objLanguagecode->getName($Institutions['country']). '</a> |
          <a class="greyListingHeading">' . $Institutions['city'] . '</a>

<br/>
           </div> ';
        }
    }
    echo $content;
//    $fieldset1 = $this->newObject('fieldset', 'htmlelements');
//    $institutionsFsLegend = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
//    $fieldset1->setLegend();
//    $fieldset1->addContent($content);
//    echo $fieldset1->show();
    
    $aabLink =  new link($this->uri(array("action" =>'groupList',"page"=>'10a_tpl.php')));
    $aabLink->link = 'LIST';
    echo $aabLink->show();



    ?>


                                             </td>
                            </table>
                      </div>
                       </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function(){

        jQuery("a[class=deleteinstitution]").click(function(){

            var r=confirm( "Are you sure you want to delete this institution?");
            if(r== true){
                window.location=this.href;
            }
            return false;
        }


    );

    }


);
</script>







         



<!--
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
                    	<div class="viewAsDiv">View as: </div>-->




                            <?php
//                               $abLink = new link($this->uri(array("action" => 'groupGrid')));
//                               $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png"alt="Grid" width="19" height="15" class="imgFloatRight" >';
//                               $ablLink2 = new link($this->uri(array("action" => 'groupGrid')));
//                               $ablLink2->link='<div class="gridListDivView"><a href="" class="gridListViewLinks">GRID</a></div> <div class="gridListPipe">|</div>';
//
//                               echo $abLink->show();
//                               echo $ablLink2->show();


                            ?>
<!--                        <script>
                            $('.test').click(function(){alert('dsfsdfsd');})
                        </script>-->
<!--                             <div class="gridListDivView"><a href="#" class="gridListViewLinks">GRID</a></div> <div class="gridListPipe">|</div>-->


                               <?php
//                               $abLink3 = new link($this->uri(array("action" =>'groupList')));
//                               $abLink4 = new link($this->uri(array("action" =>'groupList')));
//                                $abLink4->link='<div class="gridListDivView"><a href="" class="gridListViewLinks">LIST</a></div>';
//                               $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
//                               echo $abLink3->show();
//                               echo $abLink4->show();

                            ?>

<!--                        <div class="gridListDivView"><a href="#" class="gridListViewLinks">LIST</a></div>-->
<!--                    </div>

                </div>-->

                <?php
//                $objTable = $this->getObject('htmltable', 'htmlelements');
//                $objTable->cssClass = "gridListingTable";
//                $objTable->width = NULL;
//
//                $groups = $this->objDbGroups->getAllGroups();
//
//                foreach ($groups as $group) {
//                        $objTable->startRow();
//                        $objTable->addCell($this->objGroupUtil->content($group));
//                        $objTable->addCell(
//          );
//                        $objTable->endRow();
//
//                }
//                echo $objTable->show();

                ?>







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
<!--            </div>-->
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







