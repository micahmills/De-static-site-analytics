# DE Static Site Analytics WordPress Plugin

A simple WordPress plugin for adding Google Tag Manager to WordPress multisite installations.

## Description

This plugin provides a simple way to add Google Tag Manager tracking code to all pages on your WordPress site or WordPress multisite network. It includes a single text field in the WordPress admin where you can enter your GTM container ID.

## Features

- Simple settings page in WordPress admin
- Single text field for GTM container ID
- Automatic validation of container ID format
- Adds GTM code to all pages on the front-end
- Full WordPress multisite support
- Network admin compatible
- Follows Google Tag Manager implementation best practices

## Installation

1. Upload the `de-static-site-analytics` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > DE Analytics to configure your GTM container ID

## Configuration

1. Navigate to **Settings > DE Analytics** in your WordPress admin
2. Enter your Google Tag Manager container ID in the format `GTM-XXXXXXX`
3. Click **Save Settings**

The plugin will automatically add the GTM tracking code to all pages on your site.

## Multisite Usage

This plugin is fully compatible with WordPress multisite. It can be:
- Network activated to be available across all sites
- Activated individually on specific sites
- Configured per-site with different GTM container IDs

## GTM Container ID Format

The container ID should be in the format: `GTM-XXXXXXX`

Example: `GTM-ABC1234`

## What Gets Added

When a valid container ID is configured, the plugin adds:

1. **GTM JavaScript snippet** in the `<head>` section
2. **GTM noscript iframe** immediately after the opening `<body>` tag

This follows the official Google Tag Manager implementation guidelines.

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## License

This plugin is licensed under the GPL v2 or later.

## Support

For issues and feature requests, please visit: https://github.com/micahmills/De-static-site-analytics
