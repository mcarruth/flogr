<?php
/**
 * @author Mike Carruth <mikecarruth@gmail.com>
 * @version 2.5.7
 * @package Flogr
 * @link http://flogr.googlecode.com
 */

require_once('profiler.php');
require_once(dirname(__DIR__) . '/admin/env_loader.php');
require_once(dirname(__DIR__) . '/admin/security.php');

// Load Flickr API key from environment variable for security
define('FLOGR_FLICKR_API_KEY', env('FLICKR_API_KEY', ''));
define('FLOGR_SIZE',            'thumbnail');
define('FLOGR_PER_PAGE',        5);
define('FLOGR_PHOTO_EXTRAS',    'description, license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_m, url_z, url_l, url_o');

/**
 * The Flogr_Page:: class is the base class for the flogr page handlers.  This class
 * manages the phpFlickr instance, the request parameters, and contains some some common
 * functionality.
 *
 * @author Mike Carruth <mikecarruth@gmail.com>
 * @version 2.5.7
 * @package Flogr
 * @link http://flogr.googlecode.com
 */
class Flogr_Page {
    var $paramPage;
    var $paramPerPage;
    var $paramPhotoId;
    var $paramSetId;
    var $paramTags;
    var $paramSize;
    var $paramSort;
    var $phpFlickr;
    var $photoList;
    
    function __construct() {

        if (!$this->phpFlickr) {
            // Validate API key is set
            if (empty(FLOGR_FLICKR_API_KEY)) {
                die('ERROR: FLICKR_API_KEY not set. Please copy .env.example to .env and add your Flickr API key.');
            }

            $this->phpFlickr = new phpFlickr(FLOGR_FLICKR_API_KEY);

            // Load cache settings from environment with security
            $cacheUser = env('CACHE_SQL_USER', defined('CACHE_SQL_USER') ? CACHE_SQL_USER : '');
            $cachePass = env('CACHE_SQL_PASSWORD', defined('CACHE_SQL_PASSWORD') ? CACHE_SQL_PASSWORD : '');
            $cacheServer = env('CACHE_SQL_SERVER', defined('CACHE_SQL_SERVER') ? CACHE_SQL_SERVER : '');
            $cacheDb = env('CACHE_SQL_DATABASE', defined('CACHE_SQL_DATABASE') ? CACHE_SQL_DATABASE : '');
            $cachePath = env('CACHE_PATH', defined('CACHE_PATH') ? CACHE_PATH : '');

            if ($cacheUser && $cachePass && $cacheServer && $cacheDb) {
                // Use environment variables for cache credentials
                $mysqlConnection = sprintf(
                    'mysql://%s:%s@%s/%s',
                    $cacheUser,
                    $cachePass,
                    $cacheServer,
                    $cacheDb
                );
                $this->phpFlickr->enableCache('db', $mysqlConnection);
            }
            else if ($cachePath) {
               $this->phpFlickr->enableCache('fs', $cachePath);
            }
        }

        // Sanitize and validate all input parameters
        $defaultPerPage = defined('FLOGR_THUMBNAILS_PER_PAGE') ? FLOGR_THUMBNAILS_PER_PAGE : 48;
        $defaultTags = defined('FLOGR_TAGS_INCLUDE') ? FLOGR_TAGS_INCLUDE : '';
        $defaultSort = defined('FLOGR_SORT') ? FLOGR_SORT : '';

        $this->paramPage    = validate_int($_GET['page'] ?? null, 1);
        $this->paramPerPage = validate_int($_GET['perPage'] ?? null, $defaultPerPage);
        $this->paramPhotoId = validate_flickr_id($_GET['photoId'] ?? null);
        $this->paramSetId   = validate_flickr_id($_GET['setId'] ?? null);
        $this->paramTags    = validate_tags($_GET['tags'] ?? null) ?: $defaultTags;
        $this->paramSize    = validate_size($_GET['size'] ?? null, FLOGR_SIZE);
        $this->paramSort    = validate_sort($_GET['sort'] ?? null, $defaultSort);
    }

    // Keep old constructor for PHP 4 compatibility
    function Flogr_Page() {
        $this->__construct();
    }

    function run_tests() {
        if (1 == version_compare( PHP_VERSION, '5.0.0' )) {
            
            global $flogr;
            $class = new ReflectionClass(get_class($this));
            $methods = $class->getMethods();
            $className = $class->getName();
            $methodname = null;
        
            $totalStartTime = microtime(true);
        
            echo "<p style='font-size:x-large'>{$className} Tests</p>";
            print "<table colspan='1' cellpadding='1' border='1'>";

            foreach ( $methods as $method ) {
                $methodName = $method->getName();
                switch ($methodName) {
                    case "run_tests":
                    case "photo_slideshow_thumbnails":
                    case "photo_link_thumbnails":
                    case "next_page_link":
                    case "prev_page_link":
                    case "Flogr_Page":
                    case $className:
                        continue 2;
                }

                echo "<tr><td>" . $method->getName() . "</td><td>";
                
                $callStartTime = microtime(true);

                $return = $method->invoke($this);
                if ($return) {
                    if (is_array($return)) print_r($return);
                    else echo $return;
                }

                $callRunTime = round(microtime(true) - $callStartTime, 2);               

                echo "</td></tr>";

                /*
                $message = "{$className}::{$methodName}() took {$callRunTime}s";
                if ($callRunTime < 1) {
                    $flogr->logInfo($message);
                }
                else if ($callRunTime < 2) {
                    $flogr->logWarning($message);                
                }
                else {
                    $flogr->logErr($message); 
                } 
*/               
            }
            
            print "</table>";

            $totalRunTime = round(microtime(true) - $totalStartTime, 4);
            //$flogr->logInfo("{$className} tests took {$totalRunTime}s");
        }
    }

    function get_slideshow_thumbnails( $photos = null ) {
        $date = null;
        $thumbs = null;
        $photos = $photos ? $photos : $photoList;

        if ( $photos['photo'] ) {
            foreach ($photos['photo'] as $photo) {
                if ( FLOGR_SHOW_DATE_TAKEN && isset( $photo['datetaken'] ) ) {
                    $date = date( FLOGR_DATE_FORMAT, strtotime( $photo['datetaken'] ) );
                }
                else if ( isset( $photo['dateupload'] ) ) {
                    $date = date( FLOGR_DATE_FORMAT, $photo['dateupload'] );
                }
        
                $title = htmlspecialchars($photo['title'], ENT_QUOTES);
                $titleLink = htmlspecialchars("<a href=index.php?photoId=" . $photo['id'] . ">" . $photo['title'] . " | " . $date . "</a><span class='ui-icon ui-icon-extlink'></span>", ENT_QUOTES);
                if ( !$title ) { $title = 'untitled'; }

                $thumbs .=
                    "<a href='" . $this->phpFlickr->buildPhotoURL($photo, FLOGR_SLIDESHOW_PHOTO_QUALITY) .
                    "' rel='lightbox-thumbnails' title='{$titleLink}' alt='{$title}'>" .
                        "<img class='thumbnail' src='" .
                        $this->phpFlickr->buildPhotoURL($photo, "square") . "' title='{$title}' />" .
                    "</a>";
                        
            }
        }
        return $thumbs;
    }        

    function slideshow_thumbnails( $photos = null ) {
    	$photos = $photos ? $photos : $photoList;
    	echo $this->get_slideshow_thumbnails( $photos );
    }
    
    function photo_link_thumbnails( $photos, $link = null ) {
        $date = null;
        $thumbs = null;
        $title = null;
    	  $photos = $photos ? $photos : $photoList;
        
        if ( $photos['photo'] ) {
            foreach ($photos['photo'] as $photo) {
                if ( FLOGR_SHOW_DATE_TAKEN && isset( $photo['datetaken'] ) ) {
                    $date = date( FLOGR_DATE_FORMAT, strtotime( $photo['datetaken'] ) );
                }
                else if ( isset( $photo['dateupload'] ) ) {
                    $date = date( FLOGR_DATE_FORMAT, $photo['dateupload'] );
                }
        
                $title = htmlspecialchars($photo['title'], ENT_QUOTES);
                
                $thumbs .=
                    "<a href='" . $link . $photo['id'] .  
                    "' title='" . $title . 
                    " | " . $date . "' alt='' name='" . $photo['id'] . "'>" .
                        "<img class='thumbnail' src='" . 
                        $this->phpFlickr->buildPhotoURL($photo, "square") . 
                        "' title='" . $title . "::" . $date . "' alt=''/>" .
                    "</a>";            
            }
        }
                
        echo $thumbs;
    }

    function get_previous_page_href( $photos ) {
        $prevPage = $this->paramPage < $photos['pages'] ? $this->paramPage + 1 : 0;

        if ( $prevPage ) {
            $href = $_SERVER['PHP_SELF'] . "?";

            // copy all existing query params except 'page'
            foreach ($_GET as $key=>$val) {
                if ( $key == "page" ) {
                    continue;
                }
                $href .= "$key=$val&";
            }
            $href .= "page=$prevPage";
            return $href;
        }
    }

    function get_previous_page_link( $photos, $inner = 'prev' ) {
        $href = $this->get_previous_page_href($photos);
        if ($href) {
            return "<a id='prevLink' href='" . $href . "'>$inner</a>";
        }
        return "<a id='prevLink'>$inner</a>";
    }
    
    function previous_page_link( $photos, $inner = 'prev' ) {
    	echo $this->get_previous_page_link( $photos, $inner );
    }

    function get_next_page_href( $photos ) {
        $nextPage = 1 < $this->paramPage ? $this->paramPage - 1: 0;

        if ( $nextPage ) {
            $href = $_SERVER['PHP_SELF'] . "?";

            // copy all existing query params except 'page'
            foreach ($_GET as $key=>$val) {
                if ( $key == "page" ) {
                    continue;
                }
                $href .= "$key=$val&";
            }
            $href .= "page=$nextPage";
            return $href;
        }
    }

    function get_next_page_link( $photos, $inner = 'next' ) {
        $href = $this->get_next_page_href($photos);
        if ($href) {
            return "<a id='nextLink' href='" . $href . "'>$inner</a>";
        }
        return "<a id='nextLink'>$inner</a>";
    }
    
    function next_page_link( $photos, $inner = 'next' ) {
    	echo $this->get_next_page_link( $photos, $inner );
    }
    
	function getconst($const) {
    	return (defined($const)) ? constant($const) : null;
	}
}
?>