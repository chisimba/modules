
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
                    <a>
                        <?php
                        echo $institutionGUI->showEditInstitutionLinkThumbnail($institutionId);
                        ?>
                    </a>
                    <a>
                        <?php
                        echo $institutionGUI->showEditInstitutionLink($institutionId);
                        ?>
                    </a>
                    <a>
                        <?php
                        echo ' | ';
                        echo $institutionGUI->showNewInstitutionLinkThumbnail();
                        ?>
                    </a>
                    <a>
                        <?php
                        echo $institutionGUI->showNewInstitutionLink();
                        ?>
                    </a>
                    <br>
                    <br />
                    <div class="leftImageHolder rightTwent">
                        <img src= "
                        <?php
                        echo $institutionGUI->showInstitutionThumbnail();
                        ?> " width="79" height="101"><br />
                    </div>
                    <?php
                        echo $institutionGUI->showInstitutionDescription($institutionId);
                    ?>
                        <br>


                        <br>

                        <div class="adaptationInnerPageHeding"><h3 class="pinkText">Adaptations</h3></div>
                        <br>
                    <?php
                        $filtering = $this->getobject('filterdisplay', 'unesco_oer');
                        echo $filtering->SideFilter('4_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
                    ?>
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
                <span class="greyText fontBold">Type of institution:</span> <a class="greyText fontBold"> <?php echo $institutionGUI->showInstitutionType(); ?></a>
                <br><br>
                <span class="greyText fontBold">Country:</span> <a class="greyText fontBold"> <?php echo $institutionGUI->showInstitutionCountry(); ?> </a>
                <br><br>
                <span class="greyText fontBold">Address:</span><?php
                        $address = $institutionGUI->showInstitutionAddress();
                        echo $address['address1'];

                        if (!empty($address['address2'])) {
                            echo ', ' . $address['address2'];
                        }

                        if (!empty($address['address3'])) {
                            echo ', ' . $address['address3'];
                        }
                    ?>
                        <br><br>
                        <span class="greyText fontBold">Institution website:</span> <a class="greyTextLink">
                <?php
                        //$acLink = new link($this->uri(array("action" => "gotoURL", "url" => $institutionGUI->showInstitutionWebsiteLink())));
                        $acLink = new link('http://'.$institutionGUI->showInstitutionWebsiteLink());
                        $acLink->cssClass = 'greyTextLink';
                        $acLink->link = $institutionGUI->showInstitutionWebsiteLink();

                        echo $acLink->show();
                ?></a>
                    <br><br>
                    <span class="greyText fontBold">Keywords:</span> <a class="greyText fontBold"><?php
                        $keywords = $institutionGUI->showInstitutionKeywords();
                        echo $keywords['keyword1'];

                        if (!empty($keywords['keyword2'])) {
                            echo ' | ' . $keywords['keyword2'];
                        } ?></a>
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
