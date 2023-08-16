<?php
/**
 * Class FSE_Theme_Generator_Command
 *
 * @package FSE_Theme_Generator
 * @since 1.0
 * @license GPL2+
 */

// Return if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FSE_Theme_Generator_Command extends WP_CLI_Command {


	/**
	 * Generates a Full Site Editing theme.
	 *
	 * ## OPTIONS
	 *
	 * <theme-name>
	 * : The name of the theme.
	 *
	 * [--sass=<sass>]
	 * : Whether to support Sass. (true or false)
	 *
	 * ## EXAMPLES
	 *
	 * wp generate_fse_theme my_fse_theme --sass=true
	 *
	 * @param array $args
	 * @param array $assoc_args
	 */
	public function __invoke( $args, $assoc_args ) {
		$theme_name   = $args[0];
		$support_sass = isset( $assoc_args['sass'] ) && 'true' === $assoc_args['sass'];

		$theme_directory = $this->createThemeDirectory( $theme_name );

		$this->createTemplateFilesAndParts( $theme_directory );
		$this->createAssetsDirectoriesAndFiles( $theme_directory, $support_sass, $theme_name );

		$this->createPackageJson( $theme_directory, $theme_name, $support_sass );
		$this->createThemeJson( $theme_directory );

		$message = "Full Site Editing theme '$theme_name' generated";
		if ( $support_sass ) {
			$message .= ' with Sass support';
		}
		WP_CLI::success( $message );
	}

	private function createThemeDirectory( $theme_name ) {
		// Convert theme name to slug
		$theme_name = strtolower( str_replace( ' ', '-', $theme_name ) );

		$theme_directory = WP_CONTENT_DIR . '/themes/' . $theme_name;
		wp_mkdir_p( $theme_directory );

		// Create template folder
		wp_mkdir_p( $theme_directory . '/template' );

		// Create template parts folder
		wp_mkdir_p( $theme_directory . '/template-parts' );

		return $theme_directory;
	}

	private function createTemplateFilesAndParts( $theme_directory ) {
		$template_files = array( 'single.html', 'archive.html', '404.html', 'search.html', 'index.html' );
		$template_parts = array( 'header.html', 'footer.html', 'post-meta.html' );

		foreach ( $template_files as $template_file ) {
			touch( $theme_directory . '/template/' . $template_file );
		}

		foreach ( $template_parts as $template_part ) {
			touch( $theme_directory . '/template-parts/' . $template_part );
		}
	}

	private function createAssetsDirectoriesAndFiles( $theme_directory, $support_sass, $theme_name ) {
		$assets_directory = $theme_directory . '/assets';
		$subdirectories   = array( 'images', 'js', 'css' );

		foreach ( $subdirectories as $subdirectory ) {
			wp_mkdir_p( $assets_directory . '/' . $subdirectory );
		}

		if ( $support_sass ) {
			$sass_directories = array( 'sass', 'sass/global', 'sass/variables' );

			foreach ( $sass_directories as $sass_directory ) {
				wp_mkdir_p( $assets_directory . '/' . $sass_directory );
			}

			$sass_globals_path   = $assets_directory . '/sass/global/_globals.scss';
			$sass_variables_path = $assets_directory . '/sass/variables/_variables.scss';
			$sass_style_path     = $assets_directory . '/sass/style.scss';

			file_put_contents( $sass_globals_path, '/* Initial global styles */' );
			file_put_contents( $sass_variables_path, '/* Variables for styles */' );
			file_put_contents( $sass_style_path, "@import 'variables/_variables';\n@import 'global/_globals';" );
		}

		$js_content  = "console.log('Hello from $theme_name theme JS!');";
		$css_content = "/* Styles for $theme_name theme */";

		file_put_contents( $assets_directory . '/js/' . $theme_name . '.js', $js_content );
		file_put_contents( $assets_directory . '/css/style.css', $css_content );
	}

	private function createPackageJson( $theme_directory, $theme_name, $support_sass ) {
		$package_json = array(
			'name'            => $theme_name,
			'version'         => '1.0.0',
			'description'     => 'Your theme Description',
			'author'          => 'Hilay Trivedi',
			'license'         => 'MIT',
			'keywords'        => array(
				'WordPress',
				'FSE',
				$theme_name,
			),
			'homepage'        => 'https://github.com/' . $theme_name . '',
			'bugs'            => array(
				'url' => 'https://github.com/' . $theme_name . '/issues',
			),
			'devDependencies' => array(
				'@wordpress/i18n' => '^4.37.0',
				'dir-archiver'    => '^1.1.1',
				'node-sass'       => '^7.0.1',
				'npm-run-all'     => '^4.1.5',
				'rtlcss'          => '^3.5.0',
			),
			'rtlcssConfig'    => array(
				'options' => array(
					'autoRename'       => false,
					'autoRenameStrict' => false,
					'blacklist'        => array(),
					'clean'            => true,
					'greedy'           => false,
					'processUrls'      => false,
					'stringMap'        => array(),
				),
				'plugins' => array(),
				'map'     => false,
			),
			'scripts'         => array(
				'watch'       => 'node-sass assets/sass/ -o ./ --source-map true --output-style expanded --indent-type tab --indent-width 1 -w',
				'compile:css' => "node-sass sass/ -o ./ && stylelint '*.css' --fix || true && stylelint '*.css' --fix",
				'compile:rtl' => 'rtlcss style.css style-rtl.css',
				'lint:scss'   => "wp-scripts lint-style 'sass/**/*.scss'",
				'lint:js'     => "wp-scripts lint-js 'js/*.js'",
				'bundle'      => 'dir-archiver --src . --dest ../_s.zip --exclude .DS_Store .stylelintrc.json .eslintrc .git .gitattributes .github .gitignore README.md composer.json composer.lock node_modules vendor package-lock.json package.json .travis.yml phpcs.xml.dist sass style.css.map yarn.lock',
				'build'       => 'npm-run-all --sequential build:**',
			),
		);

		file_put_contents( $theme_directory . '/package.json', json_encode( $package_json, JSON_PRETTY_PRINT ) );
	}

	private function createThemeJson( $theme_directory ) {
		$theme_json = array(
			'schema'        => 'https://schemas.wp.org/trunk/theme.json',
			'version'       => 2,
			'settings'      => array(
				// ... copy the settings from your provided theme.json ...
			),
			'styles'        => array(
				// ... copy the styles from your provided theme.json ...
			),
			'templateParts' => array(
				// ... copy the templateParts from your provided theme.json ...
			),
		);

		file_put_contents( $theme_directory . '/theme.json', json_encode( $theme_json, JSON_PRETTY_PRINT ) );
	}
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'generate_fse_theme', 'FSE_Theme_Generator_Command' );
}
