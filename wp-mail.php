<?php

/**
 * WP Mail
 *
 * @param string|string[] $to
 * @param string $subject
 * @param string $message
 * @param string|string[] $headers
 * @param string|string[] $attachments
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
						error_log( $content );
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
		'user-agent' => 'resend-wordpress/' . get_bloginfo( 'version' ),
		'body'       => wp_json_encode( $body ),
	);

	$response = wp_remote_post( 'https://api.resend.com/emails', $args );

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		do_action( 'wp_mail_failed', new WP_Error( 'wp_mail_failed' ) );
		Resend_Admin::add_status( 'resend-error', json_decode( wp_remote_retrieve_body( $response ), true ) );
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
