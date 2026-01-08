# Installation and Usage Guide

## Quick Start

### Installation Steps

1. **Upload the Plugin**
   - Download or clone this repository
   - Copy the `de-static-site-analytics` folder to your WordPress installation's `/wp-content/plugins/` directory

2. **Activate the Plugin**
   - Log in to your WordPress admin panel
   - Navigate to **Plugins** > **Installed Plugins**
   - Find "DE Static Site Analytics" in the list
   - Click **Activate**
   - For multisite: You can **Network Activate** to make it available across all sites

3. **Configure GTM Container ID**
   - Go to **Settings** > **DE Analytics**
   - Enter your Google Tag Manager container ID (format: `GTM-XXXXXXX`)
   - Click **Save Settings**

### Example Configuration

**GTM Container ID Field:**
```
GTM-ABC1234
```

### What Happens After Configuration

Once you've saved a valid GTM container ID, the plugin automatically adds the following code to every page:

#### In the `<head>` section:
```html
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-ABC1234');</script>
<!-- End Google Tag Manager -->
```

#### After the opening `<body>` tag:
```html
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ABC1234"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
```

## Multisite Considerations

### Network Activation
When network activated, each site in your multisite network can have its own GTM container ID configured independently.

### Per-Site Configuration
1. Switch to the site you want to configure
2. Go to **Settings** > **DE Analytics** for that site
3. Enter the site-specific GTM container ID
4. Each site can have a different container ID or share the same one

### Network Admin Access
The plugin menu is also available in the Network Admin panel for easy access.

## Verification

To verify the plugin is working:

1. **Save your GTM container ID** in the settings
2. **Visit your site's front-end** (any public page)
3. **View page source** (Right-click > View Page Source)
4. **Search for "Google Tag Manager"** - you should see both the script and noscript tags with your container ID

Alternatively, you can use browser developer tools:
- Open DevTools (F12)
- Check the Network tab for requests to `googletagmanager.com`
- Check Console for `dataLayer` object

## Troubleshooting

### Container ID Not Saving
- Ensure the format is correct: `GTM-XXXXXXX` (uppercase)
- The plugin will show an error if the format is invalid

### GTM Code Not Appearing
- Verify you saved the container ID
- Check that you're viewing the front-end (not admin pages)
- Clear any caching plugins
- Ensure your theme supports the `wp_head()` and `wp_body_open()` hooks

### Multisite Issues
- Ensure you're configuring the correct site in the network
- Each site needs its own configuration
- Network activation doesn't automatically configure all sites

## Security Features

- Input sanitization of all user input
- GTM container ID format validation
- Proper escaping of output to prevent XSS
- Follows WordPress security best practices

## Browser Compatibility

The GTM code provided by this plugin is compatible with all modern browsers and follows Google's official implementation guidelines.
