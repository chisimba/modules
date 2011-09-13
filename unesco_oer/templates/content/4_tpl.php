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
            <a class="greyTextTwelveSize noUnderline"><?php echo $institutionGUI->showInstitutionName() ?></a>
        </div>
        <div class="adaptationsBackgroundColor">
            <div class="innerLeftContent">
                <div class="tenPixelLeftPadding twentyPixelRightPadding">
                    <h2 class="adaptationListingLink"><?php echo $institutionGUI->showInstitutionName() ?>
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
                        <img src="<?php
                        echo $institutionGUI->showInstitutionThumbnail();
                        ?>" width="121" height="156"><br />
                    </div>
                    <div class="institutionFullDescription">
                        <?php
                        echo $institutionGUI->showInstitutionDescription($institutionId);
                        ?>
                    </div>

                    <br><br>
                    <div class="adaptationInnerPageHeding"><h3 class="pinkText">Adaptations</h3></div>
                    <br>

                    
                    </div>
                </div>

                <div class="innerRightColumn4">

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
                        <span class="greyText fontBold">Institution website:</span> <a href="#" class="greyTextLink"><?php echo $institutionGUI->showInstitutionWebsiteLink(); ?></a>
                        <br><br>
                        <span class="greyText fontBold">Keywords:</span> <a>
                <?php
                        $keywords = $institutionGUI->showInstitutionKeywords();
                        echo $keywords['keyword1'];

                        if (!empty($keywords['keyword2'])) {
                            echo '</a> <a>| ' . $keywords['keyword2'];
                        } ?>
                    </a>
                    <br><br>

                </div>
            </div>

       
</div>