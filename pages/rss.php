<?php
/********************************************************************************
 * File: rss.php
 * Desc: Returns RSS v0.92 feed for last 20 flickr photos.
 * 
 * Change   Date        By          Description
 * 1        05/13/2007  mcarruth    Created
 ********************************************************************************/
header('Content-type: application/rss+xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
// echo "<rss version='0.92'>";
echo "<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>";

require_once('photo.php');
require_once('user.php');

$photos = $photo->get_photos(null, null, null, 10, 1);          	
$userInfo = $user->get_info();

//
// Boiler-plate RSS info
//
echo "
<channel>
   <title>" . htmlspecialchars(SITE_TITLE , ENT_QUOTES) . "</title>
   <link>" . htmlspecialchars(SITE_URL, ENT_QUOTES) . "</link>
   <description>" . htmlspecialchars(SITE_DESCRIPTION, ENT_QUOTES) . "</description>
   <pubDate>" . date(DATE_RFC2822) . "</pubDate>
   <lastBuildDate>" . date(DATE_RFC2822) . "</lastBuildDate>
   <atom:link href=\"" . SITE_URL ."/index.php?type=rss\" rel=\"self\" type=\"application/rss+xml\" />
   <image>
      <url>http://farm" . $userInfo['iconfarm'] . ".static.flickr.com/" . $userInfo['iconserver']. "/buddyicons/" . $userInfo['nsid'] . ".jpg</url>
      <title>" . htmlspecialchars(SITE_TITLE, ENT_QUOTES) . "</title>
      <link>" . htmlspecialchars(SITE_URL, ENT_QUOTES) . "</link>
   </image>";

//
// Generate items for the last 10 photos
//
foreach ($photos['photo'] as $p) {
    $date = $photo->get_dateposted( $p['id'] );
    $rssdate = $photo->get_dateposted( $p['id'], DATE_RFC2822 );
    $photoURL = htmlspecialchars("<a href='" . SITE_URL . "/index.php?photoId=" . $p['id'] . "'>");
    $photoURL .= htmlspecialchars($photo->get_img($p['id'], "Medium"));
    $photoURL .= htmlspecialchars("</a>");

   echo "<item>";
   echo "<title>" . $photo->get_title( $p['id'] ) . "</title>";
   echo "<link>" . SITE_URL . "/index.php?photoId=" . $p['id'] . "</link>";
   echo "<description>" . $photoURL . "&lt;p&gt;" . $photo->get_description( $p['id'] ) . "&lt;/p&gt;&lt;p&gt;" . $date . "&lt;/p&gt;</description>";
   echo "<guid>" . SITE_URL . "/index.php?photoId=" . $p['id'] . "</guid>";
   echo "<pubDate>" . $rssdate ."</pubDate>";
   echo "</item>";
}

echo "
</channel>
</rss>";

?>
