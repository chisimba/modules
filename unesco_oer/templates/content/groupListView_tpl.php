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




                            <?php
                               $abLink = new link($this->uri(array("action" => 'groupGrid')));
                               $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-grid.png"alt="Grid" width="19" height="15" class="imgFloatRight" >';
                               $ablLink2 = new link($this->uri(array("action" => 'groupGrid')));
                               $ablLink2->link='<div class="gridListDivView"><a href="" class="gridListViewLinks">GRID</a></div> <div class="gridListPipe">|</div>';

                               echo $abLink->show();
                               echo $ablLink2->show();


                            ?>
                        <script>
                            $('.test').click(function(){alert('dsfsdfsd');})
                        </script>
<!--                             <div class="gridListDivView"><a href="#" class="gridListViewLinks">GRID</a></div> <div class="gridListPipe">|</div>-->


                               <?php
                               $abLink3 = new link($this->uri(array("action" =>'groupList')));
                               $abLink4 = new link($this->uri(array("action" =>'groupList')));
                                $abLink4->link='<div class="gridListDivView"><a href="" class="gridListViewLinks">LIST</a></div>';
                               $abLink->link = '<img src="skins/unesco_oer/images/icon-sort-by-list.png" alt="List" width="19" height="15" class="imgFloatRight">';
                               echo $abLink3->show();
                               echo $abLink4->show();

                            ?>

<!--                        <div class="gridListDivView"><a href="#" class="gridListViewLinks">LIST</a></div>-->
                    </div>

                </div>

                <?php
                $objTable = $this->getObject('htmltable', 'htmlelements');
                $objTable->cssClass = "gridListingTable";
                $objTable->width = NULL;

                $groups = $this->objDbGroups->getAllGroups();

                foreach ($groups as $group) {
                        $objTable->startRow();
                        $objTable->addCell($this->objGroupUtil->content($group));
                        $objTable->addCell(
          );
                        $objTable->endRow();

                }
                echo $objTable->show();

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

                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">See all adaptations (15)</a></div>

                            </div>
                            <div class="listingAdaptationsLinkAndIcon">
                            	<img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">See UNSECO orginals</a></div>
                            </div>


                        </div>
                        <div class="featuredAdaptedBy">Adapted By</div>
                        <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" class="smallAdaptationImageGrid">

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
        
       
 


<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

jQuery(document).ready(function(){

    jQuery("a[class=join]").click(function(){

        var r=confirm( "Are you sure you want to join this group?");
        if(r== true){
            window.location=this.href;
        }
        return false;
    }
);

}
);

jQuery(document).ready(function(){

    jQuery("a[class=memberofgroup]").click(function(){

        var r=confirm( "Your are a member of this group\n you can not join again!!!");
        if(r== true){
            window.location=this.href;
        }
        return false;
    }


);
}
);


</script>

