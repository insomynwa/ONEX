<?php

class Onex_Bank{

	private $table_name;

	private $id;
	public function GetId(){ return $this->id; }

	private $nama;
	public function GetNama() { return $this->nama; }
	public function SetNama( $nama ) { $this->nama = $nama; }
	
	private $pemilik;
	public function GetPemilik() { return $this->pemilik; }
	public function SetPemilik($pemilik) { $this->pemilik = $pemilik; }

	private $no_rekening;
	public function GetNoRekening() { return $this->no_rekening; }
	public function SetNoRekening($no_rekening) { $this->no_rekening = $no_rekening; }


	function __construct(){
		$this->table_name = "onex_bank";

		//add_action( 'wp_print_scripts', array ($this, 'AjaxBankLoadScripts') );
		/*add_action( 'wp_ajax_AjaxGetBankList', array( $this, 'AjaxLoad_Bank_List') );
		add_action( 'wp_ajax_AjaxGetBankDetail', array( $this, 'AjaxLoad_Bank_Detail') );*/
	}

	/*function AjaxBankLoadScripts(){
		wp_localize_script( 'ajax-bank', 'ajax_one_express', array( 'ajaxurl' => admin_url('admin-ajax.php')) );
	}*/

	/*function AjaxLoad_Bank_List(){
		$attributes['bank'] = $this->GetBankAll();
		echo $this->getHtmlTemplate( plugin_dir_path( __FILE__ ) .'/templates/', 'bank_list', $attributes );
		wp_die();
	}*/

	/*function AjaxLoad_Bank_Detail(){

	}*/

	public function SetABank_Id( $bank_id){
		global $wpdb;
		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name
					WHERE id_bank = %d ",
					$bank_id 
					),
				ARRAY_A
				);
		
		$this->id = 0;
		if( !is_null($row )) {
			$this->id = $row['id_bank'];
			$this->nama = $row['nama_bank'];
			$this->pemilik = $row['pemilik_rekening'];
			$this->no_rekening = $row['no_rekening'];
		}
	}

	/*private function getHtmlTemplate( $location, $template_name, $attributes = null ){
		if(! $attributes) $attributes = array();

		ob_start();
		require( $location . $template_name . '.php');
		$html = ob_get_contents();
		ob_end_clean();
		echo $html;
	}*/

	/*public function GetBankAll(){
		global $wpdb;
		$attributes = null;

		$attributes = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM $this->table_name",
						null
					)
				);

		return $attributes;
	}*/

	public function GetAllBank(){
		global $wpdb;

		$result = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT id_bank FROM $this->table_name",
						null
					)
				);

		return $result;
	}

	public function AddNewBank(){
		global $wpdb;

		$result = array('status'=>false, 'message' =>'');

		if( $wpdb->insert(
				$this->table_name,
				array(
					'nama_bank' => $this->nama,
					'pemilik_rekening' => $this->pemilik,
					'no_rekening' => $this->no_rekening
				),
				array('%s','%s','%s')
			)
		){
			$result['status'] = true;
			$result['message'] = "Berhasil menambah bank.";
		}else{
			$result['message'] = "Terjadi kesalahan.";
		}
		return $result;
	}

	public function AddBank( $data){
		global $wpdb;

		$result = array('status'=>false, 'message' =>'');

		if( $wpdb->insert(
				$this->table_name,
				array(
					'nama_bank' => $data['menudist_nama'],
					'pemilik_rekening' => $data['menudist_harga'],
					'no_rekening' => $data['menudist_gambar']
				),
				array('%s','%s','%s')
			)
		){
			$result['status'] = true;
			$result['message'] = "Berhasil menambah bank.";
		}else{
			$result['message'] = "Terjadi kesalahan.";
		}
		return $result;
	}

	public function UpdateBank(){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		/*if ($data['dist_gambar'] == '' ) $data['dist_gambar'] = 'NOIMAGE';*/

		if($wpdb->update(
			$this->table_name,
			array(
				'nama_bank' => $this->nama,
				'pemilik_rekening' => $this->pemilik,
				'no_rekening' => $this->no_rekening
			),
			array('id_bank' => $this->id),
			array('%s','%s', '%s'),
			array('%d')
		)){
			$result['status'] = true;
			$result['message'] = 'Bank berhasil diperbaharui.';
		}else{
			$result['status'] = false;
			$result['message'] = 'Terjadi kesalahan.';
		}

		return $result;
	}

	public function DeleteBank(){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);

		if($wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE id_bank = %d",
				$this->id
			)
		)){
			$result['status'] = true;
			$result['message'] = 'Berhasil menghapus Bank.';
		}else{
			$result['message'] = 'Terjadi Kesalahan.';
		}
		return $result;
	}

	public function GetBankById($id){
		/*global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name WHERE id_dist = %d",
					$id
				), ARRAY_A
			);
		$attributes['distributor'] = $row;
		return $attributes;*/
	}
}

?>