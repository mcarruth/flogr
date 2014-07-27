===============================================================================
flogr - Customizable photo gallery for photos hosted on Flickr

    Author: Mike Carruth (mikecarruth [at] google [dot] com
  Homepage: http://code.google.com/p/flogr/
 Newsgroup: http://groups.google.com/group/flogr
===============================================================================

---------------------------------------------------------------------
 Introduction
---------------------------------------------------------------------

  Flogr is a photoblog application powered by PHP, MySQL, and 
  Flickr. It is an easy to setup and easy to customize alternative 
  for folks that currently hack their own photoblog using MT or 
  Wordpress, or those trying to make Gallery or PixelPost work with 
  Flickr. If you use Flickr and want to have your own photoblog, 
  but don't want to hack blog software or upload the same pictures 
  multiple times (to flickr and pixelpost for example) then flogr 
  may be what you're after.

---------------------------------------------------------------------
 Features
---------------------------------------------------------------------

  * Customizable photoblog interface for your flickr photos
  * Photo details overlay displays description, EXIF data, tags, geo data and comments
  * Thumbnail viewer displays photos by date taken, photoset, and tag
  * Slimbox photo slideshow
  * Flickr tag cloud page
  * RSS 2.0 support

---------------------------------------------------------------------
 Credits
---------------------------------------------------------------------

  * phpFlickr
  * Slimbox
  * JQuery
    
---------------------------------------------------------------------
 Requirements
---------------------------------------------------------------------

  - PHP 4 or >
  - Mysql (optional - used to cache images for improved performance)
   
---------------------------------------------------------------------
 Setup
---------------------------------------------------------------------

  1. Update the required application options inc/config.php
  2. Cconfigure filesystem or database caching (optional)
  3. Customize the indicated section of about.php (optional)
  4. Copy all files from into a folder in your web server.   
            
---------------------------------------------------------------------
 Download
---------------------------------------------------------------------

  http://code.google.com/p/flogr/downloads/list

---------------------------------------------------------------------
 Support
---------------------------------------------------------------------
   
  Bugs and Feature Requests
  - http://code.google.com/p/flogr/issues/list

  Wiki
  - http://code.google.com/p/flogr/w/list

  Forum
  - http://groups.google.com/group/flogr
    
---------------------------------------------------------------------
 History
---------------------------------------------------------------------

    Version 2.4 (November 7, 2010)
    * Added map to photo info overlay
    * Reformatted link in slimbox slideshow

    Version 2.3 (December 10, 2009)
    * Issue #70 - Last image not displayed on main page
    * Updated to Slimbox2 w/JQuery
    * Changed main page navigation system
    * Reformatted photo info overlay
    * Issue #36 - Added location data w/links to photo info overlay

    Version 2.2 (November 26, 2009)
    * Add support for Flickr Groups
    * Changed default photo size to 'Medium'
    * Changed icon on slideshow caption link

    Version 2.1 (June 6, 2009)
    * Fixed RSS issue 

    Version 2.0 (May 22, 2009)
    * Major redesign to allow easier creation of themes
    * Major refactoring to optimize performance
    * Integrated Firebug logging
    * Fixed several issues 

    Version 1.7.2 (December 1, 2007)
    * Fixed  issue #30  Photo size configuration
    * Fixed  issue #31  Removed mouseover/mouseout effects
    * Fixed  issue #32  Forced utf-8 char encoding
    * Fixed  issue #33  Use photo specific copyright notice 

    Version 1.7.1 (November 20, 2007)
    * Fixed  issue #29  RSS feed timing out and not validating 
      
    Version 1.7 (November 18, 2007)
    * Redesigned details overlay div
    * Redesigned tag cloud page
    * Redesigned about page
    * Added support for flickr groups
    * Fixed issue #27 - Details overlay div is sometimes not sized correctly
    * Fixed issue #24 - Problem with accentuated characters

    Version 1.6 (November 11, 2007)
    * Fixed bug with html in photo/set title or description
    * Fixed bug where supplied filter tags were ignored for previous photos
    * Redesigned photo details fly-in div
    * Moved exif labels into config file
    * Moved main photo size into config file
    * Refactored index.php and recent.php
    * Added tag cloud
    * Added support for browsing by tag

    Version 1.5 (August 6, 2007)
    * Performance improvements
    * Fixed several issues around the main page photo
    * Fixed a security issue where db settings were visible
    * Added favorites page
    * Added photo tooltip effect
    * Modified photo details popup
    * Adjusted layout of recent and sets pages
    * Update mootools/slimbox

    Version 1.4 (May 19, 2007)
    * Changed set thumbnail to use user selected photo
    * Moved photo comments, description and exif data to div overlay

    Version 1.3.1 (May 17, 2007)
    * Fixed IE "Invalid Operation" bug on main page
    * Hide next/prev buttons when no more pages
    * Fixed info page footer wrapping issue in IE

    Version 1.3 (May 16, 2007)
    * Changed main photo date to be date posted (instead of date taken)
    * Add more exif fields
    * Fixed a bug where main photo was not rendered when original upload size was not available

    Version 1.2 (May 13, 2007)
    * Added RSS feed
    * Fixed EXIF photo bug where ISO speed was not being shown

    Version 1.1 (May 10, 2007)
    * Added flogr home page link to info page

    Version 1.0 (May 8, 2007)
    * Initial Release            
    
---------------------------------------------------------------------
 License
---------------------------------------------------------------------
  Copyright (c) 2009 Mike Carruth

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in
  all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
  THE SOFTWARE.