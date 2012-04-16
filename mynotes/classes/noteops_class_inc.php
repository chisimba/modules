<?php

/**
 *
 * A simple notes module operations object
 * 
 * Arbitrary notes about anything, and organized using tags. These notes can be
 * shared with friends, members of a context, or made public.
 *
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
 *
 * @version
 * @package    mynotes
 * @author     Nguni Phakela info@nguni52.co.za
 * @copyright  2010 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 * 
 */
// security check - must be included in all scripts
if (!
        /**
         * Description for $GLOBALS
         * @global unknown $GLOBALS['kewl_entry_point_run']
         * @name   $kewl_entry_point_run
         */
        $GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * 
 * A simple notes module operations object
 *
 * @category  Chisimba
 * @author    Nguni Phakela
 * @version
 * @copyright 2010 AVOIR
 *
 */
class noteops extends object {

    /**
     *
     * @var string Object $objLanguage String for the language object
     * @access public
     *
     */
    public $objLanguage;

    /**
     * Standard init function
     *
     * Create title
     *
     * @return NULL
     */
    public function init() {
        // Load the functions specific to this module.
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('mynotes.js'));
        // Instantiate the language object.
        $this->objLanguage = $this->getObject('language', 'language');
    }

    /**
     * Render the input box for creating a note.
     *
     * @return string The input box and button
     *
     */
    private function showNoteBox($keyValue) {
        $noteid = $keyValue;
        if ($this->objUser->isLoggedIn()) {
            $target = $this->uri(array(
                'action' => 'save',
                'ownerid' => $ownerId), 'mynotes');
            $target = str_replace('&amp;', "&", $target);

            $onlyText = $this->objLanguage->languageText("mod_mynotes_onlytext", "wall", "No HTML, only links and text");
            $enterText = $this->objLanguage->languageText("mod_mynotes_entertext", "mynotes", "Enter your note here...");
            $shareText = $this->objLanguage->languageText("mod_mynotes_share", "mynotes", "Share");
            $ret = '<div id=\'updateBox\'>
	            ' . $enterText . '
	            <textarea class=\'notepost\' id=\'notepost_' . $noteid . '\'></textarea>
	            <input type="hidden" id=\'target_' . $noteid . '\' value="' . $target . '" name="target_' . $wallid . '" />
	            <button class=\'shareBtn\' id=\'' . $noteid . '\'>' . $shareText . '</button>
	            <div class="mynotes_onlytext" id="mynotes_onlytext_' . $wallid . '">' . $onlyText . '</div>
	            <div class=\'clear\'></div>
	            </div>';
        } else {
            $ret = NULL;
        }
        return $ret;
    }
    
    /**
     * Method to display the posts editor in its entirety
     *
     * @param  integer $userid
     * @param  integer $editid
     * @param  string $defaultText Default Text to be populated in the Editor
     * @return boolean
     */
    public function noteEditor($userid, $editid = NULL, $defaultText = NULL)
    {
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        // $this->loadClass('heading', 'htmlelements');
        $this->loadClass('href', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        if (isset($editid)) {
            $mode = 'editpost';
            // get the relevant post from the editid
            $editparams = $this->objDbBlog->getPostById($editid);
            if (!empty($editparams)) {
                //               print_r($editparams);
                $editparams = $editparams[0];
                $editparams['tags'] = $this->objDbBlog->getPostTags($editid);
            }
        }
        if (!isset($mode)) {
            $mode = NULL;
        }
        if (!isset($editparams)) {
            $editparams = NULL;
        }
        $postform = new form('postadd', $this->uri(array(
            'action' => 'postadd',
            'mode' => $mode,
            'id' => $editparams['id'],
            'postexcerpt' => $editparams['post_excerpt'],
            'postdate' => $editparams['post_date']
        )));
        $pfieldset = $this->newObject('fieldset', 'htmlelements');
        if ($this->getParam('action', 'add')=='postedit') {
            $pFieldSetLabel = $this->objLanguage->languageText('mod_blog_postedit', 'blog');
        } else {
            $pFieldSetLabel = $this->objLanguage->languageText('mod_blog_posthead', 'blog');
        }
        $pfieldset->setLegend(' ' . $pFieldSetLabel . ' ');
        $ptable = $this->newObject('htmltable', 'htmlelements');
        $ptable->cellpadding = 5;
        // post title field
        $ptable->startRow();
        $plabel = new label($this->objLanguage->languageText('mod_blog_posttitle', 'blog') . ':', 'input_posttitle');
        $title = new textinput('posttitle');
        $title->size = 60;
        $postform->addRule('posttitle', $this->objLanguage->languageText("mod_blog_phrase_ptitlereq", "blog") , 'required');
        if (isset($editparams['post_title'])) {
            $title->setValue(stripslashes($editparams['post_title']));
        }
        $ptable->addCell($plabel->show());
        $ptable->addCell($title->show());
        $ptable->endRow();
        // post category field
        // dropdown of cats
        $ptable->startRow();
        $pdlabel = new label($this->objLanguage->languageText('mod_blog_postcat', 'blog') . ':', 'input_cat');
        $pDrop = new dropdown('cat');
        if (isset($editparams['post_category'])) {
        	// var_dump($editparams);
        	// category voodoo
            if ($editparams['post_category'] == '0') {
                $epdisp = $this->objLanguage->languageText("mod_blog_word_default", "blog");
            } else {
                $mapcats = $this->objDbBlog->mapKid2Parent($editparams['post_category']);
                if (isset($mapcats[0])) {
                    $epdisp = $mapcats[0]['cat_name'];
                }
            }
            $pDrop->addOption($editparams['post_category'], $epdisp);
            $pDrop->setSelected($editparams['post_category']);
            $pDrop->addOption(1, $this->objLanguage->languageText("mod_blog_defcat", "blog"));
        } else {
            $pDrop->addOption(0, $this->objLanguage->languageText("mod_blog_defcat", "blog"));
        }
        $pcats = $this->objDbBlog->getAllCats($userid);
        foreach($pcats as $adds) {
            $pDrop->addOption($adds['id'], stripslashes($adds['cat_name']));
        }
        $ptable->addCell($pdlabel->show());
        $ptable->addCell($pDrop->show());
        $ptable->endRow();
        // post status dropdown
        $ptable->startRow();
        $pslabel = new label($this->objLanguage->languageText('mod_blog_poststatus', 'blog') . ':', 'input_status');
        $psDrop = new dropdown('status');
        $psDrop->addOption(0, $this->objLanguage->languageText("mod_blog_published", "blog"));
       	$psDrop->addOption(1, $this->objLanguage->languageText("mod_blog_draft", "blog"));
        if (isset($editparams['post_status']) && $editparams['post_status'] == 1) {
        	$psDrop->setSelected(1);
        } else {
       		$psDrop->setSelected(0);
        }
        $ptable->addCell($pslabel->show());
        $ptable->addCell($psDrop->show());
        $ptable->endRow();
        // allow comments?
        $this->loadClass("checkbox", "htmlelements");
        $commentsallowed = new checkbox('commentsallowed', $this->objLanguage->languageText("mod_blog_word_yes", "blog") , true);
        $ptable->startRow();
        $pcomlabel = new label($this->objLanguage->languageText('mod_blog_commentsallowed', 'blog') . ':', 'input_commentsallowed');
        $ptable->addCell($pcomlabel->show());
        $ptable->addCell($commentsallowed->show());
        $ptable->endRow();
        // Sticky post?
        $this->loadClass("checkbox", "htmlelements");
        if (isset($editparams['stickypost']) && $editparams['stickypost'] == 1) {
            $sticky = new checkbox('stickypost', 1, TRUE);
        } else {
            $sticky = new checkbox('stickypost', 1, FALSE);
        }
        $ptable->startRow();
        $pstickylabel = new label($this->objLanguage->languageText('mod_blog_stickypost', 'blog') . ':', 'input_stickypost');
        $ptable->addCell($pstickylabel->show());
        $ptable->addCell($sticky->show());
        $ptable->endRow();
        // show as a PDF?
        $this->loadClass("checkbox", "htmlelements");
        if (isset($editparams['showpdf']) && $editparams['showpdf'] == 1) {
            $showpdf = new checkbox('showpdf', 1, TRUE);
        } else {
            $showpdf = new checkbox('showpdf', 1, FALSE);
        }
        $ptable->startRow();
        $showpdflabel = new label($this->objLanguage->languageText('mod_blog_showpdf', 'blog') . ':', 'input_showpdf');
        $ptable->addCell($showpdflabel->show());
        $ptable->addCell($showpdf->show());
        $ptable->endRow();
        // post excerpt
        $this->loadClass('textarea', 'htmlelements');
        $pexcerptlabel = new label($this->objLanguage->languageText('mod_blog_postexcerpt', 'blog') . ':', 'input_postexcerpt');
        $pexcerpt = new textarea('postexcerpt');
        $pexcerpt->setName('postexcerpt');
        $ptable->startRow();
        if (isset($editparams['post_excerpt'])) {
            $pexcerpt->setcontent(stripslashes($editparams['post_excerpt']));
            // nl2br - htmmlentittes +

        }
        $ptable->addCell($pexcerptlabel->show());
        $ptable->addCell($pexcerpt->show());
        $ptable->endRow();
        // post content
        $pclabel = new label($this->objLanguage->languageText('mod_blog_pcontent', 'blog') . ':', 'input_postcontent');
        $pcon = $this->newObject('htmlarea', 'htmlelements');
        $pcon->setName('postcontent');
        $pcon->height = 400;
        $pcon->width = '100%';
        $pcon->setDefaultToolbarSet();
        if (isset($editparams['post_content'])) {
            $pcon->setcontent((stripslashes(($editparams['post_content']))));
        } else if (!is_null($defaultText)) {
            $pcon->setcontent($defaultText);
        }
        $ptable->startRow();
        $ptable->addCell($pclabel->show());
        $ptable->addCell($pcon->show());
        $ptable->endRow();
        // tags input box
        $ptable->startRow();
        $tlabel = new label($this->objLanguage->languageText('mod_blog_tags', 'blog') . ':', 'input_tags');
        $tags = new textinput('tags');
        $tags->size = '65%';
        if (isset($editparams['tags'])) {
            // this thing should be an array, so we need to loop thru and create the comma sep list again
            $tagstr = NULL;
            foreach($editparams['tags'] as $taglets) {
                $tagstr.= $taglets['meta_value'] . ",";
            }
            $tags->setValue(stripslashes($tagstr));
        }
        $ptable->addCell($tlabel->show());
        $ptable->addCell($tags->show());
        $ptable->endRow();
        // CC licence
        $lic = $this->getObject('licensechooser', 'creativecommons');
        $ptable->startRow();
        $pcclabel = new label($this->objLanguage->languageText('mod_blog_cclic', 'blog') . ':', 'input_cclic');
        $ptable->addCell($pcclabel->show());
        if (isset($editparams['post_lic'])) {
            $lic->defaultValue = $editparams['post_lic'];
        }
        $ptable->addCell($lic->show());
        $ptable->endRow();

        // geoTagging map part
        // only show this is simplemap module is installed - we need the gmaps api key stored there
        $this->objModules = $this->getObject('modules', 'modulecatalogue');
        if($this->objModules->checkIfRegistered('simplemap') && $this->objModules->checkIfRegistered('georss'))
        {
        	$this->objHead = $this->getObject('htmlheading', 'htmlelements');
        	$this->objHead->type = 3;
        	$this->objHead->str = $this->objLanguage->languageText("mod_blog_geotagposts", "blog");
        	$gmapsapikey = $this->sysConfig->getValue('mod_simplemap_apikey', 'simplemap');
        	$css = '<style type="text/css">
        #map {
            width: 100%;
            height: 350px;
            border: 1px solid black;
            background-color: grey;
        }
    </style>';

        	$google = "<script src=\"/blog/resources/common'http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$gmapsapikey."' type=\"text/javascript\"></script>";
        	$olsrc = $this->getJavascriptFile('lib/OpenLayers.js','georss');
        	$js = "<script type=\"text/javascript\">
        var lon = 5;
        var lat = 40;
        var zoom = 17;
        var map, layer, drawControl, g;

        OpenLayers.ProxyHost = \"/proxy/?url=\";
        function init(){
            g = new OpenLayers.Format.GeoRSS();
            map = new OpenLayers.Map( 'map' , { controls: [] , 'numZoomLevels':20 });
            var hybrid = new OpenLayers.Layer.Google( \"Google Hybrid Map\" , {type: G_HYBRID_MAP, 'maxZoomLevel':18} );
            var wmsLayer = new OpenLayers.Layer.WMS( \"Public WMS\",
                \"http://labs.metacarta.com/wms/vmap0?\", {layers: 'basic'});

            map.addLayers([wmsLayer, hybrid]);

            map.addControl(new OpenLayers.Control.MousePosition());
            map.addControl( new OpenLayers.Control.MouseDefaults() );
            map.addControl( new OpenLayers.Control.LayerSwitcher() );
            map.addControl( new OpenLayers.Control.PanZoomBar() );

            map.setCenter(new OpenLayers.LonLat(0,0), 2);

            map.events.register(\"click\", map, function(e) {
                var lonlat = map.getLonLatFromViewPortPx(e.xy);
                OpenLayers.Util.getElement(\"input_geotag\").value = lonlat.lat + \",  \" +
                                          + lonlat.lon
            });

        }
    </script>";

        	// add the lot to the headerparams...
        	$this->appendArrayVar('headerParams', $css.$google.$olsrc.$js);
        	$this->appendArrayVar('bodyOnLoad', "init();");
        	// add the table row with the map in it.
        	// a heading
        	$ptable->startRow();
        	$ptable->addCell('');
        	$ptable->addCell($this->objHead->show()); // , '100%', $valign="top", 'center', null, 'colspan=2','0');
        	$ptable->endRow();
            // and now the map
        	$ptable->startRow();
        	$gtlabel = new label($this->objLanguage->languageText('mod_blog_geotag', 'blog') . ':', 'input_geotags');
        	$gtags = '<div id="map"></div>';
        	$geotags = new textinput('geotag', NULL, NULL, '100%');
        	if (isset($editparams['geolat']) && isset($editparams['geolon'])) {
            	$geotags->setValue($editparams['geolat'].", ".$editparams['geolon']);
        	}
        	$ptable->addCell($gtlabel->show());
        	$ptable->addCell($gtags.$geotags->show());
        	$ptable->endRow();
        }

        $ts = new textinput('post_ts', NULL, 'hidden', NULL);
        // $ts->extra = "hidden";
        if (isset($editparams['post_ts'])) {
            $ts->setValue($editparams['post_ts']);
        }
        $postform->addRule('posttitle', $this->objLanguage->languageText("mod_blog_phrase_ptitlereq", "blog") , 'required');
        // $postform->addRule('postcontent', $this->objLanguage->languageText("mod_blog_phrase_pcontreq", "blog"),'required');
        $pfieldset->addContent($ptable->show());
        $postform->addToForm($pfieldset->show() . $ts->show());
        $this->objPButton = &new button($this->objLanguage->languageText('mod_blog_word_post', 'blog'));
        $this->objPButton->setIconClass("save");
        $this->objPButton->setValue($this->objLanguage->languageText('mod_blog_word_post', 'blog'));
        $this->objPButton->setToSubmit();
        // $postform->addToForm($this->objPButton->show());
        // $postform = $postform->show();
        // return $postform;
        // check box Added By Irshaad Hoosain
        $this->loadClass('checkbox', 'htmlelements');
        $siteblogcheckbox = new checkbox('checkbox');
        // ,'unassign',false);
        $siteblogcheckbox = $siteblogcheckbox->show();
        // IS Admin
        $siteblogcheckbox = new checkbox('checkbox');
        // ,'unassign',false);
        $siteblogcheckbox = $siteblogcheckbox->show();
        // IS Admin
        $this->objUser = $this->getObject('user', 'security');
        if ($this->objUser->inAdminGroup($userid, 'Site Admin')) {
            $postform->addToForm('Site Blog' . ' ' . $siteblogcheckbox);
        } else {
        }
        $postform->addToForm('<br>' . ' ' . '</br>');
        $postbutton_text = $this->objPButton->show();
        $postform->addToForm($postbutton_text);
        $postform = $postform->show();
        return $postform;
    }

}

?>
