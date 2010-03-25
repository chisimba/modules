<?php
/**
 *
 * General ops class for the PANSA Maps module
 *
 * PHP version 5
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
 * @category  Chisimba
 * @package   pansamaps
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: $
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (! /**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
// end security check


/**
 *
 * Ops class for PANSA Maps
 *
 * @author Paul Scott <pscott@uwc.ac.za>
 * @package pansamaps
 *
 */
class pansaops extends object {

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;
    public $objSysConfig;

    /**
     *
     * Constructor
     *
     * @access public
     *
     */
    public function init() {
        $this->objLanguage = $this->getObject ( 'language', 'language' );
        $this->objFeatureBox = $this->getObject ( 'featurebox', 'navigation' );
        $this->objSysConfig  = $this->getObject ( 'dbsysconfig', 'sysconfig' );
    }
    
    
    /**
	 * Method to render a search form
	 */
	public function searchBox() {
        $this->loadClass('textinput', 'htmlelements');
        $qseekform = new form('qseek', $this->uri(array(
            'action' => 'searchvenues',
        )));
        $qseekform->addRule('keyword', $this->objLanguage->languageText("mod_pansamaps_phrase_searchtermreq", "pansamaps") , 'required');
        $qseekterm = new textinput('keyword');
        $qseekterm->size = 15;
        $qseekform->addToForm($qseekterm->show());
        $this->objsTButton = &new button($this->objLanguage->languageText('word_search', 'system'));
        $this->objsTButton->setValue($this->objLanguage->languageText('word_search', 'system'));
        $this->objsTButton->setToSubmit();
        $qseekform->addToForm($this->objsTButton->show());
        $qseekform = $qseekform->show();
        $objFeatureBox = $this->getObject('featurebox', 'navigation');
        $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_pansamaps_qseek", "pansamaps") , $this->objLanguage->languageText("mod_pansamaps_qseekinstructions", "pansamaps") . "<br />" . $qseekform);

        return $ret;
    }
    
    /**
	 * Method to render an input form
	 */
	public function inputForm() {
        $this->loadClass('textinput', 'htmlelements');
        $iform = new form('iform', $this->uri(array(
            'action' => 'adddata',
        )));
        $iform->addRule('keyword', $this->objLanguage->languageText("mod_pansamaps_phrase_searchtermreq", "pansamaps") , 'required');
        
        $iterm = new textinput('keyword');
        $qseekterm->size = 15;
        $qseekform->addToForm($qseekterm->show());
        $this->objsTButton = &new button($this->objLanguage->languageText('word_search', 'system'));
        $this->objsTButton->setValue($this->objLanguage->languageText('word_search', 'system'));
        $this->objsTButton->setToSubmit();
        $qseekform->addToForm($this->objsTButton->show());
        $qseekform = $qseekform->show();
        $objFeatureBox = $this->getObject('featurebox', 'navigation');
        $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_pansamaps_qseek", "pansamaps") , $this->objLanguage->languageText("mod_pansamaps_qseekinstructions", "pansamaps") . "<br />" . $qseekform);

        return $ret;
    }
    
    
    public function viewLocMap($lat, $lon, $zoom = 15) {
        $gmapsapikey = $this->objSysConfig->getValue('mod_simplemap_apikey', 'simplemap');
        $css = '<link href="http://www.google.com/apis/maps/base.css" rel="stylesheet" type="text/css"></link>';
        
        $google = "<script src=\"http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAq_-zASCRreQq9Xuux802xBTDsDhw_IRCOMb7qVKI55haMJ-cbhQN-EDFOnhw3_hHjRxFUQl29_igPQ\"
            type=\"text/javascript\"></script>
    <script type=\"text/javascript\">
    //<![CDATA[
    
    function createMarker(point,html) {
        var marker = new GMarker(point);
        GEvent.addListener(marker, \"click\", function() {
          marker.openInfoWindowHtml(html);
        });
        return marker;
      }

    function refresh(){
          var map = new GMap2(document.getElementById(\"map\"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        map.setCenter(new GLatLng(-30, 25), 6);

          GDownloadUrl(\"http://127.0.0.1/c3/index.php?module=pansamaps&action=getmapdata\", function(data, responseCode) {
          // To ensure against HTTP errors that result in null or bad data,
          // always check status code is equal to 200 before processing the data
          if(responseCode == 200) {
            var xml = GXml.parse(data);
            var markers = xml.documentElement.getElementsByTagName(\"marker\");
            for (var i = 0; i < markers.length; i++) {
              var tweet = markers[i].getAttribute(\"tweet\");
              var point = new GLatLng(parseFloat(markers[i].getAttribute(\"lat\")), parseFloat(markers[i].getAttribute(\"lng\")));
              var marker = createMarker(point, tweet)
              map.addOverlay(marker);
            }
            
          } else if(responseCode == -1) {
            alert(\"Data request timed out. Please try later.\");
          } else { 
            alert(\"Request resulted in error. Check XML file is retrievable.\");
          }
        });
          window.setTimeout(\"refresh()\", 30000);

    }

    function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById(\"map\"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        map.setCenter(new GLatLng(-30, 25), 6);
        GDownloadUrl(\"http://127.0.0.1/c3/index.php?module=pansamaps&action=getmapdata\", function(data, responseCode) {
          // To ensure against HTTP errors that result in null or bad data,
          // always check status code is equal to 200 before processing the data
          if(responseCode == 200) {
            var xml = GXml.parse(data);
            var markers = xml.documentElement.getElementsByTagName(\"marker\");
            for (var i = 0; i < markers.length; i++) {
              var tweet = markers[i].getAttribute(\"tweet\");
              var point = new GLatLng(parseFloat(markers[i].getAttribute(\"lat\")), parseFloat(markers[i].getAttribute(\"lng\")));
              var marker = createMarker(point, tweet)
              map.addOverlay(marker);
              map.addOverlay(marker);
	    }
          } else if(responseCode == -1) {
	    alert(\"Data request timed out. Please try later.\");
          } else { 
            alert(\"Request resulted in error. Check XML file is retrievable.\");
          }
        });

        // window.setTimeout(\"refresh()\", 30000);

      }
    }

    //]]>
    </script>";
    
        // add the lot to the headerparams...
        $this->appendArrayVar('headerParams', $css.$google);
        $this->appendArrayVar('bodyOnLoad', "load();");
        
        $gtags = '<div id="map" style="width: 768px; height: 768px"></div>';
        return $gtags;
    }
    
}
?>
