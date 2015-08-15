<?php

namespace Child_Page_Tree\Lib;

/**
 * Class WPUpdatePhp
 *
 * @package Child_Page_Tree\Lib
 * @author Coen Jacobs
 * @link https://github.com/WPupdatePHP/wp-update-php
 */
class WPUpdatePhp {
	/** @var String */
	private $minimum_version;

	/** @var String */
	private $plugin_name;

	/**
	 * @param $minimum_version
	 * @param $name
	 */
	public function __construct( $minimum_version, $name ) {
		$this->minimum_version = $minimum_version;
		$this->plugin_name = $name;
	}

	/**
	 * @param $version
	 *
	 * @return bool
	 */
	public function does_it_meet_required_php_version( $version ) {
		if ( $this->is_minimum_php_version( $version ) ) {
			return true;
		}

		$this->load_minimum_required_version_notice();
		return false;
	}

	/**
	 * @param $version
	 *
	 * @return boolean
	 */
	private function is_minimum_php_version( $version ) {
		return version_compare( $this->minimum_version, $version, '<=' );
	}

	/**
	 * @return void
	 */
	private function load_minimum_required_version_notice() {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		}
	}

	public function admin_notice() {
		echo '<div class="error"><p>';
		echo sprintf( __( "Unfortunately, the plugin \"%s\" can not run on PHP versions older than %s. Read more information about <a href=\"%s\">how you can update</a>.", 'child-page-tree' ),
				esc_attr( $this->plugin_name ),
				esc_attr( $this->minimum_version ),
				esc_html( "http://www.wpupdatephp.com/update/" )
		);
		echo '</p></div>';
	}
}