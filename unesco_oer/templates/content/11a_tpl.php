<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
?>
<?php $this->setLayoutTemplate('maincontent_layout_tpl.php'); ?>
            <div class="subNavigation"></div>
<!--        	 Left column DIv -->
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
                             $memberLink=new link($this->uri(array("action" =>'groupMembersForm','id'=>$this->getParam('id'))));
                             $No_Of_Members=$this->ObjDbUserGroups->groupMembers($this->getParam('id'));
                             $memberLink->link="Members(".$No_Of_Members.")";
                             echo $memberLink->show();
                             ?>

                            </a></li>
                     <li><a href="#">
                             <?php
                             $groupadaptationLink=new link($this->uri(array("action" =>'11c','id'=>$this->getParam('id'))));
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
                	<div class="paddingContentTopLeftRightBottom">
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
                    </div>
                </div>
                <br><br><br>
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
