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
	private $onex_tarif_kirim_obj;

	public function __construct(){

		$this->onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
		$this->onex_distributor_obj = new Onex_Distributor();
		$this->onex_kategori_menu_obj = new Onex_Kategori_Menu();
		$this->onex_menu_distributor_obj = new Onex_Menu_Distributor();
		$this->onex_lokasi_obj = new Onex_Lokasi();
		$this->onex_bank_obj = new Onex_Bank();
		$this->onex_promo_obj = new Onex_Promo();
		$this->onex_invoice_obj = new Onex_Invoice();
		$this->onex_tarif_kirim_obj = new Onex_Ongkos_Kirim();

		add_action('admin_menu', array( $this, 'create_menu') );
		add_action('admin_enqueue_scripts', array( $this, 'load_onex_scripts'));
		add_action('my_hourly_event', array( $this, 'deactivateInvoiceAfter24hours'));

		add_action('wp_ajax_AjaxRetrieveJenisDeliveryList', array( $this, 'AjaxRetrieve_JenisDelivery_List') );
		add_action('wp_ajax_AjaxRetrieveJenisDeliveryDetail', array( $this, 'AjaxRetrieve_JenisDelivery_Detail') );
		add_action('wp_ajax_AjaxRetrieveDistributorList', array( $this, 'AjaxRetrieve_Distributor_List')) ;
		add_action('wp_ajax_AjaxRetrieveDistributorDetail', array( $this, 'AjaxRetrieve_Distributor_Detail')) ;
		add_action('wp_ajax_AjaxAddDistributor', array( $this, 'AjaxAdd_Distributor')) ;
		add_action('wp_ajax_AjaxUpdateTarifKirim', array( $this, 'AjaxUpdate_TarifKirim'));

		//add_action('wp_enqueue_scripts', array( $this, 'load_plugin_styles'));
	}

	public static function plugin_activated(){

		wp_schedule_event( current_time('timestamp'), 'hourly', 'my_hourly_event');
	}

	function deactivateInvoiceAfter24hours(){
		$expiredInvoices = $this->onex_invoice_obj->GetExpiredInvoice();
		$pemesanan_menu = new Onex_Pemesanan_Menu();
		foreach ( $expiredInvoices as $exin){
			$exin_id = $exin->id_invoice;
			$pemesanan_menu->DeletePesananMenu_Invoice( $exin_id);
		}
		$this->onex_invoice_obj->DeactivateInvoice();

	}

	function load_onex_scripts(){
		wp_enqueue_media();

		wp_register_script("otw-scripts", plugin_dir_url(__FILE__).'/js/one-express.js');
		wp_register_script("bootstrapminjs", get_template_directory_uri().'/css/bootstrap/js/bootstrap.min.js');
		wp_register_style("bootstrapmincss", get_template_directory_uri().'/css/bootstrap/css/bootstrap.min.css');

		wp_enqueue_script("otw-scripts");
		wp_enqueue_script("bootstrapminjs");
		wp_enqueue_style("bootstrapmincss");

		wp_localize_script( "otw-scripts", "ajax_one_express", array('ajaxurl'=>admin_url('admin-ajax.php')) );
		//wp_enqueue_script( "ajax-distributor", plugin_dir_url( __FILE__ ) . '../js/ajax-distributor.js', array( 'jquery' ) );
		//wp_localize_script( 'ajax-distributor', 'ajax_one_express', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	function AjaxRetrieve_JenisDelivery_List(){
		$jenis_delivery_all = $this->onex_jenis_delivery_obj->GetAllJenisDelivery();
		$attributes = array();
		$nmr = 0;
		//var_dump($distributor_all);
		foreach( $jenis_delivery_all as $jd){
			$katdel_id = $jd->id_katdel;
			$jenis_delivery = new Onex_Jenis_Delivery();
			$jenis_delivery->SetAJenisDelivery( $katdel_id);
			$attributes['katdel'][$nmr] = $jenis_delivery;
			$nmr += 1;
		}
		echo $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_list', $attributes );
		wp_die();
	}

	function AjaxRetrieve_JenisDelivery_Detail(){
		if( isset( $_GET['kategori_delivery']) ){
			$get_id_katdel = sanitize_text_field( $_GET['kategori_delivery']);
			$this->onex_jenis_delivery_obj->SetAJenisDelivery( $get_id_katdel );
			$attributes = array();
			$attributes['katdel'] = $this->onex_jenis_delivery_obj;

			$distributor_list = $this->onex_distributor_obj->GetAll_Id_Distributor_JenisDelivery( $get_id_katdel);
			$nmr = 0;
			foreach ($distributor_list as $d ) {
				$distributor_id = $d->id_dist;
				$distributor = new Onex_Distributor();
				$distributor->SetADistributor( $distributor_id);
				$attributes['distributor'][$nmr] = $distributor;
				$nmr += 1;
				
			}
			echo $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_detail', $attributes );
		}
		wp_die();
	}

	function AjaxRetrieve_Distributor_List(){
		$distributor_all = $this->onex_distributor_obj->GetAllDistributor();
		$attributes = array();
		$nmr = 0;
		//var_dump($distributor_all);
		foreach( $distributor_all as $d){
			$distributor_id = $d->id_dist;
			$distributor = new Onex_Distributor();
			$distributor->SetADistributor( $distributor_id);//var_dump($distributor->GetNama());
			$attributes['distributor'][$nmr] = $distributor;

			$katdel = new Onex_Jenis_Delivery();
			$katdel->SetAJenisDelivery( $distributor->GetKatdel());
			$attributes['katdel'][$nmr] = $katdel;
			$nmr += 1;
		}
		echo $this->getHtmlTemplate(  'distributor/templates/', 'distributor_list', $attributes);
		wp_die();
	}

	function AjaxRetrieve_Distributor_Detail(){
	  	if ( isset( $_GET["distributor"] ) && $_GET['distributor']>0 ) {
			$get_distributor_id = sanitize_text_field( $_GET['distributor']);
			$distributor = new Onex_Distributor();
			$distributor->SetADistributor( $get_distributor_id);

			$attributes['distributor'] = $distributor;

			$katmenus = $this->onex_kategori_menu_obj->GetAllKategoriMenu_Distributor( $get_distributor_id);
			$nmr = 0;
			foreach($katmenus as $km){
				$katmenu_id = $km->id_katmenu;
				$katmenu = new Onex_Kategori_Menu();
				$katmenu->SetAKategoriMenu( $katmenu_id);
				$attributes['katmenu'][$nmr] = $katmenu;

				$menudists = $this->onex_menu_distributor_obj->GetAllMenu_Kategori( $katmenu_id);
				$i = 0;
				foreach( $menudists as $md){
					$menudel_id = $md->id_menudel;
					$menudel = new Onex_Menu_Distributor();
					$menudel->SetAMenuDistributor( $menudel_id );
					$attributes[$katmenu_id][$i] = $menudel;
					$i += 1;
				}
				$nmr += 1;
			}
			echo $this->getHtmlTemplate(  'distributor/templates/', 'distributor_detail', $attributes);
			wp_die();
		}
	}

	function AjaxAdd_Distributor(){
	  	echo $this->getHtmlTemplate(  'distributor/templates/', 'distributor_tambah', $attributes);
	  	wp_die();
	}

	function AjaxUpdate_TarifKirim(){

		$result = array(
			'status' => false,
			'message' => ''
			);
		//var_dump($result);
		if( isset( $_POST['id']) && $_POST['id'] > 0){
			$tarif_id = sanitize_text_field($_POST['id']);

			$this->onex_tarif_kirim_obj->SetATarifKirim_Id( $tarif_id);
			$this->onex_tarif_kirim_obj->SetJarakMinimal( sanitize_text_field( $_POST['jarak_minimal']));
			$this->onex_tarif_kirim_obj->SetTarifMinimal( sanitize_text_field( $_POST['tarif_minimal']));
			$this->onex_tarif_kirim_obj->SetTarifNormal( sanitize_text_field( $_POST['tarif_normal']));
			//var_dump($this->onex_tarif_kirim_obj);
			if($this->onex_tarif_kirim_obj->UpdateTarifKirim()){
				$active_invoice_ids = $this->onex_invoice_obj->GetAllActiveInvoice();
				foreach( $active_invoice_ids as $invoice_id){
					$invoice_id = $invoice_id->id_invoice;
					$invoice = new Onex_Invoice();
					$invoice->SetAnInvoice_Id( $invoice_id);
					$biaya_kirim = $this->onex_tarif_kirim_obj->CountBiayaKirim( $invoice->GetJarakKirim() );
					$invoice->SetBiayaKirim( $biaya_kirim);
					$invoice->UpdateBiayaKirim();
				}
				$result['status'] = true;
				$result['message'] = 'Berhasil update tarif';
			}else{
				$result['message'] = 'Gagal update tarif';
			}
		}
		echo wp_json_encode( $result);
		wp_die();
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
		add_submenu_page(
			null,
			'Hapus Kategori Menu',
			'Hapus Kategori Menu',
			'manage_options',
			'onex-kategori-menu-hapus',
			array( $this, 'RenderKategoriMenuHapus')//'onex_jenis_delivery_tambah'
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

		// Sub Tarif Pengiriman
		add_submenu_page(
			'onex-main-page',
			'Tarif Pengiriman',
			'Tarif Pengiriman',
			'manage_options',
			'onex-tarif-kirim-page',
			array( $this, 'RenderTarifKirimList')
		);

		// Sub MENU "BANK" **********************
		/*add_submenu_page(
			'onex-main-page',
			'Bank',
			'Bank',
			'manage_options',
			'onex-bank-page',
			array( $this, 'RenderBankList')
		);*/
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

	function RenderTarifKirimList(){
		$this->onex_tarif_kirim_obj->SetATarifKirim();
		$attributes = $this->onex_tarif_kirim_obj;

		//var_dump(plugin_dir_url(__FILE__).'js/one-express.js');
		return $this->getHtmlTemplate(  'ongkos-kirim/templates/', 'ongkos_kirim_main', $attributes);
	}

	function RenderDeliveryList(){
		
		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_main', $attributes);
	}

	function RenderDeliveryTambah(){

		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_tambah', $attributes);
	}

	function RenderDeliveryHapus(){
		//var_dump($_GET['id']);
		$this->onex_jenis_delivery_obj->SetAJenisDelivery( $_GET['id']);
		$attributes = $this->onex_jenis_delivery_obj;

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
		$this->onex_distributor_obj->SetADistributor( $_GET['id']);
		$attributes = $this->onex_distributor_obj;

		return $this->getHtmlTemplate( 'distributor/templates/', 'distributor_hapus', $attributes);
	}

	function RenderDistributorUpdate(){
		$distributor_id = sanitize_text_field( $_GET['id']);
		$this->onex_distributor_obj->SetADistributor( $distributor_id);
		$attributes['distributor'] = $this->onex_distributor_obj;

		$katdels = $this->onex_jenis_delivery_obj->GetAllJenisDelivery();
		$i = 0;
		foreach ($katdels as $k){
			$katdel_id = $k->id_katdel;
			$katdel = new Onex_Jenis_Delivery();
			$katdel->SetAJenisDelivery( $katdel_id);
			$attributes['katdel'][$i] = $katdel;
			$i+=1;
		}//var_dump($attributes['katdel'] );
		//$attributes['distributor'] = $this->onex_distributor_obj->GetDistributor($_GET['id']);
		//$attributes['katdel'] = $this->onex_jenis_delivery_obj->DeliveryList();

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

	function RenderKategoriMenuHapus(){
		$this->onex_kategori_menu_obj->SetAKategoriMenu( $_GET['id'] );
		$attributes = $this->onex_kategori_menu_obj;

		return $this->getHtmlTemplate(  'kategori-menu/templates/', 'kategori_menu_hapus', $attributes);
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
		$get_menudel_id = sanitize_text_field( $_GET['menu']);
		$this->onex_menu_distributor_obj->SetAMenuDistributor( $get_menudel_id );
		$attributes = $this->onex_menu_distributor_obj;

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
