<?php

include_once(WP_PLUGIN_DIR . '/one-express/template-set/onex_template.php');
include_once(WP_PLUGIN_DIR . '/one-express/jenis-delivery/onex-jenis-delivery.php');
include_once(WP_PLUGIN_DIR . '/one-express/kategori-menu/onex-kategori-menu.php');
include_once(WP_PLUGIN_DIR . '/one-express/menu-distributor/onex-menu-distributor.php');
class Onex_Distributor{

	private $table_name;
	private $table_jenis_delivery;

	private $id;
	public function GetId(){ return $this->id; }

	private $nama;
	public function GetNama() { return $this->nama; }
	public function SetNama($nama) { $this->nama = $nama; }

	private $alamat;
	public function GetAlamat() { return $this->alamat; }
	public function SetAlamat($alamat) { $this->alamat = $alamat; }
	
	private $telp;
	public function GetTelp() { return $this->telp; }
	public function SetTelp($telp) { $this->telp = $telp; }
	
	private $email;
	public function GetEmail() { return $this->email; }
	public function SetEmail($email) { $this->email = $email; }
	
	private $keterangan;
	public function GetKeterangan() { return $this->keterangan; }
	public function SetKeterangan($keterangan) { $this->keterangan = $keterangan; }
	
	private $gambar;
	public function GetGambar() { return $this->gambar; }
	public function SetGambar($gambar) { $this->gambar = $gambar; }
	
	private $katdel;
	public function GetKatdel() { return $this->katdel; }
	public function SetKatdel($katdel) { $this->katdel = $katdel; }
	
	private $kode;
	public function GetKode() { return $this->kode; }
	public function SetKode($kode) { $this->kode = $kode; }


	function __construct(){
		$this->table_name = "onex_distributor";
		$this->table_jenis_delivery = "onex_kategori_delivery";


		//add_action('wp_print_scripts', array( $this, 'AjaxDistributorLoadScripts')) ;
		// add_action('wp_ajax_AjaxGetDistributorList', array( $this, 'AjaxLoad_Distributor_List')) ;
		// add_action('wp_ajax_AjaxGetDistributorDetail', array( $this, 'AjaxLoad_Distributor_Detail')) ;
	}

	/*function AjaxDistributorLoadScripts(){
		// load our jquery file that sends the $.post request
		wp_enqueue_script( "ajax-distributor", plugin_dir_url( __FILE__ ) . '../js/ajax-distributor.js', array( 'jquery' ) );
	 
		// make the ajaxurl var available to the above script
		wp_localize_script( 'ajax-distributor', 'ajax_one_express', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
	}*/

	/*function AjaxLoad_Distributor_List(){
		$attributes['distributor'] = $this->DistributorList();
		echo $this->getHtmlTemplate(  'templates/', 'distributor_list', $attributes);
		wp_die();
	}*/

	/*function AjaxLoad_Distributor_Detail(){
		// first check if data is being sent and that it is the data we want
	  	if ( isset( $_GET["distributor"] ) ) {
			// now set our response var equal to that of the POST var (this will need to be sanitized based on what you're doing with with it)
			//$response = $_GET["distributor"];
			// send the response back to the front end
			$attributes['distributor'] = $this->GetDistributor( $_GET["distributor"]);

			$onex_kategori_menu_obj = new Onex_Kategori_Menu();
			$attributes['distributor_rel']['katmenu'] = $onex_kategori_menu_obj->GetKategoriByDistributor( $_GET['distributor']);

			$onex_menu_distributor_obj = new Onex_Menu_Distributor();
			foreach( $attributes['distributor_rel']['katmenu'] as $diskat => $value ){
				$attributes['distributor_rel']['menudist'][$value->nama_katmenu] = $onex_menu_distributor_obj->GetMenuByDistributorKategori( $_GET['distributor'], $value->id_katmenu );
			}
			//var_dump($attributes['distributor_rel']['menudist']);
			
			echo $this->getHtmlTemplate(  'templates/', 'distributor_detail', $attributes);
			wp_die();
		}
	}*/

	public function SetADistributor( $distributor_id){
		global $wpdb;
		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name
					WHERE id_dist = %d ",
					$distributor_id 
					),
				ARRAY_A
				);
		
		$this->id = 0;
		if( !is_null($row )) {
			$this->id = $row['id_dist'];
			$this->nama = $row['nama_dist'];
			$this->alamat = $row['alamat_dist'];
			$this->telp = $row['telp_dist'];
			$this->email = $row['email_dist'];
			$this->keterangan = $row['keterangan_dist'];
			$this->gambar = $row['gambar_dist'];
			$this->katdel = $row['katdel_id'];
			$this->kode = $row['kode_dist'];
		}
	}

	public function GetAllDistributor(){
		global $wpdb;
		
		$result = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT id_dist FROM $this->table_name
						 ORDER BY nama_dist ASC"
						 ,null
					)
				);

		return $result;
	}

	public function UpdateDistributor(){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		//if ($data['dist_gambar'] == '' ) $data['dist_gambar'] = 'NOIMAGE';

		if($wpdb->update(
			$this->table_name,
			array(
				'nama_dist' => $this->nama,
				'alamat_dist' => $this->alamat,
				'katdel_id' => $this->katdel,
				'telp_dist' => $this->telp,
				'email_dist' => $this->email,
				'keterangan_dist' => $this->keterangan,
				'gambar_dist' => $this->gambar,
				'kode_dist' => $this->kode
			),
			array('id_dist' => $this->id),
			array('%s','%s','%d','%s','%s','%s', '%s', '%s'),
			array('%d')
		)){
			$result['status'] = true;
			$result['message'] = 'Distributor berhasil diperbaharui.';
		}else{
			//$result['status'] = true;
			$result['message'] = 'Gagal update.';
		}

		return $result;
	}

	public function DistributorList(){
		global $wpdb;

		$attributes = null;
		
		$attributes = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT d.*, kd.nama_katdel FROM $this->table_name d
						 LEFT JOIN $this->table_jenis_delivery kd
						 ON d.katdel_id=kd.id_katdel
						 ORDER BY d.nama_dist ASC"
						 ,null
					)
				);

		return $attributes;
	}

	public function GetDistributorAll(){
		global $wpdb;

		$attributes = null;

		$attributes =
			$wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM $this->table_name ORDER BY nama_dist ASC",
						null
					)
				);

		return $attributes;
	}

	public function GetDistributorByTemplate($template_name){
		global $wpdb;

		$template = new Onex_Template();

		$template_id = $template->GetTemplateIdByName($template_name);
			
		$attributes = null;

		if($template_id){

			$kat_delivery = new Onex_Jenis_Delivery();

			$kat_del_id = $kat_delivery->GetJenisDeliveryByTemplate($template_id);

			if($kat_del_id){
				if($wpdb->get_var("SELECT COUNT(*) FROM $this->table_name") > 0){
							
					$attributes = 
						$wpdb->get_results(
							$wpdb->prepare(
								"SELECT * FROM $this->table_name d
								 WHERE katdel_id = %d",
								 $kat_del_id
							)
						);
				}
			}
		}

		return $attributes;
	}

	public function GetAll_Id_Distributor_JenisDelivery( $katdel_id){
		global $wpdb;

		$result = 
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT id_dist FROM $this->table_name
					WHERE katdel_id = %d",
					$katdel_id
				)
			);

		return $result;
	}

	public function GetDistributorByJenisDelivery($id){
		global $wpdb;

		$attributes = null;

		$attributes = 
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name
					WHERE katdel_id = %d",
					$id
				)
			);

		return $attributes;
	}

	public function AddDistributor($data){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		if ($data['dist_gambar'] == '' ) $data['dist_gambar'] = 'NOIMAGE';
		if($wpdb->insert(
			$this->table_name,
			array(
				'nama_dist' => $data['dist_nama'],
				'alamat_dist' => $data['dist_alamat'],
				'katdel_id' => $data['dist_jenis_delivery'],
				'telp_dist' => $data['dist_telp'],
				'email_dist' => $data['dist_email'],
				'keterangan_dist' => $data['dist_keterangan'],
				'gambar_dist' => $data['dist_gambar'],
				'kode_dist' => $data['dist_kode']
			),
			array('%s','%s','%d','%s','%s','%s', '%s', '%s')
		)){
			$result['status'] = true;
			$result['message'] = 'Berhasil menambah distributor.';
		}else{
			$result['status'] = true;
			$result['message'] = 'Gagal menambah distributor.';
		}
		return $result;
	}

	/*public function UpdateDistributor($id, $data){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		if ($data['dist_gambar'] == '' ) $data['dist_gambar'] = 'NOIMAGE';

		if($wpdb->update(
			$this->table_name,
			array(
				'nama_dist' => $data['dist_nama'],
				'alamat_dist' => $data['dist_alamat'],
				'katdel_id' => $data['dist_jenis_delivery'],
				'telp_dist' => $data['dist_telp'],
				'email_dist' => $data['dist_email'],
				'keterangan_dist' => $data['dist_keterangan'],
				'gambar_dist' => $data['dist_gambar'],
				'kode_dist' => $data['dist_kode']
			),
			array('id_dist' => $id),
			array('%s','%s','%d','%s','%s','%s', '%s', '%s'),
			array('%d')
		)){
			$result['status'] = true;
			$result['message'] = 'Distributor berhasil diperbaharui.';
		}else{
			$result['status'] = true;
			$result['message'] = 'Tidak ada pembaharuan distributor.';
		}

		return $result;
	}*/

	/*public function DeleteDistributor($id){
		global $wpdb;

		if($wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE id_dist = %d",
				$id
			)
		)){
			return 'Berhasil menghapus Distributor.';
		}else{
			return 'Terjadi Kesalahan.';
		}
	}*/

	public function DeleteDistributor(){
		global $wpdb;

		$result = array( 'status' => false, 'message' => '');

		if($wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE id_dist = %d",
				$this->id
			)
		)){
			$result['status'] = true;
			$result['message'] = 'Berhasil menghapus Distributor.';
		}else{
			$result['message'] = 'Terjadi Kesalahan.';
		}

		return $result;
	}

	public function DeleteDistributor_JenisDelivery( $katdel_id){
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE katdel_id = %d",
				$katdel_id
			)
		);
	}

	public function GetDistributor($id){
		global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name WHERE id_dist = %d",
					$id
				), ARRAY_A
			);
		$attributes = $row;
		return $attributes;
	}

	public function GetAlamatDistributor( $id_distributor){
		global $wpdb;
		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT alamat_dist FROM $this->table_name WHERE id_dist = %d",
					$id_distributor
					), 
				ARRAY_A
				);

		return $row['alamat_dist'];
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

?>