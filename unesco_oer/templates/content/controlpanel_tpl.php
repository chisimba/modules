

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
                            <img src="skins/unesco_oer/images/products.png" alt="Adaptation placeholder" >
                            <br/>  
                            <br/>  
                            <?php
                            $abLink = new link($this->uri(array("action" => 'home')));

                            $abLink->link = 'Products';
                            echo '&nbsp;' . $abLink->show();
                            ?> 

                        </div>
                    </td>
                    <td width="152">

                        <div id="controlPanelCell">
                            <img src="skins/unesco_oer/images/users.png" alt="Institutions" >
                            <br/>  
                            <br/>  
                            <?php
                            $link = new link($this->uri(array("action" => 'userListingForm')));

                            $link->link = 'Users';
                            echo '&nbsp;' . $link->show();
                            ?> 

                        </div>


                    </td>
                    <td width="269">
                        <div id="controlPanelCell">
                            <img src="skins/unesco_oer/images/users.png" alt="Institutions" >
                            <br/>  
                            <br/>  
                            <?php
                            $link = new link($this->uri(array("action" => 'groupListingForm')));

                            $link->link = 'Groups';
                            echo '&nbsp;' . $link->show();
                            ?> 

                        </div>
                    </td>
                </tr>
                <tr>
                    <td>

                        <div id="controlPanelCell">
                            <img src="skins/unesco_oer/images/institutions.png" alt="Institutions" >
                            <br/>  
                            <br/>  
                            <?php
                            $link = new link($this->uri(array("action" => 'viewInstitutions')));

                            $link->link = 'Institutions';
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
