# Testing Guide for Flogr

This guide will help you test your refactored Flogr installation locally.

## Option 1: Docker (Easiest - Recommended)

### Prerequisites
- Docker installed on your machine
- Docker Compose (usually comes with Docker Desktop)

### Quick Start

1. **Create a `.env` file:**
   ```bash
   cp .env.example .env
   ```

2. **Add your Flickr API key to `.env`:**
   ```bash
   FLICKR_API_KEY=your_actual_api_key_here
   ```

3. **Update your Flickr User ID in `admin/config.php` (line 27)**

4. **Start the Docker container:**
   ```bash
   docker-compose up -d
   ```

5. **Visit in browser:**
   ```
   http://localhost:8080
   ```

6. **View logs:**
   ```bash
   docker-compose logs -f
   ```

7. **Stop the container:**
   ```bash
   docker-compose down
   ```

## Option 2: PHP Built-in Server (Quick Testing)

### Prerequisites
- PHP 7.4+ installed

### Steps

1. **Navigate to project directory:**
   ```bash
   cd /Users/michaelcarruth/Source/flogr-1
   ```

2. **Create `.env` file:**
   ```bash
   cp .env.example .env
   # Edit .env and add your Flickr API key
   ```

3. **Update `admin/config.php` with your Flickr User ID**

4. **Start PHP built-in server:**
   ```bash
   php -S localhost:8080
   ```

5. **Visit in browser:**
   ```
   http://localhost:8080
   ```

**Note:** The built-in server is great for testing but not recommended for production.

## Option 3: MAMP/XAMPP (GUI-based)

### For macOS (MAMP)

1. **Download and install MAMP:**
   - Visit [https://www.mamp.info/en/downloads/](https://www.mamp.info/en/downloads/)
   - Download MAMP (free version)
   - Install and launch

2. **Configure MAMP:**
   - Open MAMP
   - Click "Preferences" → "Web Server"
   - Set Document Root to: `/Users/michaelcarruth/Source/flogr-1`
   - Click "Start Servers"

3. **Setup Flogr:**
   ```bash
   cd /Users/michaelcarruth/Source/flogr-1
   cp .env.example .env
   # Edit .env and add your API key
   ```

4. **Visit in browser:**
   ```
   http://localhost:8888
   ```

### For Windows (XAMPP)

1. **Download and install XAMPP:**
   - Visit [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Download and install
   - Launch XAMPP Control Panel

2. **Copy Flogr to htdocs:**
   ```
   C:\xampp\htdocs\flogr\
   ```

3. **Start Apache:**
   - In XAMPP Control Panel, click "Start" next to Apache

4. **Setup `.env` file:**
   - Copy `.env.example` to `.env`
   - Add your Flickr API key

5. **Visit in browser:**
   ```
   http://localhost/flogr
   ```

## Option 4: Local Apache/Nginx (Advanced)

### macOS with Homebrew

```bash
# Install Apache
brew install httpd

# Configure Apache
sudo nano /usr/local/etc/httpd/httpd.conf

# Add virtual host for flogr
<VirtualHost *:8080>
    DocumentRoot "/Users/michaelcarruth/Source/flogr-1"
    ServerName flogr.local
    <Directory "/Users/michaelcarruth/Source/flogr-1">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

# Add to /etc/hosts
sudo nano /etc/hosts
127.0.0.1 flogr.local

# Start Apache
sudo brew services start httpd

# Visit: http://flogr.local:8080
```

## Testing Checklist

Once you have Flogr running, test these features:

### Basic Functionality
- [ ] Homepage loads without errors
- [ ] Photos from your Flickr account display
- [ ] Photo titles and descriptions show correctly
- [ ] Clicking a photo shows detail view
- [ ] Navigation menu works

### Page Types
- [ ] **Photo view** (`index.php` or `index.php?type=photo`)
- [ ] **Recent photos** (`index.php?type=recent`)
- [ ] **Photo sets** (`index.php?type=sets`)
- [ ] **Tags** (`index.php?type=tags`)
- [ ] **Tag cloud** displays correctly
- [ ] **Map view** (`index.php?type=map`) - if you have geo-tagged photos
- [ ] **Favorites** (`index.php?type=favorites`)
- [ ] **About page** (`index.php?type=about`)
- [ ] **RSS feed** (`index.php?type=rss`)

### Security Tests
- [ ] Try accessing `.env` file in browser: `http://localhost:8080/.env`
  - Should return 403 Forbidden or 404 Not Found
- [ ] Try invalid type parameter: `http://localhost:8080/index.php?type=invalid`
  - Should default to photo page, no error
- [ ] Check security headers:
  ```bash
  curl -I http://localhost:8080
  ```
  - Should see: `X-Frame-Options`, `X-XSS-Protection`, etc.

### Error Handling
- [ ] Try invalid photo ID: `index.php?photoId=invalid`
  - Should handle gracefully
- [ ] Remove `.env` file temporarily
  - Should show error: "FLICKR_API_KEY not set"
- [ ] Check error logs for warnings

### Performance (Optional)
- [ ] Enable caching in `.env`
- [ ] Verify pages load faster on second visit
- [ ] Check cache directory/database for stored data

## Debugging Tips

### Check PHP Error Logs

**Built-in server:**
```bash
# Errors show in terminal where you started the server
```

**MAMP:**
```bash
tail -f /Applications/MAMP/logs/php_error.log
```

**XAMPP (Windows):**
```
C:\xampp\apache\logs\error.log
```

**Apache/Nginx:**
```bash
# macOS
tail -f /usr/local/var/log/httpd/error_log

# Linux
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log
```

### Common Issues

**"FLICKR_API_KEY not set"**
```bash
# Verify .env exists
ls -la .env

# Check contents
cat .env

# Should see: FLICKR_API_KEY=your_key_here
```

**"Call to undefined function validate_string()"**
```bash
# Check that security.php is being loaded
grep -n "require_once.*security" admin/flogr.php

# Should see line 63: require_once('security.php');
```

**Photos not loading**
```bash
# Check Flickr API key is valid
# Check your Flickr User ID is correct in admin/config.php
# Verify your Flickr photos are public
```

**Permission errors**
```bash
# Set correct permissions
chmod 600 .env
chmod 755 admin pages themes
chmod 644 admin/*.php pages/*.php
```

## Browser Developer Tools

1. **Open Developer Tools** (F12 or Cmd+Option+I)
2. **Console tab** - Check for JavaScript errors
3. **Network tab** - Check for failed requests
4. **Security tab** - Verify HTTPS (if applicable)

## Testing with Different PHP Versions

```bash
# Using Docker to test different PHP versions
docker run -d -p 8080:80 -v $(pwd):/var/www/html php:7.4-apache
docker run -d -p 8081:80 -v $(pwd):/var/www/html php:8.0-apache
docker run -d -p 8082:80 -v $(pwd):/var/www/html php:8.1-apache
```

## Automated Testing Script

Create a simple test script `test.sh`:

```bash
#!/bin/bash

echo "Testing Flogr Installation..."

# Check .env exists
if [ ! -f .env ]; then
    echo "❌ .env file not found"
    exit 1
else
    echo "✓ .env file exists"
fi

# Check API key is set
if grep -q "FLICKR_API_KEY=your_flickr_api_key_here" .env; then
    echo "❌ FLICKR_API_KEY not configured"
    exit 1
else
    echo "✓ FLICKR_API_KEY configured"
fi

# Check required files exist
files=("admin/flogr.php" "admin/security.php" "admin/env_loader.php" "pages/page.php")
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "✓ $file exists"
    else
        echo "❌ $file missing"
    fi
done

echo ""
echo "✓ Basic checks passed!"
echo "Now test in browser: http://localhost:8080"
```

Run it:
```bash
chmod +x test.sh
./test.sh
```

## Next Steps

Once testing is complete and everything works:
1. Deploy to production server
2. Set up HTTPS/SSL
3. Configure caching for performance
4. Set up monitoring/logging
5. Create regular backups

## Need Help?

- Check the error logs (see above)
- Review [SECURITY.md](SECURITY.md) for security-specific issues
- Review [README.md](README.md) for configuration details
- Email: mikecarruth@gmail.com
