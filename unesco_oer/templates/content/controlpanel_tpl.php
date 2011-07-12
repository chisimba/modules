

<div class="mainContentHolder">
    <div class="pageBreadCrumb"></div>
    <fieldset>
        <legend>Administrative tools</legend>
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
                            <br/>  Products';
                            echo '&nbsp;' . $abLink->show();
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
                            <br/>  Users';
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
                            <a href="?module=groupadmin" class="prifileLinks">Groups</a>
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
                            <br/> Groups';
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

                            $link->link = ' <img src="skins/unesco_oer/images/institutions.png" alt="Institutions" >
                            <br/>  
                            <br/> Institutions';
                            echo '&nbsp;' . $link->show();}
                            ?> 

                        </div>

                    </td>

                    <td width="269">
                        <div id="controlPanelCell">

                            <?php
                            $link = new link($this->uri(array("action" => 'viewProductThemes')));

                            $link->link = '<img src="skins/unesco_oer/images/product_theme.png" alt = "Themes" height = "55" width = "55">
                            <br/>
                            <br/> Themes';
                            echo '&nbsp;' . $link->show();
                            ?>

                        </div>
                    </td>
                    <td width="269">
                        <div id="controlPanelCell">

                            <?php
                            $link = new link($this->uri(array("action" => 'viewLanguages')));

                            $link->link = '<img src="skins/unesco_oer/images/icon-languages.png" alt = "Languages">
                            <br/>
                            <br/> Languages';
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
                            $link->link = '<img src="skins/unesco_oer/images/product-type.png" alt = "Product Type" height = "55" width = "55">
                            <br/>
                            <br/> Product Type';
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
