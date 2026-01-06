# Deploying Flogr to Render.com

This guide walks you through deploying flogr to Render's free tier with automatic deploys from GitHub.

## Prerequisites

- GitHub account with flogr repository pushed
- Render.com account (free) - sign up at https://render.com
- Flickr API key - get one at https://www.flickr.com/services/api/misc.api_keys.html
- Your Flickr User ID - look it up at http://idgettr.com/

## Step-by-Step Deployment

### 1. Push Your Code to GitHub

Make sure all files including `render.yaml` and `Dockerfile` are committed and pushed to your GitHub repository.

```bash
git add .
git commit -m "Add Render deployment configuration"
git push origin master
```

### 2. Sign Up for Render

1. Go to https://render.com
2. Click "Get Started for Free"
3. Sign up with your GitHub account (recommended for easier integration)

### 3. Create a New Web Service

1. From the Render dashboard, click "New +" → "Web Service"
2. Click "Connect" next to your GitHub account if not already connected
3. Find and select your `flogr` repository
4. Render will detect the `render.yaml` file automatically

### 4. Configure the Service

Render should auto-populate these from `render.yaml`:

- **Name**: `flogr` (or change to `flogr-demo`, etc.)
- **Runtime**: Docker
- **Plan**: Free

### 5. Add Environment Variables

Click "Advanced" to add environment variables:

**Required:**
- `FLICKR_API_KEY` = `your_flickr_api_key_here`
- `FLICKR_USER_ID` = `your_flickr_user_id` (e.g., `95137114@N00`)

**Optional (for caching):**
- `CACHE_PATH` = `/tmp/flogr_cache` (already set in render.yaml)

Click "Create Web Service"

### 6. Wait for Deployment

Render will:
1. Pull your code from GitHub
2. Build the Docker image (takes 2-5 minutes first time)
3. Deploy to a public URL like `https://flogr.onrender.com`

Watch the deployment logs in real-time.

### 7. Set Up Custom Domain (Optional)

Once deployed successfully:

1. Go to your service settings
2. Click "Custom Domain"
3. Add your subdomain: `flogr.mikecarruth.org`
4. Render will provide DNS instructions:
   - Add a CNAME record in your domain registrar:
     - Name: `flogr`
     - Value: `flogr.onrender.com` (or whatever Render provides)
5. SSL certificate is automatically provisioned (free via Let's Encrypt)

### 8. Enable Auto-Deploy

By default, Render auto-deploys when you push to `master`:

1. Go to Settings → Build & Deploy
2. Ensure "Auto-Deploy" is set to "Yes"
3. Select branch: `master`

Now every `git push` will trigger a new deployment!

## Deployment Architecture

```
GitHub (master branch)
    ↓ (auto-deploy on push)
Render.com
    ↓ (builds Docker image)
Docker Container (PHP 7.4 + Apache)
    ↓ (serves on port 80)
https://flogr.mikecarruth.org
```

## Free Tier Limitations

Render's free tier includes:

- ✅ 750 hours/month (enough for 24/7 uptime)
- ✅ Automatic SSL certificates
- ✅ Custom domains
- ✅ Auto-deploy from GitHub
- ⚠️ Spins down after 15 minutes of inactivity (cold starts take ~30 seconds)
- ⚠️ 512MB RAM limit

The spin-down means the first visitor after inactivity will wait ~30 seconds for the container to wake up.

## Troubleshooting

### Build Fails

Check the logs in Render dashboard. Common issues:
- Dockerfile syntax errors
- Missing files in repository

### Container Starts But Shows Error

- Check environment variables are set correctly
- Verify FLICKR_API_KEY is valid
- Ensure FLICKR_USER_ID is correct

### Photos Not Loading

- Verify your Flickr photos are public
- Check Flickr API key has proper permissions
- Look at browser console for errors

### Service Keeps Spinning Down

This is normal on free tier. Upgrade to paid tier ($7/month) for always-on service.

## Updating Your Deployment

Just push changes to GitHub:

```bash
git add .
git commit -m "Update flogr configuration"
git push origin master
```

Render automatically rebuilds and deploys within 2-5 minutes.

## Monitoring

From the Render dashboard you can:
- View deployment logs
- Monitor resource usage
- See request metrics
- Access shell (for debugging)

## Cost

- **Free tier**: $0/month (with spin-down)
- **Starter tier**: $7/month (always-on, no spin-down)

## Next Steps

Once flogr is deployed:

1. Update your README.md with the live demo URL
2. Test all features (photos, map, tags, slideshow)
3. Share your custom domain with users

## Support

Questions? Contact mikecarruth@gmail.com or open an issue on GitHub.
