<?php
/*
 * Map template for simple map to demonstrate a simple Google map api
 * Created on Jan 29, 2007
 * 
 */
 //Echo the map layer to the page
 echo $str;
?>
 


 

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
      //map.setCenter(new GLatLng(-33.799669,18.364472),6);
      map.setCenter(new GLatLng(-24.7333333,31.0333333),6);
      
<?php
	//Place the map data here
    echo $myMap;
?>

    }
    
    // display a warning if the browser was not compatible
    else {
      alert("Sorry, the Google Maps API is not compatible with this browser");
    }

    //]]>
    </script>