<?php
/**
 * Installs the database, if it's not already present.
 */
class Embi_Installer {

	/**
	 * @access public
	 * @static
	 */
	function check () {
		/*
		$is_installed = get_site_option('embi', false);
		$is_installed = $is_installed ? $is_installed : get_option('embi', false);
		if (!$is_installed) Embi_Installer::install();
		*/
	}

	/**
	 * @access private
	 * @static
	 */
	function install () {
		$me = new Embi_Installer;
		$me->create_default_options();
	}

	/**
	 * @access private
	 */
	function create_default_options () {
		/*
		update_site_option('embi', array (
			// ...
		));
		*/
	}
}