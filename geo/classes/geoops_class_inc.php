<?php

/**
 * Geo Helper Class
 *
 * Convenience class for interacting with MongoDB
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
 * @package   geo
 * @author    Paul Scott <pscott209@gmail.com>
 * @copyright 2011 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: mongoops_class_inc.php 19535 2010-10-28 18:22:39Z charlvn $
 * @link      http://avoir.uwc.ac.za/
 * @seealso   http://www.mongodb.org/
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
 * Geo Helper Class
 *
 * Convenience class for interacting with MongoDB.
 *
 * @category  Chisimba
 * @package   geo
 * @author    Paul Scott <pscott209@gmail.com>
 * @copyright 2011 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: mongoops_class_inc.php 19535 2010-10-28 18:22:39Z charlvn $
 * @link      http://avoir.uwc.ac.za/
 * @seealso   http://www.mongodb.org/
 */
class geoops extends object
{

	/**
	 * Instance of the dbsysconfig class of the sysconfig module.
	 *
	 * @access private
	 * @var    object
	 */
	private $objSysConfig;

	private $geowikiBase;

	private $flickrBase;

	/*
	 * Initialises some of the object's properties.
	 *
	 * @access public
	 */
	public function init()
	{
		// Objects
		$this->objSysConfig    = $this->getObject('dbsysconfig', 'sysconfig');
		$this->geowikiBase     = "http://api.wikilocation.org/articles?";
		$this->flickrBase      = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=db8df9a1b93963aed7a5fdea50c718b0&license=cc+by+sa&accuracy=16&";
		$this->objProxy        = $this->getObject('proxyparser', 'utilities');
		$this->objLanguage     = $this->getObject ( 'language', 'language' );
		$this->objConfig       = $this->getObject('altconfig', 'config');
	}

	public function getWikipedia($lon, $lat, $radius=1500) {
		$url = $this->geowikiBase."lat=".$lat."&lng=".$lon."&radius=".$radius;
		$proxyArr = $this->objProxy->getProxy();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if (!empty($proxyArr) && $proxyArr['proxy_protocol'] != '') {
			curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'] . ":" . $proxyArr['proxy_port']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'] . ":" . $proxyArr['proxy_pass']);
		}
		$articlesjson = curl_exec($ch);
		curl_close($ch);

		return json_decode($articlesjson);
	}

	public function getFlickr($lon, $lat, $fradius=0.5) {
		$url = $this->flickrBase."lat=".$lat."&lon=".$lon."&radius=".$fradius."&radius_units=km&format=json&nojsoncallback=1";
		$proxyArr = $this->objProxy->getProxy();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if (!empty($proxyArr) && $proxyArr['proxy_protocol'] != '') {
			curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'] . ":" . $proxyArr['proxy_port']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'] . ":" . $proxyArr['proxy_pass']);
		}
		$flickrjson = curl_exec($ch);
		curl_close($ch);

		return json_decode($flickrjson);
	}

	public function picUploadForm() {
		$ret = NULL;
		$this->loadClass('form', 'htmlelements');
		$objSelectFile = $this->newObject('selectfile', 'filemanager');
		$objSelectFile->restrictFileList = array('jpg', 'jpeg', 'png', 'gif');
		$objSelectFile->name = 'pic';
		$form = new form ('uploadpic', $this->uri(array('action'=>'uploadpic'), 'events'));
		$form->addToForm($objSelectFile->show());
		$button = new button ('submitform', $this->objLanguage->languageText("mod_userregistration_uploadpic", "userregistration"));
		$button->setToSubmit();
		$form->addToForm('<p align="center"><br />'.$button->show().'</p>');
		$ret .= $form->show();
		return $ret;
	}

	public function geoLocationForm($editparams = NULL, $eventform = FALSE) {
		$this->loadClass('form', 'htmlelements');
		$this->objModules = $this->getObject('modules', 'modulecatalogue');
		$ret = NULL;
		$lat = 0;
		$lon = 0;
		$zoom = 2;
		$currLocation = $this->objCookie->get('events_latlon');
		$currloc = explode("|", $currLocation);
		if(!empty($currloc) && isset($currloc[0]) && isset($currloc[1])) {
			$lat = $currloc[0];
			$lon = $currloc[1];
			$zoom = 10;
		}
		if($this->objModules->checkIfRegistered('simplemap') && $this->objModules->checkIfRegistered('georss'))
		{
			$form = new form ('geoloc', $this->uri(array('action'=>'setlocation')));
			$this->loadClass('label', 'htmlelements');
			$this->objHead = $this->getObject('htmlheading', 'htmlelements');
			$this->objHead->type = 3;
			$this->objHead->str = $this->objLanguage->languageText("mod_events_geoposition", "events");
			$gmapsapikey = $this->objSysConfig->getValue('mod_simplemap_apikey', 'simplemap');
			$css = '<style type="text/css">
        #map {
            width: 100%;
            height: 350px;
            border: 1px solid black;
            background-color: white;
        }
    </style>';

			$google = "<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$gmapsapikey."' type=\"text/javascript\"></script>";
			$olsrc = $this->getJavascriptFile('lib/OpenLayers.js','georss');
			$js = "<script type=\"text/javascript\">
        var lon = 5;
        var lat = 40;
        var zoom = 17;
        var map, layer, drawControl, g;

        OpenLayers.ProxyHost = \"/proxy/?url=\";
        function init(){
            g = new OpenLayers.Format.GeoRSS();
            map = new OpenLayers.Map( 'map' , { controls: [] , 'numZoomLevels':20, projection: new OpenLayers.Projection(\"EPSG:900913\"), displayProjection: new OpenLayers.Projection(\"EPSG:4326\") });
            var normal = new OpenLayers.Layer.Google( \"Google Map\" , {type: G_NORMAL_MAP, 'maxZoomLevel':18} );
            var hybrid = new OpenLayers.Layer.Google( \"Google Hybrid Map\" , {type: G_HYBRID_MAP, 'maxZoomLevel':18} );
            
            map.addLayers([normal, hybrid]);

            map.addControl(new OpenLayers.Control.MousePosition());
            map.addControl( new OpenLayers.Control.MouseDefaults() );
            map.addControl( new OpenLayers.Control.LayerSwitcher() );
            map.addControl( new OpenLayers.Control.PanZoomBar() );

            map.setCenter(new OpenLayers.LonLat($lon,$lat), $zoom);

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
			$ptable = $this->newObject('htmltable', 'htmlelements');
			$ptable->cellpadding = 3;
			// a heading
			$ptable->startRow();
			//$ptable->addCell('');
			$ptable->addCell($this->objHead->show()); // , '100%', $valign="top", 'center', null, 'colspan=2','0');
			$ptable->endRow();
			// and now the map
			$ptable->startRow();
			$gtlabel = new label($this->objLanguage->languageText("mod_events_geoposition", "events") . ':', 'input_geotags');
			$gtags = '<div id="map"></div>';
			$geotags = new textinput('geotag', NULL, NULL, '100%');
			if (isset($editparams['geolat']) && isset($editparams['geolon'])) {
				$geotags->setValue($editparams['geolat'].", ".$editparams['geolon']);
			}
			//$ptable->addCell($gtlabel->show());
			$ptable->addCell($gtags.$geotags->show());
			$ptable->endRow();

			$fieldset = $this->newObject('fieldset', 'htmlelements');
			$fieldset->legend = '';
			$fieldset->contents = $ptable->show();
			$button = new button ('submitform', $this->objLanguage->languageText("mod_events_setlocation", "events"));
			$button->setToSubmit();
			$form->addToForm($fieldset->show().'<p align="center"><br />'.$button->show().'</p>');
			$ret .= $form->show();
		}
		else {
			$ret .= "Map cannot be shown";
		}

		return $ret;
	}
	
	public function getHTML5Loc() {
		$form = new form ('geoloc', $this->uri(array('action'=>'setlocation')));
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $url = $this->uri(array('action' => 'setloc'), 'geo');
        $url = str_replace('&amp;', '&', $url);
	    $js = '<script type="text/javascript">
               if (navigator.geolocation) {
                   navigator.geolocation.getCurrentPosition(function(position) {  
                   var url="'.$url.'&lat=" + position.coords.latitude + "&lon=" + position.coords.longitude;
                   document.location.href = url;
                   });
               }
               </script>';
        // add the lot to the headerparams...
		$this->appendArrayVar('headerParams', $js);	
		return '<div id="pos"></div>';
	}
	
    public function makeMapMarkers($dataObj, $lat, $lon) {
        // build up a set of markers for a google map
        $head = "<markers>";
        $body = NULL;
        foreach($dataObj as $data) {
            if($data->latitude == "" || $data->longitude == "") {
                continue;
            }
            else {
                $body .= '<marker lat="'.$data->latitude.'" lng="'.$data->longitude.'" info="'.htmlentities($data->name).'" />';
            }
        }
        $body .= '<marker lat="'.$lat.'" lng="'.$lon.'" info="'.htmlentities("You are here!").'" />';
        $tail = "</markers>";
        $data = $head.$body.$tail;
        $path = $this->objConfig->getModulePath()."geo/markers.xml";
        if(!file_exists($path)) {
            touch($path);
            chmod($path, 0777);
        }
        else {
            unlink($path);
            touch($path);
            chmod($path, 0777);
        }
        file_put_contents($path, $data);
        
        return $data;
    }
    
    public function makeGMap($lat, $lon) {
    	$gmapsapikey = $this->objSysConfig->getValue('mod_simplemap_apikey', 'simplemap');
    	$uri = $this->uri('');
        //$css = '<link href="http://www.google.com/apis/maps/base.css" rel="stylesheet" type="text/css"></link>';
        $css = '<style type="text/css">  
            html, body {
                font: 100%/1.5 Arial; 
            }
        </style>';
        $this->appendArrayVar('headerParams', $css);
        $google = "<script src=\"http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=$gmapsapikey\"
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

    function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById(\"map\"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        map.setCenter(new GLatLng($lat, $lon), 14);
        GDownloadUrl(\"packages/geo/markers.xml\", function(data, responseCode) {
          // To ensure against HTTP errors that result in null or bad data,
          // always check status code is equal to 200 before processing the data
          if(responseCode == 200) {
            var xml = GXml.parse(data);
            var markers = xml.documentElement.getElementsByTagName(\"marker\");
            for (var i = 0; i < markers.length; i++) {
              var info = markers[i].getAttribute(\"info\");
              var point = new GLatLng(parseFloat(markers[i].getAttribute(\"lat\")), parseFloat(markers[i].getAttribute(\"lng\")));
              var marker = createMarker(point, info)
              map.addOverlay(marker);
              map.addOverlay(marker);
	    }
          } else if(responseCode == -1) {
	    alert(\"Data request timed out. Please try later.\");
          } else { 
            alert(\"Request resulted in error. Check XML file is retrievable.\");
          }
        });
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
