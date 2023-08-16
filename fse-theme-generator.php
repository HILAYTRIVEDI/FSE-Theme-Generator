<?php
/**
 * Plugin Name: FSE Theme Generator
 * Description: A custom WP-CLI command plugin to generate Full Site Editing themes with Sass support.
 * Version: 1.0
 * Author: Hilay Trivedi
 * Author URI: https://github.com/HILAYTRIVEDI
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: fse-theme-generator
 * Domain Path: /languages
 * Requires WP: 5.8
 * Requires PHP: 7.0
 */

// Return if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( defined( 'WP_CLI' ) && WP_CLI ) {
	include_once __DIR__ . '/class-fse-theme-generator-command.php';
	WP_CLI::add_command( 'generate_fse_theme', 'FSE_Theme_Generator_Command' );
}
