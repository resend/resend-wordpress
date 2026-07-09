# Contributing to Resend WordPress Plugin

Thank you for your interest in contributing! We welcome bug reports, feature requests, and pull requests.

## Code of Conduct

Please note that this project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project you agree to abide by its terms.

## How to Report Bugs

### Before Submitting a Bug Report

- Check the [FAQ](README.md#troubleshooting) in the README
- Check the [existing issues](https://github.com/resend/resend-wordpress/issues)
- Check your WordPress error logs (`wp-content/debug.log`)
- Test with all other plugins deactivated

### Submitting a Bug Report

When submitting a bug, please include:

```
**Describe the bug**
A clear description of what the bug is.

**Steps to reproduce**
1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

**Expected behavior**
What you expected to happen.

**Screenshots**
If applicable, add screenshots.

**Environment**
- WordPress Version: [e.g. 6.8]
- PHP Version: [e.g. 8.0]
- Plugin Version: [e.g. 1.0.0]
- Other plugins: [List any other plugins you have installed]

**Error Logs**
[Paste any relevant error messages from your error log]
```

## How to Suggest Enhancements

### Before Submitting

- Check if the enhancement has already been suggested
- Check the plugin's feature scope and goals

### Submitting an Enhancement

```
**Is your feature request related to a problem?**
Describe the problem.

**Describe the solution you'd like**
A clear description of what you want to happen.

**Describe alternatives you've considered**
Alternative implementations or features.

**Additional context**
Any other context.
```

## Pull Requests

### Before Starting

1. Fork the repository
2. Create a new branch: `git checkout -b my-feature`
3. Install dependencies: `composer install`

### Code Standards

The plugin follows [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/). Before submitting:

```bash
# Check your code
composer run lint

# Auto-fix issues
composer run format
```

### Writing Code

- Use 4 spaces for indentation (no tabs)
- Use single quotes for strings except where double quotes are needed
- Add hooks and filters where possible for extensibility
- Add inline comments for complex logic
- Follow WordPress naming conventions

### Commit Messages

Write clear commit messages:

```
Short description (50 characters or less)

More detailed explanation if necessary. Wrap at about 72
characters. Why is this change needed?

Fixes #123
```

### Testing

- Test with WordPress 5.8+ and the latest version
- Test with PHP 7.2+ (including 8.0+)
- Test with all other email plugins deactivated
- Test the admin interface thoroughly
- Send test emails to verify functionality

### Documentation

- Update README.md if adding features
- Update inline code comments
- Update the CHANGELOG if needed
- Add PHPDoc blocks for functions

### Submitting

1. Push to your fork
2. Submit a pull request to the main branch
3. Link any related issues
4. Describe what your PR does

## Development Setup

### Local Installation

```bash
# Clone the repository
git clone https://github.com/resend/resend-wordpress.git
cd resend-wordpress

# Install dependencies
composer install

# Create a symlink to your WordPress plugins directory
ln -s $(pwd) /path/to/wp-content/plugins/resend
```

### File Structure

```
resend-wordpress/
├── resend.php                 # Main plugin file
├── class-resend.php           # Core plugin class
├── class-resend-admin.php     # Admin functionality
├── wp-mail.php                # wp_mail() override
├── views/                     # Admin interface templates
├── public/                    # CSS and JavaScript
├── composer.json              # PHP dependencies
├── phpcs.xml.dist             # Code standards config
└── README.md                  # Documentation
```

### Useful Commands

```bash
# Run code linting
composer run lint

# Auto-fix code standards issues
composer run format

# Check PHP compatibility
phpcs --standard=PHPCompatibility .

# Check against WordPress standards
phpcs --standard=WordPress .
```

## Plugin Development Tips

### Adding a Settings Page

```php
add_options_page(
    'Page Title',
    'Menu Title', 
    'manage_options',
    'page-slug',
    'callback_function'
);
```

### Adding AJAX Handlers

```php
add_action( 'wp_ajax_my_action', 'my_ajax_handler' );

function my_ajax_handler() {
    check_ajax_referer( 'nonce-name' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error();
    }
    // Your code here
    wp_send_json_success();
}
```

### Using Hooks

```php
// Apply a filter
$value = apply_filters( 'resend_my_filter', $value );

// Execute an action
do_action( 'resend_my_action' );
```

### Sanitizing Input

```php
$email = sanitize_email( $_POST['email'] );
$text = sanitize_text_field( $_POST['text'] );
```

### Escaping Output

```php
echo esc_html( $text );
echo esc_url( $url );
echo esc_attr( $attr );
```

## Review Process

1. Automated tests check code standards
2. A maintainer reviews your code
3. You may be asked to make changes
4. Once approved, your PR is merged
5. It will be included in the next release

## Questions?

- Check the [README](README.md)
- Read the [Code](https://github.com/resend/resend-wordpress)
- Create an [Issue](https://github.com/resend/resend-wordpress/issues)
- Visit [Resend Docs](https://resend.com/docs)

## License

By contributing to this project, you agree that your contributions will be licensed under its GPL-2.0-or-later license.

---

Thank you for contributing to making Resend for WordPress better! 🎉