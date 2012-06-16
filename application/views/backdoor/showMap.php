<!DOCTYPE html >
  <head>
  <style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0px; padding: 0px }
  #map { height: 100% }
</style>

    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Truck GPS Map</title>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src='<?php echo base_url(); ?>js/jquery-1.4.2.min.js'></script>
    <script type="text/javascript">
    //<![CDATA[

    function load() {
      var map;
      
      $.ajax({
	  url: '<?php echo base_url(); ?>index.php/backdoor/createGPSXML/<?php echo $gps_id; ?>',
	  success: function(xml) {
	  	var markers = xml.documentElement.getElementsByTagName("marker");
	  	
	   map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng( parseFloat(markers[0].getAttribute("lat") ), parseFloat(markers[0].getAttribute("lng")) ),
        zoom: 13,
        mapTypeId: 'roadmap'
      });
		
		var path = new Array();
        for (var i = 0; i < markers.length; i++) {
          path.push( new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng"))) );
        }

        
        var truckPath = new google.maps.Polyline({
		    path: path,
		    strokeColor: "#0000FF",
		    strokeOpacity: 1.0,
		    strokeWeight: 2
	    });
	    
	    truckPath.setMap(map);

	  }
    });
    
    }
    //]]>

  </script>

  </head>

  <body onload="load()">
    <div id="map" style="width:100%; height:100%"></div>

  </body>

</html>