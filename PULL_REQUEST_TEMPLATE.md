## Summary

Unsplash and sanitize GET/SERVER inputs used in admin display and plugin activation to address WordPress plugin-security warnings.

## Changes

- Sanitized and unslashed $_GET['view'] and $_GET['status'] in class-resend-admin.php before usage.
- Sanitized and unslashed $_SERVER['SCRIPT_NAME'] in class-resend.php::plugin_activation().

## Security rationale

Processing raw request data without wp_unslash() and sanitization can lead to security warnings and potential vulnerabilities. These changes apply wp_unslash() and appropriate sanitization functions before using values derived from the request. No behavior changes expected.

## Testing notes

- Verify admin pages render correctly for ?view=start and ?view=stats.
- Exercise AJAX endpoints (enter key, settings, send test) — they already check nonces and sanitize inputs.
