=== Resend ===

Contributors: resend
Tags: email, transactional email, smtp, mail, resend
Tested up to: 7.0
Requires at least: 5.9
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The best API to reach humans instead of spam folders. Build, test, and deliver transactional emails at scale with Resend.

== Description ==

Resend for WordPress is the easiest way to deliver transactional and marketing emails at scale. Say goodbye to spam folders and delivery issues.

**Key Features:**

* **Simple Setup** - Connect your Resend account with just an API key
* **Reliable Delivery** - Resend ensures your emails reach the inbox, not spam
* **Transactional Emails** - Perfect for password resets, notifications, confirmations, and more
* **Easy Configuration** - Set sender name and email directly from WordPress settings
* **Test Emails** - Send test emails to verify your setup works correctly
* **Statistics** - View your email sending statistics

**How it works:**

The Resend plugin hijacks WordPress' native `wp_mail()` function and routes all emails through the Resend API. This means any email sent by WordPress (password resets, notifications, contact forms, etc.) will be sent through Resend's reliable infrastructure.

**Getting Started:**

1. Create a free account at [Resend.com](https://resend.com)
2. Generate an API key in your Resend dashboard
3. Install and activate the Resend WordPress plugin
4. Paste your API key into the plugin settings
5. Configure your sender name and email address
6. Test with our built-in test email feature

**Requirements:**

* WordPress 5.8 or higher
* PHP 7.2 or higher
* A Resend account (free tier available)

**Need Help?**

* [Resend Documentation](https://resend.com/docs)
* [Resend Support](https://resend.com/help)
* [Plugin Support](https://github.com/resend/resend-wordpress)

== Installation ==

= From WordPress Admin =

1. Go to **Plugins → Add New**
2. Search for "Resend"
3. Click **Install Now**
4. Click **Activate**
5. You will be redirected to the setup page
6. Follow the on-screen instructions to connect your Resend account

= Manual Installation =

1. Download the plugin ZIP file
2. Go to **Plugins → Add New → Upload Plugin**
3. Select the downloaded ZIP file and click **Install Now**
4. Click **Activate Plugin**
5. Go to **Settings → Resend**
6. Enter your Resend API key and complete the setup

= Using FTP/SFTP =

1. Download and extract the plugin ZIP file
2. Upload the `resend` folder to `/wp-content/plugins/` via FTP
3. Activate the plugin from the **Plugins** page in WordPress admin
4. Go to **Settings → Resend** to configure

== Frequently Asked Questions ==

= Do I need a Resend account? =

Yes, you will need a Resend account to use this plugin. You can create a free account at [Resend.com](https://resend.com).

= What emails will be sent through Resend? =

All emails sent using WordPress's `wp_mail()` function will be sent through Resend. This includes password resets, user notifications, contact form submissions, and any other email sent by WordPress or plugins using the standard `wp_mail()` function.

= Will my existing email settings still work? =

The Resend plugin overrides the default WordPress email sending mechanism. Make sure you configure the plugin correctly before activating it.

= Can I test if it's working? =

Yes! The plugin includes a built-in test email feature. Go to **Settings → Resend** and click the "Send Test Email" button.

= What if another plugin is blocking emails? =

The plugin will notify you if another plugin or custom code is interfering. The plugin shows an admin notice if `wp_mail` is already defined by another plugin.

= Is my API key secure? =

Your API key is stored in the WordPress database options table. We recommend using a strong database password and keeping WordPress updated. Never share your API key with anyone.

= Can I use this on multisite? =

The plugin works with WordPress multisite, but each site will need its own Resend API key.

= What happens to my emails if I deactivate the plugin? =

When you deactivate the plugin, WordPress will revert to its default email sending behavior. Any emails queued in Resend will still be sent normally.

= Does this support attachments? =

Yes, if the Resend API supports it. The plugin uses the standard `wp_mail()` function which supports attachments.

= Can I customize the sender email or name? =

Yes! You can set a custom sender email and name in the plugin settings page.

== Changelog ==

= 1.0.0 =
* Initial release
* Basic email sending via Resend API
* Settings page with API key management
* Test email functionality
* Custom sender configuration

== Screenshots ==

1. Setup page with easy API key entry
2. Configuration page with sender settings
3. Test email feature to verify setup
4. Settings page with email history

== Support ==

For support, feature requests, or bug reports, please visit:
* [GitHub Issues](https://github.com/resend/resend-wordpress/issues)
* [Resend Support](https://resend.com/help)

== Terms of Use ==

By using this plugin, you agree to Resend's [Terms of Service](https://resend.com/terms).
