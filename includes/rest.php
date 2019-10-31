<?php
/**
 * Sets up a WP REST route handling autoshare metadata.
 *
 * @since 1.0.0
 * @package TenUp\Autoshare
 */

namespace TenUp\Autoshare\REST;

use WP_REST_Response;
use WP_REST_Server;
use const TenUp\Autoshare\Core\Post_Meta\TWEET_BODY_KEY;
use const TenUp\Autoshare\Core\Post_Meta\ENABLE_AUTOSHARE_KEY;
use const TenUp\Autoshare\Core\POST_TYPE_SUPPORT_FEATURE;

use function TenUp\Autoshare\Core\Post_Meta\get_tweet_status_message;
use function TenUp\Autoshare\Core\Post_Meta\save_autoshare_meta_data;


/**
 * The namespace for plugin REST endpoints.
 *
 * @since 1.0.0
 */
const REST_NAMESPACE = 'autoshare';

/**
 * The plugin REST version.
 *
 * @since 1.0.0
 */
const REST_VERSION = 'v1';

/**
 * The REST route for autoshare metadata.
 *
 * @since 1.0.0
 */
const AUTOSHARE_REST_ROUTE = 'post-autoshare-meta';

/**
 * Adds WP hook callbacks.
 *
 * @since 1.0.0
 */
function add_hook_callbacks() {
	add_action( 'rest_api_init', __NAMESPACE__ . '\register_post_autoshare_meta_rest_route' );
	add_action( 'rest_api_init', __NAMESPACE__ . '\register_tweet_status_rest_field' );
}

/**
 * Registers the autoshare REST route.
 *
 * @since 1.0.0
 */
function register_post_autoshare_meta_rest_route() {
	register_rest_route(
		sprintf( '%s/%s', REST_NAMESPACE, REST_VERSION ),
		sprintf( '/%s/(?P<id>[\d]+)', AUTOSHARE_REST_ROUTE ),
		[
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => __NAMESPACE__ . '\update_post_autoshare_meta',
			'permission_callback' => __NAMESPACE__ . '\update_post_autoshare_meta_permission_check',
			'args'                => [
				'id'                 => [
					'description'       => __( 'Unique identifier for the object.', 'auto-share-for-twitter' ),
					'required'          => true,
					'sanitize_callback' => 'absint',
					'type'              => 'integer',
					'validate_callback' => 'rest_validate_request_arg',
				],
				TWEET_BODY_KEY       => [
					'description'       => __( 'Tweet text, if overriding the default', 'auto-share-for-twitter' ),
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'string',
					'validate_callback' => 'rest_validate_request_arg',
				],
				ENABLE_AUTOSHARE_KEY => [
					'description'       => __( 'Whether autoshare is enabled for the current post', 'auto-share-for-twitter' ),
					'required'          => true,
					'sanitize_callback' => 'absint',
					'type'              => 'boolean',
					'validate_callback' => 'rest_validate_request_arg',
				],
			],
		]
	);
}

/**
 * Provides the autoshare meta rest route for a provided post.
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 * @return string The REST route for a post.
 */
function post_autoshare_meta_rest_route( $post_id ) {
	return sprintf( '%s/%s/%s/%d', REST_NAMESPACE, REST_VERSION, AUTOSHARE_REST_ROUTE, intval( $post_id ) );
}

/**
 * Checks whether the current user has permission to update autoshare metadata.
 *
 * @since 1.0.0
 * @param WP_REST_Request $request A REST request containing post autoshare metadata to update.
 * @return boolean
 */
function update_post_autoshare_meta_permission_check( $request ) {
	return current_user_can( 'edit_post', $request['id'] );
}

/**
 * Updates autoshare metadata associated with a post.
 *
 * @since 1.0.0
 * @param WP_REST_Request $request A REST request containing post autoshare metadata to update.
 * @return WP_REST_Response REST response with information about the current autoshare status.
 */
function update_post_autoshare_meta( $request ) {
	$params = $request->get_params();

	save_autoshare_meta_data( $request['id'], $params );
	$message = 1 === $params[ ENABLE_AUTOSHARE_KEY ] ?
		__( 'Autoshare enabled.', 'auto-share-for-twitter' ) :
		__( 'Autoshare disabled.', 'auto-share-for-twitter' );

	return rest_ensure_response(
		[
			'enabled'  => $params[ ENABLE_AUTOSHARE_KEY ],
			'message'  => $message,
			'override' => ! empty( $params[ TWEET_BODY_KEY ] ),
		]
	);
}

/**
 * Adds a REST field returning the tweet status message array for the current post.
 *
 * @since 0.1.0
 */
function register_tweet_status_rest_field() {
	register_rest_field(
		get_post_types_by_support( POST_TYPE_SUPPORT_FEATURE ),
		'autoshare_status',
		[
			'get_callback' => function( $data ) {
				return get_tweet_status_message( $data['id'] );
			},
			'schema'       => [
				'context'     => [
					'edit',
				],
				'description' => __( 'Autoshare status message', 'auto-share-for-twitter' ),
				'type'        => 'object',
			],
		]
	);
}
