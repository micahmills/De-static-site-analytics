# DE Static Site Analytics WordPress Plugin

A simple WordPress plugin for adding Google Tag Manager or Google Analytics 4 to WordPress multisite installations.

## Description

This plugin provides a simple way to add Google Tag Manager or Google Analytics 4 tracking code to all pages on your WordPress site or WordPress multisite network. It includes a single text field in the WordPress admin where you can enter your GTM container ID or GA4 measurement ID.

## Features

- Simple settings page in WordPress admin
- Single text field for GTM container ID or GA4 measurement ID
- Automatic validation of tag ID format
- Adds appropriate tracking code to all pages on the front-end
- Supports both Google Tag Manager and Google Analytics 4
- Full WordPress multisite support
- Network admin compatible
- Follows Google implementation best practices

## Installation

1. Upload the `de-static-site-analytics` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > DE Analytics to configure your GTM container ID

## Configuration

1. Navigate to **Settings > DE Analytics** in your WordPress admin
2. Enter your Google Tag Manager container ID (format: `GTM-XXXXXXX`) or Google Analytics 4 measurement ID (format: `G-XXXXXXXXXX`)
3. Click **Save Settings**

The plugin will automatically detect the tag type and add the appropriate tracking code to all pages on your site.

## Multisite Usage

This plugin is fully compatible with WordPress multisite. It can be:
- Network activated to be available across all sites
- Activated individually on specific sites
- Configured per-site with different tag IDs

## Supported Tag Formats

### Google Tag Manager (GTM)
The container ID should be in the format: `GTM-XXXXXXX`

Example: `GTM-ABC1234`

### Google Analytics 4 (GA4)
The measurement ID should be in the format: `G-XXXXXXXXXX`

Example: `G-ABC1234567`

## What Gets Added

The plugin automatically detects your tag type and adds the appropriate tracking code:

### For Google Tag Manager (GTM-XXXXXXX):
1. **GTM JavaScript snippet** in the `<head>` section
2. **GTM noscript iframe** immediately after the opening `<body>` tag

### For Google Analytics 4 (G-XXXXXXXXXX):
1. **GA4 gtag.js snippet** in the `<head>` section

This follows the official implementation guidelines for each platform.

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## License

This plugin is licensed under the GPL v2 or later.

## Support

For issues and feature requests, please visit: https://github.com/micahmills/De-static-site-analytics
