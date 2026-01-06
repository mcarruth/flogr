<?php

/**
 * @author Mike Carruth <mikecarruth@gmail.com>
 * @version 2.5.7
 * @package Flogr
 * @link http://mcarruth.github.io/flogr/
 */

/**
 * Defines a required constant - quits if value not set.
 *
 * @param string name   Name of constant   
 * @param string value  Value for constant
 */
function REQUIRED_SETTING($name, $value) {
    if (!isset($value) || $value == '') {
        die("<p>You need to set <b>'{$name}'</b> in <code>admin\config.php</code> before you can being using flogr.</p>");
    }
    define($name, $value);
}

/**
 * Defines an optional constant - wrapper for define().
 *
 * @param string name   Name of constant
 * @param string value  Value for constant
 */
function OPTIONAL_SETTING($name, $value) {
    define($name, $value);
}

/**
 * Decides which include path delimiter to use.  Windows should be using a semi-colon
 * and everything else should be using a colon.  If this isn't working on your system,
 * comment out this if statement and manually set the correct value into $path_delimiter.
 */
if (strpos(__FILE__, ':') !== false) {
    $path_delimiter = ';';
} else {
    $path_delimiter = ':';
}

ini_set('include_path',
        dirname(__FILE__) . '/../include/PEAR' . $path_delimiter .
        dirname(__FILE__) . '/../include/phpFlickr-2.1.0' . $path_delimiter .
        dirname(__FILE__) . '/../admin' . $path_delimiter .
        dirname(__FILE__) . '/../pages' . $path_delimiter .
        ini_get('include_path'));

/**
 * Set error reporting to show errors and warnings, but not notices
 * This helps catch security issues and bugs during development
 */
error_reporting(E_ALL & ~E_NOTICE);

/**
 * Includes
 */
require_once('phpFlickr.php');
require_once('Log.php');
require_once('config.php');
require_once('security.php');

// Set security headers
set_security_headers();

/**
 * Create the flogr instance and let's get going
 */
if (!isset($flogr))
    $flogr = new Flogr();
$flogr->run();

/**
 * The Flogr:: class is responsible for mapping requests to the appropriate 
 * handler page based on the type request parameter (ex 'index.php?type=photo')
 * and setting up logging.
 *
 * @author Mike Carruth <mikecarruth@gmail.com>
 * @package Flogr
 */
class Flogr {
    /*
     * Maps request type to template page.
     *
     * @var array
     */

    var $_pageMap = array(
        '' => 'photo.php',
        'photo' => 'photo.php',
        'recent' => 'recent.php',
        'sets' => 'sets.php',
        'tags' => 'tags.php',
        'map' => 'map.php',
        'map_data' => 'map_data.php',
        'favorites' => 'favorites.php',
        'about' => 'about.php',
        'rss' => 'rss.php'
    );

    /*
     * Maps flogr log levels to PEAR::LOG
     */
    var $_logLevels = array(
        FLOGR_LOG_NONE => PEAR_LOG_NONE,
        FLOGR_LOG_ERR => PEAR_LOG_ERR,
        FLOGR_LOG_WARNING => PEAR_LOG_WARNING,
        FLOGR_LOG_DEBUG => PEAR_LOG_DEBUG
    );
    var $_logMask = PEAR_LOG_ALL;
    var $_logger = null;
    var $_logHandlers = array(
        'firebug' => null,
    );

    function __construct() {
        // PEAR Log methods are now properly declared as static
        $this->_logger = Log::singleton('composite', '', '', null, $this->_logLevels[FLOGR_LOG_LEVEL]);
        $keys = array_keys($this->_logHandlers);
        foreach ($keys as $key) {
            $handler = Log::singleton($key, '', '', null, $this->_logLevels[FLOGR_LOG_LEVEL]);
            $this->_logHandlers[$key] = $handler;
            $this->_logger->addChild($handler);
        }
    }

    function logInfo($string) {
        if (!defined('FLOGR_DISABLE_LOGGING') || !FLOGR_DISABLE_LOGGING) {
            $this->_logger->info($string);
        }
    }

    function logWarning($string) {
        if (!defined('FLOGR_DISABLE_LOGGING') || !FLOGR_DISABLE_LOGGING) {
            $this->_logger->warning($string);
        }
    }

    function logErr($string) {
        if (!defined('FLOGR_DISABLE_LOGGING') || !FLOGR_DISABLE_LOGGING) {
            $this->_logger->err($string);
        }
    }

    function logDebug($string) {
        if (!defined('FLOGR_DISABLE_LOGGING') || !FLOGR_DISABLE_LOGGING) {
            $this->_logger->debug($string);
        }
    }

    function run() {
        if (defined('FLICKR_USER_ID') || defined('FLICKR_GROUP_ID')) {
            // Sanitize and validate the type parameter
            $type = isset($_GET['type']) ? validate_string($_GET['type'], '', 50) : '';

            // Validate that the type exists in our page map to prevent arbitrary file inclusion
            if (!array_key_exists($type, $this->_pageMap)) {
                // Default to photo page if type is invalid
                $type = '';
            }

            if ($type != 'rss' && $type != 'map_data') {
                include('header.php');
                include(SITE_THEME_PATH . 'header.php');
            }

            include($this->_pageMap[$type]);
            include(SITE_THEME_PATH . $this->_pageMap[$type]);

            if ($type != 'rss' && $type != 'map_data') {
                include(SITE_THEME_PATH . 'footer.php');
                include('footer.php');
            }
        } else {
            die('<p>ERROR: FLICKR_USER_ID or FLICKR_GROUP_ID must be set in admin/config.php before using flogr.</p>');
        }
    }

}

?>
