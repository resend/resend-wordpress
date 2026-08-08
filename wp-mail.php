<?php
/**
 * WordPress Mail class
 *
 * @package Resend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Split an address header into the array of addresses the API expects.
 *
 * Callers of wp_mail() may pass several addresses in one header, comma
 * separated, as `Cc: a@example.com, b@example.com`. Entries may be a bare
 * address or the `Name <email>` form, both of which the API accepts as-is.
 *
 * @param string $content Header value.
 * @return string[]
 */
function resend_address_list( $content ) {
	return array_values( array_filter( array_map( 'trim', explode( ',', (string) $content ) ) ) );
}

/**
 * WP Mail
 *
 * @param string|string[] $to          Array or comma-separated list of email addresses to send message.
 * @param string          $subject     Email subject.
 * @param string          $message     Message contents.
 * @param string|string[] $headers     Optional. Additional headers.
 * @param string|string[] $attachments Optional. Paths to files to attach.
 * @return bool
 */
function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
	// Compact the input, apply the filters, and extract them back out.
	$atts = apply_filters( 'wp_mail', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) );

	$pre_wp_mail = apply_filters( 'pre_wp_mail', null, $atts );

	if ( null !== $pre_wp_mail ) {
		return $pre_wp_mail;
	}

	if ( isset( $atts['to'] ) ) {
		$to = $atts['to'];
	}

	if ( ! is_array( $to ) ) {
		$to = explode( ',', $to );
	}

	$api_key = Resend::get_api_key();

	$content_type = 'text/plain';
	$reply_to     = array();
	$cc           = array();
	$bcc          = array();

	if ( empty( $headers ) ) {
		$headers = array();
	} else {
		if ( ! is_array( $headers ) ) {
			// Explode the headers out, so this function can take
			// both string headers and an array of headers.
			$temp_headers = explode( "\n", str_replace( "\r\n", "\n", $headers ) );
		} else {
			$temp_headers = $headers;
		}

		$headers = array();

		if ( ! empty( $temp_headers ) ) {
			foreach ( (array) $temp_headers as $header ) {
				// Explode them out.
				list($name, $content) = explode( ':', trim( $header ), 2 );

				// Cleanup crew.
				$name    = trim( $name );
				$content = trim( $content );

				switch ( strtolower( $name ) ) {
					case 'content-type':
						if ( strpos( $content, ';' ) ) {
							list($type, $charset_content) = explode( ';', $content );
							$content_type                 = trim( $type );
						} elseif ( trim( $content ) !== '' ) {
							$content_type = trim( $content );
						}
						break;
					case 'reply-to':
						$reply_to = array_merge( $reply_to, resend_address_list( $content ) );
						break;
					case 'cc':
						$cc = array_merge( $cc, resend_address_list( $content ) );
						break;
					case 'bcc':
						$bcc = array_merge( $bcc, resend_address_list( $content ) );
						break;
					default:
						// Add it to our grand headers array.
						$headers[ trim( $name ) ] = trim( $content );
						break;
				}
			}
		}
	}

	$content_type = apply_filters( 'wp_mail_content_type', $content_type );

	if ( ! isset( $from_name ) ) {
		$from_name = Resend::get_from_name();
	}

	if ( ! isset( $form_email ) ) {
		$from_email = Resend::get_from_address();
	}

	$from_name  = apply_filters( 'wp_mail_from_name', $from_name );
	$from_email = apply_filters( 'wp_mail_from', $from_email );

	$from = $from_name . ' <' . $from_email . '>';

	$body = array(
		'from'    => $from,
		'to'      => is_array( $to ) ? $to : array( $to ),
		'subject' => $subject,
		'html'    => 'text/html' === $content_type ? $message : null,
		'text'    => 'text/plain' === $content_type ? $message : null,
	);

	if ( ! empty( $reply_to ) ) {
		$body['reply_to'] = $reply_to;
	}

	if ( ! empty( $cc ) ) {
		$body['cc'] = $cc;
	}

	if ( ! empty( $bcc ) ) {
		$body['bcc'] = $bcc;
	}

	// Anything the switch above did not recognise is a custom header the caller
	// asked for, so pass it through rather than collecting it and dropping it.
	if ( ! empty( $headers ) ) {
		$body['headers'] = $headers;
	}

	foreach ( $attachments as $attachment ) {
		if ( is_readable( $attachment ) ) {
			$body['attachments'][] = array(
				'content'  => base64_encode( file_get_contents( $attachment ) ),
				'filename' => basename( $attachment ),
			);
		}
	}

	$args = array(
		'headers'    => array(
			'Accept'        => 'application/json',
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $api_key,
		),
		'user-agent' => sprintf( 'resend-wordpress/%s (WordPress/%s; PHP/%s)', RESEND_VERSION, get_bloginfo( 'version' ), phpversion() ),
		'body'       => wp_json_encode( $body ),
	);

	$response = wp_remote_post( 'https://api.resend.com/emails', $args );

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		$error_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Core passes wp_mail_failed a WP_Error carrying the reason and the mail
		// data, and listeners log both. An empty WP_Error reduces every failure to
		// an indistinguishable blank line: "Domain is not verified" reads very
		// differently from a timeout.
		if ( is_wp_error( $response ) ) {
			$reason = $response->get_error_message();
		} elseif ( ! empty( $error_body['message'] ) ) {
			$reason = $error_body['message'];
		} else {
			$reason = 'HTTP ' . wp_remote_retrieve_response_code( $response );
		}

		do_action(
			'wp_mail_failed',
			new WP_Error(
				'wp_mail_failed',
				$reason,
				array(
					'to'          => $body['to'],
					'subject'     => $subject,
					'message'     => $message,
					'headers'     => $headers,
					'attachments' => $attachments,
				)
			)
		);
		if ( class_exists( 'Resend_Admin' ) ) {
			Resend_Admin::add_status( 'resend-error', $error_body );
		}
		return false;
	}

	do_action(
		'wp_mail_succeeded',
		array(
			'to'          => $body['to'],
			'subject'     => $body['subject'],
			'message'     => $body['html'] ?? $body['text'],
			'headers'     => $headers,
			'attachments' => $body['attachments'] ?? null,
		)
	);

	return true;
}
