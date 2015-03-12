<?php 
/**
 * Flogr config settings 
 *
 * @author Mike Carruth <mikecarruth@gmail.com>
 * @version 2.5.7
 * @package Flogr
 * @link http://flogr.googlecode.com
 */

/**
 * Your Flickr user id and/or group id 
 *
 * Note: A flickr id (not name) is needed.  You can lookup your id at 
 * http://idgettr.com/.
 * 
 * User id only - 	
 * 	Provide only your flickr user id and flogr will use the user photostream
 * 
 * Group id only - 
 * 	Provide only your flickr group id and flogr will use the group photostream
 * 
 * User and Group id - 
 * 	Provide both your user and group ids and flogr will show only the photos 
 * 	you have posted to the given group.
 */
OPTIONAL_SETTING('FLICKR_USER_ID',  '95137114@N00');
OPTIONAL_SETTING('FLICKR_GROUP_ID', '');
//OPTIONAL_SETTING('FLICKR_USER_ID',  '');
//OPTIONAL_SETTING('FLICKR_GROUP_ID', '82648219@N00');

/**
 * Site settings
 */
REQUIRED_SETTING('SITE_TITLE',         'Flogr');
REQUIRED_SETTING('SITE_DESCRIPTION',   'A photoblog application built on Flickr');
REQUIRED_SETTING('SITE_THEME',         'blackstripe');
REQUIRED_SETTING('SITE_THEME_PATH',    'themes/' . SITE_THEME . '/');
$url = "http://". $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
REQUIRED_SETTING('SITE_URL',           substr($url, 0, strrpos($url, "/")));

/************************************************************************
 * Flogr settings
 ************************************************************************/

/* EXIF tags to include */
REQUIRED_SETTING('FLOGR_EXIF', 'Make,Model,Software,Exposure,Aperture,Shutter Speed,ISO Speed,Flash');

/*
 * Quality (resolution) of the photo on the main photo page.  Can me:
 *
 * Square:      Small square 75x75
 * Thumbnail:   100 on longest side
 * Small:       240 on longest side
 * Medium:      500 on longest side
 * Medium640:   640 on longest side
 * Large:       1024 on longest side
 * Original:    Original size
 * 
 * Note: Before May 25th 2010 large photos only exist for very large original
 * images.  Also I highly recommend against setting this to "Original" since
 * it can take considerably longer to download high resolution original photos
 * and it is unecessary since the photos will be scaled down to
 * FLOGR_MAIN_PHOTO_SIZE anyway.
 */
REQUIRED_SETTING('FLOGR_PHOTO_QUALITY', 'Medium640');

/* Desired width of main photo */
REQUIRED_SETTING('FLOGR_MAIN_PHOTO_SIZE', 750);

/*
 * Quality (resolution) of the photo in the slideshow.  Can me:
 * 
 * Square:      Small square 75x75
 * Thumbnail:   100 on longest side
 * Small:       240 on longest side
 * Medium:      500 on longest side
 * Medium640:   640 on longest side
 * Large:       1024 on longest side
 * Original:    Original size
 *
 * Note: Before May 25th 2010 large photos only exist for very large original
 * images.  Also I highly recommend against setting this to "Original" since
 * it can take considerably longer to download high resolution original photos.
 */
REQUIRED_SETTING('FLOGR_SLIDESHOW_PHOTO_QUALITY', 'Medium640');

/* Photosets to include - separate multiple sets with commas. */
OPTIONAL_SETTING('FLOGR_PHOTOSETS_INCLUDE', 'Favorites');

/* Number of tags to include in the tag cloud */
REQUIRED_SETTING('FLOGR_TAGS_COUNT', 200);

/* Tags to include - separate multiple tags with commas */
OPTIONAL_SETTING('FLOGR_TAGS_INCLUDE', '');

/* Hide FLOGR_TAGS_INCLUDE from tag cloud */
OPTIONAL_SETTING('FLOGR_TAGS_INCLUDE_HIDE', '');

/* PHP date format - http://us2.php.net/date */
REQUIRED_SETTING('FLOGR_DATE_FORMAT', 'F j, Y');

/* Set to true to use date taken - false to use date uploaded */
REQUIRED_SETTING('FLOGR_SHOW_DATE_TAKEN', true);

/* Number of thumbnails to show on the 'recent' page */
REQUIRED_SETTING('FLOGR_THUMBNAILS_PER_PAGE', 48);

/**
 * The order in which to sort returned photos. Deafults to date-posted-desc 
 * (unless you are doing a radial geo query, in which case the default sorting 
 * is by ascending distance from the point specified). The possible values are: 
 * date-posted-asc, date-posted-desc, date-taken-asc, date-taken-desc, 
 * interestingness-desc, interestingness-asc, and relevance.
 */
OPTIONAL_SETTING('FLOGR_SORT', '');

/* Log level */

/** 
 * Constants
 */
define('FLOGR_LOG_NONE',      0);
define('FLOGR_LOG_ERR',       1);
define('FLOGR_LOG_WARNING',   2);
define('FLOGR_LOG_DEBUG',     3);
REQUIRED_SETTING('FLOGR_LOG_LEVEL', FLOGR_LOG_ERR);

/**
 * Cache Settings
 *
 * To improve performance flogr can be configured to cache photos to a MySql database
 * or to the web server.
 */
OPTIONAL_SETTING('CACHE_SQL_USER', '');
OPTIONAL_SETTING('CACHE_SQL_PASSWORD', '');
OPTIONAL_SETTING('CACHE_SQL_SERVER', '');
OPTIONAL_SETTING('CACHE_SQL_DATABASE', '');
OPTIONAL_SETTING('CACHE_PATH', '');
?>
