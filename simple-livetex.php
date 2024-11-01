<?php
/**
 * Plugin Name: Simple LiveTex
 * Plugin URI:
 * Description: Enables <a href="https://livetex.ru/">LiveTex</a> widget on all pages.
 * Version:     1.0.0
 * Author:      hayk
 * Author URI:  http://hayk.500plus.org/
 * Text Domain: simple-livetex
 * Domain Path: /languages
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Simple LiveTex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Simple LiveTex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Simple LiveTex. If not, see https://www.gnu.org/licenses/gpl-3.0.txt.
 *
 */

if (!defined('WP_CONTENT_URL')) {
	define ('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}

if (!defined('WP_CONTENT_DIR')) {
	define ('WP_CONTENT_DIR', ABSPATH.'wp-content');
}

if (!defined('WP_PLUGIN_URL')) {
	define ('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}

if (!defined('WP_PLUGIN_DIR')) {
	define ('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
}

function activate_simple_livetex() {
	if (!get_option('simple_livetex_id')) {
		add_option('simple_livetex_id', '0');
	}
}

function deactive_simple_livetex() {

}

function uninstall_simple_livetex() {
	delete_option('simple_livetex_id');
}

function admin_init_simple_livetex() {
	register_setting('simple-livetex', 'simple_livetex_id');
}

function admin_menu_simple_livetex() {
	add_options_page('Simple LiveTex', 'Simple LiveTex', 'manage_options', 'simple-livetex', 'options_page_simple_livetex');
}

function options_page_simple_livetex() {
	include (WP_PLUGIN_DIR.'/simple-livetex/options.php');
}

function simple_livetex() {
	if ($simple_livetex_id = get_option('simple_livetex_id')) {
		wp_enqueue_script('simple-livetex', '//cs15.livetex.ru/js/client.js');
		$code = "window['liveTex'] = true; window['liveTexID'] = $simple_livetex_id; window['liveTex_object'] = true;";
		wp_add_inline_script('simple-livetex', $code, 'before');
	}
}

register_activation_hook(__FILE__, 'activate_simple_livetex');
register_deactivation_hook(__FILE__, 'deactive_simple_livetex');
register_uninstall_hook(__FILE__, 'uninstall_simple_livetex');

if (is_admin()) {
	add_action('admin_init', 'admin_init_simple_livetex');
	add_action('admin_menu', 'admin_menu_simple_livetex');
}

if (!function_exists('wp_get_current_user')) {
	include (ABSPATH . 'wp-includes/pluggable.php');
}

if (!is_admin()) {
	add_action('wp_head', 'simple_livetex');
}