<?php
/*
 * Map template for simple map to demonstrate a simple Google map api
 * Created on Jan 29, 2007
 * 
 */
?>
<div id="map" style="width: 800px; height: 600px"></div>
 


 
   <noscript><b>JavaScript must be enabled in order for you to use Google Maps.</b> 
      However, it seems JavaScript is either disabled or not supported by your browser. 
      To view Google Maps, enable JavaScript by changing your browser options, and then 
      try again.
    </noscript>

 

    <script type="text/javascript">
    //<![CDATA[
    
    if (GBrowserIsCompatible()) { 

      // A function to create the marker and set up the event window
      // Dont try to unroll this function. It has to be here for the function closure
      // Each instance of the function preserves the contends of a different instance
      // of the "marker" and "html" variables which will be needed later when the event triggers.    
      function createMarker(point,html) {
        var marker = new GMarker(point);
        GEvent.addListener(marker, "click", function() {
          marker.openInfoWindowHtml(html);
        });
        return marker;
      }

      // Display the map, with some controls and set the initial location 
      var map = new GMap2(document.getElementById("map"));
      map.addControl(new GLargeMapControl());
      map.addControl(new GMapTypeControl());
      map.setCenter(new GLatLng(-33.799569,18.364472),6);
    
      // Set up three markers with info windows 
    
      var point = new GLatLng(-33.799569,18.364472);
      var marker = createMarker(point,'Nelson Mandela spent 27 years in prison, <br />most of them on Robben Island, in Table Bay, <br />near Cape Town<br /> <img src="http://www.robben-island.org.za/images/front_ani.gif"><br /><a href="http://en.wikipedia.org/wiki/Robben_Island" target="_blank">Read about Robben Island</a>')
      map.addOverlay(marker);

      var point = new GLatLng(-33.876125,18.573292);
      var marker = createMarker(point,'Cape Town International Airport')
      map.addOverlay(marker);

      var point = new GLatLng(-33.93247761290581,18.9306926);
      var marker = createMarker(point,'Stellenbosh where good wine is made')
      map.addOverlay(marker);

    }
    
    // display a warning if the browser was not compatible
    else {
      alert("Sorry, the Google Maps API is not compatible with this browser");
    }

    //]]>
    </script>