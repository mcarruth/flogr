#!/bin/bash

# Flogr Installation Test Script
# This script performs basic validation checks

echo "========================================="
echo "Testing Flogr Installation"
echo "========================================="
echo ""

EXIT_CODE=0

# Test 1: Check .env file
echo "Test 1: Checking .env file..."
if [ ! -f .env ]; then
    echo "❌ FAIL: .env file not found"
    echo "   Run: cp .env.example .env"
    EXIT_CODE=1
else
    echo "✓ PASS: .env file exists"

    # Check if API key is configured
    if grep -q "FLICKR_API_KEY=your_flickr_api_key_here" .env 2>/dev/null; then
        echo "⚠️  WARN: FLICKR_API_KEY appears to be using default value"
        echo "   Edit .env and add your actual Flickr API key"
    elif grep -q "FLICKR_API_KEY=" .env 2>/dev/null; then
        echo "✓ PASS: FLICKR_API_KEY is configured"
    else
        echo "❌ FAIL: FLICKR_API_KEY not found in .env"
        EXIT_CODE=1
    fi
fi

echo ""

# Test 2: Check required files
echo "Test 2: Checking required files..."
REQUIRED_FILES=(
    "index.php"
    "admin/flogr.php"
    "admin/config.php"
    "admin/security.php"
    "admin/env_loader.php"
    "admin/header.php"
    "admin/footer.php"
    "pages/page.php"
    "pages/photo.php"
    "pages/recent.php"
)

ALL_FILES_EXIST=true
for file in "${REQUIRED_FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "✓ $file"
    else
        echo "❌ $file (MISSING)"
        ALL_FILES_EXIST=false
        EXIT_CODE=1
    fi
done

if [ "$ALL_FILES_EXIST" = true ]; then
    echo "✓ PASS: All required files present"
else
    echo "❌ FAIL: Some required files are missing"
fi

echo ""

# Test 3: Check file permissions
echo "Test 3: Checking file permissions..."
if [ -f .env ]; then
    PERMS=$(stat -f "%Lp" .env 2>/dev/null || stat -c "%a" .env 2>/dev/null)
    if [ "$PERMS" = "600" ] || [ "$PERMS" = "640" ]; then
        echo "✓ PASS: .env has secure permissions ($PERMS)"
    else
        echo "⚠️  WARN: .env permissions are $PERMS (recommend 600)"
        echo "   Run: chmod 600 .env"
    fi
fi

echo ""

# Test 4: Check PHP syntax (if PHP is available)
echo "Test 4: Checking PHP syntax..."
if command -v php &> /dev/null; then
    echo "✓ PHP is installed: $(php -v | head -n 1)"

    # Check syntax of key files
    PHP_SYNTAX_OK=true
    for file in admin/flogr.php admin/security.php pages/page.php; do
        if php -l "$file" > /dev/null 2>&1; then
            echo "✓ $file syntax OK"
        else
            echo "❌ $file has syntax errors"
            php -l "$file"
            PHP_SYNTAX_OK=false
            EXIT_CODE=1
        fi
    done

    if [ "$PHP_SYNTAX_OK" = true ]; then
        echo "✓ PASS: PHP syntax checks passed"
    fi
else
    echo "⚠️  SKIP: PHP not installed, cannot check syntax"
fi

echo ""

# Test 5: Check Flickr User ID configuration
echo "Test 5: Checking Flickr configuration..."
if grep -q "OPTIONAL_SETTING('FLICKR_USER_ID',\s*'95137114@N00')" admin/config.php 2>/dev/null; then
    echo "⚠️  WARN: FLICKR_USER_ID appears to be using example value"
    echo "   Edit admin/config.php line 27 with your Flickr User ID"
elif grep -q "OPTIONAL_SETTING('FLICKR_USER_ID',\s*'')" admin/config.php 2>/dev/null; then
    echo "⚠️  WARN: FLICKR_USER_ID is empty"
    echo "   Edit admin/config.php line 27 with your Flickr User ID"
else
    echo "✓ PASS: FLICKR_USER_ID appears to be configured"
fi

echo ""

# Test 6: Check security.php is loaded
echo "Test 6: Checking security integration..."
if grep -q "require_once.*security\.php" admin/flogr.php; then
    echo "✓ PASS: security.php is loaded in flogr.php"
else
    echo "❌ FAIL: security.php not loaded in flogr.php"
    EXIT_CODE=1
fi

if grep -q "set_security_headers()" admin/flogr.php; then
    echo "✓ PASS: Security headers are enabled"
else
    echo "❌ FAIL: Security headers not called"
    EXIT_CODE=1
fi

echo ""

# Test 7: Check .gitignore
echo "Test 7: Checking .gitignore..."
if [ -f .gitignore ]; then
    if grep -q "^\.env$" .gitignore; then
        echo "✓ PASS: .env is in .gitignore"
    else
        echo "⚠️  WARN: .env not found in .gitignore"
        echo "   Add '.env' to .gitignore to prevent accidental commits"
    fi
else
    echo "⚠️  WARN: .gitignore file not found"
fi

echo ""

# Summary
echo "========================================="
echo "Test Summary"
echo "========================================="

if [ $EXIT_CODE -eq 0 ]; then
    echo "✓ All critical tests passed!"
    echo ""
    echo "Next steps:"
    echo "1. Ensure .env has your Flickr API key"
    echo "2. Update admin/config.php with your Flickr User ID"
    echo "3. Start a web server and test in browser"
    echo ""
    echo "Quick start options:"
    echo "  - PHP built-in server: php -S localhost:8080"
    echo "  - Docker: docker-compose up -d"
    echo ""
    echo "Then visit: http://localhost:8080"
else
    echo "❌ Some tests failed. Please fix the issues above."
    echo ""
    echo "See TESTING.md for detailed testing instructions."
fi

echo ""
exit $EXIT_CODE
