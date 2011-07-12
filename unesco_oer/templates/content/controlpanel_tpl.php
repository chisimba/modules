

<div class="mainContentHolder">
    <div class="pageBreadCrumb"></div>
    <fieldset>
        <legend><?php $fieldSetHeading = $this->objLanguage->languageText('mod_unesco_oer_control_panel_fieldset', 'unesco_oer');
        echo $fieldSetHeading; ?></legend>
        <br><br>
        <center>
            <table width="80%"  cellpadding="0" cellspacing="0" class="groupListingTable">
                <tr>
                    <td valign="top" align="middle" width="152">
                        <div id="controlPanelCell">

                            <?php
                            $abLink = new link($this->uri(array("action" => 'home')));

                            $abLink->link = '<img src="skins/unesco_oer/images/products.png" alt="Adaptation placeholder" >
                            <br/>  
                            <br/>';
                            $productsTitle = $this->objLanguage->languageText('mod_unesco_oer_control_panel_products_title', 'unesco_oer');
                            echo $productsTitle . '&nbsp;' . $abLink->show();
                            ?> 

                        </div>
                    </td>
                    <td width="152">

                        <div id="controlPanelCell">

                            <?php
                              if($this->objUser->isAdmin()){

                            $link = new link($this->uri(array("action" => 'userListingForm')));

                            $link->link = '<img src="skins/unesco_oer/images/users.png" alt="Institutions" >
                            <br/>  
                            <br/>';
                            $usersTitle = $this->objLanguage->languageText('mod_unesco_oer_control_panel_users_title', 'unesco_oer');
                            echo $usersTitle . $link->show();}
                            ?>

                        </div>


                    </td>
                    <td width="269">
                        <div id="controlPanelCell">
                            <?php
                            if($this->objUser->isAdmin()){
                                ?>
                            <img src="skins/unesco_oer/images/users.png" alt="Groups" >
                            <br/>
                            <br/>
                            <a href="?module=groupadmin" class="prifileLinks">
                                <?php
                                $groupsTitle = $this->objLanguage->languageText('mod_unesco_oer_control_panel_groups_title', 'unesco_oer');
                            echo $groupsTitle;?>
                            </a>
                            <?php
                            }else{}
                            ?>
                           
                      </div>

                    <td width="269">
                        <div id="controlPanelCell">
                            <?php
                             if($this->objUser->isAdmin()){
                            $link = new link($this->uri(array("action" => 'groupListingForm')));

                            $link->link = ' <img src="skins/unesco_oer/images/institutions.png" alt="Institutions" >
                            <br/>
                            <br/> ';
                            $gfroupsTitle = $this->objLanguage->languageText('mod_unesco_oer_control_panel_groups_title', 'unesco_oer');
                            echo $groupsTitle . '&nbsp;' . $link->show();}
                             ?>
                             </div>
                         </td>
                    
                </tr>
                <tr>
                    <td>

                        <div id="controlPanelCell">

                            <?php
                             if($this->objUser->isAdmin()){
                            $link = new link($this->uri(array("action" => 'viewInstitutions')));

                            $link->link = ' <img src="skins/unesco_oer/images/institutions.png" alt="Institutions" >
                            <br/>  
                            <br/>';
                            $institutionsTitle = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
                            echo $institutionsTitle . '&nbsp;' . $link->show();}
                            ?> 

                        </div>

                    </td>

                    <td width="269">
                        <div id="controlPanelCell">

                            <?php
                            $link = new link($this->uri(array("action" => 'viewProductThemes')));

                            $link->link = '<img src="skins/unesco_oer/images/product_theme.png" alt = "Themes" height = "55" width = "55">
                            <br/>
                            <br/>';
                            $themesTitle = $this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer');
                            echo $themesTitle . '&nbsp;' . $link->show();
                            ?>

                        </div>
                    </td>
                    <td width="269">
                        <div id="controlPanelCell">

                            <?php
                            $link = new link($this->uri(array("action" => 'viewLanguages')));

                            $link->link = '<img src="skins/unesco_oer/images/icon-languages.png" alt = "Languages">
                            <br/>
                            <br/> ';
                            $languagesTitle = $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer');
                            echo $languagesTitle . '&nbsp;' . $link->show();
                            ?>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="269">
                        <div id="controlPanelCell">

                            <?php
                            $link = new link($this->uri(array("action" => 'viewProductTypes')));
                            $link->link = '<img src="skins/unesco_oer/images/product-type.png" alt = "Product Type" height = "55" width = "55">
                            <br/>
                            <br/> ';
                            $languagesTitle = $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer');
                            echo $languagesTitle . '&nbsp;' . $link->show();
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        </center>
        <br>
        <br><br><br>
    </fieldset>


    <!-- Right column DIv -->
</div>
