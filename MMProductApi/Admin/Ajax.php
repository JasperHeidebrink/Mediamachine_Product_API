<?php
/**
 * Class for handling admin
 *
 * @package MM_Product_API
 */

namespace MMProductApi\Admin;

use MMProductApi\Api\StockApi;
use MMProductApi\ProductUpdater;

class Ajax {

	/**
	 * @var int
	 */
	private $posts_per_page = 5;

	public function __construct() {
		add_action( 'wp_ajax_mmloadapidata', [ $this, 'load_api_data' ] );
		add_action( 'wp_ajax_nopriv_mmloadapidata', [ $this, 'load_api_data' ] );
	}

	/**
	 * Load the API data
	 *
	 * @return void
	 */
	public function load_api_data(): void
    {
		$page = absint($_POST['page'] ?? 0);

        $data = (new ProductUpdater())->update_prices_from_api($page );
        if ( isset( $data['error'] ) ) {
            wp_send_json_error( '<div style="background:#f00">' . esc_html($data['error']) . '</div>' );
        }
        wp_send_json_success( $data );

		if ( empty( $attachments ) ) {
			wp_send_json_error( '<div style="background:#0f0">no new files found</div>' );
		}

		$html    = '';
		$counter = ( $page - 1 ) * $this->posts_per_page;
		foreach ( $attachments as $attachment_post ) {
			$post_id    = $attachment_post->ID;
			$attachment = new Attachment( $post_id, $regenerate_thumbnails );
			$counter ++;


			if ( $attachment->is_migrated() ) {
				$attachment->regenerate_thumbnails();
				$html .= $this->show_note( $post_id, $counter, 'already migrated', '#ccc' );
				continue;
			}

			if ( ! $attachment->migrate() ) {
				$html .= $this->show_note( $post_id, $counter, 'failed', '#f00' );
				continue;
			}

			$html .= $this->show_note( $post_id, $counter, 'success', '#0f0' );
		}

		wp_send_json_success( $html );
	}

	/**
	 * @param int $post_id
	 * @param int $counter
	 * @param string $status
	 * @param string $color
	 *
	 * @return string
	 */
	private function show_note( int $post_id, int $counter = 0, string $status = 'success', string $color = '#ccc' ): string {
		$link    = wp_get_attachment_url( $post_id );
		$counter = str_pad( $counter, 6, '0', STR_PAD_LEFT );

		return sprintf(
			'<div style="background:%1$s">
						<span style="width:3rem; text-align: right;">%2$s :</span> 
						<span style="width:3rem;">%3$s :</span> 
						<strong style="width:3rem;">%4$s</strong>
						<a href="%5$s" style="margin-left:2rem;">%5$s</a>
					</div>',
			$color, $counter, $post_id, $status, $link
		);

	}
}
