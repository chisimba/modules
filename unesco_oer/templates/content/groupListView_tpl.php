<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
 $this->objLanguagecode=$this->getObject('languagecode', 'language');
?>



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
                            $link = new link($this->uri(array("action" => 'groupRegistationForm',"page"=>'10a_tpl.php')));
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




// $Totalgroups = $this->objDbGroups->getAllGroups();
//$groups=$this->objDbGroups->getgroups($start, $limit);
//$objTable = $this->getObject('htmltable', 'htmlelements');
//$objTable->cssClass = "darkGreyColour";
//foreach ($groups as $group) {
//
//    $objTable->startRow();
//    $objTable->addCell($this->objGroupUtil->content($group));
//    $objTable->endRow();
//    }
//    echo $objTable->show();
//    $start=$limit;
//    $limit=$limit*2;
// $totalgroup=count($Totalgroups);
// echo $this->objPagination->getPaginationString(1,$totalgroup,2,2,"?module=unesco_oer&action=groupList&page=10a_tpl.php");
// $groups=$this->objDbGroups->getgroups($start, $limit);
// $start=$limit;
// $limit=$start+$start;







     $Totalgroups = $this->objDbGroups->getAllGroups();
     $total_pages = count($Totalgroups);

	/* Setup vars for query. */
	$targetpage = "?module=unesco_oer&action=groupList&page=10a_tpl.php"; 	//your file name  (the name of this file)
	$limit = 5; 								//how many items to show per page
	$page = $_GET['page'];
       	if($page)
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
	   	$start = 0;								//if no page var is given, set start to 0

	/* Get data. */
      	$sql = $this->objDbGroups->getgroups(2, 5);
       // print_r($sql);
       	$result =mysql_query($sql);
       
     
      

	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1

	/*
		Now we apply our rules and draw the pagination object.
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1)
			$pagination.= "<a href=\"$targetpage?page=$prev\">« previous</a>";
		else
			$pagination.= "<span class=\"disabled\">« previous</span>";

		//pages
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
				}
			}
		}

		//next button
		if ($page < $counter - 1)
			$pagination.= "<a href=\"$targetpage?page=$next\">next »</a>";
		else
			$pagination.= "<span class=\"disabled\">next »</span>";
		$pagination.= "</div>\n";
             
	}
       
        ?>




     <?php
$row=True;
   while($row = mysql_fetch_array($result)){
    

         
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
$groups=$this->objDbGroups->getgroups($start, $limit);
$objTable = $this->getObject('htmltable', 'htmlelements');
$objTable->cssClass = "darkGreyColour";
foreach ($groups as $group) {
    $objTable->startRow();
    $objTable->addCell($this->objGroupUtil->content($group));
    $objTable->endRow();
    }
    echo $objTable->show();
        }

    ?>
   
  <?php echo $pagination; ?>



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
                                    <div class="featuredHeader"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_browse_map', 'unesco_oer') ?></div>
                                </div>
                          <div class="rightColumnBorderedmap">
                        <div >
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
       
                <!-- Footer-->







