# Map Setup Guide

## ✅ **UPDATE: Now Using OpenStreetMap (Free!)**

**Good news!** Flogr now uses **OpenStreetMap** instead of Google Maps.

- ✅ **100% Free** - No API key needed
- ✅ **No signup** - No account required
- ✅ **Works immediately** - No configuration

**You can skip this entire guide!** The map just works now. See [OPENSTREETMAP.md](OPENSTREETMAP.md) for details.

---

## Legacy: Google Maps Setup (No Longer Needed)

*This guide is kept for reference only. The map feature now uses OpenStreetMap by default.*

~~The map feature in Flogr displays your geotagged photos on a Google Map. As of 2018, Google requires an API key to use the Maps JavaScript API.~~

## Quick Setup

### 1. Get a Google Maps API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project (or select an existing one)
3. Enable the **Maps JavaScript API**:
   - Go to "APIs & Services" → "Library"
   - Search for "Maps JavaScript API"
   - Click "Enable"
4. Create credentials:
   - Go to "APIs & Services" → "Credentials"
   - Click "Create Credentials" → "API Key"
   - Copy your API key

### 2. Secure Your API Key (Important!)

1. In the Google Cloud Console, click on your API key
2. Under "Application restrictions":
   - Choose "HTTP referrers (web sites)"
   - Add your domain: `yourdomain.com/*`
   - For local testing, add: `localhost/*`
3. Under "API restrictions":
   - Choose "Restrict key"
   - Select only "Maps JavaScript API"
4. Click "Save"

### 3. Add to Your .env File

Edit your `.env` file and add:

```bash
GOOGLE_MAPS_API_KEY=your_actual_api_key_here
```

### 4. Restart Your Server

If using Docker:
```bash
docker-compose restart
```

If using Apache:
```bash
sudo systemctl restart apache2
```

## Cost Information

- Google provides $200/month in free Maps API usage
- This equals approximately 28,000 map loads per month
- For most personal photo blogs, this is more than enough
- Monitor usage at: https://console.cloud.google.com/

## Testing

1. Visit: `http://yourdomain.com/index.php?type=map`
2. You should see:
   - A map with markers for geotagged photos (if you have any)
   - OR "No geotagged photos found" (if you don't have any)

## Troubleshooting

### Map Shows Error Message

**Error:** "Google Maps failed to load. Please add a valid GOOGLE_MAPS_API_KEY..."

**Solutions:**
1. Make sure you added the API key to your `.env` file
2. Verify the key is correct (no extra spaces)
3. Ensure you enabled the Maps JavaScript API in Google Cloud Console
4. Check that you restarted your web server after adding the key

### Map Shows "No Geotagged Photos"

**This is normal if:**
- You haven't uploaded photos with GPS data
- Your Flickr photos don't have location tags

**To add location data to photos:**
1. Use a camera/phone with GPS
2. Or manually add location in Flickr: Photo → Edit → Map

### Map Shows Blank/Grey Tiles

**Solutions:**
1. Check browser console (F12) for JavaScript errors
2. Verify API key restrictions allow your domain
3. Check that you haven't exceeded the free tier quota
4. Ensure your API key has Maps JavaScript API enabled

## What Was Changed

### Files Updated:
- `themes/blackstripe2/map.php` - Updated to use modern API with key
- `themes/blackstripe/map.php` - Updated API URL
- `themes/whitestripe/map.php` - Updated API URL
- `themes/whitestripe2/map.php` - Updated API URL
- `.env.example` - Added GOOGLE_MAPS_API_KEY field

### Changes Made:
1. ✅ Changed from HTTP to HTTPS
2. ✅ Added API key parameter
3. ✅ Added error handling for missing API key
4. ✅ Added error handling for no geotagged photos
5. ✅ Added helpful error messages with setup instructions

## Old vs New

**Before (deprecated):**
```html
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
```

**After (modern):**
```html
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
```

## Additional Resources

- [Google Maps JavaScript API Documentation](https://developers.google.com/maps/documentation/javascript)
- [Get an API Key](https://developers.google.com/maps/documentation/javascript/get-api-key)
- [API Key Best Practices](https://developers.google.com/maps/api-security-best-practices)
- [Pricing Calculator](https://mapsplatformtransition.withgoogle.com/calculator)

## Security Best Practices

1. ✅ **Restrict your API key** to specific domains
2. ✅ **Don't commit** your API key to version control (it's in `.env`, which is in `.gitignore`)
3. ✅ **Limit API access** to only Maps JavaScript API
4. ✅ **Monitor usage** regularly in Google Cloud Console
5. ✅ **Set up billing alerts** to avoid unexpected charges

---

*Last updated: January 5, 2026*
*Flogr Version: 2.5.7+*
