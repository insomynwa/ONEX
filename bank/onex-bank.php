<?php

class Onex_Bank{

	private $table_name;

	function __construct(){
		$this->table_name = "onex_bank";

		add_action( 'wp_print_scripts', array ($this, 'AjaxBankLoadScripts') );
		add_action( 'wp_ajax_AjaxGetBankList', array( $this, 'AjaxLoad_Bank_List') );
		add_action( 'wp_ajax_AjaxGetBankDetail', array( $this, 'AjaxLoad_Bank_Detail') );
	}

	function AjaxBankLoadScripts(){
		wp_localize_script( 'ajax-bank', 'ajax_one_express', array( 'ajaxurl' => admin_url('admin-ajax.php')) );
	}

	function AjaxLoad_Bank_List(){
		$attributes['bank'] = $this->GetBankAll();
		echo $this->getHtmlTemplate( plugin_dir_path( __FILE__ ) .'/templates/', 'bank_list', $attributes );
		wp_die();
	}

	function AjaxLoad_Bank_Detail(){

	}

	private function getHtmlTemplate( $location, $template_name, $attributes = null ){
		if(! $attributes) $attributes = array();

		ob_start();
		require( $location . $template_name . '.php');
		$html = ob_get_contents();
		ob_end_clean();
		echo $html;
	}

	public function GetBankAll(){
		global $wpdb;
		$attributes = null;

		$attributes = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM $this->table_name",
						null
					)
				);

		return $attributes;
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

	public function UpdateBank($id, $data){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		/*if ($data['dist_gambar'] == '' ) $data['dist_gambar'] = 'NOIMAGE';

		if($wpdb->update(
			$this->table_name,
			array(
				'nama' => $data['dist_nama'],
				'alamat' => $data['dist_alamat'],
				'kategori_delivery' => $data['dist_jenis_delivery'],
				'telp' => $data['dist_telp'],
				'email' => $data['dist_email'],
				'keterangan' => $data['dist_keterangan'],
				'gambar' => $data['dist_gambar']
			),
			array('id_dist' => $id),
			array('%s','%s'),
			array('%d')
		)){
			$result['status'] = true;
			$result['message'] = 'Distributor berhasil diperbaharui.';
		}else{
			$result['status'] = true;
			$result['message'] = 'Tidak ada pembaharuan distributor.';
		}

		return $result;*/
	}

	public function DeleteBank($id){
		/*global $wpdb;

		if($wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE id_dist = %d",
				$id
			)
		)){
			return 'Berhasil menghapus Distributor.';
		}else{
			return 'Terjadi Kesalahan.';
		}*/
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