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

//<?php
//echo $this->objGroupUtil->groupPerPage();
// //$this->objGroupUtil->populateListView();
//?>


            </div>
        	<!-- Center column DIv -->
            <div class="centerColumnDiv">












                <?php
    /*
     * This program is free software; you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation; either version 2 of the License, or
     * (at your option) any later version.
     * This program is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     * You should have received a copy of the GNU General Public License
     * along with this program; if not, write to the
     * Free Software Foundation, Inc.,
     * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
     */

    /* This is a FEATURED PRODUCT UI
     *
     */


// set up html elements
    $this->loadClass('htmlheading', 'htmlelements');
    $this->loadClass('link', 'htmlelements');
    $objIcon = $this->newObject('geticon', 'htmlelements');


// setup and show heading
    $header = new htmlheading();
    $header->type = 1;
    $header->str = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
    echo '<div id="institutionheading">';
    //echo $header->show() .
            '<br><br />';

    $institutionGUI = $this->getObject('institutiongui', 'unesco_oer');

    echo '</div>';
//$institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
//$institutionGUI->showAllInstitutions();
// retrieve data from tbl_unesco_oer_feturedproducts
    $content = '';
    $Institution = $institutionGUI->showAllInstitutions();
    if (count($Institution) > 0) {
        foreach ($Institution as $Institutions) {
            $institutionGUI->getInstitution($Institutions['id']);
            $name = $institutionGUI->showInstitutionName();
            $creator = $adaptedProduct['creator'];

            $institutionLink = new link($this->uri(array("action" => '4', 'institutionId' => $Institutions['id'])));
            $institutionLink->cssClass = 'darkGreyColour';
            $institutionLink->link = '<img align="top"  width="79" height="79" src="' .
                    $institutionGUI->showInstitutionThumbnail() . '" />';

            //Trim the text of the description
            if (strlen($institutionGUI->showInstitutionDescription()) > 100) {
                $description = substr($institutionGUI->showInstitutionDescription(), 0, 100) . '...';
            } else {
                $description = $institutionGUI->showInstitutionDescription();
            }


            $content.='
            <div id="institutions"> ' . $institutionLink->show() . '&nbsp;&nbsp;' . $description . '<br/>
          ' . $institutionGUI->showEditInstitutionLinkThumbnail($Institutions['id']) . ' |
          ' . $institutionGUI->showDeleteInstitutionLinkThumbnail($Institutions['id']) . '&nbsp;&nbsp;

          <a class="greyListingHeading">' . $institutionGUI->showInstitutionWebsiteLink() . '</a> |
          <a class="greyListingHeading">' . $institutionGUI->showInstitutionCountry() . '</a> |
          <a class="greyListingHeading">' . $institutionGUI->showInstitutionCity() . '</a>

<br/>
           </div> ';
        }
    }
    $fieldset1 = $this->newObject('fieldset', 'htmlelements');
    $institutionsFsLegend = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
    $fieldset1->setLegend($institutionsFsLegend);
    $fieldset1->addContent($content);
    echo $fieldset1->show();


//echo $myTable->show();
    ?>
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

