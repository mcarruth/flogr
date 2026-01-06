# flogr

![PHP 8.1](https://img.shields.io/badge/PHP-8.1-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

flogr is a php script that will display your public flickr photos in a customizable photo gallery you host on your website. If you use flickr but want to have a different look and feel for your photo gallery you may like flogr.

**✨ Now fully compatible with PHP 8.1!** This version includes modern security features, free OpenStreetMap integration, and comprehensive PHP 8.1 compatibility fixes. 

Go on and take it for a [spin](http://flogr.mikecarruth.org/).


## Main Features
- Customizable photoblog interface for your flickr photos
- Display all flickr photos, only photos with certain tags or only certain photosets
- Displays photo details, EXIF data, tags, geo location, and photo comments
- Thumbnail viewer displays photos by date taken, photoset, and tag
- Embedded Slimbox photo slideshow
- Map view of your geo tagged photos
- Flickr tag cloud page
- RSS 2.0 support

## Screenshots
![Main](https://github.com/mcarruth/flogr/blob/gh-pages/images/main.png?raw=true=250)
![Recent](https://github.com/mcarruth/flogr/blob/gh-pages/images/recent.png?raw=true)
![Slide](https://github.com/mcarruth/flogr/blob/gh-pages/images/slide.png?raw=true)
![Map](https://github.com/mcarruth/flogr/blob/gh-pages/images/map.png?raw=true)
![Tag](https://github.com/mcarruth/flogr/blob/gh-pages/images/tag.png?raw=true)

## Installation

### Prerequisites
- PHP 7.4+ (PHP 8.0+ recommended)
- Apache or Nginx web server
- Flickr account and API key

### Setup Instructions

1. **Download and unpack the zip locally**

2. **Get your Flickr API Key**
   - Visit [https://www.flickr.com/services/api/misc.api_keys.html](https://www.flickr.com/services/api/misc.api_keys.html)
   - Apply for a non-commercial API key
   - Copy your API key

3. **Configure Environment Variables**
   ```bash
   # Copy the example environment file
   cp .env.example .env
   ```

   Edit `.env` and add your Flickr API key:
   ```bash
   FLICKR_API_KEY=your_flickr_api_key_here
   ```

4. **Configure Flogr Settings**
   - Enter your Flickr user ID on line 27 of `admin/config.php`
   - You can lookup your Flickr ID at [http://idgettr.com/](http://idgettr.com/)

   ```php
   /**
    * Your Flickr user id and/or group id
    *
    * Note: A flickr id (not name) is needed.
    */
   OPTIONAL_SETTING('FLICKR_USER_ID', 'YOUR-FLICKR-USER-ID');
   ```

5. **Optional: Configure Caching**

   For better performance, you can enable caching in your `.env` file:

   **MySQL Cache:**
   ```bash
   CACHE_SQL_USER=your_db_user
   CACHE_SQL_PASSWORD=your_db_password
   CACHE_SQL_SERVER=localhost
   CACHE_SQL_DATABASE=flogr_cache
   ```

   **File System Cache:**
   ```bash
   CACHE_PATH=/tmp/flogr_cache
   ```

6. **Set Correct Permissions**
   ```bash
   # Protect your .env file
   chmod 600 .env

   # If using file system cache
   mkdir -p /tmp/flogr_cache
   chmod 755 /tmp/flogr_cache
   ```

7. **Upload to your webserver**
   - Upload the contents to your webserver (e.g., `http://foo.com/bar`)
   - **IMPORTANT:** Ensure `.env` is NOT accessible via web browser

8. **Verify Installation**
   - Visit your site in a browser
   - You should see your Flickr photos displayed

### Security Notes

⚠️ **Important Security Updates Applied**

This version includes critical security fixes:
- Removed hardcoded API credentials
- Added input validation and sanitization
- Implemented XSS protection
- Added security headers
- Fixed path traversal vulnerabilities

See [SECURITY.md](SECURITY.md) for detailed security information.

### Troubleshooting

**"FLICKR_API_KEY not set" error:**
- Ensure `.env` file exists and contains `FLICKR_API_KEY=your_key`
- Check file permissions on `.env` (should be readable by web server)

**Photos not displaying:**
- Verify your Flickr API key is correct
- Check that your Flickr user ID is set in `admin/config.php`
- Ensure your Flickr photos are set to public

**Performance issues:**
- Enable caching (MySQL or file system)
- Consider using a CDN for static assets

## Questions
The best way to get your questions answered is emailing me mikecarruth@gmail.com

## Donate
flogr is free to use. Want to share the love? You can by [feeding my caffeine addiction or buying me a beer](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9896181). Thanks!
