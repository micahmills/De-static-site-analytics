# DE Static Site Analytics

A WordPress multisite plugin for adding Google Tag Manager to DE static sites.

## Overview

This plugin provides a simple way to add Google Tag Manager tracking code to all pages on your WordPress site or WordPress multisite network.

## Features

- ✅ Simple settings page with a single text field for GTM container ID
- ✅ Automatic validation of GTM container ID format (GTM-XXXXXXX)
- ✅ Adds GTM tracking code to all pages automatically
- ✅ Full WordPress multisite support
- ✅ Network admin compatible
- ✅ Follows Google Tag Manager best practices

## Quick Start

1. Copy the `de-static-site-analytics` folder to `/wp-content/plugins/`
2. Activate the plugin in WordPress admin
3. Go to **Settings > DE Analytics**
4. Enter your GTM container ID (e.g., `GTM-ABC1234`)
5. Save settings

That's it! The GTM code will now appear on all pages of your site.

## Documentation

- [Installation Guide](INSTALLATION.md) - Detailed installation and configuration instructions
- [Plugin README](de-static-site-analytics/README.md) - Plugin-specific documentation

## Plugin Structure

```
de-static-site-analytics/
├── de-static-site-analytics.php  # Main plugin file
└── README.md                     # Plugin documentation
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- A valid Google Tag Manager container ID

## License

GPL v2 or later
