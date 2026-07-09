# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024

### Added
- Initial release of Resend WordPress Plugin
- Email delivery via Resend API
- Admin settings page for API key management
- Custom sender name and email configuration
- Test email functionality
- Support for all WordPress email functions via `wp_mail()` override
- Security measures: nonces, capability checks, input sanitization
- Multisite compatibility
- Admin help tabs and documentation
- AJAX handlers for settings management
- Plugin activation/deactivation hooks
- Conflict detection for other mail plugins

### Security
- API key storage in WordPress database options
- HTTPS enforcement for all API requests
- User input sanitization and validation
- Admin nonce verification
- Capability checks on all admin functions

## Planned Features

### [1.1.0] - Planned
- Email statistics dashboard
- Bounce and complaint handling
- Scheduled email logs
- Additional Resend API features
- Enhanced error logging
- Settings import/export

### Future
- Block editor support
- Email template builder
- Multiple API key support
- Advanced logging and debugging
- REST API endpoints
- WooCommerce integration

## Deprecated

None yet.

## Known Issues

None reported yet.

## Support

For issues, feature requests, or questions:
- [GitHub Issues](https://github.com/resend/resend-wordpress/issues)
- [Resend Support](https://resend.com/help)
- [Documentation](https://resend.com/docs)