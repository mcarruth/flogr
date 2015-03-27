<?php $p = new Profiler('Map page load', 0, 2); ?>
<?php
require_once( 'photo.php' );
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    //<![CDATA[

    function load() {
        $.get("index.php?type=map_data", function(data) {
            var xml = data;
            var markers = xml.documentElement.getElementsByTagName("marker");

            var map = new google.maps.Map(document.getElementById("map"), {
                center: new google.maps.LatLng(markers[0].getAttribute("lat"), markers[0].getAttribute("lng")),
                zoom: 8,
                mapTypeId: 'roadmap'
            });

            var infoWindow = new google.maps.InfoWindow({
                maxWidth: 75
            });

            for (var i = 0; i < markers.length; i++) {
                var title = markers[i].getAttribute("title");
                var url = markers[i].getAttribute("url");
                var html = markers[i].getAttribute("html");
                var point = new google.maps.LatLng(
                    parseFloat(markers[i].getAttribute("lat")),
                    parseFloat(markers[i].getAttribute("lng")));

                var marker = new google.maps.Marker({
                    map: map,
                    title: title,
                    position: point
                });
                bindInfoWindow(marker, map, infoWindow, url, html);
            }
        });
    }

    function bindInfoWindow(marker, map, infoWindow, url, html) {
        google.maps.event.addListener(marker, 'click', function() {
            window.location = url;
        });
        google.maps.event.addListener(marker, 'mouseover', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
    }

    //]]>
</script>


<div id="leftcontent"></div>

<div id="centercontent">
    <div id='page_header'>
        <span id='page_title'>
          	Map
        </span>
    </div>

    <div id='page'>

        <!-- Show the photo and link the photo to the previous photo -->
        <div id='thumbnail_container'>
            <div id="map" style="width:730px;height:500px;"></div>
        </div>
    </div>
</div>

<div id="rightcontent"></div>

<script type='text/javascript'>
    $(document).ready(function(){
        $("#menuitem_map").addClass("selected");
        load();
    });
</script>