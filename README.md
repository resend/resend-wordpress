# Resend WordPress Plugin

The easiest way to deliver transactional and marketing emails at scale with WordPress.

[![WordPress Plugin](https://img.shields.io/wordpress/plugin/v/resend?label=WordPress%20Plugin)](https://wordpress.org/plugins/resend/)
[![License](https://img.shields.io/badge/license-GPL--2.0-blue)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%207.2-blue)](https://www.php.net)

## Overview

Resend for WordPress ensures your transactional emails reach the inbox every time. This plugin intercepts all WordPress emails and routes them through the Resend API for reliable delivery.

**Why Resend?**
- 📬 Superior deliverability with intelligent infrastructure
- 🚀 Easy setup in minutes
- 📊 Detailed analytics and monitoring
- 💰 Generous free tier to get started
- 🔒 Enterprise security and compliance

## Features

- ✅ Simple setup with automatic activation redirect
- ✅ One-click email testing
- ✅ Custom sender name and email configuration
- ✅ Works with all WordPress email functions
- ✅ Compatible with contact forms, notification plugins, and more
- ✅ Secure API key management
- ✅ Clean, intuitive admin interface
- ✅ Multisite compatible

## Installation

### From WordPress.org Plugin Directory

1. Go to **Plugins → Add New**
2. Search for "Resend"
3. Click **Install Now**
4. Click **Activate**
5. You'll be redirected to setup

### Manual Installation

1. Download the plugin as a ZIP file
2. In WordPress admin, go to **Plugins → Add New → Upload Plugin**
3. Upload the ZIP file and click **Install Now**
4. Click **Activate Plugin**

### Via Composer

```bash
composer require resend/resend-wordpress
```

Then activate in your WordPress admin panel.

## Quick Start

1. **Create a Resend Account**
   - Sign up for free at [resend.com](https://resend.com)

2. **Generate an API Key**
   - Go to your [Resend dashboard](https://dashboard.resend.com)
   - Navigate to API Keys
   - Create a new API key

3. **Connect in WordPress**
   - Go to **Settings → Resend**
   - Paste your API key
   - Configure sender name and email

4. **Send a Test Email**
   - Click "Send Test Email"
   - Verify you received it
   - Start sending!

## Configuration

### API Key Setup

The plugin uses Resend's API to send emails. You'll need:

1. A Resend account (free tier available)
2. An API key with sending permissions
3. A verified sender domain

**To create an API key:**
1. Visit your [Resend dashboard](https://dashboard.resend.com/api-keys)
2. Click "Create API Key"
3. Copy the key (starts with `re_`)
4. Paste it in **Settings → Resend**

### Sender Configuration

Configure who emails appear to come from:

- **From Email**: The email address emails are sent from
- **From Name**: The display name in the From field

**Important**: The "From Email" must be a verified sender address in your Resend account.

## Usage

Once activated, the plugin automatically handles all WordPress emails:

- User account notifications
- Password reset emails
- Plugin notifications
- Contact form submissions
- Custom emails from themes/plugins using `wp_mail()`

### Sending Emails in Code

Use WordPress's standard `wp_mail()` function - the plugin handles the rest:

```php
wp_mail(
    'user@example.com',
    'Email Subject',
    'Email body content'
);
```

All emails are automatically routed through Resend!

## Troubleshooting

### "wp_mail already declared" Error

Another plugin or custom code is overriding `wp_mail()`. The Resend plugin must be the only one handling email sending.

**Solution**: Disable other mail plugins or check your theme/child-theme for custom mail code.

### Emails Not Sending

1. Verify your API key is correct (starts with `re_`)
2. Check that your sender email is verified in Resend
3. Send a test email from the plugin settings
4. Check your WordPress error logs
5. Visit [Resend Support](https://resend.com/help)

### Test Email Not Received

1. Check your spam/junk folder
2. Verify the test email address is correct
3. Ensure your Resend account is on an active plan
4. Check your firewall/host restrictions

## Requirements

- **WordPress**: 5.8 or later
- **PHP**: 7.2 or later
- **Resend Account**: Free or paid plan

## Security

- API keys are stored securely in the WordPress database
- All requests to Resend use HTTPS
- Nonces protect admin forms
- User capability checks on all settings pages
- All user input is sanitized and validated

**Best Practices:**
- Keep WordPress and plugins updated
- Use strong database passwords
- Never share your API key
- Regenerate keys if compromised

## Development

### Setting Up Locally

```bash
git clone https://github.com/resend/resend-wordpress.git
cd resend-wordpress
composer install
```

### Code Standards

The plugin follows WordPress coding standards:

```bash
# Run linting
composer run lint

# Auto-fix issues
composer run format
```

### File Structure

```
resend-wordpress/
├── resend.php                 # Main plugin file
├── class-resend.php           # Core plugin class
├── class-resend-admin.php     # Admin functionality
├── wp-mail.php                # wp_mail() override
├── views/                     # Admin templates
├── public/                    # CSS and JavaScript
├── composer.json              # Dependencies
└── README.md                  # This file
```

## Contributing

We welcome contributions! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

For issues and feature requests, please use [GitHub Issues](https://github.com/resend/resend-wordpress/issues).

## Support

- 📚 [Resend Documentation](https://resend.com/docs)
- 💬 [Resend Support](https://resend.com/help)
- 🐛 [GitHub Issues](https://github.com/resend/resend-wordpress/issues)
- 💼 [Enterprise Support](https://resend.com/contact)

## License

This plugin is licensed under the GPL-2.0-or-later license. See [LICENSE](LICENSE) for details.

## Credits

Built with ❤️ by [Resend](https://resend.com) and [contributors](https://github.com/resend/resend-wordpress/graphs/contributors).

---

**Ready to get started?** [Create your free Resend account](https://resend.com/signup)