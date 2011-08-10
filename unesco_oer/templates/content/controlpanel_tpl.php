

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
                            $productsTitle = $this->objLanguage->languageText('mod_unesco_oer_control_panel_products_title', 'unesco_oer');
                            $abLink->link = '<img src="skins/unesco_oer/images/products.png" alt="Adaptation placeholder" >
                            <br/>  
                            <br/>  ' .  $productsTitle  ;
                            echo '&nbsp;' . $abLink->show();
                            ?> 

                        </div>
                    </td>
                    <td width="152">

                        <div id="controlPanelCell">

                            <?php
                              if($this->objUser->isAdmin()){

                            $link = new link($this->uri(array("action" => 'userListingForm')));
                            $usersTitle = $this->objLanguage->languageText('mod_unesco_oer_control_panel_users_title', 'unesco_oer');
                            $link->link = '<img src="skins/unesco_oer/images/users.png" alt="Institutions" >
                            <br/> 
                            <br/>  ' . $usersTitle;
                            echo '&nbsp;' . $link->show();}
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
                                echo $groupsTitle;
                                ?>
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
                            $groupsTitle = $this->objLanguage->languageText('mod_unesco_oer_control_panel_product_groups_title', 'unesco_oer');
                            
                            $link->link = '<img src="skins/unesco_oer/images/institutions.png" alt="Product groups" height = "55" width = "55">
                            <br/> <br/>
                            <br/> ' . $groupsTitle;
                            echo '&nbsp;' . $link->show();}
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
                            $institutionsTitle = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');

                            $link->link = ' <img src="skins/unesco_oer/images/institutions.png" alt="Institutions" height = "55" width = "55">
                            <br/> 
                            <br/> ' . $institutionsTitle;
                            echo '&nbsp;' . $link->show();}
                            ?> 

                        </div>

                    </td>

                    <td width="269">
                        <div id="controlPanelCell">

                            <?php
                            $link = new link($this->uri(array("action" => 'viewProductThemes')));
                            $themesTitle = $this->objLanguage->languageText('mod_unesco_oer_product_themes', 'unesco_oer');
                            
                            $link->link = '<img src="skins/unesco_oer/images/product_theme.png" alt = "Themes" height = "55" width = "55">
                            <br/>
                            <br/> ' . $themesTitle;
                            echo '&nbsp;' . $link->show();
                            ?>

                        </div>
                    </td>
                    <td width="269">
                        <div id="controlPanelCell">

                            <?php
                            $link = new link($this->uri(array("action" => 'viewLanguages')));
                            $languagesTitle = $this->objLanguage->languageText('mod_unesco_oer_languages', 'unesco_oer');
                            
                            $link->link = '<img src="skins/unesco_oer/images/large-icon-languages.png" alt = "Languages" height = "55" width = "55">
                            <br/>
                            <br/> ' . $languagesTitle;
                            echo '&nbsp;' . $link->show();
                            ?>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="269">
                        <div id="controlPanelCell">
                            <?php
                            $link = new link($this->uri(array("action" => 'viewProductTypes')));
                            $productTypes = $this->objLanguage->languageText('mod_unesco_oer_control_panel_product_types_title', 'unesco_oer');

                            $link->link = '<img src="skins/unesco_oer/images/product-type.png" alt = "Product Type" height = "55" width = "55">
                            <br/>
                            <br/> ' . $productTypes;
                            echo '&nbsp;' . $link->show();
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
