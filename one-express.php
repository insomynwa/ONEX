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
include_once('status-pemesanan/onex-status-pemesanan.php');
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
		add_action('wp_ajax_AjaxRetrieveBankList', array( $this, 'AjaxRetrieve_Bank_List') );
		add_action('wp_ajax_AjaxRetrievePemesananList', array( $this, 'AjaxRetrieve_Pemesanan_List') );
		add_action('wp_ajax_AjaxRetrieveInvoiceDetail', array( $this, 'AjaxRetrieve_Invoice_Detail') );
		add_action('wp_ajax_AjaxRetrievePagination', array( $this, 'AjaxRetrieve_Pagination') );
		add_action('wp_ajax_AjaxCreatePagination', array( $this, 'AjaxCreate_Pagination') );
		add_action('wp_ajax_AjaxRetrieveList', array( $this, 'AjaxRetrieveListFor') );
		add_action('wp_ajax_AjaxRetrieveKategoriOnDistributor', array( $this, 'AjaxRetrieve_Kategori_OnDistributor') );
		add_action('wp_ajax_AjaxRetrieveStatusPemesanan', array( $this, 'AjaxRetrieve_StatusPemesanan_List') );
		add_action('wp_ajax_AjaxUpdateStatusPemesanan', array( $this, 'AjaxUpdate_StatusPemesanan') );

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

		wp_register_script("otw-scripts", plugin_dir_url(__FILE__).'js/one-express.js');
		wp_register_script("bootstrapminjs", get_template_directory_uri().'/css/bootstrap/js/bootstrap.min.js');
		wp_register_style("bootstrapmincss", get_template_directory_uri().'/css/bootstrap/css/bootstrap.min.css');
		wp_register_style("plugincss", plugin_dir_url(__FILE__).'css/styles.css');

		wp_enqueue_script("otw-scripts");
		wp_enqueue_script("bootstrapminjs");
		wp_enqueue_style("bootstrapmincss");
		wp_enqueue_style("plugincss");

		wp_localize_script( "otw-scripts", "ajax_one_express", array('ajaxurl'=>admin_url('admin-ajax.php')) );
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
		
		foreach( $distributor_all as $d){
			$distributor_id = $d->id_dist;
			$distributor = new Onex_Distributor();
			$distributor->SetADistributor( $distributor_id);
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

	function AjaxRetrieve_Bank_List(){
		$bank_all = $this->onex_bank_obj->GetAllBank();
		$attributes = array();
		$nmr = 0;
		
		foreach( $bank_all as $b){
			$bank_id = $b->id_bank;
			$bank = new Onex_Bank();
			$bank->SetABank_Id( $bank_id);
			$attributes['bank'][$nmr] = $bank;
			$nmr += 1;
		}
		echo $this->getHtmlTemplate( 'bank/templates/', 'bank_list', $attributes );
		wp_die();
	}

	/*function AjaxRetrieve_Pemesanan_List(){
		if( isset( $_GET['page']) && $_GET['page']!="" && isset( $_GET['status']) && $_GET['status']!="" ){
			$get_page = sanitize_text_field( $_GET['page']);
			$get_status = sanitize_text_field( $_GET['status']);

			$limit = 3;
			$offset = ( $get_page - 1) * $limit;

			$invoices = $this->onex_invoice_obj->GetLimitInvoice_Status( $get_status, $limit, $offset);

			$data= array();

			$nmr = 0;
			foreach( $invoices as $i){
				$invoice_id = $i->id_invoice;

				$invoice = new Onex_Invoice();
				$pemesanan = new Onex_Pemesanan_Menu();
				$distributor = new Onex_Distributor();
				$bank = new Onex_Bank();

				$invoice->SetAnInvoice_Id( $invoice_id);
				$attributes['invoice'][$nmr] = $invoice;
				$distributor->SetADistributor( $invoice->GetDistributor());
				$attributes['distributor'][$nmr] = $distributor;
				$attributes['total_pemesanan'][$nmr] = $pemesanan->GetTotalNilaiPesanan( $invoice_id);
				$bank->SetABank_Id( $invoice->GetBank());
				$attributes['bank'][$nmr] = $bank;
				$nmr += 1;
			}
			$attributes['nomor'] = $offset + 1;
			
			echo $this->getHtmlTemplate(  'pemesanan/templates/', 'pemesanan_list', $attributes);
		}

		
		wp_die();
	}*/

	/*function AjaxRetrieve_Pagination(){
		if( isset($_GET['status']) && $_GET['status'] != "" ){
			$get_status = sanitize_text_field( $_GET['status']);

			if( $get_status == "unconfirmed") {
				$jumlah_data = $this->onex_invoice_obj->CountInvoiceStatus( $get_status);
				$limit = 3;
				$jumlah_page = intval($jumlah_data / $limit);
				if( $jumlah_data % $limit > 0)
					$jumlah_page += 1;
				//var_dump($jumlah_data, $jumlah_page);
				$data['jumlah_page'] = $jumlah_page;
				$data['status'] = $get_status;
			}
			echo $this->getHtmlTemplate( 'pemesanan/templates/', 'pemesanan_list_pagination', $data );
		}
		wp_die();
	}*/

	function AjaxCreate_Pagination(){
		if( isset( $_GET['forlist']) && $_GET['forlist']!="" && isset( $_GET['status']) && $_GET['status']!="" && isset( $_GET['limit']) && $_GET['limit']!="") {
			$get_forlist = sanitize_text_field( $_GET['forlist']);
			$get_filter = sanitize_text_field( $_GET['status']);
			$get_limit = sanitize_text_field( $_GET['limit']);
			if( $get_forlist=="menudel") {
				if( $get_filter == "all") {
					$jumlah_data = $this->onex_menu_distributor_obj->CountAllMenu();
					$limit = $get_limit;
					$jumlah_page = intval($jumlah_data / $limit);
					if( $jumlah_data % $limit > 0)
						$jumlah_page += 1;
					$data['jumlah_page'] = $jumlah_page;
				}
			}else if( $get_forlist=="pemesanan"){
				/*if( $get_filter=="waiting" ){*/
					$status = $get_filter;
					$jumlah_data = $this->onex_invoice_obj->CountInvoiceStatus( $status);
					$limit = $get_limit;
					$jumlah_page = intval($jumlah_data / $limit);
					if( $jumlah_data % $limit > 0)
						$jumlah_page += 1;
					$data['jumlah_page'] = $jumlah_page;
				/*}else if( $get_filter=="all"){
					$status = 0;
					$jumlah_data = $this->onex_invoice_obj->CountInvoiceStatus( $status);
					$limit = 3;
					$jumlah_page = intval($jumlah_data / $limit);
					if( $jumlah_data % $limit > 0)
						$jumlah_page += 1;
					$data['jumlah_page'] = $jumlah_page;
				}else if( $get_filter=="pengiriman"){
					$status = 2;
					$jumlah_data = $this->onex_invoice_obj->CountInvoiceStatus( $status);
					$limit = 3;
					$jumlah_page = intval($jumlah_data / $limit);
					if( $jumlah_data % $limit > 0)
						$jumlah_page += 1;
					$data['jumlah_page'] = $jumlah_page;
				}else if( $get_filter=="terkirim"){
					$status = 3;
					$jumlah_data = $this->onex_invoice_obj->CountInvoiceStatus( $status);
					$limit = 3;
					$jumlah_page = intval($jumlah_data / $limit);
					if( $jumlah_data % $limit > 0)
						$jumlah_page += 1;
					$data['jumlah_page'] = $jumlah_page;
				}else if( $get_filter=="batal"){
					$status = 4;
					$jumlah_data = $this->onex_invoice_obj->CountInvoiceStatus( $status);
					$limit = 3;
					$jumlah_page = intval($jumlah_data / $limit);
					if( $jumlah_data % $limit > 0)
						$jumlah_page += 1;
					$data['jumlah_page'] = $jumlah_page;
				}*/
			}
			$data['forlist'] = $get_forlist;
			$data['filter'] = $get_filter;
			$data['limit'] = $get_limit;
			echo $this->getHtmlTemplate( 'templates/', 'onex_pagination', $data );
		}
		wp_die();
	}

	function AjaxRetrieveListFor(){
		if( isset( $_GET['page']) && $_GET['page']!="" && isset( $_GET['forlist']) && $_GET['forlist']!="" && isset( $_GET['status']) && $_GET['status']!="" && isset( $_GET['limit']) && $_GET['limit']!=""){
			$get_page = sanitize_text_field( $_GET['page']);
			$get_forlist = sanitize_text_field( $_GET['forlist']);
			$get_filter = sanitize_text_field( $_GET['status']);
			$get_limit = sanitize_text_field( $_GET['limit']);

			if( $get_forlist == "menudel" ){

				if( $get_filter == "all") {
					$limit = $get_limit;
					$offset = ( $get_page - 1) * $limit;
					$dir = "";
					$filename = "";

					$all_menu = $this->onex_menu_distributor_obj->GetAllMenu($limit, $offset);
					$attributes = array();
					$nmr=0;
					foreach( $all_menu as $m){
						$menudel_id = $m->id_menudel;
						$menu = new Onex_Menu_Distributor();
						$menu->SetAMenuDistributor( $menudel_id );
						$attributes['menu'][$nmr] = $menu;
						$katmenu = new Onex_Kategori_Menu();
						$katmenu->SetAKategoriMenu( $menu->GetKatmenu());
						$attributes['katmenu'][$nmr] = $katmenu;
						$distributor = new Onex_Distributor();
						$distributor->SetADistributor( $menu->GetDistributor());
						$attributes['distributor'][$nmr] = $distributor;
						$nmr += 1;
					}
					$attributes['nomor'] = $offset + 1;
					$dir = "menu-distributor/templates/";
					$filename = "menu_distributor_list";
				}
			}else if( $get_forlist == "pemesanan") {

				/*if( $get_filter == "waiting") {*/
					$status = $get_filter;
					$limit = $get_limit;
					$offset = ( $get_page - 1) * $limit;

					$invoices = $this->onex_invoice_obj->GetLimitInvoice_Status( $status, $limit, $offset);

					$data= array();

					$nmr = 0;
					foreach( $invoices as $i){
						$invoice_id = $i->id_invoice;

						$invoice = new Onex_Invoice();
						$pemesanan = new Onex_Pemesanan_Menu();
						$distributor = new Onex_Distributor();
						$bank = new Onex_Bank();
						$status_pemesanan = new Onex_Status_Pemesanan();

						$invoice->SetAnInvoice_Id( $invoice_id);
						$attributes['invoice'][$nmr] = $invoice;
						$distributor->SetADistributor( $invoice->GetDistributor());
						$attributes['distributor'][$nmr] = $distributor;
						$attributes['total_pemesanan'][$nmr] = $pemesanan->GetTotalNilaiPesanan( $invoice_id);
						$bank->SetABank_Id( $invoice->GetBank());
						$attributes['bank'][$nmr] = $bank;
						$status_pemesanan->SetAStatusPemesanan_Id( $invoice->GetStatusPemesanan() );
						$attributes['status'][$nmr] = $status_pemesanan;
						$nmr += 1;
					}
					$attributes['nomor'] = $offset + 1;
					$dir = "pemesanan/templates/";
					$filename = "pemesanan_list";
				/*}else if( $get_filter == "all") {
					$status = 0;
					$limit = 3;
					$offset = ( $get_page - 1) * $limit;

					$invoices = $this->onex_invoice_obj->GetLimitInvoice_Status( $status, $limit, $offset);

					$data= array();

					$nmr = 0;
					foreach( $invoices as $i){
						$invoice_id = $i->id_invoice;

						$invoice = new Onex_Invoice();
						$pemesanan = new Onex_Pemesanan_Menu();
						$distributor = new Onex_Distributor();
						$bank = new Onex_Bank();
						$status_pemesanan = new Onex_Status_Pemesanan();

						$invoice->SetAnInvoice_Id( $invoice_id);
						$attributes['invoice'][$nmr] = $invoice;
						$distributor->SetADistributor( $invoice->GetDistributor());
						$attributes['distributor'][$nmr] = $distributor;
						$attributes['total_pemesanan'][$nmr] = $pemesanan->GetTotalNilaiPesanan( $invoice_id);
						$bank->SetABank_Id( $invoice->GetBank());
						$attributes['bank'][$nmr] = $bank;
						$status_pemesanan->SetAStatusPemesanan_Id( $invoice->GetStatusPemesanan() );
						$attributes['status'][$nmr] = $status_pemesanan;
						$nmr += 1;
					}
					$attributes['nomor'] = $offset + 1;
					$dir = "pemesanan/templates/";
					$filename = "pemesanan_list";
				}else if( $get_filter == "pengiriman") {
					$status = 2;
					$limit = 3;
					$offset = ( $get_page - 1) * $limit;

					$invoices = $this->onex_invoice_obj->GetLimitInvoice_Status( $status, $limit, $offset);

					$data= array();

					$nmr = 0;
					foreach( $invoices as $i){
						$invoice_id = $i->id_invoice;

						$invoice = new Onex_Invoice();
						$pemesanan = new Onex_Pemesanan_Menu();
						$distributor = new Onex_Distributor();
						$bank = new Onex_Bank();
						$status_pemesanan = new Onex_Status_Pemesanan();

						$invoice->SetAnInvoice_Id( $invoice_id);
						$attributes['invoice'][$nmr] = $invoice;
						$distributor->SetADistributor( $invoice->GetDistributor());
						$attributes['distributor'][$nmr] = $distributor;
						$attributes['total_pemesanan'][$nmr] = $pemesanan->GetTotalNilaiPesanan( $invoice_id);
						$bank->SetABank_Id( $invoice->GetBank());
						$attributes['bank'][$nmr] = $bank;
						$status_pemesanan->SetAStatusPemesanan_Id( $invoice->GetStatusPemesanan() );
						$attributes['status'][$nmr] = $status_pemesanan;
						$nmr += 1;
					}
					$attributes['nomor'] = $offset + 1;
					$dir = "pemesanan/templates/";
					$filename = "pemesanan_list";
				}else if( $get_filter == "terkirim") {
					$status = 3;
					$limit = 3;
					$offset = ( $get_page - 1) * $limit;

					$invoices = $this->onex_invoice_obj->GetLimitInvoice_Status( $status, $limit, $offset);

					$data= array();

					$nmr = 0;
					foreach( $invoices as $i){
						$invoice_id = $i->id_invoice;

						$invoice = new Onex_Invoice();
						$pemesanan = new Onex_Pemesanan_Menu();
						$distributor = new Onex_Distributor();
						$bank = new Onex_Bank();
						$status_pemesanan = new Onex_Status_Pemesanan();

						$invoice->SetAnInvoice_Id( $invoice_id);
						$attributes['invoice'][$nmr] = $invoice;
						$distributor->SetADistributor( $invoice->GetDistributor());
						$attributes['distributor'][$nmr] = $distributor;
						$attributes['total_pemesanan'][$nmr] = $pemesanan->GetTotalNilaiPesanan( $invoice_id);
						$bank->SetABank_Id( $invoice->GetBank());
						$attributes['bank'][$nmr] = $bank;
						$status_pemesanan->SetAStatusPemesanan_Id( $invoice->GetStatusPemesanan() );
						$attributes['status'][$nmr] = $status_pemesanan;
						$nmr += 1;
					}
					$attributes['nomor'] = $offset + 1;
					$dir = "pemesanan/templates/";
					$filename = "pemesanan_list";
				}else if( $get_filter == "batal") {
					$status = 4;
					$limit = 3;
					$offset = ( $get_page - 1) * $limit;

					$invoices = $this->onex_invoice_obj->GetLimitInvoice_Status( $status, $limit, $offset);

					$data= array();

					$nmr = 0;
					foreach( $invoices as $i){
						$invoice_id = $i->id_invoice;

						$invoice = new Onex_Invoice();
						$pemesanan = new Onex_Pemesanan_Menu();
						$distributor = new Onex_Distributor();
						$bank = new Onex_Bank();
						$status_pemesanan = new Onex_Status_Pemesanan();

						$invoice->SetAnInvoice_Id( $invoice_id);
						$attributes['invoice'][$nmr] = $invoice;
						$distributor->SetADistributor( $invoice->GetDistributor());
						$attributes['distributor'][$nmr] = $distributor;
						$attributes['total_pemesanan'][$nmr] = $pemesanan->GetTotalNilaiPesanan( $invoice_id);
						$bank->SetABank_Id( $invoice->GetBank());
						$attributes['bank'][$nmr] = $bank;
						$status_pemesanan->SetAStatusPemesanan_Id( $invoice->GetStatusPemesanan() );
						$attributes['status'][$nmr] = $status_pemesanan;
						$nmr += 1;
					}
					$attributes['nomor'] = $offset + 1;
					$dir = "pemesanan/templates/";
					$filename = "pemesanan_list";
				}*/
					
			}

			echo $this->getHtmlTemplate(  $dir, $filename, $attributes);
		}
		wp_die();
	}

	function AjaxRetrieve_Kategori_OnDistributor(){
		if( isset($_GET['distributor'] ) && $_GET['distributor'] != "" ) {
			$get_distributor = sanitize_text_field( $_GET['distributor']);

			$all_katmenu = $this->onex_kategori_menu_obj->GetAllKategoriMenu_Distributor( $get_distributor);
			$nmr = 0;
			foreach( $all_katmenu as $km){
				$katmenu_id = $km->id_katmenu;
				$katmenu = new Onex_Kategori_Menu();
				$katmenu->SetAKategoriMenu( $katmenu_id);
				$attributes['nama'][$nmr] = $katmenu->GetNama();
				$attributes['id'][$nmr] = $katmenu_id;
				$nmr += 1;
			}
			echo wp_json_encode($attributes);
		}
		wp_die();
	}

	function AjaxRetrieve_Invoice_Detail(){

		if( isset($_GET['invoice'] ) && $_GET['invoice'] != "" ) {
			$get_invoice_id = sanitize_text_field( $_GET['invoice'] );

			$invoice = new Onex_Invoice();
			$distributor = new Onex_Distributor();
			$data_pelanggan = new Onex_Data_Pembeli();
			$invoice->SetAnInvoice_Id( $get_invoice_id);
			$distributor->SetADistributor( $invoice->GetDistributor() );
			$data_pelanggan->SetDataPembeliUser( $invoice->GetUser() );
			$data = array();
			$data['invoice'] = $invoice;
			$data['distributor'] = $distributor;
			$data['data_pelanggan'] = $data_pelanggan;

			$list_pesanan = $invoice->GetPesanan();
			$n = 0;
			foreach( $list_pesanan as $p){
				$pesanan_id = $p->id_pesanan;
				$pesanan = new Onex_Pemesanan_Menu();
				$pesanan->SetPesananMenu_Id( $pesanan_id);
				$data['pesanan'][$n] = $pesanan;
				$menudel = new Onex_Menu_Distributor();
				$menudel->SetAMenuDistributor( $pesanan->GetMenudel() );
				$data['menu'][$n] = $menudel;
				$n += 1;
			}
			//var_dump($data['menu']);
			$pemesanan = new Onex_Pemesanan_Menu();
			$total_nilai_menu = $pemesanan->GetTotalNilaiPesanan( $get_invoice_id);
			$total_nilai_menu_ppn = $total_nilai_menu + ( $total_nilai_menu * 0.05 );
			$total_pembayaran = $total_nilai_menu_ppn + $invoice->GetBiayaKirim();
			$data['total_pembayaran'] = $total_pembayaran;

			echo $this->getHtmlTemplate( 'pemesanan/templates/', 'pemesanan_detail_invoice', $data );
		}
		wp_die();
	}

	function AjaxRetrieve_StatusPemesanan_List(){
		if( isset($_GET['invoice']) && $_GET['invoice']!="") {
			$get_invoice = sanitize_text_field( $_GET['invoice']);

			$invoice = new Onex_Invoice();
			$invoice->SetAnInvoice_Id( $get_invoice);
			$data['current_status'] = $invoice->GetStatusPemesanan();
			$data['invoice'] = $get_invoice;

			$statuses = new Onex_Status_Pemesanan();
			$all_status = $statuses->GetAllStatusPemesananId();
			$nmr = 0;
			foreach ($all_status as $s) {
				$status_id = $s->id_status;
				$status = new Onex_Status_Pemesanan;
				$status->SetAStatusPemesanan_Id($status_id);
				$data['status'][$nmr] = $status;

				$nmr+=1;
			}
			echo $this->getHtmlTemplate( 'pemesanan/templates/', 'pemesanan_status', $data );
		}

		wp_die();
	}

	public function AjaxUpdate_StatusPemesanan(){
		if( isset( $_POST['invoice']) && isset($_POST['status']) && $_POST['invoice']!="" && $_POST['status']!="" ){
			$post_invoice = sanitize_text_field( $_POST['invoice']);
			$post_status = sanitize_text_field( $_POST['status']);

			$invoice = new Onex_Invoice();
			$invoice->SetAnInvoice_Id( $post_invoice);
			$invoice->SetStatusPemesanan( $post_status);
			if( $post_status>1) $invoice->SetStatusAdminConfirm( 1);
			$result = $invoice->UpdateStatusPemesanan();
			echo wp_json_encode($result);
		}
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
			array( $this, 'RenderDeliveryTambah')
		);
		add_submenu_page(
			null,
			'Hapus Jenis Delivery',
			'Hapus Jenis Delivery',
			'manage_options',
			'onex-jenis-delivery-hapus',
			array( $this, 'RenderDeliveryHapus')
		);
		add_submenu_page(
			null,
			'Update Jenis Delivery',
			'Update Jenis Delivery',
			'manage_options',
			'onex-jenis-delivery-update',
			array( $this, 'RenderDeliveryUpdate')
		);

		// Sub MENU "DISTRIBUTOR" ************
		add_submenu_page(
			'onex-main-page',
			'Distributor One Express',
			'Distributor',
			'manage_options',
			'onex-distributor-page',
			array( $this, 'RenderDistributorList')
		);
		add_submenu_page(
			null,
			'Tambah Distributor',
			'Tambah Distributor',
			'manage_options',
			'onex-distributor-tambah',
			array( $this, 'RenderDistributorTambah')
		);
		add_submenu_page(
			null,
			'Update Distributor',
			'Update Distributor',
			'manage_options',
			'onex-distributor-update',
			array( $this, 'RenderDistributorUpdate')
		);
		add_submenu_page(
			null,
			'Hapus Distributor',
			'Hapus Distributor',
			'manage_options',
			'onex-distributor-hapus',
			array( $this, 'RenderDistributorHapus')
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
			array( $this, 'RenderKategoriMenuTambah')
		);
		add_submenu_page(
			null,
			'Update Kategori Menu',
			'Update Kategori Menu',
			'manage_options',
			'onex-kategori-menu-update',
			array( $this, 'RenderKategoriMenuUpdate')
		);
		add_submenu_page(
			null,
			'Hapus Kategori Menu',
			'Hapus Kategori Menu',
			'manage_options',
			'onex-kategori-menu-hapus',
			array( $this, 'RenderKategoriMenuHapus')
		);

		// Sub MENU "MENU DISTRIBUTOR"
		add_submenu_page(
			'onex-main-page',
			'Menu',
			'Menu',
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
			array( $this, 'RenderMenuDistributorTambah')
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
			array( $this, 'RenderMenuDistributorTambah')
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
			array( $this, 'RenderBankTambah')
		);
		add_submenu_page(
			null,
			'Hapus Bank',
			'Hapus Bank',
			'manage_options',
			'onex-bank-hapus',
			array( $this, 'RenderBankHapus')
		);
		add_submenu_page(
			null,
			'Update Bank',
			'Update Bank',
			'manage_options',
			'onex-bank-update',
			array( $this, 'RenderBankUpdate')
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

		// Sub MENU "PEMESANAN" ****************
		add_submenu_page(
			'onex-main-page',
			'Pemesanan',
			'Pemesanan',
			'manage_options',
			'onex-pemesanan-page',
			array( $this, 'RenderPemesananList')
			);
	}

	function RenderPemesananList(){
		$statuses = new Onex_Status_Pemesanan();
		$nmr = 0;
		$all_status = $statuses->GetAllStatusPemesananId();
		//var_dump($all_status);
		foreach( $all_status as $s){
			$id_status = $s->id_status;
			$status = new Onex_Status_Pemesanan();
			$status->SetAStatusPemesanan_Id($id_status);
			$attributes['status'][$nmr] = $status;
			$nmr += 1;
		}
		return $this->getHtmlTemplate(  'pemesanan/templates/', 'pemesanan_main', $attributes);
	}

	function RenderTarifKirimList(){
		$this->onex_tarif_kirim_obj->SetATarifKirim();
		if( $this->onex_tarif_kirim_obj->GetId()==0){
			$new_tarif = new Onex_Ongkos_Kirim();
			$new_tarif->SetJarakMinimal(5);
			$new_tarif->SetTarifMinimal(10000);
			$new_tarif->SetTarifNormal(3000);
			$new_tarif->CreateNewTarifKirim();
			$attributes = $new_tarif;
		}else{
			$attributes = $this->onex_tarif_kirim_obj;
		}

		return $this->getHtmlTemplate(  'ongkos-kirim/templates/', 'ongkos_kirim_main', $attributes);
	}

	function RenderDeliveryList(){
		
		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_main', $attributes);
	}

	function RenderDeliveryTambah(){

		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_tambah', $attributes);
	}

	function RenderDeliveryHapus(){
		$this->onex_jenis_delivery_obj->SetAJenisDelivery( $_GET['id']);
		$attributes = $this->onex_jenis_delivery_obj;

		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_hapus', $attributes);
	}

	function RenderDeliveryUpdate(){
		$attributes['katdel'] = $this->onex_jenis_delivery_obj->GetDelivery($_GET['id']);

		return $this->getHtmlTemplate( 'jenis-delivery/templates/', 'delivery_update', $attributes);
	}

	function RenderDistributorList(){
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
		}

		return $this->getHtmlTemplate( 'distributor/templates/', 'distributor_update', $attributes);
	}

	function RenderKategoriMenuList(){

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

		return $this->getHtmlTemplate(  'menu-distributor/templates/', 'menu_distributor_main', $attributes);
	}

	function RenderMenuDistributorTambah(){
		if(isset($_GET['distributor']) && isset($_GET['kategori'])){
			$get_distributor = sanitize_text_field($_GET['distributor']);
			$get_katmenu = sanitize_text_field( $_GET['kategori']);

			$distributor = new Onex_Distributor();
			$distributor->SetADistributor($get_distributor);
			$katmenu = new Onex_Kategori_Menu();
			$katmenu->SetAKategoriMenu($get_katmenu);

			$attributes['distributor'] = $distributor;
			$attributes['katmenu'] = $katmenu;
			/*$attributes['distributor'] = $this->onex_distributor_obj->GetDistributor( $_GET['distributor']);
			$attributes['katmenu'] = $this->onex_kategori_menu_obj->GetKategoriMenuById( $_GET['kategori']);*/
			$attributes['single'] = true;
		}else{
			$attributes['single'] = false;
			$all_distributor = $this->onex_distributor_obj->GetAllDistributor();
			$all_katmenu = $this->onex_kategori_menu_obj->GetAllKategori();
			$nmr = 0;
			foreach ($all_distributor as $d) {
				$distributor_id = $d->id_dist;
				$distributor = new Onex_Distributor();
				$distributor->SetADistributor($distributor_id);
				$attributes['distributor'][$nmr] = $distributor;
				$nmr += 1;
			}
			$nmr = 0;
			foreach ($all_katmenu as $km) {
				$katmenu_id = $km->id_katmenu;
				$katmenu = new Onex_Kategori_Menu();
				$katmenu->SetAKategoriMenu($katmenu_id);
				$attributes['katmenu'][$nmr] = $katmenu;
				$nmr += 1;
			}
		}

		return $this->getHtmlTemplate(  'menu-distributor/templates/', 'menu_distributor_tambah', $attributes);
	}

	function RenderMenuDistributorHapus(){
		$get_menudel_id = sanitize_text_field( $_GET['menu']);
		$this->onex_menu_distributor_obj->SetAMenuDistributor( $get_menudel_id );
		$attributes = $this->onex_menu_distributor_obj;

		return $this->getHtmlTemplate( 'menu-distributor/templates/', 'menu_distributor_hapus', $attributes);
	}

	function RenderMenuDistributorUpdate(){

		if( isset($_GET['menu'])) {
			$get_menu_id = sanitize_text_field( $_GET['menu']);
			$get_distributor_id = "";
			$get_katmenu_id = "";
			$attributes = array();

			$all_distributor = $this->onex_distributor_obj->GetAllDistributor();
			$nmr = 0;
			foreach( $all_distributor as $d){
				$distributor_id = $d->id_dist;
				$dist = new Onex_Distributor();
				$dist->SetADistributor( $distributor_id);
				$attributes['all-distributor'][$nmr] = $dist;
				$nmr += 1;
			}

			$menudist = new Onex_Menu_Distributor();
			$menudist->SetAMenuDistributor( $get_menu_id );
			$attributes['menudel'] = $menudist;
			$distributor = new Onex_Distributor();
			$distributor->SetADistributor( $menudist->GetDistributor(0));
			$attributes['menu-distributor'] = $distributor;
			$katmenu = new Onex_Kategori_Menu();
			$katmenu->SetAKategoriMenu( $menudist->GetKatmenu());
			$attributes['menu-katmenu'] = $katmenu;
		}

		return $this->getHtmlTemplate(  'menu-distributor/templates/', 'menu_distributor_update', $attributes);
	}

	function RenderLokasiList(){

		return $this->getHtmlTemplate(  'lokasi/templates/', 'lokasi_main', $attributes);
	}

	function RenderBankList(){

		return $this->getHtmlTemplate(  'bank/templates/', 'bank_main', $attributes);
	}

	function RenderBankTambah(){

		return $this->getHtmlTemplate(  'bank/templates/', 'bank_tambah', $attributes);
	}

	function RenderBankHapus(){
		$get_bank_id = sanitize_text_field( $_GET['id']);
		$bank = new Onex_Bank();
		$bank->SetABank_Id( $get_bank_id);
		$attributes = $bank;

		return $this->getHtmlTemplate( 'bank/templates/', 'bank_hapus', $attributes);
	}

	function RenderBankUpdate(){
		$get_bank_id = sanitize_text_field( $_GET['id']);
		$bank = new Onex_Bank();
		$bank->SetABank_Id( $get_bank_id);
		$attributes = $bank;

		return $this->getHtmlTemplate(  'bank/templates/', 'bank_update', $attributes);
	}

	function RenderPromoList(){

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
