<?php
    // security check - must be included in all scripts
    if (!$GLOBALS['kewl_entry_point_run']) {
        die("You cannot view this page directly");
    }
    // end security check

/**
 * This object hold all the utility method that the cms modules might need
 *
 * @package cmsadmin
 * @category chisimba
 * @copyright AVOIR
 * @license GNU GPL
 * @author Charl Mert
 */

    class ui extends object
    {
       /**
        * The context  object
        *
        * @access private
        * @var object
        */
        protected $_objContext;	

      /**
        * The inContextMode  object
        *
        * @access private
        * @var object
        */
        protected $inContextMode;	

      /**
        * The sections  object
        *
        * @access private
        * @var object
        */
        protected $_objSections;

      /**
        * The Content object
        *
        * @access private
        * @var object
        */
        protected $_objContent;

      /**
        * The Skin object
        *
        * @access private
        * @var object
        */
        protected $objSkin;

      /**
        * The Content Front Page object
        *
        * @access private
        * @var object
        */
        protected $objFrontPage;

      /**
        * The User object
        *
        * @access private
        * @var object
        */
        protected $objUser;

      /**
        * The user model
        *
        * @access private
        * @var object
        */
        protected $objUserModel;

      /**
        * Feature box object
        *
        * @var object
        */
        public $objFeatureBox;

      /**
        * Class Constructor
        *
        * @access public
        * @return void
        */
        public function init()
        {
            try {
                $this->objMap =$this->newObject('dbmap', 'shorturl');
                $this->objGrid =$this->newObject('jqgrid', 'htmlelements');

                $this->setVar('jquery_boxy_theme', 'shorturl');

                $this->objBox = $this->newObject('jqboxy', 'htmlelements');
                $this->jQuery =$this->newObject('jquery', 'htmlelements');
                //$this->objConfig =$this->newObject('altconfig', 'config');
                $this->objLanguage =$this->newObject('language', 'language');

                //Loading the jqGrid with cms theme
                $this->objGrid->loadGrid('shorturl');
                
                //Live Query
                $this->jQuery->loadLiveQueryPlugin();
                $this->jQuery->loadFormPlugin();

                $this->loadClass('textinput', 'htmlelements');
                $this->loadClass('checkbox', 'htmlelements');
                $this->loadClass('radio', 'htmlelements');
                $this->loadClass('dropdown', 'htmlelements');
                //$this->loadClass('form', 'htmlelements');
                $this->loadClass('button', 'htmlelements');
                $this->loadClass('link', 'htmlelements');
                //$this->loadClass('label', 'htmlelements');
                //$this->loadClass('hiddeninput', 'htmlelements');
                //$this->loadClass('textarea','htmlelements');
                $this->loadClass('htmltable','htmlelements');
                $this->loadClass('layer', 'htmlelements');
                $this->loadClass('jqboxy', 'htmlelements');

            } catch (Exception $e){
                throw customException($e->getMessage());
                exit();
            }
        }

   /**
	* Method to return the Main Form
	*
	* @access public
	* @return HTML
	*/
        public function showList()
        {
            $h3 = $this->newObject('htmlheading', 'htmlelements');
            $objForm = new form('addfrm', $this->uri(array('action' => $action, 'id' => $contentId, 'frontman' => $frontMan), 'cmsadmin'));
            $objForm->setDisplayType(3);

            $tableContainer = new htmlTable();
            $tableContainer->width = "100%";
            $tableContainer->cellspacing = "0";
            $tableContainer->cellpadding = "0";
            $tableContainer->border = "0";
            $tableContainer->attributes = "align ='center'";

            $table_list = new htmlTable();
            $table_list->width = "100%";
            $table_list->cellspacing = "0";
            $table_list->cellpadding = "0";
            $table_list->border = "0";
            $table_list->attributes = "align ='center'";

            $this->objGrid->url = '?module=shorturl&action=getjsondata';
            $this->objGrid->editurl = '?module=shorturl&action=edit';
            $this->objGrid->dataType = 'json';
            $this->objGrid->panelId = '';
            $this->objGrid->height = '100%';
            $this->objGrid->multiselect = true;

//unbinds and then binds (to avoid duplicate binding) the delete events when the grid has loaded.
$this->objGrid->loadComplete = <<<GRIDLOADCALLBACK
                function(){
                    //Binding Edit/Delete Events when grid has loaded
                    var ids = jQuery("#chisimba_grid_01").getDataIDs();
                    ids = ids.toString().split(",");
                    for (i = 0; i < ids.length; i++){
                        unbindDeleteEvent('del_' + ids[i], 'rm_' + ids[i], 'chisimba_grid_01',  ids[i]);
                        bindDeleteEvent('del_' + ids[i], 'rm_' + ids[i], 'chisimba_grid_01',  ids[i]);
                        bindAjaxEditForm('edit_' + ids[i], ids[i]);
        
                        row_id = ids[i];
                        bindFormSubmit('frm_addgrid_' + row_id, 'frm_submit_btn_' + row_id);
                    }

                    jQuery('#content').css('height', 'auto');
                    //jQuery('#chisimba_grid_container').addClass('grid_padding_bottom');
                    
                }
GRIDLOADCALLBACK;

            $this->objGrid->addColumn('Match URL', 'match_url', '180', 'left', true);
            $this->objGrid->addColumn('Target URL', 'target_url', '180', 'left', true);
            //$this->objGrid->addColumn('Dynamic', 'is_dynamic', '70', 'center', false);
            $this->objGrid->addColumn('Order', 'ordering', '44', 'center', true);
            $this->objGrid->addColumn('Options', 'ordering', '60', 'left', false);
            $this->objGrid->addColumn('Date', 'datestamp', '140', 'left', true);

            $this->objGrid->buildGrid('Short URL Manager');

            //Attaching the grids refresh event to the link with id set like : <a id='refreh_grid' href='javascript:void(0)'>
            $this->objGrid->attachRefreshEvent('refresh_grid');
            $this->objGrid->attachDeleteMultipleEvent('delete_grid_items');

            //Attaching Delete Confirm Click Events

//Attaching the callback method to dynamically bind DELETE events for Ajax loaded items
//This will be called when the grid completes the JSON load
$script = <<<DELETECALLBACK
<script type="text/javascript">
    //Method to provide a callback function to bind delete events
    function bindDeleteEvent(icon_id, yes_btn_id, grid_id, row_id){

            //Binding the trash icon click event to boxy confirmation form
            jQuery('#' + icon_id).livequery('click',function(){
                var dialog = new Boxy("<table>    <tr style=\"padding-bottom:20px;\">        <td>Are you sure you want to delete this item?</td>    </tr>    <tr>        <td align=\"center\">            <input id=\"" + yes_btn_id + "\" type=\"button\" onclick=\'Boxy.get(this).hide(); return false\' value=\"Yes\" style=\"width:50px;\">            <input type=\"button\" onclick=\'Boxy.get(this).hide(); return false\' value=\"No\" style=\"width:50px;\">            <input type=\"button\" onclick=\'Boxy.get(this).hide(); return false\' value=\"Cancel\" style=\"width:50px;\">        </td>    </tr></table>", {title: "Confirm Delete"});
            });
    
            //Binding boxy confirmation form's 'Yes' button to trigger the grids delete function
            jQuery('#' + yes_btn_id).livequery('click',function(){
                jQuery('#' + grid_id).delGridRow(row_id);
            });

    }

    //Method to unbind all delete events
    function unbindDeleteEvent(icon_id, yes_btn_id, grid_id, row_id){

            //unbind ALL delete events at the given index
            jQuery('#' + icon_id).expire();
            jQuery('#' + yes_btn_id).expire();

    }

</script>
DELETECALLBACK;
$this->appendArrayVar('headerParams', $script);

//Attaching the callback method to dynamically bind EDIT events for Ajax loaded items
//This will be called when the grid completes the JSON load
$script = <<<EDITCALLBACK
<script type="text/javascript">
    //Method to provide a callback function to bind edit events
    function bindAjaxEditForm(anchor_id, row_id){
        //Initializing the AJAX Boxy Window
        options = {
            sessionName: 'PHPSESSID',
            showBoxOnSessionExpiry: false,
            ajaxComplete: function(data){
                jQuery('#chisimba_grid_01').hideLoading();

                //Firefox 3.05beta won't rebind after first event fired (Boxy submit only works once)
                bindFormSubmit('frm_addgrid_' + row_id, 'frm_submit_btn_' + row_id);

            },
            ajaxError: function(data){
                location.href="?module=shorturl";
            },
            ajaxSessionExpired: function(data){
                jQuery('#chisimba_grid_01').hideLoading();
                document.location.href="?module=shorturl";
            }
        };

        jQuery('#' + anchor_id).livequery('click',function(){
            jQuery('#chisimba_grid_01').showLoading();
        });

        jQuery('#' + anchor_id).boxy(options);

    }

</script>
EDITCALLBACK;
$this->appendArrayVar('headerParams', $script);

            //$this->bindDeleteConfirmEvents(); //DON'T UNCOMMENT

            //Attaching Edit Click Events
            //$this->bindEditEvents(); //DON'T UNCOMMENT

            $table_panel = new htmlTable();
            $table_panel->width = "100%";
            $table_panel->cellspacing = "0";
            $table_panel->cellpadding = "0";
            $table_panel->border = "0";
            $table_panel->attributes = "align ='center'";

            /*
            $tableContainer->startRow();
            $tableContainer->addCell($table_panel->show());
            $tableContainer->endRow();
            */

            $tableContainer->startRow();
            $tableContainer->addCell($this->objGrid->show());
            $tableContainer->endRow();
    
            //Add validation for title            
            $errTitle = $this->objLanguage->languageText('mod_cmsadmin_entertitle', 'cmsadmin');
            $objForm->addRule('title', $errTitle, 'required');
            $objForm->addToForm($tableContainer->show());
            //$objForm->addToForm($div1->show());
            //add action
            $objForm->addToForm($txt_action);

            //Testing the output div of the ajax form submission
            $objLayer = new layer();
            $objLayer->id = 'output_div';

            $display = $objForm->show().$objLayer->show();

            //jQuery Toggle to display the addKeys Form
            //TODO: need to abstract the jquery functionality below but theres just no time now ;-)

            $script = "
                <script language='javascript'>
                    //jQuery standard toggle effect
            
                    function doToggle(id, chkid) {
                        chkBox = jQuery('#' + chkid);
                        if (chkBox.attr('checked')){
                            jQuery('#' + id).show('slow');

                            jQuery('#txtMatchUrl').addClass('match_target_url_grow').show('slow');
                            jQuery('#txtTargetUrl').addClass('match_target_url_grow').show('slow');

                            jQuery('#txtMatchUrl').attr('disabled','true');
                            
                        } else {
                            jQuery('#' + id).hide('slow');
                            jQuery('#txtMatchUrl').addClass('match_target_url_init').show('slow');
                            jQuery('#txtTargetUrl').addClass('match_target_url_init').show('slow');

                            jQuery('#txtMatchUrl').removeAttr('disabled');

                        }
                    }

                    var key_counter;

                    function activateAddLink() {
                        var linkContainer = jQuery('#addkeybuttoncontainer');
                        linkContainer.css({display:'block'});
                    }

                    jQuery(document).ready(function(){

                        //jQuery('#addkeysform').hide('slow');
                        doToggle('addkeysform', 'input_is_dynamic');
                        //jQuery('#addkeybutton').hide('slow');
                        doToggle('addkeybuttoncontainer', 'input_is_dynamic');

                        //Setting the key counter
                        var key_counter = 1;
                        jQuery('#addkeybutton').livequery('click', function() {
                            var _parent = jQuery('#keyclonesource').parent();
                            _parent.clone().insertAfter(_parent).attr('name','txtKeyChanged' + key_counter);
                            key_counter++;
                        });

/*
                        jQuery('#sData').livequery('click', function(){
                            //var gr = jQuery('#editgrid').getSelectedRow(); 
                            jQuery('#chisimba_grid_01').editGridRow(
                                '1',
                                    {
                                        height:280,
                                        reloadAfterSubmit:false
                                    }
                            ); 
                            
                        });
*/


/*
                        //Handling the submit for all dynamic add forms
                        jQuery('#frm_addgrid').livequery('submit', function() {
                            var options = {
                                target: 'output_div',
                                url:    '?module=shorturl&action=edit',
                                type:   'POST',
                                data: {'dukey':'houser'},

                                //beforeSubmit:   
                                //    function(responseText) {
                                //        jQuery('#chisimba_grid_01').trigger('processGrid');
                                //        alert('Success');
                                //        return true;
                                //    },

                                success: 
                                    function(responseText) {
                                        //jQuery('#chisimba_grid_01').trigger('reloadGrid');
                                        alert(responseText);
                                    },

                                semantic: false,
                                resetForm: false,
                                clearForm: false
                            };
                            
                            //jQuery('#chisimba_grid_01').showProcessing();

                            //jQuery('#frm_addgrid').ajaxSubmit(options); //This implementation is broken in the plugin, should report as bug
                            jQuery('#frm_addgrid').ajaxSubmit(function(responseText) {
                                //jQuery('#chisimba_grid_01').hideProcessing();
                                //setTimeout(function(){jQuery('#chisimba_grid_01').trigger('reloadGrid');}, 1000);

                                jQuery('#chisimba_grid_01').trigger('reloadGrid');
                            });
                            
                            //Hiding the boxy form window
                            jQuery('#frm_addgrid').parents('.boxy-wrapper').hide();

                            //Reloading the grid
                            return false; // cancel conventional submit
                        });
*/








                        //Handling GLOBAL Ajax Errors
                        jQuery('#chisimba_grid_container').bind('ajaxError', function(){
                            location.href = '?module=shorturl';
                        });

                        //New Form Click Event
                        /*
                        jQuery('#add_mapping_form').livequery('submit', function() {
                            var anchor_id = 'add_mapping_form';

                            //Initializing the AJAX Boxy Window
                            options = {
                                onSuccess: function(){
                                    jQuery('#chisimba_grid_01').hideLoading();
                                }
                            };
                    
                            jQuery('#' + anchor_id).livequery('click',function(){
                                jQuery('#chisimba_grid_01').showLoading();
                            });
                    
                            jQuery('#' + anchor_id).boxy(options);
                        });
                        */


                        //Binding the 'New' button
                        bindFormSubmit('frm_addgrid_', 'frm_submit_btn_');

                    });

                    function bindFormSubmit(id, btn_id){
                        window.console.log('binding id : ' + id + ' || button id : ' + btn_id);
                        //Unbind ALL edit events first avoid multiple binding
                        jQuery('#' + btn_id).expire();

                        //Rebind
                        jQuery('#' + btn_id).livequery('click', function() {
                            window.console.log('caught event on button : ' + btn_id);

                            //jQuery('#chisimba_grid_01').showProcessing();

                            //jQuery('#frm_addgrid').ajaxSubmit(options); //This implementation is broken in the plugin, should report as bug
                            jQuery('#' + id).ajaxSubmit(function(responseText) {
                                //jQuery('#chisimba_grid_01').hideProcessing();
                                jQuery('#chisimba_grid_01').trigger('reloadGrid');
                            });
                            
                            //Hiding the boxy form window
                            jQuery('#' + id).parents('.boxy-wrapper').hide();

                            //Reloading the grid
                            return false; // cancel conventional submit
                        });

                    }

                    </script>";

            $this->appendArrayVar('headerParams', $script);

            return $display;
        }

       /**
        * Method to Bind the Delete Confirm requests for the current mappings
        * Depricated: Use callback binding instead
        */
        public function bindDeleteConfirmEvents()
        {
            $arrMaps = $this->objMap->getAll();
            if (!empty($arrMaps)){
                foreach ($arrMaps as $map){
                    //Attaching the Delete/Confirm Dialogs to the links

$innerHtml = <<<HTSRC
<table>
    <tr style="padding-bottom:20px;">
        <td>Are you sure you want to delete this item?</td>
    </tr>
    <tr>
        <td align="center">
            <input id="rm_$map[id]" type="button" onclick='Boxy.get(this).hide(); return false' value="Yes" style="width:50px;"/>
            <input type="button" onclick='Boxy.get(this).hide(); return false' value="No" style="width:50px;"/>
            <input type="button" onclick='Boxy.get(this).hide(); return false' value="Cancel" style="width:50px;"/>
        </td>
    </tr>
</table>
HTSRC;
                    //Stripping new lines for js compatibility
                    $innerHtml = str_replace("\n", '', $innerHtml);
                    $this->objBox->setHtml($innerHtml);
                    $this->objBox->setTitle('Confirm Delete');
                    $this->objBox->attachClickEvent('del_'.$map['id']);

                    // Binding the Actual Delete events to the 'OK' buttons of the corresponding confirm dialog
                    // When you click ok to delete, the handler will be ready to remove the specified row from 
                    // the grid via ajax request.
                    $this->objGrid->attachDeleteEvent("rm_$map[id]", $map['id']);
                }
            }
        }

       /**
        * Method to Bind the Edit for the current mappings
        * 
        */
        public function bindEditEvents()
        {
            $arrMaps = $this->objMap->getAll();
            if (!empty($arrMaps)){
                foreach ($arrMaps as $map){
                    //Attaching the Delete/Confirm Dialogs to the links

                    $innerHtml = $this->getAddMappingForm($map['id']);

                    //Stripping new lines for js compatibility
                    $innerHtml = str_replace("\n", '', $innerHtml);
                    $this->objBox->setHtml($innerHtml);
                    $this->objBox->setTitle('Edit URL Mapping');
                    $this->objBox->attachClickEvent('edit_'.$map['id']);

                    // Binding the Actual Delete events to the 'OK' buttons of the corresponding confirm dialog
                    // When you click ok to delete, the handler will be ready to remove the specified row from 
                    // the grid via ajax request.
                    $this->objGrid->attachDeleteEvent("rm_$map[id]", $map['id']);
                }
            }

        }

   /**
    * Method to return the Mapping Form
    *
    * @param string $mapId The id of the mapping to be edited.Default NULL for adding new mapping
    * @access public
    */
        public function getAddMappingForm($mapId = '')
        {

            //Load Edit Values when supplied with id
            $arrMaps = array();
            $arrKeys = array();
            if ($mapId != '') {
                $arrMaps = $this->objMap->getAll(" WHERE id = '$mapId' ");
                $arrMaps = $arrMaps[0];
                //Getting associated keys if dynamic
                if ($arrMaps['is_dynamic']) {
                    $arrKeys = $this->objMap->getKeys($mapId);
                }
            }

            $table = new htmlTable();
            $table->width = "100%";
            $table->cellspacing = "0";
            $table->cellpadding = "10";
            $table->border = "0";
            $table->attributes = "align ='center'";

            $tbl = new htmlTable();
            $tbl->width = "100%";
            $tbl->cellspacing = "0";
            $tbl->cellpadding = "10";
            $tbl->border = "0";
            $tbl->attributes = "align ='center'";

            //TODO: Add Language Items for these
            //Match
            $lblMatchUrl = 'Match URL:';

            if ($arrMaps['is_dynamic']) {
                //Disabled
                $txtMatchUrl = "<input type='text' id='txtMatchUrl' name='txtMatchUrl' class='match_target_url_init' value='$arrMaps[match_url]' disabled/>";
            } else {
                //Enabled
                $txtMatchUrl = "<input type='text' id='txtMatchUrl' name='txtMatchUrl' class='match_target_url_init' value='$arrMaps[match_url]'/>";
            }

            $tbl->startRow();
            $tbl->addCell($lblMatchUrl, '', '', '', 'boxy_td_left', '','');
            $tbl->addCell($txtMatchUrl, '', 'top', 'left');
            $tbl->endRow();

            //Target
            $lblTargetUrl = 'Target URL:';
            $txtTargetUrl = "<input type='text' id='txtTargetUrl' name='txtTargetUrl' class='match_target_url_init' value='$arrMaps[target_url]'/>";

            $tbl->startRow();
            $tbl->addCell($lblTargetUrl, '', '', '', 'boxy_td_left', '','');
            $tbl->addCell($txtTargetUrl, '', 'top', 'left');
            $tbl->endRow();

            //Dynamic
            $lblDynamic = 'Dynamic:';
            $chkbox = new checkbox('is_dynamic');
            $chkbox->ischecked = $arrMaps['is_dynamic'];
            $chkbox->extra = 'onclick="doToggle(\'addkeysform\', \'input_is_dynamic\'); doToggle(\'addkeybutton\', \'input_is_dynamic\'); activateAddLink()"';


            //$chkDynamic = $chkbox->show(); //ENABLE DYNAMIC

            //$btnAddKey = '<a id="addkeybutton" href="#"> Add Key </a>';

            $tbl->startRow();
            //$tbl->addCell($lblDynamic, '', '', '', 'boxy_td_left', '',''); //ENABLE DYNAMIC
            $tbl->addCell('<div style="float:left;">'.$chkDynamic.' </div> <div id="addkeybuttoncontainer" style="float:right;display:none;">'.$btnAddKey.'</div>');
            $tbl->endRow();

            //Submit/Cancel
            //$btnOk = "<input type='submit' id='sData' name='sData' value='Save' style='width:50px;'/>";
            $btnOk = "<input type='button' id='frm_submit_btn_$mapId' name='frm_add_submit' value='Save' style='width:50px;'/>";
            $btnCancel = "<input type='button' id='cancel' name='cancel' value='Cancel' style='width:50px;' onclick='Boxy.get(this).hide(); return false'/>";

            $tbl1 = new htmlTable();
            $tbl1->width = "100%";
            $tbl1->cellspacing = "0";
            $tbl1->cellpadding = "0";
            $tbl1->border = "0";
            $tbl1->attributes = "align ='center'";

            $tbl1->startRow();
            $tbl1->addCell($btnOk.' '.$btnCancel, '', '', 'center', '', '','');
            $tbl1->endRow();

            //addkeysform
            $tblKeys = new htmlTable();
            $tblKeys->width = "100%";
            $tblKeys->cellspacing = "0";
            $tblKeys->cellpadding = "0";
            $tblKeys->border = "0";
            $tblKeys->attributes = "align ='center'";
            //$tblKeys->id = 'addkeysform';

            $tblKeys->row_attributes = 'id="keyclonesource"';
            $tblKeys->startRow();
            $tblKeys->addCell('', '', '', '', 'boxy_td_key_left', '','');
            $tblKeys->addCell('Table:');
            $tblKeys->addCell('Column:');
            $tblKeys->endRow();

            //TODO: Revisit the key cloning
            // Fixed Amount of Keys
            /*
            if ($arrMaps['is_dynamic']) {
                var_dump($arrKeys);
                exit;
            }*/


            for ($i = 1; $i < 6; $i++) {
                //Key[$i]
                $lblKey = "Key $i:";
                $txtKeyTable = "<input type='text' id='txtKeyTable$i' name='txtKeyTable$i' value='".$arrKeys[$i-1]['tbl_name']."'/>";
                $txtKeyField = "<input type='text' id='txtKeyField$i' name='txtKeyField$i' value='".$arrKeys[$i-1]['tbl_field']."'/>";
    
                $tblKeys->row_attributes = 'id="keyclonesource"';
                $tblKeys->startRow();
                $tblKeys->addCell($lblKey, '', '', '', 'boxy_td_key_left', '','');
                $tblKeys->addCell($txtKeyTable, '', '', '', 'boxy_td_key_table_pad_right', '');
                $tblKeys->addCell($txtKeyField);
                $tblKeys->endRow();
            }

            /* // Dynamic UI key cloning works but not feasible for purpose of adding shorturls
            $lblKey = "Key:";
            $txtKeyTable = "<input type='text' id='txtKeyTable' name='txtKeyTable' value=''/>";
            $txtKeyField = "<input type='text' id='txtKeyField' name='txtKeyField' value=''/>";

            $tblKeys->row_attributes = 'id="keyclonesource"';
            $tblKeys->startRow();
            $tblKeys->addCell($lblKey, '', '', '', 'boxy_td_key_left', '','');
            $tblKeys->addCell($txtKeyTable, '', '', '', '', 'style="padding-right:10px;"');
            $tblKeys->addCell($txtKeyField);
            $tblKeys->endRow();
            */

            $layer = new layer();
            $layer->id = 'addkeysform';

            if ($arrMaps['is_dynamic']) {
                $layer->cssClass = 'toggleShow';
            } else {
                $layer->cssClass = 'toggleHide';
            }

            $layer->str = $tblKeys->show();

            //Adding All to Container here
            $table->startRow();
            $table->addCell($tbl->show()/*.$layer->show()*/.'<div style="padding-bottom:10px"></div>'.$tbl1->show(), '', '', 'center', '', '','');
            $table->endRow();

            $action = '<input type="hidden" name="oper" value="edit" />';
            $action .= '<input type="hidden" name="id" value="'.$arrMaps['id'].'" />';

            //Stripping New Lines and preparing for boxy input = (Facebook style window)
            $display = '<form id="frm_addgrid_'.$mapId.'" class="FormGrid" name="frm_addgrid" action="?module=shorturl&action=edit" method="GET">';
            $display .= str_replace("\n", '', $table->show());
            $display .= $action;
            $display .= '</form>';

            return $display;
        }


       /**
        * Method to return the Header + Top Navigation items
        *
        * @return string The top navigation header
        * @access public
        */
        public function showTopNav()
        {

            $objIcon = $this->newObject('geticon', 'htmlelements');
            $tbl = $this->newObject('htmltable', 'htmlelements');
            $tblH = $this->newObject('htmltable', 'htmlelements');
            $h3 = $this->getObject('htmlheading', 'htmlelements');
            //$Icon = $this->newObject('geticon', 'htmlelements');
            $objContainerLayer = $this->newObject('layer', 'htmlelements');
            $objLayer = $this->newObject('layer', 'htmlelements');
            //$Icon->setIcon('loading_circles_big');
            $objRound =$this->newObject('roundcorners','htmlelements');
            $objIcon->setIcon('shorturl_big', 'png', 'icons/modules/');

            $topNav = $this->getTopNav();

            //$strShortUrl = $this->objLanguage->languageText('mod_shorturl_main', 'cmsadmin')
            $strShortUrl = 'Short URL Manager';
            $h3->str = $strShortUrl;

            $tblH->width = '300px';
            $tblH->startRow();
            $tblH->addCell($objIcon->show(), '50px');
            $tblH->addCell($h3->show(), '150px', 'center');
            $tblH->endRow();
            
            $objLayer->str = $tblH->show();
            $objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
            $header = $objLayer->show();
            
            $objLayer->str = $topNav;
            $objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
            $header .= $objLayer->show();
            
            $objLayer->str = '';
            $objLayer->border = '; clear:both; margin:0px; padding:0px;';
            $headShow = $objLayer->show();
            
            $objContainerLayer->str = $header.$headShow.'<hr />';
            $objContainerLayer->id = 'shorturl_header';
            $objContainerLayer->width = '726px';
            
            return $objContainerLayer->show();
        }


       /**
        * Method to get the Top Navigation Icons for Short URL
        * 
        * @return str the string top navigation
        *
        * @access public
        */
        public function getTopNav(){

            //Declare objects
            $tbl = $this->newObject('htmltable', 'htmlelements');
            $objIcon = $this->newObject('geticon', 'htmlelements');

            $iconList = '';

            //Setting up the Boxy Form
            
            $this->objBox->setHtml($this->getAddMappingForm());
            $this->objBox->setTitle('Add URL Mapping');
            $this->objBox->attachClickEvent('add_mapping_form');
            
            // New / Add
            $url = 'javascript:void(0)';
            $linkText = $this->objLanguage->languageText('word_new');
            $iconList .= $objIcon->getCleanTextIcon('add_mapping_form', $url, 'new', $linkText, 'png', 'icons/shorturl/');

            // Refresh Grid
            $url = 'javascript:void(0)';
            //$linkText = $this->objLanguage->languageText('word_refresh');
            $linkText = 'Refresh';
            $iconList .= $objIcon->getCleanTextIcon('refresh_grid', $url, 'refresh', $linkText, 'png', 'icons/shorturl/');

            /* //Need to revisit multiple deletes using jqGrid - Need to fix this in the jqgrid.formedit.js
            // Delete
            $url = 'javascript:void(0)';
            //$linkText = $this->objLanguage->languageText('word_refresh');
            $linkText = 'Delete';
            $iconList .= $objIcon->getCleanTextIcon('delete_griditems', $url, 'delete', $linkText, 'png', 'icons/shorturl/');
            */

            return '<div style="align:right;">'.$iconList.'</div>';

            //return $tbl->show();

        }



    }

?>
