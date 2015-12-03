<?php
/*
Plugin Name: One Express
Version: 1.0
Author: Hamba Allah
*/
include_once('jenis-delivery/onex-jenis-delivery.php');
include_once('distributor/onex-distributor.php');
include_once('kategori-menu/onex-kategori-menu.php');
include_once('menu-distributor/onex-menu-distributor.php');
class Onex_Plugin{

	private $onex_jenis_delivery_obj;
	private $onex_distributor_obj;
	private $onex_kategori_menu_obj;
	private $onex_menu_distributor_obj;

	public function __construct(){
		$this->onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
		$this->onex_distributor_obj = new Onex_Distributor();
		$this->onex_kategori_menu_obj = new Onex_Kategori_Menu();
		$this->onex_menu_distributor_obj = new Onex_Menu_Distributor();

		add_action('admin_menu', array( $this, 'create_menu') );
		add_action('admin_enqueue_scripts', array( $this, 'load_wp_media_files'));

		/*add_action('wp_print_scripts', array( $this, 'test_ajax_load_scripts')) ;
		add_action('wp_ajax_test_response', array( $this, 'text_ajax_process_request')) ;*/
	}

	public static function plugin_activated(){

		/**
		*
		* INSERT DATA
		*
		*/
		global $wpdb;
		$onex_katdel_table_name = "onex_kategori_delivery";

		if($wpdb->get_var("SHOW TABLES LIKE '$onex_katdel_table_name'") == $onex_katdel_table_name){
			if($wpdb->get_var("SELECT COUNT(*) FROM $onex_katdel_table_name") < 1){
				$wpdb->insert(
					$onex_katdel_table_name,
					array(
						"kategori" => "Food Delivery",
						"keterangan" => "delivery khusus antar makanan dari restaurant ke pelanggan"
					),
					array('%s', '%s')
				);
			}
		}

		$onex_katmenumakmin_table_name = "onex_kategori_menu_makmin";
		if( $wpdb->get_var("SHOW TABLES LIKE '$onex_katmenumakmin_table_name'") == $onex_katmenumakmin_table_name ){
			if($wpdb->get_var("SELECT COUNT(*) FROM $onex_katmenumakmin_table_name") < 1){
				$wpdb->insert(
					$onex_katmenumakmin_table_name,
					array(
						"nama_kategori" => "Makanan"
					),
					array('%s')
				);
			}
		}
	}

	function load_wp_media_files(){
		wp_enqueue_media();
	}

	public function create_menu(){
		// MAIN MENU *************************
		add_menu_page(
			'One Express',
			'One Express',
			'manage_options',
			'onex-main-page',
			onex_main_page,
			'',
			3
		);

		// Sub MENU "JENIS DELIVERY" ************

		add_submenu_page(
			'onex-main-page',
			'Jenis Delivery One Express',
			'Jenis Delivery',
			'manage_options',
			'onex-jenis-delivery-page',
			array( $this, 'RenderDeliveryList')//'onex_jenis_delivery_page'
		);
		add_submenu_page(
			null,
			'Tambah Jenis Delivery',
			'Tambah Jenis Delivery',
			'manage_options',
			'onex-jenis-delivery-tambah',
			array( $this, 'render_delivery_tambah')//'onex_jenis_delivery_tambah'
		);
		add_submenu_page(
			null,
			'Hapus Jenis Delivery',
			'Hapus Jenis Delivery',
			'manage_options',
			'onex-jenis-delivery-hapus',
			array( $this, 'render_delivery_hapus')//'onex_jenis_delivery_hapus'
		);
		add_submenu_page(
			null,
			'Update Jenis Delivery',
			'Update Jenis Delivery',
			'manage_options',
			'onex-jenis-delivery-update',
			array( $this, 'render_delivery_update')//'onex_jenis_delivery_update'
		);

		// Sub MENU "DISTRIBUTOR" ************
		add_submenu_page(
			'onex-main-page',
			'Distributor One Express',
			'Distributor',
			'manage_options',
			'onex-distributor-page',
			array( $this, 'RenderDistributorList')//'onex_distributor_page'
		);
		add_submenu_page(
			null,
			'Tambah Distributor',
			'Tambah Distributor',
			'manage_options',
			'onex-distributor-tambah',
			array( $this, 'RenderDistributorTambah')//'onex_distributor_tambah'
		);
		add_submenu_page(
			null,
			'Update Distributor',
			'Update Distributor',
			'manage_options',
			'onex-distributor-update',
			array( $this, 'RenderDistributorUpdate')//'onex_distributor_update'
		);
		add_submenu_page(
			null,
			'Hapus Distributor',
			'Hapus Distributor',
			'manage_options',
			'onex-distributor-hapus',
			array( $this, 'RenderDistributorHapus')//'onex_distributor_hapus'
		);

		// Sub MENU KATEGORI MENU
		add_submenu_page(
			'onex-main-page',
			'Kategori Menu',
			'Kategori Menu',
			'manage_options',
			'onex-kategori-menu-page',
			array( $this, 'RenderKategoriMenuList')
		);
		add_submenu_page(
			null,
			'Tambah Kategori Menu',
			'Tambah Kategori Menu',
			'manage_options',
			'onex-kategori-menu-tambah',
			array( $this, 'RenderKategoriMenuTambah')//'onex_jenis_delivery_tambah'
		);

		// Sub MENU "MENU DISTRIBUTOR"
		add_submenu_page(
			'onex-main-page',
			'Menu Distributor',
			'Menu Distributor',
			'manage_options',
			'onex-menu-distributor-page',
			array( $this, 'RenderMenuDistributorList')
		);
		add_submenu_page(
			null,
			'Tambah Menu Distributor',
			'Tambah Menu Distributor',
			'manage_options',
			'onex-menu-distributor-tambah',
			array( $this, 'RenderMenuDistributorTambah')//'onex_jenis_delivery_tambah'
		);

		// Sub MENU "BANK" **********************
		/*add_submenu_page(
			'onex-main-page',
			'Bank',
			'Bank',
			'manage_options',
			'onex-bank-page',
			'onex_bank_page'
		);*/

		// Sub MENU "MENU MAKMIN" ****************
		/*add_submenu_page(
			'onex-main-page',
			'Jenis Menu',
			'Jenis Menu',
			'manage_options',
			'onex-jenis-menu-page',
			'onex_jenis_menu_page'
		);*/
	}

	function RenderDeliveryList(){
		//$onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
		//$attributes = $this->onex_jenis_delivery_obj->DeliveryList();
		//var_dump($attributes);
		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_main', $attributes);
	}

	function render_delivery_tambah(){
		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_tambah', $attributes);
	}

	function render_delivery_hapus(){
		//var_dump($_GET['id']);
		$attributes = $this->onex_jenis_delivery_obj->GetDelivery($_GET['id']);

		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_hapus', $attributes);
	}

	function render_delivery_update(){
		$attributes = $this->onex_jenis_delivery_obj->GetDelivery($_GET['id']);

		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_update', $attributes);
	}

	function RenderDistributorList(){
		//$attributes = $this->onex_distributor_obj->DistributorList();
		//var_dump($attributes);
		return $this->getHtmlTemplate(  'distributor/templates/', 'distributor_main', $attributes);
	}

	function RenderDistributorTambah(){
		return $this->getHtmlTemplate(  'distributor/templates/', 'distributor_tambah', $attributes);
	}

	function RenderDistributorHapus(){
		$attributes = $this->onex_distributor_obj->GetDistributor($_GET['id']);

		return $this->getHtmlTemplate( 'distributor/templates/', 'distributor_hapus', $attributes);
	}

	function RenderDistributorUpdate(){
		$attributes = $this->onex_distributor_obj->GetDistributor($_GET['id']);

		return $this->getHtmlTemplate( 'distributor/templates/', 'distributor_update', $attributes);
	}

	function RenderKategoriMenuList(){
		$attributes = $this->onex_kategori_menu_obj->GetKategoriMenuList();

		return $this->getHtmlTemplate(  'kategori-menu/templates/', 'kategori_menu_list', $attributes);
	}

	function RenderKategoriMenuTambah(){
		return $this->getHtmlTemplate(  'kategori-menu/templates/', 'kategori_menu_tambah', $attributes);
	}

	function RenderMenuDistributorList(){
		$attributes = $this->onex_menu_distributor_obj->GetMenuDistributorList();

		return $this->getHtmlTemplate(  'menu-distributor/templates/', 'menu_distributor_list', $attributes);
	}

	function RenderMenuDistributorTambah(){
		return $this->getHtmlTemplate(  'menu-distributor/templates/', 'menu_distributor_tambah', $attributes);
	}

	private function getHtmlTemplate( $location, $template_name, $attributes = null ){
		if(! $attributes) $attributes = array();

		ob_start();
		require( $location . $template_name . '.php');
		$html = ob_get_contents();
		ob_end_clean();
		echo $html;
	}
}

$onex_plugin_obj = new Onex_Plugin();


function get_jenis_delivery(){
	$delivery_obj = new Onex_Jenis_Delivery();
	$content = $delivery_obj->DeliveryList();
	return $content;
}

function get_distributor(){
	$distributor_obj = new Onex_Distributor();
	$content = $distributor_obj->DistributorList();
	return $content;
}

function get_distributor_by_template($template_name){
	$distributor = new Onex_Distributor();
	$content = $distributor->GetDistributorByTemplate($template_name);
	return $content;
}

function get_distributor_by_id(){
	$onex_distributor_obj = new Onex_Distributor();
	$content = $onex_distributor_obj->GetDistributor($_GET['distributor']);
	return $content;
}

function get_kategori_menu(){
	$katmenu_obj = new Onex_Kategori_Menu();
	$content = $katmenu_obj->GetKategoriMenuList();
	return $content;
}

register_activation_hook( __FILE__, array('Onex_Plugin', 'plugin_activated'));

define('ONEXPLUGINDIR', plugin_dir_path(__FILE__));
require_once(ONEXPLUGINDIR . 'onex-main.php');
// require_once(ONEXPLUGINDIR . 'distributor/onex-distributor.php');
// require_once(ONEXPLUGINDIR . 'distributor/onex-distributor-tambah.php');
// require_once(ONEXPLUGINDIR . 'distributor/onex-distributor-update.php');
// require_once(ONEXPLUGINDIR . 'distributor/onex-distributor-hapus.php');
//require_once(ONEXPLUGINDIR . 'jenis-delivery/onex-jenis-delivery.php');
//require_once(ONEXPLUGINDIR . 'jenis-delivery/onex-jenis-delivery-tambah.php');
//require_once(ONEXPLUGINDIR . 'jenis-delivery/onex-jenis-delivery-update.php');
//require_once(ONEXPLUGINDIR . 'jenis-delivery/onex-jenis-delivery-hapus.php');
// require_once(ONEXPLUGINDIR . 'bank/onex-bank.php');
// require_once(ONEXPLUGINDIR . 'jenis-menu/onex-jenis-menu.php');
