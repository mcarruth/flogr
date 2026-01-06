# Quick Start Guide

Get Flogr up and running in 5 minutes!

## Prerequisites

- PHP 7.4 or higher
- Web server (Apache/Nginx)
- Flickr account

## Setup (5 Steps)

### 1. Get Flickr Credentials

**API Key:**
1. Go to [https://www.flickr.com/services/api/misc.api_keys.html](https://www.flickr.com/services/api/misc.api_keys.html)
2. Click "Request an API Key"
3. Select "Non-Commercial Key"
4. Fill out the form
5. Copy your API Key

**User ID:**
1. Go to [http://idgettr.com/](http://idgettr.com/)
2. Enter your Flickr username
3. Copy your User ID (format: `12345678@N00`)

### 2. Configure Environment

```bash
# Copy the environment template
cp .env.example .env

# Edit .env and add your API key
nano .env  # or use any text editor
```

Add your API key:
```bash
FLICKR_API_KEY=your_api_key_here
```

### 3. Configure Flogr Settings

Edit `admin/config.php` (line 27):

```php
OPTIONAL_SETTING('FLICKR_USER_ID', 'your_user_id_here');
```

### 4. Set Permissions

```bash
chmod 600 .env
```

### 5. Upload & Test

Upload files to your web server and visit your site!

## Optional: Enable Caching

For better performance, add to `.env`:

```bash
# File cache (easiest)
CACHE_PATH=/tmp/flogr_cache

# OR MySQL cache (faster)
CACHE_SQL_USER=your_db_user
CACHE_SQL_PASSWORD=your_db_password
CACHE_SQL_SERVER=localhost
CACHE_SQL_DATABASE=flogr_cache
```

## Customization

### Change Theme

Edit `admin/config.php` (line 37):

```php
REQUIRED_SETTING('SITE_THEME', 'blackstripe2');
```

Available themes:
- `blackstripe2` (default)
- `blackstripe`
- `whitestripe2`
- `whitestripe`
- `zoom-light`

### Change Site Title

Edit `admin/config.php` (line 35-36):

```php
REQUIRED_SETTING('SITE_TITLE', 'My Photo Gallery');
REQUIRED_SETTING('SITE_DESCRIPTION', 'My awesome photos from Flickr');
```

## Troubleshooting

### Photos Not Showing?

1. Check your API key is correct
2. Verify your User ID is correct
3. Make sure your Flickr photos are public
4. Check web server error logs

### "FLICKR_API_KEY not set" Error?

1. Ensure `.env` file exists
2. Check `.env` has correct format: `FLICKR_API_KEY=your_key`
3. Verify file permissions: `chmod 600 .env`

### 404 or Blank Page?

1. Check web server error logs
2. Ensure PHP is installed and working
3. Verify file permissions are correct

## Need More Help?

- **Full Documentation:** See [README.md](README.md)
- **Security Info:** See [SECURITY.md](SECURITY.md)
- **Upgrading:** See [UPGRADE.md](UPGRADE.md)
- **Email:** mikecarruth@gmail.com

## Next Steps

Once your site is running:

1. Customize your theme (CSS in `themes/[theme]/css/`)
2. Enable caching for better performance
3. Configure SSL/HTTPS
4. Set up regular backups
5. Monitor error logs

That's it! Your Flickr photoblog should now be live! 🎉
