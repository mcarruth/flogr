<?php
/**
 * @author Mike Carruth <mikecarruth@gmail.com>
 * @version 2.5.7
 * @package Flogr
 * @link http://flogr.googlecode.com
 */

require_once('profiler.php');

define('FLOGR_FLICKR_API_KEY',  '64735d606d8cc904a3f62d3ed56d56b9');
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
    
    function Flogr_Page() {
        
        if (!$this->phpFlickr) {
            $this->phpFlickr = new phpFlickr(FLOGR_FLICKR_API_KEY);
            //$this->phpFlickr->setProxy('157.54.108.18', 80);
            
            if (CACHE_SQL_USER && 
                CACHE_SQL_PASSWORD && 
                CACHE_SQL_SERVER && 
                CACHE_SQL_DATABASE) {
                $mysqlConnection =    
                    'mysql://' . 
                    CACHE_SQL_USER . ':' . 
                    CACHE_SQL_PASSWORD . '@' . 
                    CACHE_SQL_SERVER . '/' . 
                    CACHE_SQL_DATABASE;
                                         
                $this->phpFlickr->enableCache('db', $mysqlConnection);
            }
            else if (CACHE_PATH) {
               $this->phpFlickr->enableCache('fs', CACHE_PATH);
            }
        }
        
        $this->paramPage    = $_GET['page'] ? $_GET['page'] : 1;
        $this->paramPerPage = $_GET['perPage'] ? $_GET['perPage'] : FLOGR_THUMBNAILS_PER_PAGE;
        $this->paramPhotoId = $_GET['photoId'];
        $this->paramSetId   = $_GET['setId'];
        $this->paramTags    = $_GET['tags'] ? $_GET['tags'] : FLOGR_TAGS_INCLUDE;
        $this->paramSize    = $_GET['size'] ? $_GET['size'] : FLOGR_SIZE;
        $this->paramSort    = $_GET['sort'] ? $_GET['sort'] : FLOGR_SORT;
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
                    "' rel='lightbox-thumbnails' title='{$title} | ${date}' alt='{$title}' rev='{$titleLink}'>" .
                        "<img class='thumbnail' src='" . 
                        $this->phpFlickr->buildPhotoURL($photo, "square") . "'/>" .
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
    
    function get_previous_page_link( $photos, $inner = 'prev' ) {
    	$prevPage = $this->paramPage < $photos['pages'] ? $this->paramPage + 1 : 0;
        
        if ( $prevPage ) {
            $url = "<a id='prevLink' href=" . $_SERVER['PHP_SELF'] . "?";

            // copy all existing query params except 'page'
            foreach ($_GET as $key=>$val) {
                if ( $key == "page" ) {
                    continue;
                }
                $url .= "$key=$val&";                    
            }
            $url .= "page=$prevPage";
            return $url . ">$inner</a>";
        }           
    }
    
    function previous_page_link( $photos, $inner = 'prev' ) {
    	echo $this->get_previous_page_link( $photos, $inner );
    }
   
    function get_next_page_link( $photos, $inner = 'next' ) {
    	$nextPage = 1 < $this->paramPage ? $this->paramPage - 1: 0;
        
        if ( $nextPage ) {
            $url = "<a id='nextLink' href=" . $_SERVER['PHP_SELF'] . "?";
            
            // copy all existing query params except 'page'
            foreach ($_GET as $key=>$val) {
                if ( $key == "page" ) {
                    continue;
                }
                $url .= "$key=$val&";                    
            }
            $url .= "page=$nextPage";
            return $url . ">$inner</a>";
        }           
    }
    
    function next_page_link( $photos, $inner = 'next' ) {
    	echo $this->get_next_page_link( $photos, $inner );
    }
    
	function getconst($const) {
    	return (defined($const)) ? constant($const) : null;
	}
}
?>