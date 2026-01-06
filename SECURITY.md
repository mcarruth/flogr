# Security Guidelines for Flogr

## Recent Security Improvements

This document outlines the security enhancements made to Flogr to address vulnerabilities and follow modern security best practices.

## Critical Security Fixes Applied

### 1. Hardcoded API Credentials Removed ✓
**Issue**: Flickr API key was hardcoded in source code (`pages/page.php`)
**Fix**: API key now loaded from environment variables via `.env` file
**Action Required**:
- Copy `.env.example` to `.env`
- Add your Flickr API key to `.env`
- Never commit `.env` to version control (already in `.gitignore`)

### 2. Input Validation & Sanitization ✓
**Issue**: Direct use of `$_GET` parameters without validation
**Fix**: All user inputs now validated through security helper functions
**Functions Added**:
- `validate_int()` - Validates integer inputs
- `validate_string()` - Sanitizes string inputs
- `validate_flickr_id()` - Validates Flickr ID format
- `validate_tags()` - Validates tag strings
- `validate_size()` - Validates photo size parameters
- `validate_sort()` - Validates sort parameters

### 3. XSS Prevention ✓
**Issue**: User-controlled data echoed without sanitization
**Fix**: `sanitize_output()` function for all HTML output
**Implementation**: All user-facing output now properly escaped

### 4. Path Traversal Prevention ✓
**Issue**: `$_GET['type']` used directly for file inclusion
**Fix**: Type parameter validated against whitelist in `$_pageMap`
**Result**: Arbitrary file inclusion no longer possible

### 5. Security Headers ✓
**Added Headers**:
```
X-Frame-Options: SAMEORIGIN
X-XSS-Protection: 1; mode=block
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
Content-Security-Policy: [configured for Flickr integration]
```

### 6. Error Reporting Improved ✓
**Changed**: `error_reporting(E_ERROR)` → `error_reporting(E_ALL & ~E_NOTICE)`
**Benefit**: Security issues and bugs now visible during development

### 7. Bug Fixes ✓
- Fixed undefined variable `$photoId` in `user.php:39` (should be `$userId`)
- Implemented proper 404 error handler
- Added error handling for Flickr API calls

## Security Best Practices

### Environment Variables
Always use environment variables for sensitive data:
```php
// ✓ GOOD
$api_key = env('FLICKR_API_KEY');

// ✗ BAD
$api_key = '64735d606d8cc904a3f62d3ed56d56b9';
```

### Input Validation
Always validate and sanitize user input:
```php
// ✓ GOOD
$photoId = validate_flickr_id($_GET['photoId'] ?? null);

// ✗ BAD
$photoId = $_GET['photoId'];
```

### Output Encoding
Always escape output to prevent XSS:
```php
// ✓ GOOD
echo sanitize_output($photo['title']);

// ✗ BAD
echo $photo['title'];
```

### Error Handling
Use safe wrappers for external API calls:
```php
// ✓ GOOD
$result = safe_flickr_call(function() use ($flickr, $photoId) {
    return $flickr->photos_getInfo($photoId);
}, []);

// ✗ BAD (no error handling)
$result = $flickr->photos_getInfo($photoId);
```

## Configuration Security Checklist

- [ ] `.env` file created with proper API credentials
- [ ] `.env` file NOT committed to version control
- [ ] `.gitignore` includes `.env`
- [ ] File permissions set correctly (`.env` should be 600 or 640)
- [ ] Cache credentials (if using MySQL) stored in `.env`
- [ ] Production error reporting configured appropriately
- [ ] HTTPS enabled on production server
- [ ] Web server configured to deny access to `.env` files

## Known Limitations

### Outdated Dependencies
The following dependencies are outdated and may have known vulnerabilities:
- **jQuery 1.11.2** (2014) - Consider upgrading to 3.x
- **jQuery UI 1.11.4** (2015) - Consider upgrading to 1.13.x
- **PEAR libraries** - Largely unmaintained
- **phpFlickr 2.1.0** (2011) - Consider using official Flickr PHP SDK

**Recommendation**: Evaluate and update these dependencies based on your security requirements.

### PHP Version Support
This application was designed for PHP 4.0+. For production use:
- Minimum: PHP 7.4 (for security updates)
- Recommended: PHP 8.0+ (for best performance and security)

## Reporting Security Issues

If you discover a security vulnerability, please:
1. **DO NOT** open a public GitHub issue
2. Email the maintainer directly
3. Include detailed description and reproduction steps
4. Allow reasonable time for a fix before public disclosure

## Additional Security Measures

### Apache/Nginx Configuration
Prevent direct access to sensitive files:

**Apache (.htaccess)**:
```apache
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
```

**Nginx**:
```nginx
location ~ /\.env {
    deny all;
    return 404;
}
```

### File Permissions
```bash
# Set restrictive permissions on .env
chmod 600 .env

# Ensure web server can read config files
chmod 644 admin/*.php pages/*.php

# Protect against execution if uploaded
chmod 644 themes/**/*.{css,js}
```

### Database Security
If using MySQL cache:
- Use strong passwords
- Create dedicated database user with minimal privileges
- Never use root database account
- Enable SSL for database connections if available

## Security Audit Log

| Date | Issue | Severity | Status |
|------|-------|----------|--------|
| 2026-01-05 | Hardcoded API key | High | Fixed |
| 2026-01-05 | Direct $_GET usage | High | Fixed |
| 2026-01-05 | XSS vulnerabilities | High | Fixed |
| 2026-01-05 | Path traversal risk | Critical | Fixed |
| 2026-01-05 | Missing security headers | Medium | Fixed |
| 2026-01-05 | Bug in user.php | Low | Fixed |
| 2026-01-05 | Disabled error reporting | Medium | Fixed |
| 2026-01-05 | Outdated jQuery | Medium | Open |

## References

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)
- [Flickr API Documentation](https://www.flickr.com/services/api/)
