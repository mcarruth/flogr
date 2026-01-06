<?php $p = new Profiler('Map page load', 0, 2); ?>
<?php
require_once( 'photo.php' );
?>
<!-- Leaflet CSS - Free OpenStreetMap -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/leaflet/leaflet.css" />
<script src="<?php echo SITE_URL; ?>/assets/leaflet/leaflet.js"></script>
<script type="text/javascript">
    //<![CDATA[

    function load() {
        $.get("index.php?type=map_data", function(data) {
            try {
                var xml = data;
                var markers = xml.documentElement.getElementsByTagName("marker");

                if (markers.length === 0) {
                    document.getElementById("map").innerHTML = '<div style="padding: 20px; text-align: center;">No geotagged photos found.</div>';
                    return;
                }

                // Create OpenStreetMap
                var firstLat = parseFloat(markers[0].getAttribute("lat"));
                var firstLng = parseFloat(markers[0].getAttribute("lng"));

                console.log('Initializing map at:', firstLat, firstLng);

                // Configure Leaflet icon paths for self-hosted library
                L.Icon.Default.imagePath = '<?php echo SITE_URL; ?>/assets/leaflet/';

                var map = L.map('map').setView([firstLat, firstLng], 8);

                // Add OpenStreetMap tiles (completely free!)
                var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                });

                tileLayer.on('tileerror', function(error) {
                    console.error('Map tile loading error - tiles may not be accessible from your network');
                });

                tileLayer.on('tileload', function() {
                    console.log('Map tiles loading successfully');
                });

                tileLayer.addTo(map);

                // Force map to recalculate size
                setTimeout(function() {
                    map.invalidateSize();
                }, 100);

                // Add markers for each photo
                for (var i = 0; i < markers.length; i++) {
                    var title = markers[i].getAttribute("title");
                    var url = markers[i].getAttribute("url");
                    var html = markers[i].getAttribute("html");
                    var lat = parseFloat(markers[i].getAttribute("lat"));
                    var lng = parseFloat(markers[i].getAttribute("lng"));

                    var marker = L.marker([lat, lng], {title: title}).addTo(map);

                    // Bind popup and click handler
                    marker.bindPopup(html);
                    marker.on('click', function(e) {
                        var clickedUrl = e.target.options.url;
                        if (clickedUrl) {
                            window.location = clickedUrl;
                        }
                    });
                    marker.options.url = url;
                }

                console.log('Map initialized with', markers.length, 'markers');
            } catch (error) {
                console.error('Error loading map:', error);
                document.getElementById("map").innerHTML = '<div style="padding: 20px; text-align: center; color: #cc0000;">Map initialization error. Check browser console for details.</div>';
            }
        }).fail(function(error) {
            console.error('Error fetching map data:', error);
            document.getElementById("map").innerHTML = '<div style="padding: 20px; text-align: center; color: #cc0000;">Failed to load map data.</div>';
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

        // Load OpenStreetMap - No API key needed!
        if (typeof L === 'undefined') {
            document.getElementById("map").innerHTML = '<div style="padding: 20px; text-align: center; color: #cc0000;">Map library failed to load. Please check your internet connection.</div>';
        } else {
            load();
        }
    });
</script>