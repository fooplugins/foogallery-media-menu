<?php
/**
 * FooGallery Media Menu Extension
 *
 * Move the FooGallery menu items under the Media Menu
 *
 * @package   FooGalleryMediaMenu
 * @author    Brad Vincent <brad@fooplugins.com>
 * @license   GPL-2.0+
 * @link      https://github.com/fooplugins/foogallery-media-menu-extension
 * @copyright 2014 FooPlugins LLC
 *
 * @wordpress-plugin
 * Plugin Name: FooGallery - Media Menu Extension
 * Description: Move the FooGallery menu items under the Media Menu
 * Version:     1.0.2
 * Author:      bradvin
 * Author URI:  http://fooplugins.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( !class_exists( 'FooGallery_Media_Menu_Extension' ) ) {

	class FooGallery_Media_Menu_Extension {

		function __construct() {
			add_filter( 'foogallery_gallery_posttype_register_args', array($this, 'remove_posttype_menus') );
			add_filter( 'foogallery_album_posttype_register_args', array($this, 'remove_posttype_menus') );
			add_filter( 'foogallery_admin_menu_parent_slug', array($this, 'change_menu_parent_slug') );
			add_action( 'foogallery_admin_menu_before', array($this, 'create_menus') );
			//add_filter( 'foogallery_extensions_activate_success-media_menu', array($this, 'override_activation_success_message') );
			add_filter( 'foogallery_extensions_redirect_url-media_menu', array($this, 'override_redirection_url'), 10, 2 );
			add_filter( 'foogallery_admin_menu_labels', array($this, 'override_menu_labels') );
		}

		function remove_posttype_menus($args) {
			$args['show_in_menu'] = false;
			return $args;
		}

		function change_menu_parent_slug( $parent_slug ) {
			return 'upload.php';
		}

		function create_menus() {
			add_media_page( __( 'Galleries', 'foogallery' ), __( 'Galleries', 'foogallery' ), 'upload_files', 'edit.php?post_type=' . FOOGALLERY_CPT_GALLERY );
			add_media_page( __( 'Add Gallery', 'foogallery' ), __( 'Add Gallery', 'foogallery' ), 'upload_files', 'post-new.php?post_type=' . FOOGALLERY_CPT_GALLERY );
			$api = new FooGallery_Extensions_API();
			if ( $api->is_active( 'albums' ) ) {
				add_media_page( __( 'Albums', 'foogallery' ), __( 'Albums', 'foogallery' ), 'upload_files', 'edit.php?post_type=' . FOOGALLERY_CPT_ALBUM );
				add_media_page( __( 'Add Album', 'foogallery' ), __( 'Add Album', 'foogallery' ), 'upload_files', 'post-new.php?post_type=' . FOOGALLERY_CPT_ALBUM );
			}
		}

//		function override_activation_success_message($result) {
//			$result['message'] = $result['message'] . ' Booyah!';
//			return $result;
//		}

		function override_redirection_url($redirect_url, $action) {
			if ( 'activate' === $action ) {
				$redirect_url = str_replace( FOOGALLERY_ADMIN_MENU_PARENT_SLUG, 'upload.php', $redirect_url );
				$redirect_url = str_replace( 'upload.php&', 'upload.php?', $redirect_url );
			} else if ( 'deactivate' === $action ) {
				$redirect_url = str_replace( 'upload.php', FOOGALLERY_ADMIN_MENU_PARENT_SLUG, $redirect_url );
				$redirect_url = str_replace( FOOGALLERY_ADMIN_MENU_PARENT_SLUG .'?', FOOGALLERY_ADMIN_MENU_PARENT_SLUG . '&', $redirect_url );
			}
			return $redirect_url;
		}

		function override_menu_labels() {
			return array(
				array(
					'page_title' => __( 'FooGallery Settings', 'foogallery' ),
					'menu_title' => __( 'Gallery Settings', 'foogallery' )
				),
				array(
					'page_title' => __( 'FooGallery Extensions', 'foogallery' ),
					'menu_title' => __( 'Gallery Extensions', 'foogallery' )
				),
				array(
					'page_title' => __( 'FooGallery Help', 'foogallery' ),
					'menu_title' => __( 'Gallery Help', 'foogallery' )
				),
				array(
					'page_title' => __( 'FooGallery System Information', 'foogallery' ),
					'menu_title' => __( 'Gallery System Info', 'foogallery' ),
				),
			);
		}
	}
}
