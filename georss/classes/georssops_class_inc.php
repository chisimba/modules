<?php

class georssops extends object 
{
	
	public function init()
	{
		$this->objLanguage = $this->getObject('language', 'language');
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
	}
	
	public function setupMap()
	{
		$gmapsapikey = $this->objSysConfig->getValue('mod_simplemap_apikey', 'simplemap');
		$css = '<style type="text/css">
        #map {
            width: 100%;
            height: 350px;
            border: 1px solid black;
            background-color: grey;
        }
    </style>';
		
		$google = "<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$gmapsapikey."' type=\"text/javascript\"></script>";
		$olsrc = $this->getJavascriptFile('lib/OpenLayers.js','georss');
		$js = "<script type=\"text/javascript\">
        var lon = 5;
        var lat = 40;
        var zoom = 17;
        var map, layer, drawControl, g;

        function serialize(feature) {
            feature.attributes = {};
            var name = prompt(\"Title for feature?\");
            feature.attributes['title'] = name;
            var desc = prompt(\"Description for feature?\");
            feature.attributes['description'] = desc;
            var data = g.write(feature.layer.features);
            OpenLayers.Util.getElement(\"gml\").value = data;
        }

        OpenLayers.ProxyHost = \"/proxy/?url=\";
        function init(){
            g = new OpenLayers.Format.GeoRSS();
            map = new OpenLayers.Map( 'map' , { controls: [] , 'numZoomLevels':20 });
            var hybrid = new OpenLayers.Layer.Google( \"Google Hybrid Map\" , {type: G_HYBRID_MAP, 'maxZoomLevel':18} );
            var wmsLayer = new OpenLayers.Layer.WMS( \"Public WMS\", 
                \"http://labs.metacarta.com/wms/vmap0?\", {layers: 'basic'}); 
            var pointLayer = new OpenLayers.Layer.Vector(\"Point Layer\");
            pointLayer.onFeatureInsert = serialize;
            
            map.addLayers([wmsLayer, pointLayer, hybrid]);
      
            map.addControl(new OpenLayers.Control.EditingToolbar(pointLayer));
            map.addControl(new OpenLayers.Control.MousePosition());
            map.addControl( new OpenLayers.Control.MouseDefaults() );
            map.addControl( new OpenLayers.Control.LayerSwitcher() );
            map.addControl( new OpenLayers.Control.PanZoomBar() );
            
            
            var newl = new OpenLayers.Layer.GeoRSS( 'GeoRSS', '".$this->getResourceUri('georss.xml', 'georss')."' );
            map.addLayer(newl);
            var newl2 = new OpenLayers.Layer.GeoRSS( 'My Flickr GeoRSS', '".$this->getResourceUri('flickr.xml', 'georss')."' );
            map.addLayer(newl2);
            
            map.setCenter(new OpenLayers.LonLat(0,0), 2);

        }
    </script>";
		
		// add the lot to the headerparams...
		$this->appendArrayVar('headerParams', $css.$google.$olsrc.$js);
		$this->appendArrayVar('bodyOnLoad', "init();");
		
	}
}
?>