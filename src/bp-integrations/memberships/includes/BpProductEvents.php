<?php
namespace BuddyBoss\Memberships\Classes;

use BuddyBoss\Memberships\Classes\BpmsView;

class BpProductEvents {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $events_obj;

	// class constructor
	public function __construct() {
		add_filter('set-screen-option', [__CLASS__, 'set_screen'], 10, 3);
		add_action('admin_menu', [$this, 'plugin_menu']);
	}

	public static function set_screen($status, $option, $value) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_submenu_page(
			'',
			'BuddyBoss Memberships',
			'Memberships Settings',
			'manage_options',
			'bp-memberships-log',
			[$this, 'bpmsProductEvents']
		);

		add_action("load-$hook", [$this, 'screen_option']);

	}

	/**
	 * Plugin settings page
	 */
	public function bpmsProductEvents() {
		$classObj = $this;
		BpmsView::render('admin/membership-logs', get_defined_vars());
	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args = [
			'label' => 'Events',
			'default' => 5,
			'option' => 'events_per_page',
		];

		add_screen_option($option, $args);

		$this->events_obj = new EventsList();
	}

	/** Singleton instance */
	public static function get_instance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}