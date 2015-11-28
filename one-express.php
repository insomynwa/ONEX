<?php
/*
Plugin Name: One Express
Version: 1.0
Author: Hamba Allah
*/
include_once('jenis-delivery/onex-jenis-delivery.php');
include_once('distributor/onex-distributor.php');
class Onex_Plugin{

	private $onex_jenis_delivery_obj;
	private $onex_distributor_obj;

	public function __construct(){
		$this->onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
		$this->onex_distributor_obj = new Onex_Distributor();
		add_action('admin_menu', array( $this, 'create_menu') );
		add_action('admin_enqueue_scripts', array( $this, 'load_wp_media_files'));
	}

	public static function plugin_activated(){

		/**
		*
		* CREATE PAGES
		*
		*/
		/*$page_definitions =
			array(
				'delivery-put' =>
					array(
						'title' => __( 'Delivery put', 'one-express'),
						'content' => '',
						'template' => 'delivery-put.php',
					),
				'delivery-document-and-package' =>
					array(
						'title' => __( 'Delivery document & Package', 'one-express'),
						'content' => '',
						'template' => 'delivery-document-and-package.php',
					),
				'delivery-shopping' =>
					array(
						'title' => __( 'Delivery Shopping', 'one-express'),
						'content' => '',
						'template' => 'delivery-shopping.php',
					),
			);

		foreach ( $page_definitions as $slug => $page ){
			$query = new WP_Query( 'pagename=' . $slug );

			if ( !$query->have_posts()){

				$post_id = 
					wp_insert_post(
						array(
							'post_content' => $page['content'],
							'post_name' => $slug,
							'post_title' => $page['title'],
							'post_status' => 'publish',
							'post_type' => 'page',
							'ping_status' => 'closed',
							'comment_status' => 'closed'
						)
					);

				if( $page['template'] != '' ){

					$page_template = locate_template( $page['template'] );
					if( $page_template != '' && $post_id ){
						update_post_meta( $post_id, '_wp_page_template', $page['template'] );
					}else{
						wp_die("Template hasn't been created.");
					}
						
				}

			}
		}*/

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

		// Sub MENU "JENIS DELIVERY" ************

		add_submenu_page(
			'onex-main-page',
			'Jenis Delivery One Express',
			'Jenis Delivery',
			'manage_options',
			'onex-jenis-delivery-page',
			array( $this, 'render_delivery_list')//'onex_jenis_delivery_page'
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

	function render_delivery_list(){
		//$onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
		$attributes = $this->onex_jenis_delivery_obj->DeliveryList();
		//var_dump($attributes);
		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_list', $attributes);
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
		$attributes = $this->onex_distributor_obj->DistributorList();
		//var_dump($attributes);
		return $this->getHtmlTemplate(  'distributor/templates/', 'distributor_list', $attributes);
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

/*function one_express_install(){
	global $wpdb;
	$onex_katdel_table_name = "onex_kategori_delivery";

	if($wpdb->get_var("SHOW TABLES LIKE '$onex_katdel_table_name'") == $onex_katdel_table_name){
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
register_activation_hook(__FILE__, 'one_express_install');

define('ONEXPLUGINDIR', plugin_dir_path(__FILE__));
require_once(ONEXPLUGINDIR . 'onex-main.php');
include(ONEXPLUGINDIR . 'includes/onex-menu.php');*/
