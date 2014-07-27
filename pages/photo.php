<?php

require_once( 'page.php' );

class Flogr_Photo extends Flogr_Page {

    var $info;
    var $licenses;
    var $comments;
    var $exif;

    function get_photos(
    $tags = null, $sort = null, $extras = null, $perPage = null, $page = null) {
        $p = new Profiler();
        $photoSearchParams = array(
            "user_id" => FLICKR_USER_ID,
            "group_id" => FLICKR_GROUP_ID,
            "tags" => $tags ? $tags : $this->paramTags,
            "sort" => $sort ? $sort : $this->paramSort,
            "extras" => $extras ? $extras : FLOGR_PHOTO_EXTRAS,
            "per_page" => $perPage ? $perPage : $this->paramPerPage,
            "page" => $page ? $page : $this->paramPage);

        $this->photoList = $this->phpFlickr->photos_search($photoSearchParams);
        return $this->photoList;
    }

    function get_photo($photoId = null) {
        $p = new Profiler();
        $photoId = $photoId ? $photoId : $this->paramPhotoId;

        if (!$this->photoList || $this->photoList["photo"][0]["id"] != $photoId) {
            if ($photoId) {
                /*
                 * If passed a photoId use flickr.photos.getInfo to get the photo's
                 * title and date taken, then pass those values on to flickr.photos.search
                 * to get the base $photo properties
                 */
                $this->info = $this->get_photo_info($photoId);
                $userId = $this->info["owner"]["nsid"];
                $photoSearchParams = array(
                    "user_id" => $userId,
                    "group_id" => FLICKR_GROUP_ID,
                    "min_taken_date" => $this->info["dates"]["taken"] . ":00",
                    "max_taken_date" => $this->info["dates"]["taken"] . ":59",
                    "sort" => $this->paramSort,
                    "extras" => FLOGR_PHOTO_EXTRAS,
                    "per_page" => 1,
                    "page" => $this->paramPage);

                $this->photoList = $this->phpFlickr->photos_search($photoSearchParams);
            } else {
                // Get the first photo from the user and/or group using the defaults.
                $this->photoList = $this->photoList ? $this->photoList : $this->get_photos(null, null, null, 1, null);
            }
        }
        
        return $this->photoList["photo"][0];
    }

    function photos(
    $userId = null, $tags = null, $sort = null, $extras = null, $perPage = null, $page = null) {
        $p = new Profiler();
        echo $photos = $this->get_photos($userId, $tags, $sort, $extras, $perPage, $page);
    }

    function get_photos_with_geo_data(
    $tags = null, $sort = null, $extras = null, $perPage = null, $page = null) {
        $p = new Profiler();
        $photoSearchParams = array(
            "has_geo" => "1",
            "user_id" => FLICKR_USER_ID,
            "group_id" => FLICKR_GROUP_ID,
            "tags" => $tags ? $tags : $this->paramTags,
            "sort" => $sort ? $sort : $this->paramSort,
            "extras" => $extras ? $extras : FLOGR_PHOTO_EXTRAS);
        $this->photoList = $this->phpFlickr->photos_search($photoSearchParams);
        return $this->photoList;
    }

    function get_user_favorite_photos(
    $userId = null, $extras = null, $perPage = null, $page = null) {
        $p = new Profiler();
        $this->photoList = $this->phpFlickr->favorites_getPublicList(
                        $user ? $user : FLICKR_USER_ID,
                        $extras ? $extras : FLOGR_PHOTO_EXTRAS,
                        $perPage ? $perPage : $this->paramPerPage,
                        $page ? $page : $this->paramPage);
        return $this->photoList;
    }

    function user_favorite_photos(
    $userId = null, $extras = null, $perPage = null, $page = null) {
        $p = new Profiler();
        echo $this->get_user_favorite_photos($userId, $extras, $perPage, $page);
    }

    function get_user_interestingness_photos(
    $date = null, $extras = null, $perPage = null, $page = null) {
        $p = new Profiler();
        $this->photoList = $this->phpFlickr->interestingness_getList(
                        $date,
                        $extras ? $extras : FLOGR_PHOTO_EXTRAS,
                        $perPage ? $perPage : $this->paramPerPage,
                        $page ? $page : $this->paramPage);
        return $this->photoList;
    }

    function user_interestingness_photos(
    $date = null, $extras = null, $perPage = null, $page = null) {
        $p = new Profiler();
        echo $this->get_user_interestingness_photos($date, $extras, $perPage, $page);
    }

    function get_photo_id() {
        $p = new Profiler();
        if ($this->paramPhotoId) {
            return $this->paramPhotoId;
        } else if ($this->photoList) {
            return $this->photoList["photo"][0]["id"];
        } else {
            $photo = $this->get_photo();
            return $photo["id"];
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @return unknown
     */
    function get_photo_info($photoId = null) {
        $p = new Profiler();
        $photoId = $photoId ? $photoId : $this->get_photo_id();
        if (!$this->info || !$this->info["id"] != $photoId) {
            $this->info = $this->phpFlickr->photos_getInfo($photoId);
        }
        return $this->info;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @return unknown
     */
    function get_username($photoId = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        return $photo["ownername"];
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     */
    function username($photoId = null) {
        $p = new Profiler();
        echo $this->get_username($photoId);
    }

    function get_userId($photoId = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        return $photo["owner"];
    }

    function get_userlink($photoId = null) {
        $p = new Profiler();
        return "http://www.flickr.com/people/{$this->get_userId($photoId)}/";
    }

    function userlink($photoId = null, $inner = null) {
        $p = new Profiler();
        $inner = $inner ? $inner : $this->get_username($photoId);
        echo "<a href='{$this->get_userlink($photoId)}'>{$inner}</a>";
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @return unknown
     */
    function get_realname($photoId = null) {
        $p = new Profiler();
        $info = $this->get_photo_info($photoId);
        return $info['owner']['realname'];
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     */
    function realname($photoId = null) {
        $p = new Profiler();
        echo $this->get_realname($photoId);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @return unknown
     */
    function get_location($photoId = null) {
        $p = new Profiler();
        $info = $this->get_photo_info($photoId);
        return $info['owner']['location'];
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     */
    function location($photoId = null) {
        $p = new Profiler();
        echo $this->get_location($photoId);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $this->phpFlickrormat
     * @return unknown
     */
    function get_dateposted($photoId = null, $format = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        $format = $format ? $format : FLOGR_DATE_FORMAT;
        return date($format, $photo["dateupload"]);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $this->phpFlickrormat
     */
    function dateposted($photoId = null, $format = null) {
        $p = new Profiler();
        echo $this->get_dateposted($photoId, $format);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $this->phpFlickrormat
     * @return unknown
     */
    function get_datetaken($photoId = null, $format = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        $format = $format ? $format : FLOGR_DATE_FORMAT;
        return date($format, $photo["datetaken"]);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $this->phpFlickrormat
     */
    function datetaken($photoId = null, $format = null) {
        $p = new Profiler();
        echo $this->get_datetaken($photoId, $format);
    }

    function get_exif($photoId = null) {
        $p = new Profiler();
        $photoId = $photoId ? $photoId : $this->get_photo_id();

        if (!$this->exif) {
            $this->exif = $this->phpFlickr->photos_getExif($photoId);
        }

        return $this->exif;
    }

    function exif($photoId = null) {
        $p = new Profiler();
        $exif = $this->get_exif($photoId);
        $exifData = null;
        if ($exif['exif']) {
            foreach ($exif['exif'] as $exifItem) {
                if (stristr(FLOGR_EXIF, $exifItem['label']) && !stristr($exifData, $exifItem['label'])) {
                    $exifData .= "<tr>";
                    $exifData .= "<td>" . $exifItem['label'] . "</td>";
                    if ($exifItem['clean']) {
                        $exifData .= "<td>" . $exifItem['clean'] . "</td>";
                    } else {
                        $exifData .= "<td>" . $exifItem['raw'] . "</td>";
                    }
                    $exifData .= "</tr>";
                }
            }
            echo "<table>$exifData</table>";
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @return unknown
     */
    function get_is_favorite($photoId = null, $truetext = 'Y', $falsetext = 'N') {
        $p = new Profiler();
        $info = $this->get_photo_info($photoId);
        if ($info['isfavorite']) {
            return $truetext;
        } else {
            return $falsetext;
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $truetext
     * @param unknown_type $this->phpFlickralsetext
     */
    function is_favorite($photoId = null, $truetext = 'Y', $falsetext = 'N') {
        $p = new Profiler();
        echo $this->get_is_favorite($photoId, $truetext, $falsetext);
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function get_licenses() {
        $p = new Profiler();
        if (!$this->licenses) {
            $this->licenses = $this->phpFlickr->photos_licenses_getInfo();
        }

        return $this->licenses;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @return unknown
     */
    function get_license_name($photoId = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        $licenses = $this->get_licenses();
        return $licenses[$photo["license"]]["name"];
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     */
    function license_name($photoId = null) {
        $p = new Profiler();
        echo $this->get_license_name($photoId);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $inner
     * @return unknown
     */
    function get_license_link($photoId = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        $licenses = $this->get_licenses();
        return $licenses[$photo["license"]]["url"];
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $inner
     */
    function license_link($photoId = null, $inner = null) {
        $p = new Profiler();
        $inner = $inner ? $inner : $this->get_license_name($photoId);
        echo "<a href='{$this->get_license_link($photoId)}'>{$inner}</a>";
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @return unknown
     */
    function get_title($photoId = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        return htmlspecialchars($photo["title"], ENT_QUOTES);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     */
    function title($photoId = null) {
        $p = new Profiler();
        echo $this->get_title($photoId);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @return unknown
     */
    function get_description($photoId = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        return htmlspecialchars($photo["description"], ENT_QUOTES);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     */
    function description($photoId = null) {
        $p = new Profiler();
        echo $this->get_description($photoId);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $inner
     * @return unknown
     */
    function get_photopage_link($photoId = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        return "http://www.flickr.com/photos/{$photo['owner']}/{$photo['id']}/";
    }

    function get_permalink($photoId = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        return SITE_URL . "/index.php?photoId={$this->get_photo_id($photoId)}";
    }

    function permalink($photoId = null, $inner = null) {
        $p = new Profiler();
        $inner = $inner ? $inner : $this->get_permalink($photoId);
        ;
        echo "<a href='{$this->get_permalink($photoId)}'>{$inner}</a>";
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $inner
     */
    function photopage_link($photoId = null, $inner = null) {
        $p = new Profiler();
        $inner = $inner ? $inner : $this->get_photopage_link($photoId);
        ;
        echo "<a href='{$this->get_photopage_link($photoId)}'>{$inner}</a>";
    }

    function get_views_count($photoId = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        return $photo["views"];
    }

    function views_count($photoId = null) {
        $p = new Profiler();
        echo $this->get_views_count();
    }

    function get_comments($photoId = null) {
        $p = new Profiler();
        $photoId = $photoId ? $photoId : $this->get_photo_id();

        if (!$this->comments) {
            $this->comments = $this->phpFlickr->photos_comments_getList($photoId);
        }

        return $this->comments;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @return unknown
     */
    function get_comments_count($photoId = null) {
        $p = new Profiler();
        $comments = $this->get_comments($photoId);
        return count($comments["comment"]);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     */
    function comments_count($photoId = null) {
        $p = new Profiler();
        echo $this->get_comments_count($photoId);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $before
     * @param unknown_type $sep
     * @param unknown_type $after
     * @param unknown_type $commentLink
     * @param unknown_type $buddyIcon
     * @return unknown
     */
    function get_comments_list(
    $photoId = null, $before = '<li>', $sep = ' says:<br/>', $after = '</li>', $commentLink = 'true', $buddyIcon = 'true') {
        $p = new Profiler();
        $comments = $this->get_comments($photoId);
        if ($comments['comment']) {
            $comment_list = null;
            foreach ($comments['comment'] as $comment) {
                $commentAuthor = "<a href='{$comment['permalink']}'><b>{$comment['authorname']}</b></a>";
                $commentText = $comment['_content'];
                $commentDate = "<br/><small>Posted on: " . date(FLOGR_DATE_FORMAT, $comment['datecreate']) . "</small>";
                $comment_list .= $before . $commentAuthor . $sep . $commentText . $commentDate . $after;
            }
        }

        if ($commentLink) {
            $comment_list .= $this->get_photopage_link($photoId, "<br/>Leave a comment");
        }


        return $comment_list;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $before
     * @param unknown_type $sep
     * @param unknown_type $after
     * @param unknown_type $commentLink
     * @param unknown_type $buddyIcon
     */
    function comments_list($photoId = null, $before = '<li>', $sep = ' says:<br/>', $after = '</li>', $commentLink = 'true', $buddyIcon = 'true') {
        $p = new Profiler();
        echo $this->get_comments_list($photoId, $before, $sep, $after, $commentLink, $buddyIcon);
    }

    /**
     * Enter description here ...
     * @param unknown_type $photoId
     * @param unknown_type $text
     */
    function comment_add($photoId = null, $text = null) {
        $p = new Profiler();

        $photoId = $photoId ? $photoId : $this->get_photo_id();

        $this->phpFlickr->photos_comments_addComment($photoId, $text);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $before
     * @param unknown_type $after
     * @return unknown
     */
    function get_tags_list($photoId = null, $before = '<li>', $after = '</li>') {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        $tok = strtok($photo["tags"], " ");
        $tag_list = "";
        while ($tok !== false) {
            $tag_list .= "{$before}<a href='index.php?type=recent&tags={$tok}'>{$tok}</a>{$after}";
            $tok = strtok(" ");
        }

        return $tag_list;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $before
     * @param unknown_type $after
     */
    function tags_list($photoId = null, $before = '<li>', $after = '</li>') {
        $p = new Profiler();
        echo $this->get_tags_list($photoId, $before, $after);
    }

    function get_img_src($photoId = null, $quality = FLOGR_PHOTO_QUALITY, $scaleSize = null) {
        $photo = $this->get_photo($photoId);

        switch (strtolower($quality)) {
            case "square":
                $src = $photo["url_sq"];
                $height = $photo["height_sq"];
                $width = $photo["width_sq"];
                break;
            case "thumbnail":
                $src = $photo["url_t"];
                $height = $photo["height_t"];
                $width = $photo["width_t"];
                break;
            case "small":
                $src = $photo["url_s"];
                $height = $photo["height_s"];
                $width = $photo["width_s"];
                break;
            case "medium640":
                if ( $photo["url_z"] ) {
                    $src = $photo["url_z"];
                    $height = $photo["height_z"];
                    $width = $photo["width_z"];
                } else {
                    $src = $photo["url_m"];
                    $height = $photo["height_m"];
                    $width = $photo["width_m"];
                }
                break;
            case "medium":
                $src = $photo["url_m"];
                $height = $photo["height_m"];
                $width = $photo["width_m"];
                break;
            case "large":
                $src = $photo["url_l"];
                $height = $photo["height_l"];
                $width = $photo["width_l"];
                break;
            default: // Original or custom size
                $src = $photo["url_o"];
                $height = $photo["height_o"];
                $width = $photo["width_o"];
                break;
        }

        if ($scaleSize) {
            if ($width > $height) {
                // set width to scaleSize and scale height
                $height *= $scaleSize / $width;
                $width = $scaleSize;
            } else if ($height > $width) {
                // set height to scaleSize and scale width
                $width *= $scaleSize / $height;
                $height = $scaleSize;
            }
        }

        if ($width > FLOGR_MAIN_PHOTO_SIZE) {
            // set width to scaleSize and scale height
            $scaleSize = FLOGR_MAIN_PHOTO_SIZE;
            $height *= $scaleSize / $width;
            $width = $scaleSize;
        }

        return array("url" => $src, "height" => $height, "width" => $width);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $size
     * @return unknown
     */
    function get_img($photoId = null, $quality = FLOGR_PHOTO_QUALITY, $scaleSize = null) {
        $p = new Profiler();
        $src = $this->get_img_src($photoId, $quality, $scaleSize);
        $url = $src["url"];
        $height = $src["height"];
        $width = $src["width"];
        $title = $this->get_title($photoId);
        $desc = $this->get_description($photoId);

        return "<img class='photo' src={$url} height='{$height}' width='{$width}' title='{$title}' rel='{$desc}'/>";
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $photoId
     * @param unknown_type $quality
     * @param unknown_type $scaleSize
     */
    function img($photoId = null, $quality = FLOGR_PHOTO_QUALITY, $scaleSize = null) {
        $p = new Profiler();
        echo $this->get_img($photoId, $quality, $scaleSize);
    }

    function add_comment($id = null, $comment = null) {
        $p = new Profiler();
        if ($id == null || $comment == null)
            return;
        $this->phpFlickr->photos_comments_addComment($id, $comment);
    }

    function get_geo_location($id = null) {
        $p = new Profiler();
        $photo = $this->get_photo($photoId);
        $geo = array("latitude" => $photo["latitude"], "longitude" => $photo["longitude"], "accuracy" => $photo["accuracy"]);
        if ($geo["latitude"] !== 0 && $geo["longitude"] !== 0) {
            return $geo;
        } else {
            return null;
        }
    }

}
$photo = new Flogr_Photo();
?>