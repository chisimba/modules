<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->objLanguagecode = $this->getObject('languagecode', 'language');
$this->objTopic = & $this->getObject('dbtopic', 'forum');
$forum = $this->objDbGroups->getForum($this->getParam('id'));
$limit =  " limit 30";
$order = 'date';
$topics = $this->objTopic->showTopicsInForum($forum['id'], $this->objUser->userId(), $forum['archivedate'], $order, 'asc', NULL, $limit);
$topicsNum = count($topics);
?>


<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul><li class="first">Home</li>
            <li><a href='?module=unesco_oer&action=10' alt='Groups' title='Groups'>Groups</a></li>
            <li><a href='?module=unesco_oer&action=11a' alt='groupview' title='groupGridView'><?php echo $this->objDbGroups->getGroupName($this->getParam('id')) ?></a></li>
            <li><?php echo $this->objDbGroups->getGroupName($this->getParam('id')) . " " . "discussion" ?> </li>
            <!--<li><a href='/newsroom/2430/newsitems.html' alt='Click here to view NewsItems' title='Click here to view NewsItems'>NewsItems</a></li>
            <li><a href='#' alt='Click here to view 2011-07' title='Click here to view 2011-07'>2011-07</a></li>
            <li>witsjunction</li>
            -->
        </ul>
    </div>

</div>


<div class="subNavigation"></div>
<!-- Left column DIv -->
<div class="groupWideLeftColumn">

    <div class="tenPixelLeftPadding tenPixelBottomPadding">
        <a href="#" class="groupsBreadCrumbColor">
            <?php
            $GroupLink = new link($this->uri(array("action" => '10', "page" => '10a_tpl.php')));
            $GroupLink->link = "Groups";
            echo $GroupLink->show();
            ?></a> |
        <span class="groupsBreadCrumbColor noUnderline"> <?php echo $this->objDbGroups->getGroupName($this->getParam('id')) . ", " . $this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id'))); ?></span>
    </div>
    <div class="tenPixelPaddingLeft">
        <div class="topGroupDiv">
            <div class="paddingContentTopLeftRightBottom">
                <div class="memberList">
                    <?php echo $this->objGroupUtil->topcontent($this->getParam('id')) ?>

<!--                     <img src="images/adapted-product-grid-institution-logo-placeholder.jpg" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                      <div class="textNextToGroupIcon">
                        <h2 class="greenText">Polytechnic of Namibia, journalism department</h2>
                       	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet elit vitae neque consequat congue sed ac nunc. Phasellus mattis rhoncus commodo. Fusce non metus ut nunc dapibus cursus et sit amet diam. Nunc non nibh sit amet leo bibendum sagittis. Vestibulum posuere tincidunt tincidunt. Aenean euismod vulputate volutpat.
                       </div>-->
                </div>
                <div class="memberList rightAlign">
                    <div class="saveCancelButtonHolder">

                        <div class="textNextoSubmitButton"><a id="instLink" href="#" class="greenTextBoldLink">
                                Link to institution
                            </a>
                        </div>
                        <div id="showhide" style="display: none;">


                            <!--                              <ul>
                                                            <li>
                                                                Use tree on the left to navigate existing conents
                                                            </li>
                                                            <li>
                                                                Click on content in order to edit it.
                                                            </li>
                                                            <li>
                                                                Click on the 'Create new ...' options to create new contents.
                                                            </li>
                                                            <li>
                                                                All contents have the option to delete when being edited.
                                                            </li>
                                                        </ul>-->


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



                            $this->loadClass('form', 'htmlelements');
                            $this->loadClass('htmlheading', 'htmlelements');
                            $this->loadClass('htmltable', 'htmlelements');
                            $this->loadClass('textinput', 'htmlelements');
                            $this->loadClass('label', 'htmlelements');
                            $this->loadClass('fieldset', 'htmlelements');

// setup and show heading
                            $header = new htmlheading();
                            $header->type = 1;
                            $header->str = $this->objLanguage->languageText('mod_unesco_oer_group_link_institution', 'unesco_oer');
                            echo '<div style="padding:10px;">' . $header->show();
                            $uri = $this->uri(array('action' => 'linkInstitution'));
                            $form = new form('register', $uri);
                            $table = $this->newObject('htmltable', 'htmlelements');
                            $table->width = '100%';
                            $table->border = '0';
                            $tableable->cellspacing = '0';
                            $table->cellpadding = '2';

                            $table = $this->newObject('htmltable', 'htmlelements');

                            $user_current_membership = $this->objDbGroups->getGroupInstitutions($this->getParam('id'));
                            $currentMembership = array();
                            $availablegroups = array();
                            $groups = $this->objDbInstitution->getAllInstitutions();
                            foreach ($groups as $group) {
                                if (count($user_current_membership) > 0) {
                                    foreach ($user_current_membership as $membership) {
                                        if ($membership['institution_id'] != NULL) {
                                            if (strcmp($group['id'], $membership['institution_id']) == 0) {
                                                array_push($currentMembership, $group);
                                            } else {
                                                array_push($availablegroups, $group);
                                            }
                                        }
                                    }
                                } else {
                                    array_push($availablegroups, $group);
                                }
                            }

                            $objSelectBox = $this->newObject('selectbox', 'htmlelements');
                            $objSelectBox->create($form, 'leftList[]', 'Available Institutionss', 'rightList[]', 'Chosen Institutions');
                            $objSelectBox->insertLeftOptions(
                                    $availablegroups, 'id', 'name');
                            $objSelectBox->insertRightOptions(
                                    $currentMembership, 'id', 'name');

                            $tblLeft = $this->newObject('htmltable', 'htmlelements');
                            $objSelectBox->selectBoxTable($tblLeft, $objSelectBox->objLeftList);
//Construct tables for right selectboxes
                            $tblRight = $this->newObject('htmltable', 'htmlelements');
                            $objSelectBox->selectBoxTable($tblRight, $objSelectBox->objRightList);
//Construct tables for selectboxes and headings
                            $tblSelectBox = $this->newObject('htmltable', 'htmlelements');
                            $tblSelectBox->width = '90%';
                            $tblSelectBox->startRow();
                            $tblSelectBox->addCell($objSelectBox->arrHeaders['hdrLeft'], '100pt');
                            $tblSelectBox->addCell($objSelectBox->arrHeaders['hdrRight'], '100pt');
                            $tblSelectBox->endRow();
                            $tblSelectBox->startRow();
                            $tblSelectBox->addCell($tblLeft->show(), '100pt');
                            $tblSelectBox->addCell($tblRight->show(), '100pt');
                            $tblSelectBox->endRow();

                            $table->startRow();
                            $table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_institution', 'unesco_oer'));
                            $table->addCell($tblSelectBox->show());
                            $table->endRow();


                            $fieldset = $this->newObject('fieldset', 'htmlelements');
                            $fieldset->legend = $this->objLanguage->languageText('mod_unesco_oer_group_fieldset4', 'unesco_oer');
                            $fieldset->contents = $table->show();

                            $form->addToForm($fieldset->show());
                            $form->addToForm('<br />');

                            $button = new button('submitform', $this->objLanguage->languageText('mod_unesco_oer_group_save_button', 'unesco_oer'));
                            $action = $objSelectBox->selectAllOptions($objSelectBox->objRightList) . " SubmitProduct();";
                            $button->setOnClick('javascript: ' . $action);

                            $Cancelbutton = new button('cancelform', $this->objLanguage->languageText('mod_unesco_oer_group_cancel_button', 'unesco_oer'));

                            $form->extra = 'enctype="multipart/form-data"';
                            $form->addToForm('<p align="right">' . $button->show() . $Cancelbutton->show() . '</p>');

                            if ($mode == 'addfixup') {

                                foreach ($problems as $problem) {
                                    $messages[] = $this->__explainProblemsInfo($problem);
                                }
                            }


                            if ($mode == 'addfixup' && count($messages) > 0) {
                                echo '<ul><li><span class="error">' . $this->objLanguage->languageText('mod_userdetails_infonotsavedduetoerrors', 'userdetails') . '</span>';

                                echo '<ul>';
                                foreach ($messages as $message) {
                                    if ($message != '') {
                                        echo '<li class="error">' . $message . '</li>';
                                    }
                                }

                                echo '</ul></li></ul>';
                            }

                            echo $form->show();

                            echo '</div>';
                            ?>
                            <script type="text/javascript">
                                function SubmitProduct()
                                {
                                    var objForm = document.forms['register'];
                                    //objForm.elements[element].value = value;
                                    objForm.submit();
                                }
                            </script>
                        </div>
                    </div>
                    <div class="saveCancelButtonHolder">
                        <div class="buttonSubmit"><a href=""><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18"></a></div>
                        <div class="textNextoSubmitButton"><a href="#" class="greenTextBoldLink">
                                <?php
                                $userId = $this->objUser->userId();

                                if ($this->ObjDbUserGroups->ismemberOfgroup($userId, $this->getParam('id'))) {

                                    $joinGroupLink = new link($this->uri(array('action' => "11a")));
                                    $joinGroupLink->link = 'Join Group';
                                    $joinGroupLink->cssId = 'memberofgroup';
                                } else {

                                    $joinGroupLink = new link($this->uri(array('action' => "joingroup", 'join' => 'join', 'id' => $this->getParam('id'), "page" => '10a_tpl.php')));
                                    $joinGroupLink->link = 'Join Group';
                                    $joinGroupLink->cssId = 'joingroup';
                                }
                                $joinGroupLink->cssClass = 'greenTextBoldLink';
                                echo $joinGroupLink->show();
                                ?>

                            </a>
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
                    $memberLink = new link($this->uri(array("action" => 'groupMembersForm', 'id' => $this->getParam('id'), "page" => '10a_tpl.php')));
                    $No_Of_Members = $this->ObjDbUserGroups->groupMembers($this->getParam('id'));
                    $memberLink->link = "Members(" . $No_Of_Members . ")";
                    echo $memberLink->show();
                    ?></a></li>
            <li><a href="#">
                    <?php
                    $groupadaptationLink = new link($this->uri(array("action" => '11c', 'id' => $this->getParam('id'), "page" => '10a_tpl.php')));
                    $No_Of_adaptation = count($this->objDbGroups->getGroupProductadaptation($this->getParam('id')));
                    $groupadaptationLink->link = " ADAPTATIONS(" . $No_Of_adaptation . ")";
                    echo $groupadaptationLink->show();
                    ?>
                </a></li>
            <li class="onState"><a href="#">
                    <?php
                    $discussionLink = new link($this->uri(array("action" => '11b', 'id' => $this->getParam('id'), "page" => '10a_tpl.php')));
                    $No_Of_discussion = count($this->objDbGroups->getGroupProductadaptation($this->getParam('id')));
                    $discussionLink->link = "  DISCUSSIONS($topicsNum)"; //DISCUSSIONS(".$No_Of_discussion.")";
                    echo $discussionLink->show();
                    ?></a></li>
            <li><a href="#">
                    <?php
                    $InstitutionLink = new link($this->uri(array("action" => '11d', 'id' => $this->getParam('id'), "page" => '10a_tpl.php')));
                    $No_Of_instutions = $this->objDbGroups->getNoOfInstitutions($this->getParam('id'));
                    $InstitutionLink->link = "INSTITUTIONS(" . $No_Of_instutions . ")";
                    echo $InstitutionLink->show();
                    ?>
                </a></li>

        </ul>
    </div>
    <div class="tenPixelPaddingLeft">
        <div class="topGroupDiv">
            <div class="paddingContentTopLeftRightBottom">
                <div class="discussionList">
                    <h3 class="fontBold">
                        <div class="textNextToRightFloatedImage"><a href="?module=forum&id=<?php
                  //  echo $forum['id'];
                    ?>&type=context&action=forum" class="prifileLinks">Discussion</a></div>
                                            

                </div>


            </div>
        </div>
        <br><br><br>
    </div>

    <?php
    echo$this->objGroupUtil->discussion($topics,$forum['id']);
    ?>

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
                <div class="featuredHeader">BROWSER ADAPTATION BY MAP</div>
            </div>
            <div class="rightColumnBorderedDiv">
                <div class="rightColumnContentPadding">

<?php ?>

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
$form = new form('maps', $this->uri(array("action" => 'BrowseAdaptation', "page" => '2a_tpl.php', "page" => '2a_tpl.php', 'MapEntries' => $MapEntries)));

echo $form->show();





echo $temp;
?>
                        </body>
                    </html>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript">


        jQuery(document).ready(function(){

            jQuery("a[id=joingroup]").click(function(){

                var r=confirm( "Are you sure you want to join this group?\nClick Ok a request will be sent to the group admin");
                if(r== true){
                    window.location=this.href;
                }
                return false;
            }
        );

        }
    );

        jQuery(document).ready(function(){

            jQuery("a[id=memberofgroup]").click(function(){

                var r=confirm( "Your are a member of this group\n you can not join again....!!!");
                if(r== true){
                    window.location=this.href;
                }
                return false;
            }


        );
        }
    );

        $(document).ready(function(){
            $('#instLink').click(function(){
                $('#showhide').slideToggle();
                $('.greenTextBoldLink').atrib('class','greyText');

            });


        });

        $('button[name=cancelform]').click(
        function() {
            //        window.location ='index.php?module=unesco_oer&action=11a&id=_13858_1315227715&page=10a_tpl.php';
            $('#showhide').slideToggle();
        }
    );






    </script>




