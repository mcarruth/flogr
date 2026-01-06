# Upgrade Guide

## Upgrading from Previous Versions

If you're upgrading from an older version of Flogr, follow these steps carefully to ensure a smooth transition.

## Breaking Changes

### Environment Variables Required

**Previous versions** stored the Flickr API key directly in `pages/page.php`:
```php
define('FLOGR_FLICKR_API_KEY', '64735d606d8cc904a3f62d3ed56d56b9');
```

**Current version** requires a `.env` file for security.

## Migration Steps

### Step 1: Backup Your Installation
```bash
# Backup your entire installation
tar -czf flogr-backup-$(date +%Y%m%d).tar.gz /path/to/flogr

# Backup your database if using MySQL cache
mysqldump -u username -p database_name > flogr-db-backup.sql
```

### Step 2: Note Your Configuration

Before upgrading, record your current settings from `admin/config.php`:
- Flickr User ID
- Flickr Group ID (if used)
- Site title and description
- Theme selection
- Cache settings (if configured)

### Step 3: Update Files

1. **Download or pull the latest code**
   ```bash
   git pull origin master
   # or download and extract the latest release
   ```

2. **Create .env file**
   ```bash
   cp .env.example .env
   ```

3. **Add your Flickr API key to .env**

   If you were using the old hardcoded API key, you'll need to get your own:
   - Visit [https://www.flickr.com/services/api/misc.api_keys.html](https://www.flickr.com/services/api/misc.api_keys.html)
   - Apply for a non-commercial API key
   - Add it to `.env`:
   ```
   FLICKR_API_KEY=your_new_api_key_here
   ```

4. **Migrate cache credentials to .env** (if using MySQL cache)

   **Old way** (in `admin/config.php`):
   ```php
   OPTIONAL_SETTING('CACHE_SQL_USER', 'dbuser');
   OPTIONAL_SETTING('CACHE_SQL_PASSWORD', 'dbpass');
   OPTIONAL_SETTING('CACHE_SQL_SERVER', 'localhost');
   OPTIONAL_SETTING('CACHE_SQL_DATABASE', 'flogr_cache');
   ```

   **New way** (in `.env`):
   ```
   CACHE_SQL_USER=dbuser
   CACHE_SQL_PASSWORD=dbpass
   CACHE_SQL_SERVER=localhost
   CACHE_SQL_DATABASE=flogr_cache
   ```

   The old config.php settings will still work as fallback, but using `.env` is recommended.

### Step 4: Update Configuration

Your `admin/config.php` settings should remain mostly unchanged. The main settings to verify:
- `FLICKR_USER_ID`
- `FLICKR_GROUP_ID`
- Site settings (title, description, theme)
- Other optional settings

### Step 5: Set File Permissions
```bash
# Protect .env file
chmod 600 .env

# Ensure web server can read config
chmod 644 admin/*.php
chmod 644 pages/*.php
```

### Step 6: Update .htaccess (Apache)

Add protection for `.env` file in your `.htaccess`:
```apache
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### Step 7: Test Your Installation

1. Visit your site in a browser
2. Check that photos are loading correctly
3. Test navigation (recent, sets, tags, map)
4. Verify RSS feed works
5. Check error logs for any warnings

### Step 8: Clear Cache (if enabled)

If you're using caching, clear it after upgrade:

**File System Cache:**
```bash
rm -rf /tmp/flogr_cache/*
```

**MySQL Cache:**
```sql
TRUNCATE TABLE flogr_cache_table;
```

## Verifying Security Fixes

After upgrading, verify the security improvements are active:

1. **Check .env is not accessible:**
   - Try visiting `https://yoursite.com/.env` in browser
   - Should return 403 Forbidden or 404 Not Found

2. **Verify security headers:**
   ```bash
   curl -I https://yoursite.com
   ```
   Should include:
   - `X-Frame-Options: SAMEORIGIN`
   - `X-XSS-Protection: 1; mode=block`
   - `X-Content-Type-Options: nosniff`

3. **Test input validation:**
   - Try accessing `index.php?type=invalid`
   - Should default to photo page, not show error

## Rollback Procedure

If you encounter issues and need to rollback:

1. **Restore files from backup:**
   ```bash
   cd /path/to/webroot
   tar -xzf /path/to/flogr-backup-YYYYMMDD.tar.gz
   ```

2. **Restore database (if applicable):**
   ```bash
   mysql -u username -p database_name < flogr-db-backup.sql
   ```

3. **Clear web server cache**
   ```bash
   # Apache
   sudo service apache2 reload

   # Nginx
   sudo service nginx reload
   ```

## Post-Upgrade Recommendations

### Update Dependencies (Advanced)

The current version uses some outdated libraries. Consider updating:

**jQuery:**
```html
<!-- Current: jQuery 1.11.2 -->
<!-- Consider: jQuery 3.7.1 (requires testing) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
```

**Note:** Updating jQuery may require theme modifications. Test thoroughly in a staging environment.

### Enable HTTPS

If you haven't already:
1. Obtain SSL certificate (Let's Encrypt is free)
2. Configure web server for HTTPS
3. Update `SITE_URL` in config.php to use `https://`

### Set Up Monitoring

Consider implementing:
- Error logging to track issues
- Uptime monitoring
- Performance monitoring
- Security monitoring

## Troubleshooting Common Upgrade Issues

### "FLICKR_API_KEY not set" Error

**Cause:** `.env` file missing or not loaded correctly

**Fix:**
```bash
# Verify .env exists
ls -la .env

# Check contents
cat .env

# Ensure API key is set
grep FLICKR_API_KEY .env
```

### Photos Not Loading After Upgrade

**Possible causes:**
1. Old cache data
2. API key not configured
3. File permissions changed

**Fix:**
```bash
# Clear cache
rm -rf /tmp/flogr_cache/*

# Check error logs
tail -f /var/log/apache2/error.log

# Verify .env permissions
ls -la .env
chmod 600 .env
```

### Permission Denied Errors

**Fix:**
```bash
# Set correct ownership
sudo chown -R www-data:www-data /path/to/flogr

# Set correct permissions
find /path/to/flogr -type d -exec chmod 755 {} \;
find /path/to/flogr -type f -exec chmod 644 {} \;
chmod 600 .env
```

## Need Help?

- Review [SECURITY.md](SECURITY.md) for security-specific issues
- Check the [README.md](README.md) for installation instructions
- Email: mikecarruth@gmail.com

## Changelog Summary

### Version 2.5.7+ (Security Update)

**Security Fixes:**
- Removed hardcoded API credentials
- Added comprehensive input validation
- Implemented XSS protection
- Added security headers
- Fixed path traversal vulnerability
- Improved error reporting

**Bug Fixes:**
- Fixed undefined variable in `user.php`
- Implemented proper 404 handler

**Improvements:**
- Modernized PHP constructors
- Added error handling for API calls
- Created comprehensive documentation
- Added environment variable support

**New Files:**
- `.env.example` - Environment configuration template
- `admin/env_loader.php` - Environment variable loader
- `admin/security.php` - Security helper functions
- `SECURITY.md` - Security documentation
- `UPGRADE.md` - This file
- Improved `404.php` error page
