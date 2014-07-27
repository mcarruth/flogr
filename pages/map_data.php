<?php
header('Content-type: text/xml; charset=utf-8');
$doc = new DOMDocument("1.0");
$doc->formatOutput = true;
$markers = $doc->createElement("markers");
$doc->appendChild($markers);
require_once( 'photo.php' );
$photos = $photo->get_photos_with_geo_data();
foreach ($photos["photo"] as $flickrPhoto) {
    $title = $flickrPhoto["title"];
    $desc = $flickrPhoto["description"];
    $lat = $flickrPhoto["latitude"];
    $lng = $flickrPhoto["longitude"];
    $url = "index.php?photoId={$flickrPhoto['id']}";
    $src = $flickrPhoto["url_t"];
    $height = $flickrPhoto["height_t"];
    $width = $flickrPhoto["width_t"];
    $img = "<img style='border:1px solid #333' src='{$src}' height='{$height}' width='{$width}'/>";

    $marker = $doc->createElement("marker");
    $marker->setAttribute("title", $title);
    $marker->setAttribute("lat", $lat);
    $marker->setAttribute("lng", $lng);
    $marker->setAttribute("url", $url);

    $html = "<a href='{$url}'><div style='color:#333'>{$title}<br/><br/>{$img}<br/><br/>{$desc}</div></a>";
    $marker->setAttribute("html", $html);
    $markers->appendChild($marker);
}
echo $doc->saveXML();
?>