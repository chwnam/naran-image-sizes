<?php
/**
 * Plugin Name:       Naran Image Sizes
 * Description:       Display registered image size information in the admin.
 * Author:            changwoo
 * Author URI:        https://blog.changwoo.pe.kr
 * Plugin URI:        https://github.com/chwnam/naran-image-sizes
 * Version:           1.0.0
 * Requires PHP:      5.6
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Naran_Image_Sizes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function fqn( $method ) {
	return '\\' . __NAMESPACE__ . '\\' . $method;
}

if ( is_admin() ) {
	add_action( 'load-options-media.php', fqn( 'add_settings' ) );

	function add_settings() {
		/** @uses image_sizes_field() */
		add_settings_field(
			'naran-image-sizes-field',
			__( 'Image Sizes', 'naran-image-sizes' ),
			fqn( 'image_sizes_field' ),
			'media',
			'default'
		);
	}

	function image_sizes_field() {
		global $_wp_additional_image_sizes;

		$image_sizes = [
			'medium_large' => [
				'width'  => get_option( 'medium_large_size_w' ),
				'height' => get_option( 'medium_large_size_h' ),
				'crop'   => false,
			]
		];

		foreach ( $_wp_additional_image_sizes as $name => $data ) {
			$image_sizes[ $name ] = $data;
		}

		ksort( $image_sizes );
		?>
        <style>
            #naran-image-sizes thead th {
                padding: 0 20px 2px 5px;
                width: unset;
            }

            #naran-image-sizes tbody th,
            #naran-image-sizes tbody td {
                padding: 2px 20px 2px 5px;
                width: unset;
            }
        </style>
        <table id="naran-image-sizes">
            <thead>
            <tr>
                <th><?php _e( 'Name', 'naran-image-sizes' ); ?></th>
                <th><?php _e( 'Width', 'naran-image-sizes' ); ?></th>
                <th><?php _e( 'Height', 'naran-image-sizes' ); ?></th>
                <th><?php _e( 'Crop', 'naran-image-sizes' ); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( $image_sizes as $name => $size ) : ?>
                <tr>
                    <th><?php echo esc_html( $name ); ?></th>
                    <td><?php echo isset( $size['width'] ) ? intval( $size['width'] ) : ''; ?></td>
                    <td><?php echo isset( $size['height'] ) ? intval( $size['height'] ) : ''; ?></td>
                    <td><?php echo isset( $size['crop'] ) && $size['crop'] ? __( 'yes', 'naran-image-sizes' ) : __( 'no', 'naran-image-sizes' ); ?></td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
		<?php
	}
}

add_action( 'plugins_loaded', fqn( 'load_textdomain' ) );
function load_textdomain() {
	load_plugin_textdomain( 'naran-image-sizes', false, wp_basename( __DIR__ ) );
}
