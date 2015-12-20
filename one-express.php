<?php
/*
Plugin Name: OTWExpress
Version: 1.0
*/
include_once('jenis-delivery/onex-jenis-delivery.php');
include_once('distributor/onex-distributor.php');
include_once('kategori-menu/onex-kategori-menu.php');
include_once('menu-distributor/onex-menu-distributor.php');
include_once('lokasi/onex-lokasi.php');
include_once('bank/onex-bank.php');
include_once('promo/onex-promo.php');
include_once('invoice/onex-invoice.php');
include_once('pemesanan-menu/onex-pemesanan-menu.php');
include_once('data-pembeli/onex-data-pembeli.php');
include_once('ongkos-kirim/onex-ongkos-kirim.php');
class Onex_Plugin{

	private $onex_jenis_delivery_obj;
	private $onex_distributor_obj;
	private $onex_kategori_menu_obj;
	private $onex_menu_distributor_obj;
	private $onex_lokasi_obj;
	private $onex_bank_obj;
	private $onex_promo_obj;
	private $onex_invoice_obj;

	public function __construct(){
		$this->onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
		$this->onex_distributor_obj = new Onex_Distributor();
		$this->onex_kategori_menu_obj = new Onex_Kategori_Menu();
		$this->onex_menu_distributor_obj = new Onex_Menu_Distributor();
		$this->onex_lokasi_obj = new Onex_Lokasi();
		$this->onex_bank_obj = new Onex_Bank();
		$this->onex_promo_obj = new Onex_Promo();
		$this->onex_invoice_obj = new Onex_Invoice();

		add_action('admin_menu', array( $this, 'create_menu') );
		add_action('admin_enqueue_scripts', array( $this, 'load_wp_media_files'));
		add_action('my_hourly_event', array( $this, 'deactivateInvoiceAfter24hours'));

		/*add_action('wp_print_scripts', array( $this, 'test_ajax_load_scripts')) ;
		add_action('wp_ajax_test_response', array( $this, 'text_ajax_process_request')) ;*/
	}

	public static function plugin_activated(){

		/**
		*
		* INSERT DATA
		*
		*/
		/*global $wpdb;
		$onex_katdel_table_name = "onex_kategori_delivery";

		if($wpdb->get_var("SHOW TABLES LIKE '$onex_katdel_table_name'") == $onex_katdel_table_name){
			if($wpdb->get_var("SELECT COUNT(*) FROM $onex_katdel_table_name") < 1){
				$wpdb->insert(
					$onex_katdel_table_name,
					array(
						"nama_katdel" => "Food Delivery",
						"keterangan_katdel" => "delivery khusus antar makanan dari restaurant ke pelanggan"
					),
					array('%s', '%s')
				);
			}
		}*/

		/*$onex_katmenumakmin_table_name = "onex_kategori_menu_makmin";
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
		}*/

		wp_schedule_event( current_time('timestamp'), 'hourly', 'my_hourly_event');
	}

	function deactivateInvoiceAfter24hours(){
		$this->onex_invoice_obj->DeactivateInvoice();
	}

	function load_wp_media_files(){
		wp_enqueue_media();
	}

	public function create_menu(){
		// MAIN MENU *************************
		add_menu_page(
			'OtwExpress',
			'OtwExpress',
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
			array( $this, 'RenderDeliveryTambah')//'onex_jenis_delivery_tambah'
		);
		add_submenu_page(
			null,
			'Hapus Jenis Delivery',
			'Hapus Jenis Delivery',
			'manage_options',
			'onex-jenis-delivery-hapus',
			array( $this, 'RenderDeliveryHapus')//'onex_jenis_delivery_hapus'
		);
		add_submenu_page(
			null,
			'Update Jenis Delivery',
			'Update Jenis Delivery',
			'manage_options',
			'onex-jenis-delivery-update',
			array( $this, 'RenderDeliveryUpdate')//'onex_jenis_delivery_update'
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
		add_submenu_page(
			null,
			'Update Kategori Menu',
			'Update Kategori Menu',
			'manage_options',
			'onex-kategori-menu-update',
			array( $this, 'RenderKategoriMenuUpdate')//'onex_jenis_delivery_tambah'
		);

		// Sub MENU "MENU DISTRIBUTOR"
		/*add_submenu_page(
			null,
			'Menu Distributor',
			'Menu Distributor',
			'manage_options',
			'onex-menu-distributor-page',
			array( $this, 'RenderMenuDistributorList')
		);*/
		add_submenu_page(
			null,
			'Tambah Menu Distributor',
			'Tambah Menu Distributor',
			'manage_options',
			'onex-menu-distributor-tambah',
			array( $this, 'RenderMenuDistributorTambah')//'onex_jenis_delivery_tambah'
		);
		add_submenu_page(
			null,
			'Hapus Menu Distributor',
			'Hapus Menu Distributor',
			'manage_options',
			'onex-menu-distributor-hapus',
			array( $this, 'RenderMenuDistributorHapus')
		);
		add_submenu_page(
			null,
			'Update Menu Distributor',
			'Update Menu Distributor',
			'manage_options',
			'onex-menu-distributor-update',
			array( $this, 'RenderMenuDistributorUpdate')
		);

		// Sub MENU "LOKASI"
		add_submenu_page(
			null,//'onex-main-page',
			'Lokasi',
			'Lokasi',
			'manage_options',
			'onex-lokasi-page',
			array( $this, 'RenderLokasiList')
		);
		/*add_submenu_page(
			null,
			'Tambah Menu Distributor',
			'Tambah Menu Distributor',
			'manage_options',
			'onex-menu-distributor-tambah',
			array( $this, 'RenderMenuDistributorTambah')//'onex_jenis_delivery_tambah'
		);*/

		// Sub MENU "BANK" **********************
		add_submenu_page(
			'onex-main-page',
			'Bank',
			'Bank',
			'manage_options',
			'onex-bank-page',
			array( $this, 'RenderBankList')
		);
		add_submenu_page(
			null,
			'Tambah Bank',
			'Tambah Bank',
			'manage_options',
			'onex-bank-tambah',
			array( $this, 'RenderBankTambah')//'onex_jenis_delivery_tambah'
		);

		// Sub MENU "PROMO" ****************
		add_submenu_page(
			null,//'onex-main-page',
			'Promo',
			'Promo',
			'manage_options',
			'onex-promo-page',
			array( $this, 'RenderPromoList')
		);
	}

	function RenderDeliveryList(){
		//$onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
		//$attributes = $this->onex_jenis_delivery_obj->DeliveryList();
		//var_dump($attributes);
		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_main', $attributes);
	}

	function RenderDeliveryTambah(){
		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_tambah', $attributes);
	}

	function RenderDeliveryHapus(){
		//var_dump($_GET['id']);
		$attributes['katdel'] = $this->onex_jenis_delivery_obj->GetDelivery($_GET['id']);

		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_hapus', $attributes);
	}

	function RenderDeliveryUpdate(){
		$attributes['katdel'] = $this->onex_jenis_delivery_obj->GetDelivery($_GET['id']);

		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_update', $attributes);
	}

	function RenderDistributorList(){
		//$attributes = $this->onex_distributor_obj->DistributorList();
		//var_dump($attributes);
        $schedule = wp_get_schedule( 'my_hourly_event' );
        var_dump($schedule);
		return $this->getHtmlTemplate(  'distributor/templates/', 'distributor_main', $attributes);
	}

	function RenderDistributorTambah(){
		return $this->getHtmlTemplate(  'distributor/templates/', 'distributor_tambah', $attributes);
	}

	function RenderDistributorHapus(){
		$attributes['distributor'] = $this->onex_distributor_obj->GetDistributor( $_GET['id']);

		return $this->getHtmlTemplate( 'distributor/templates/', 'distributor_hapus', $attributes);
	}

	function RenderDistributorUpdate(){
		$attributes['distributor'] = $this->onex_distributor_obj->GetDistributor($_GET['id']);
		$attributes['katdel'] = $this->onex_jenis_delivery_obj->DeliveryList();

		return $this->getHtmlTemplate( 'distributor/templates/', 'distributor_update', $attributes);
	}

	function RenderKategoriMenuList(){
		//$attributes = $this->onex_kategori_menu_obj->GetKategoriMenuList();

		return $this->getHtmlTemplate(  'kategori-menu/templates/', 'kategori_menu_main', $attributes);
	}

	function RenderKategoriMenuTambah(){
		if( isset( $_GET['distributor'])){
			$attributes['distributor'] = $this->onex_distributor_obj->GetDistributor( $_GET['distributor']);
			$attributes['single'] = true;
		}else{
			$attributes['distributor'] = $this->onex_distributor_obj->GetDistributorAll();
			$attributes['single'] = false;
		}

		return $this->getHtmlTemplate(  'kategori-menu/templates/', 'kategori_menu_tambah', $attributes);
	}

	function RenderKategoriMenuUpdate(){
		$attributes['katmenu'] = $this->onex_kategori_menu_obj->GetKategoriMenuDistributorById( $_GET['id']);

		return $this->getHtmlTemplate(  'kategori-menu/templates/', 'kategori_menu_update', $attributes);
	}

	function RenderMenuDistributorList(){
		$attributes = $this->onex_menu_distributor_obj->GetMenuDistributorList();

		return $this->getHtmlTemplate(  'menu-distributor/templates/', 'menu_distributor_list', $attributes);
	}

	function RenderMenuDistributorTambah(){
		if(isset($_GET['distributor']) && isset($_GET['kategori'])){
			$attributes['distributor'] = $this->onex_distributor_obj->GetDistributor( $_GET['distributor']);
			$attributes['katmenu'] = $this->onex_kategori_menu_obj->GetKategoriMenuById( $_GET['kategori']);
		}

		return $this->getHtmlTemplate(  'menu-distributor/templates/', 'menu_distributor_tambah', $attributes);
	}

	function RenderMenuDistributorHapus(){
		//var_dump($_GET['id']);
		$attributes['menudel'] = $this->onex_menu_distributor_obj->GetMenuDistributorById( $_GET['menu'] );

		return $this->getHtmlTemplate( 'menu-distributor/templates/', 'menu_distributor_hapus', $attributes);
	}

	function RenderMenuDistributorUpdate(){

		$attributes['menudel'] = $this->onex_menu_distributor_obj->GetMenuDistributorById( $_GET['menu']);
		$attributes['distributor'] = $this->onex_distributor_obj->GetDistributor( $attributes['menudel']['distributor_id']);
		$attributes['katmenu'] = $this->onex_kategori_menu_obj->GetKategoriMenuById( $attributes['menudel']['katmenu_id']);

		return $this->getHtmlTemplate(  'menu-distributor/templates/', 'menu_distributor_update', $attributes);
	}

	function RenderLokasiList(){
		//$attributes = $this->onex_kategori_menu_obj->GetKategoriMenuList();

		return $this->getHtmlTemplate(  'lokasi/templates/', 'lokasi_main', $attributes);
	}

	function RenderBankList(){
		//$attributes = $this->onex_kategori_menu_obj->GetKategoriMenuList();

		return $this->getHtmlTemplate(  'bank/templates/', 'bank_main', $attributes);
	}

	function RenderBankTambah(){
		//$attributes = $this->onex_kategori_menu_obj->GetKategoriMenuList();

		return $this->getHtmlTemplate(  'bank/templates/', 'bank_tambah', $attributes);
	}

	function RenderPromoList(){
		//$attributes = $this->onex_kategori_menu_obj->GetKategoriMenuList();

		return $this->getHtmlTemplate(  'promo/templates/', 'promo_main', $attributes);
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


register_activation_hook( __FILE__, array('Onex_Plugin', 'plugin_activated'));

define('ONEXPLUGINDIR', plugin_dir_path(__FILE__));
require_once(ONEXPLUGINDIR . 'onex-main.php');
