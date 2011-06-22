
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
    <!-- Left Wide column DIv -->
    <div class="LeftWideColumnDiv">
        <div class="breadCrumb">
            <a href="#" class="orangeListingHeading">Product adaptation</a> |
            <a href="#" class="greyTextTwelveSize">
                <?php
                echo $institutionGUI->showInstitutionName();
                ?>
            </a>
        </div>
        <div class="adaptationsBackgroundColor">
            <div class="innerLeftContent">
                <div class="tenPixelLeftPadding twentyPixelRightPadding">
                    <h2 class="adaptationListingLink">
                        <?php
                        echo $institutionGUI->showInstitutionName();
                        ?>
                    </h2>
                    <a><img src="skins/unesco_oer/images/icon-edit-section.png" class="Farright"></a>
                    <a href="#" class="greyTextLink">Edit metadata</a><br>
                    <br>
                    <div class="leftImageHolderInstitution">
                        <img src="skins/unesco_oer/images/3a-placeholder.jpg" alt="Placeholder" width="121" height="156"><br>
                    </div>
                    <div>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis congue aliquam orci, a vehicula quam scelerisque in. Donec sed quam enim, sit amet tincidunt magna. Quisque vel pharetra justo. Nulla facilisi. Cras mauris ipsum, varius quis suscipit vitae, sagittis nec nisl. Phasellus auctor venenatis vulputate. Nunc volutpat risus eget ante mollis et semper nisi porttitor. Nulla vitae mi nisi, vel rhoncus eros. Vivamus rutrum quam ut tortor egestas volutpat.
                        <br></div>
                    <br>
                    <div class="adaptationInnerPageHeding"><h3 class="pinkText">Adaptations</h3></div>
                    <br>
                    <div class="leftColumnDiv">
                        <div class="moduleHeader blueText">FILTER PRODUCTS</div>
                        <div class="blueNumberBackground">
                            <div class="iconOnBlueBackground"><img src="skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                            <div class="numberOffilteredProducts">4</div>
                        </div>
                        <div class="moduleSubHeader">Product matches filter criteria</div>
                        <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-type.png" alt="Type of product" class="modulesImages">Type of product</div>
                        <div class="blueBackground blueBackgroundCheckBoxText">
                            <input type="checkbox"> Model<br>
                            <input type="checkbox"> Guide<br>
                            <input type="checkbox"> Handbook<br>
                            <input type="checkbox"> Manual<br>
                            <input type="checkbox"> Bestoractile<br>
                        </div>
                        <br>
                        <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-theme.png" alt="Theme" class="modulesImages">Theme</div>
                        <div class="blueBackground">
                            <select name="theme" id="theme" class="leftColumnSelectDropdown">
                                <option value="">All</option>
                            </select>
                        </div>
                        <br>
                        <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-theme.png" alt="Theme" class="modulesImages">UNESCO Product</div>
                        <div class="blueBackground">
                            <select name="theme" id="theme" class="leftColumnSelectDropdown">
                                <option value="">All</option>
                            </select>
                        </div>
                        <br>
                        <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-languages.png" alt="Language" class="modulesImages">Language</div>
                        <div class="blueBackground">
                            <select name="language" id="language" class="leftColumnSelectDropdown">
                                <option value="">All</option>
                            </select>
                        </div>
                        <br>
                        <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages">Items per page</div>
                        <div class="blueBackground">
                            <select name="items_per_page" id="items_per_page" class="leftColumnSelectDropdown">
                                <option value="">All</option>
                            </select>
                        </div>
                        <br><br>
                        <div class="blueBackground rightAlign">
                            <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                            <a href="#" class="resetLink">RESET</a>
                        </div>

                    </div>

                    <div class="innerRightColumn4">
                        <div class="listAdaptations">
                            <div class="floaLeftDiv">
                                <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder">
                            </div>
                            <div class="rightColumInnerDiv">
                                <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                                Adapted in <a href="#" class="productAdaptationGridViewLinks">English</a>
                                <br>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                </div>

                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                </div>
                            </div>
                        </div>


                        <div class="listAdaptations">
                            <div class="floaLeftDiv">
                                <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder">
                            </div>
                            <div class="rightColumInnerDiv">
                                <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                                Adapted in <a href="#" class="productAdaptationGridViewLinks">English</a>
                                <br>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                </div>

                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                </div>
                            </div>
                        </div>


                        <div class="listAdaptations">
                            <div class="floaLeftDiv">
                                <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder">
                            </div>
                            <div class="rightColumInnerDiv">
                                <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                                Adapted in <a href="#" class="productAdaptationGridViewLinks">English</a>
                                <br>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                </div>

                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                </div>
                            </div>
                        </div>


                        <div class="listAdaptations">
                            <div class="floaLeftDiv">
                                <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder">
                            </div>
                            <div class="rightColumInnerDiv">
                                <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                                Adapted in <a href="#" class="productAdaptationGridViewLinks">English</a>
                                <br>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                </div>

                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                </div>
                            </div>
                        </div>

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
                    <span class="greyText fontBold">Type of institution:</span> <a href="#" class="greyTextLink">School</a>
                    <br><br>
                    <span class="greyText fontBold">Country:</span> <a href="#" class="greyTextLink">Namibia</a>
                    <br><br>
                    <span class="greyText fontBold">Address:</span>124 Loremipsum leborthe 1254
                    <br><br>
                    <span class="greyText fontBold">Institution website:</span> <a href="#" class="greyTextLink">www.school.ac.za</a>
                    <br><br>
                    <span class="greyText fontBold">Keywords:</span> <a href="#" class="greyTextLink">Journalism</a> | <a href="#" class="greyTextLink">Education</a>
                    <br><br>
                    <span class="greenText fontBold">Linked groups:</span>
                    <br>
                    <div class="linkedGroups">
                        <a href="#" class="greenTextLink">Poithecnic Namibia</a><br>
                        <a href="#" class="greenTextLink">Group 2</a>
                    </div>
                    <br><br>
                    <span class="greyText fontBold">Community related information:</span>
                    <div class="listCommunityRelatedInfoDiv">
                        <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png" width="18" height="18"></div>
                        <div class="communityRelatedInfoText">Ignor uploaded a file (7 hours ago)</div>
                    </div>
                    <div class="listCommunityRelatedInfoDiv">
                        <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-friend.png" width="18" height="18"></div>
                        <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                    </div>
                    <div class="listCommunityRelatedInfoDiv">
                        <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                        <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                    </div>
                    <div class="listCommunityRelatedInfoDiv">
                        <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-friend.png" width="18" height="18"></div>
                        <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                    </div>
                    <div class="listCommunityRelatedInfoDiv">
                        <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-comment-post.png" width="18" height="18"></div>
                        <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                    </div>
                    <div class="listCommunityRelatedInfoDiv">
                        <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                        <div class="communityRelatedInfoText">Ignor became member of <a href="" class="greyTextLink">Politechnic Namibia</a> (7 hours ago)</div>
                    </div>
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
                    <img src="skins/unesco_oer/images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Featured" width="45" height="49" class="smallAdaptationImageGrid">
                    <div class="featuredAdaptationRightContentDiv">
                        <span class="greyListingHeading">Manual for Investigative Journalists</span>
                        <br><br>
                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18" class="smallLisitngIcons">
                        <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">See all adaptations (15)</a></div>


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
</div>
