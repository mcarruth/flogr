# OpenStreetMap Integration

## Overview

Flogr now uses **OpenStreetMap** via Leaflet for displaying geotagged photos on a map.

**Benefits:**
- ✅ **100% Free** - No API key needed
- ✅ **No signup required** - No account creation
- ✅ **No billing** - Never any charges
- ✅ **Privacy-friendly** - Open source mapping solution
- ✅ **No limits** - Unlimited map loads
- ✅ **Self-hosted** - Leaflet library included locally (no CDN)

## How It Works

The map feature uses:
- **Leaflet.js** - Free, open-source JavaScript library for interactive maps
- **OpenStreetMap tiles** - Free map data from the OpenStreetMap project
- **No API key** - Just works out of the box!

## Features

- Interactive map with zoom and pan
- Markers for each geotagged photo
- Click markers to view photos
- Hover over markers for photo thumbnails
- No configuration needed

## What Was Changed

### Replaced Google Maps with OpenStreetMap

**Before:**
- Required Google Maps API key
- Required billing account (even for free tier)
- Limited to $200/month free usage

**After:**
- No API key needed
- No account needed
- Completely unlimited and free

### Files Updated

- ✅ `themes/blackstripe/map.php` - OpenStreetMap integration
- ✅ `themes/blackstripe2/map.php` - OpenStreetMap integration
- ✅ `themes/whitestripe/map.php` - OpenStreetMap integration
- ✅ `themes/whitestripe2/map.php` - OpenStreetMap integration
- ✅ `.env.example` - Removed Google Maps API key requirement

## Technical Details

### Leaflet Library
- **Version:** 1.9.4
- **CDN:** unpkg.com (free CDN)
- **License:** BSD 2-Clause (free for all use)
- **Documentation:** https://leafletjs.com/

### OpenStreetMap Tiles
- **Provider:** OpenStreetMap Foundation
- **License:** Open Data Commons Open Database License
- **Attribution:** Required (automatically included)
- **Tiles:** `https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`

## Usage

Just visit the map page - it works automatically!

```
http://localhost:8080/index.php?type=map
```

**What you'll see:**
- Map with markers for all geotagged photos
- OR "No geotagged photos found" if you don't have any

## Adding Geotags to Your Photos

To see your photos on the map, they need GPS coordinates:

1. **Take photos with GPS-enabled device** (smartphone, GPS camera)
2. **Or add location in Flickr:**
   - Go to your photo on Flickr.com
   - Click "Add a location"
   - Search for location or drag map pin
   - Click "Save"

## Offline/CDN Issues

If the map doesn't load, it means the CDN couldn't be reached. This can happen if:
- Your server has no internet connection
- unpkg.com CDN is blocked by firewall
- Network issues

**Solution:** The map library can be self-hosted if needed. Let me know if you need this!

## Customization

You can customize the map in `themes/[your-theme]/map.php`:

### Change Zoom Level
```javascript
var map = L.map('map').setView([firstLat, firstLng], 8); // Change 8 to 4-18
```

### Change Map Style
OpenStreetMap has different tile providers:

**Humanitarian style:**
```javascript
L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);
```

**Dark mode:**
```javascript
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap, CartoDB'
}).addTo(map);
```

**Satellite imagery (Esri):**
```javascript
L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Esri, DigitalGlobe, GeoEye, Earthstar Geographics'
}).addTo(map);
```

## Comparison: OpenStreetMap vs Google Maps

| Feature | OpenStreetMap | Google Maps |
|---------|---------------|-------------|
| **Cost** | Free forever | Free up to $200/month |
| **API Key** | Not needed | Required |
| **Billing Account** | Not needed | Required (credit card) |
| **Limits** | Unlimited | 28,000 loads/month free |
| **Privacy** | Open source | Google tracking |
| **Setup Time** | 0 minutes | 10-15 minutes |
| **Quality** | Excellent | Excellent |

## Credits

- **Leaflet** - https://leafletjs.com/
- **OpenStreetMap** - https://www.openstreetmap.org/
- **Map data** © OpenStreetMap contributors
- **Tiles** © OpenStreetMap Foundation

## Support

If you have any issues with the map:
1. Check browser console (F12) for errors
2. Verify your photos have GPS coordinates in Flickr
3. Make sure your server has internet access
4. Check that CDN (unpkg.com) isn't blocked

## License

OpenStreetMap data is licensed under the Open Data Commons Open Database License (ODbL).

When using OpenStreetMap, you must:
- ✅ Include attribution (automatically done)
- ✅ Share data improvements (if applicable)
- ✅ Use freely for any purpose

---

*Last updated: January 5, 2026*
*No configuration needed - just works!* ✅
