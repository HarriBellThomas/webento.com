<?php

if( !class_exists( 'Premise_Support' ) ) :
class Premise_Support {
	var $_tests = null;

	function Premise_Support() {
		return $this->__construct();
	}
	function __construct() {
		$this->registerTest( __( 'WordPress Version', 'premise' ), array( $this, 'getWordPressVersion' ) );
		$this->registerTest( __( 'PHP Version', 'premise' ), array( $this, 'getPHPVersion' ) );
		$this->registerTest( __( 'Premise Version', 'premise' ), array( $this, 'getPremiseVersion' ) );
		$this->registerTest( __( 'Memory Limit', 'premise' ), array( $this, 'get_memory_limit' ) );
		$this->registerTest( __( 'Membership Enabled', 'premise' ), array( $this, 'get_member_enabled' ) );
		$this->registerTest( __( 'Web Server', 'premise' ), array( $this, 'getWebServer' ) );
		$this->registerTest( __( 'Server IP', 'premise' ), array( $this, 'getIpAddress' ) );
		$this->registerTest( __( 'Installed Themes', 'premise' ), array( $this, 'getInstalledThemes' ) );
		$this->registerTest( __( 'Activated Theme', 'premise' ), array( $this, 'getActivatedTheme' ) );
		$this->registerTest( __( 'Installed Plugins', 'premise' ), array( $this, 'getInstalledPlugins' ) );
		$this->registerTest( __( 'Activated Plugins', 'premise' ), array( $this, 'getActivatedPlugins' ) );
	}

	function sendSupportRequest( $name, $email, $problem ) {
		$to = $this->getEmailAddress();
		if( empty( $to ) )
			return false;

		$output = "Greetings,\n\n";
		$output .= sprintf( __( 'This message is the result of running the %s support script on %s - you can access the site at %s .  The user was %s and you can reach them at the email address %s.', 'premise' ), __( 'Premise', 'premise' ), get_bloginfo( 'name' ), home_url( '/' ), $name, $email ) . "\n\n\n";
		$output .= "The user submitted the following message:\n\n{$problem}\n\n";

		foreach( $this->getTestResults() as $result )
			$output .= "**{$result['name']}**\n{$result['value']}\n\n\n";

		return wp_mail( $to, __( 'Premise Support Message', 'premise' ), $output, array( 'From: ' . $email ) );
	}

	function getKey($text) {
		$text = sanitize_title_with_dashes($text);
		return $text . '-' . sanitize_title_with_dashes($this->getOwnName());
	}

	function getOwnName() {
		return strtolower(get_class($this));
	}

	function getTestResults() {
		$results = array();
		foreach( $this->_tests as $test ) {
			$testResult = call_user_func( $test['callback'] );
			$results[] = array( 'name' => $test['name'], 'value' => $testResult['value'], 'warn' => $testResult['warn'] );
		}
		return $results;
	}

	function registerTest( $name, $callback ) {
		if( is_callable( $callback ) )
			$this->_tests[] = array( 'name' => $name, 'callback' => $callback );
	}

	function getEmailAddress() {
		return 'support@getpremise.com';
	}

	/// TEST CALLBACKS

	function getWordPressVersion() {
		global $wp_version;
		return array( 'value' => $wp_version, 'warn' =>  version_compare( $wp_version, '2.8.4', '<' ) );
	}

	function getPHPVersion() {
		return array( 'value' =>  phpversion(), 'warn' => false );
	}

	function getWebServer() {
		$server = $_SERVER['SERVER_SOFTWARE'];
		if( empty( $server ) )
			$server = __( 'Could not determine server software.', 'premise' );

		return array( 'value' => $server, 'warn' => false );
	}

	function getIpAddress() {
		$ip = $_SERVER['SERVER_ADDR'];
		if( empty( $ip ) )
			$ip = __( 'Could not determine server IP.', 'premise' );

		return array( 'value' => $ip, 'warn' => false );
	}

	function isScribeInstalled() {
		global $ecordia;
		if( is_object( $ecordia ) )
			return array( 'value' => __( 'Yes', 'premise' ), 'warn' => false );

		return array( 'value' => __( 'No', 'premise' ), 'warn' => true );
	}

	function getPremiseVersion() {
		return array( 'value' => PREMISE_VERSION, 'warn' => false );
	}

	function getInstalledThemes() {
		$output = '';
		foreach( get_themes() as $item )
			$output .= "{$item['Name']}\n";

		return array( 'value' => $output, 'warn' => false );
	}
	
	function getActivatedTheme() {
		return array( 'value' => get_current_theme(), 'warn' => false );
	}

	function getInstalledPlugins() {
		$output = '';
		foreach( get_plugins() as $item )
			$output .= "{$item['Name']}\n";

		return array( 'value' => $output, 'warn' => false );
	}

	function getActivatedPlugins() {
		$output = '';
		foreach( get_option( 'active_plugins' ) as $item ) {
			$path = path_join(WP_PLUGIN_DIR, $item);
			$data = get_plugin_data( $path );
			$output .= "{$data['Name']}\n";
		}
		return array( 'value' => $output, 'warn' => false );
	}
	function get_memory_limit() {

		return array( 'value' => ini_get( 'memory_limit' ), 'warn' => false );

	}
	function get_member_enabled() {

		return array( 'value' => defined( 'PREMISE_MEMBER_DIR' ) ? 'Yes' : 'No', 'warn' => false );

	}
}
endif; // class_exists( 'Premise_Support' )
