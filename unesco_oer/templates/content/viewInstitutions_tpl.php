<div class="mainWrapper">
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
    ?>
    <div style="clear:both;"></div> 
    <div class="breadCrumb module"> 
        <div id='breadcrumb'>
            <ul><li class="first">Home</li>
                <?php
                $adminTools = $this->objLanguage->languageText('mod_unesco_oer_administrative_tools', 'unesco_oer');
                $insLabel = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
                echo "<li><a href='?module=unesco_oer&action=controlpanel' alt='$adminTools' title='$adminTools'>$adminTools</a></li>";
                echo "<li class='lastc'>$insLabel</li>";
                ?>
            </ul>
        </div>

    </div>
  <div class="leftColumnDiv">

        <?php
        $filtering = $this->getobject('filterdisplay', 'unesco_oer');
        echo $filtering->SideFilter('1a_tpl.php', $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
        ?>

        <br/><br/>
        <div class="blueBackground rightAlign">
            <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
            <a href="#" class="resetLink"> 
                <?php
                $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));

                $button->onclick = "javascript:ajaxFunction23('$adaptationstring');ajaxFunction($i)";
                echo $button->show();

                $button = new button('Reset', $this->objLanguage->languageText('mod_unesco_oer_reset', 'unesco_oer'));

                $button->onclick = "javascript:window.location='{$this->uri(array("action" => 'viewInstitutions'))}';";
                echo $button->show();

//                echo "<a onclick='javascript:ajaxFunction23(".'"'.$adaptationstring.'"'.");ajaxFunction($i)' class='resetLink' >{$this->objLanguage->languageText('mod_unesco_oer_search_2', 'unesco_oer')}</a>";
//                echo $imgButton = "<input name='Go' onclick='javascript:ajaxFunction23(".'"'.$adaptationstring.'"'.");ajaxFunction($i)' type='image' src='skins/unesco_oer/images/button-search.png' value='Find'> </input>";
//
//                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
//                $abLink->cssClass = "resetLink";
//                $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset_2', 'unesco_oer');
//                echo $abLink->show();
                ?>

            </a>
        </div>
        <div class="filterheader">

        </div>
        <div class="rssFeed">
            <img src="skins/unesco_oer/images/small-icon-rss-feed.png" alt="RSS Feed" width="18" height="18" class="imgFloatRight">
            <div class="feedLinkDiv"><a href="#" class="rssFeedLink">RSS Feed</a></div>
        </div>




    </div>
        <!-- Center column DIv -->
    <div class="instCenterColumnDiv">
    
    <?php
// setup and show heading
    $header = new htmlheading();
    $header->type = 1;
    $header->str = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
    echo '<div id="institutionheading">';
    echo $header->show() . '<br><br />';

    $institutionGUI = $this->getObject('institutiongui', 'unesco_oer');

    $buttonInstCaption = $this->objLanguage->languageText('mod_unesco_oer_add_data_newInstitution', 'unesco_oer');
    $buttonInstitution = new button('Add Language Button', $buttonInstCaption);
    $buttonInstitution->setToSubmit();
    $addInstitutionLink = new link($this->uri(array('action' => 'institutionEditor')));
    $addInstitutionLink->link = $buttonInstitution->show();

    $buttonBackCaption = $this->objLanguage->languageText('mod_unesco_oer_group_back_button', 'unesco_oer');
    $controlPannel = new button('backButton', $buttonBackCaption);
    $controlPannel->setToSubmit();
    $BackToControlPanelLink = new link($this->uri(array('action' => "controlpanel")));
    $BackToControlPanelLink->link = $controlPannel->show();

    echo $addInstitutionLink->show() . '&nbsp;' . $BackToControlPanelLink->show();
    echo '</div>';

    $content = '';
    $objTable = $this->getObject('htmltable', 'htmlelements');
   // $objTable->cssClass = "gridListingTable";
    $objTable->width = "100%";

    $newRow = true;
    $count = 0;

    $Institution = $institutionGUI->showAllInstitutions();


    if (count($Institution) > 0) {

        foreach ($Institution as $Institutions) {

            $institutionGUI->getInstitution($Institutions['id']);
            $name = $institutionGUI->showInstitutionName();
            $creator = $adaptedProduct['creator'];

            $institutionLink = new link($this->uri(array("action" => '4', 'institutionId' => $Institutions['id'])));
            $institutionLink->cssClass = 'darkGreyColour';
            $institutionLink->link = '<img align="left"  width="79" height="79" src="' .
                    $institutionGUI->showInstitutionThumbnail() . '" />';

            //Trim the text of the description
            if (strlen($institutionGUI->showInstitutionDescription()) > 100) {
                $description = substr($institutionGUI->showInstitutionDescription(), 0, 100) . '...';
            } else {
                $description = $institutionGUI->showInstitutionDescription();
            }


            $cellcontent = '
            <div id="institutions"> ' . $institutionLink->show() . '&nbsp;&nbsp;' . $description . '<br/>
          ' . $institutionGUI->showEditInstitutionLinkThumbnail($Institutions['id']) . ' |
          ' . $institutionGUI->showDeleteInstitutionLinkThumbnail($Institutions['id']) . '&nbsp;&nbsp;<br/>

          <a class="greyListingHeading">' . $institutionGUI->showInstitutionWebsiteLink() . '</a> |
          <a class="greyListingHeading">' . $institutionGUI->showInstitutionCountry() . '</a> |
          <a class="greyListingHeading">' . $institutionGUI->showInstitutionCity() . '</a>

          <br/>
          </div> ';
/// public function addCell($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
   

            if ($newRow) {
                $objTable->startRow();
                $objTable->addCell($cellcontent);
                $newRow = false;
            } else {
                $objTable->addCell($cellcontent);
            }
            if ($count == 2) {
                $newRow = true;
                $objTable->endRow();
                $count = 0;
            }
             $count++;
        }
    }
    $content.=$objTable->show();
    $fieldset1 = $this->newObject('fieldset', 'htmlelements');
    $institutionsFsLegend = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
    $fieldset1->setLegend($institutionsFsLegend);
    $fieldset1->addContent('<div id ="allinstitutions">'. $content.'</div>');
    echo $fieldset1->show();


//echo $myTable->show();
    ?>
</div>
 
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